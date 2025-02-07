import { test, expect, config } from '../../setup';
import  * as forms from '../../utils/form';

test.describe('product management', () => {
    test('create product(simple, virtual, downloadable)', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/catalog/products`);
        await adminPage.waitForSelector('button.primary-button:has-text("Create Product")', { state: 'visible' });

        await adminPage.click('button.primary-button:has-text("Create Product")');

        const option = Math.random() > 0.3 ? 'simple' : (Math.random() > 0.5 ? 'downloadable' : 'virtual');

        await adminPage.selectOption('select[name="type"]:visible', { value: option });

        const options = await adminPage.$$eval('select[name="attribute_family_id"]:visible option', (options) => {
            return options.map(option => option.value);
        });

        const randomIndex = Math.floor(Math.random() * options.length);

        await adminPage.selectOption('select[name="attribute_family_id"]:visible', options[randomIndex]);

        await adminPage.fill('input[name="sku"]:visible', Math.random().toString(36).substring(7));
        await adminPage.click('button.primary-button:has-text("Save Product")');
        await forms.testForm(adminPage);

        await adminPage.waitForSelector('input[name="name"]#name');

        await adminPage.fill('input[name="name"]#name', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

        await adminPage.waitForSelector('iframe');
        const iframe = await adminPage.$$('iframe');

        const frame1 = await iframe[0].contentFrame();

        const randomHtmlContent = await forms.fillParagraphWithRandomHtml(10);

        await frame1.waitForSelector('body[data-id="short_description"] > p');

        await frame1.$eval('body[data-id="short_description"] > p', (el, content) => {
            el.innerHTML = content;
        }, randomHtmlContent);

        const frame2 = await iframe[1].contentFrame();

        const randomHtmlContent1 = await forms.fillParagraphWithRandomHtml(50);

        await frame2.$eval('body[data-id="description"] > p', (el, content) => {
            el.innerHTML = content;
        }, randomHtmlContent1);

        let number = Math.floor(Math.random() * 4) + 1;

        for (let i = 1; i <= number; i++) {
            await adminPage.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
                el.innerHTML += content;
            }, `<input type="file" name="images[files][]" accept="image/*">`);
        }

        const images = await adminPage.$$('input[type="file"][name="images[files][]"]');

        const filePath = forms.getRandomImageFile();

        for (let image of images) {
            await image.setInputFiles(filePath);
        }

        await adminPage.evaluate((content) => {
            const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
            const description = document.querySelector('textarea[name="description"]#description');

            if (shortDescription instanceof HTMLTextAreaElement) {
                shortDescription.style.display = content;
            }

            if (description instanceof HTMLTextAreaElement) {
                description.style.display = content;
            }
        }, 'block');

        await adminPage.fill('textarea[name="short_description"]', randomHtmlContent.toString());
        await adminPage.fill('textarea[name="description"]', randomHtmlContent1.toString());

        await adminPage.evaluate((content) => {
            const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
            const description = document.querySelector('textarea[name="description"]#description');

            if (shortDescription instanceof HTMLTextAreaElement) {
                shortDescription.style.display = content;
            }

            if (description instanceof HTMLTextAreaElement) {
                description.style.display = content;
            }
        }, 'none');

        await adminPage.fill('input[name="price"]#price', (Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());

        const weight = await adminPage.$$('input[name="weight"]#weight');

        if (weight.length > 0) {
            await adminPage.fill('input[name="weight"]#weight', (Math.floor(Math.random() * 10) * Math.floor(Math.random() * 10)).toString());
        }

        const checkboxs = await adminPage.$$('input[type="checkbox"] + label, input[name="categories[]"] + span');

        for (let checkbox of checkboxs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 == 1) {
                await checkbox.click();
            }
        }

        const inventories = await adminPage.$$('input[name="inventories[1]"]:visible');
        if (inventories.length > 0) {
            await adminPage.fill('input[name="inventories[1]"]', (Math.floor(Math.random() * 10) * Math.floor(Math.random() * 10)).toString());
        }

        const selects = await adminPage.$$('select.custom-select');

        for (let select of selects) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                const options = await select.$$eval('option', (options) => {
                    return options.map(option => option.value);
                });

                if (options.length > 0) {
                    const randomIndex = Math.floor(Math.random() * options.length);

                    await select.selectOption(options[randomIndex]);
                }
            }
        }

        const textareas = await adminPage.$$('textarea:visible');

        for (let textarea of textareas) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await textarea.fill(forms.generateRandomStringWithSpaces(200));
            }
        }

        const inputs = await adminPage.$$('input[name="cost"], input[name="length"], input[name="width"], input[name="height"], input[name="product_number"]');

        for (let input of inputs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await input.fill((Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());
            }
        }

        const addButtons = await adminPage.$$('div.secondary-button:not(:has-text("Add Option"))');

        for (let addButton of addButtons.slice(-3)) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await addButton.click();

                const randomProduct = forms.generateRandomProductName();
                await adminPage.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

                const exists = await adminPage.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

                if (exists) {
                    const checkboxs = await adminPage.$$('input[type="checkbox"] + label.icon-uncheckbox');

                    for (let checkbox of checkboxs) {
                        let i = Math.floor(Math.random() * 10) + 1;

                        if (
                            i % 2 == 1
                            || checkboxs.length < 3
                        ) {
                            await checkbox.scrollIntoViewIfNeeded();
                            await checkbox.click();
                        }
                    }
                }

                adminPage.click('div.primary-button:visible');

                await adminPage.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

                const crosses = await adminPage.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

                if (crosses.length > 0) {
                    for (let cross of crosses) {
                        await cross.click({ timeout: 1000 }).catch(() => null);
                    }
                }
            }
        }

        if (addButtons.length > 3) {
            for (let addButton of [addButtons[0], addButtons.length == 7 ? addButtons[2] : (addButtons.length == 5 ? addButtons[1] : addButtons[2])]) {
                let i = Math.floor(Math.random() * 5) + 1;

                for (let j = 1; j <= i; j++) {
                    await addButton.click();

                    await adminPage.waitForSelector('input[name="title"]:visible');

                    await adminPage.fill('input[name="title"]:visible', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

                    const inventories = await adminPage.$$('input[name="downloads"]:visible');
                    if (inventories.length > 0) {
                        await adminPage.fill('input[name="price"]:visible', (Math.floor(Math.random() * 500)).toString());
                        await adminPage.fill('input[name="downloads"]:visible', (Math.floor(Math.random() * 500)).toString());
                    }
                    const selects = await adminPage.$$('select[name="type"].custom-select:visible , select[name="sample_type"].custom-select:visible');

                    for (let select of selects) {
                        const option = Math.random() > 0.5 ? 'file' : 'url';

                        await select.selectOption({ value: option });
                    }

                    const files = await adminPage.$$('input[name="file"]:visible , input[name="sample_file"]:visible');
                    const urls = await adminPage.$$('input[name="url"]:visible , input[name="sample_url"]:visible');

                    const filePath = forms.getRandomImageFile();

                    for (let file of files) {
                        await file.setInputFiles(filePath);
                    }
                    for (let url of urls) {
                        await url.fill(forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));
                    }

                    await adminPage.click('p.text-xl.font-medium + button.primary-button:visible');

                    await adminPage.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

                    const crosses = await adminPage.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

                    if (crosses.length > 0) {
                        for (let cross of crosses) {
                            await cross.click();
                        }
                    }
                }
            }
        }

        await adminPage.click('.primary-button:visible');

        await expect(adminPage.getByText('Product updated successfully')).toBeVisible();
    });

    test('edit product(simple, virtual, downloadable)',  async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/catalog/products`);
        await adminPage.waitForSelector('button.primary-button:has-text("Create Product")', { state: 'visible' });

        await adminPage.waitForSelector('a > span.icon-sort-right.cursor-pointer.text-2xl:visible');
        const iconRight = await adminPage.$$('a > span.icon-sort-right.cursor-pointer.text-2xl');
        await iconRight[0].click();

        await adminPage.waitForSelector('input[name="name"]#name');

        await adminPage.fill('input[name="name"]#name', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

        await adminPage.waitForSelector('iframe');
        const iframe = await adminPage.$$('iframe');

        const frame1 = await iframe[0].contentFrame();

        const randomHtmlContent = await forms.fillParagraphWithRandomHtml(10);

        await frame1.waitForSelector('body[data-id="short_description"] > p');

        await frame1.$eval('body[data-id="short_description"] > p', (el, content) => {
            el.innerHTML = content;
        }, randomHtmlContent);

        const frame2 = await iframe[1].contentFrame();

        const randomHtmlContent1 = await forms.fillParagraphWithRandomHtml(50);

        await frame2.$eval('body[data-id="description"] > p', (el, content) => {
            el.innerHTML = content;
        }, randomHtmlContent1);

        let number = Math.floor(Math.random() * 4) + 1;

        for (let i = 1; i <= number; i++) {
            await adminPage.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
                el.innerHTML += content;
            }, `<input type="file" name="images[files][]" accept="image/*">`);
        }

        const images = await adminPage.$$('input[type="file"][name="images[files][]"]');

        const filePath = forms.getRandomImageFile();

        for (let image of images) {
            await image.setInputFiles(filePath);
        }

        await adminPage.evaluate((content) => {
            const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
            const description = document.querySelector('textarea[name="description"]#description');

            if (shortDescription instanceof HTMLTextAreaElement) {
                shortDescription.style.display = content;
            }

            if (description instanceof HTMLTextAreaElement) {
                description.style.display = content;
            }
        }, 'block');

        await adminPage.fill('textarea[name="short_description"]', randomHtmlContent.toString());
        await adminPage.fill('textarea[name="description"]', randomHtmlContent1.toString());

        await adminPage.evaluate((content) => {
            const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
            const description = document.querySelector('textarea[name="description"]#description');

            if (shortDescription instanceof HTMLTextAreaElement) {
                shortDescription.style.display = content;
            }

            if (description instanceof HTMLTextAreaElement) {
                description.style.display = content;
            }
        }, 'none');

        await adminPage.fill('input[name="price"]#price', (Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());

        const weight = await adminPage.$$('input[name="weight"]#weight');
        if (weight.length > 0) {
            await adminPage.fill('input[name="weight"]#weight', (Math.floor(Math.random() * 10) * Math.floor(Math.random() * 10)).toString());
        }

        const checkboxs = await adminPage.$$('input[type="checkbox"] + label, input[name="categories[]"] + span');

        for (let checkbox of checkboxs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 == 1) {
                await checkbox.click();
            }
        }

        const inventories = await adminPage.$$('input[name="inventories[1]"]:visible');
        if (inventories.length > 0) {
            await adminPage.fill('input[name="inventories[1]"]', (Math.floor(Math.random() * 10) * Math.floor(Math.random() * 10)).toString());
        }

        const selects = await adminPage.$$('select.custom-select');

        for (let select of selects) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                const options = await select.$$eval('option', (options) => {
                    return options.map(option => option.value);
                });

                if (options.length > 0) {
                    const randomIndex = Math.floor(Math.random() * options.length);

                    await select.selectOption(options[randomIndex]);
                }
            }
        }

        const textareas = await adminPage.$$('textarea:visible');

        for (let textarea of textareas) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await textarea.fill(forms.generateRandomStringWithSpaces(200));
            }
        }

        const deleteBtns = await adminPage.$$('p.cursor-pointer.text-red-600.transition-all');

        for (let deleteBtn of deleteBtns) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await deleteBtn.click();

                await adminPage.click('button[type="button"].transparent-button + button[type="button"].primary-button');

                break;
            }
        }

        const inputs = await adminPage.$$('input[name="cost"], input[name="length"], input[name="width"], input[name="height"], input[name="product_number"]');

        for (let input of inputs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await input.fill((Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());
            }
        }

        const addButtons = await adminPage.$$('div.secondary-button:not(:has-text("Add Option"))');

        for (let addButton of addButtons.slice(-3)) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await addButton.click();

                const randomProduct = forms.generateRandomProductName();
                await adminPage.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

                const exists = await adminPage.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

                if (exists) {
                    const checkboxs = await adminPage.$$('input[type="checkbox"] + label.icon-uncheckbox');

                    for (let checkbox of checkboxs) {
                        let i = Math.floor(Math.random() * 10) + 1;

                        if (
                            i % 2 == 1
                            || checkboxs.length < 3
                        ) {
                            await checkbox.scrollIntoViewIfNeeded();
                            await checkbox.click();
                        }
                    }
                }

                adminPage.click('div.primary-button:visible');

                await adminPage.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

                const crosses = await adminPage.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

                if (crosses.length > 0) {
                    for (let cross of crosses) {
                        await cross.click({ timeout: 1000 }).catch(() => null);
                    }
                }
            }
        }
        if (addButtons.length > 3) {
            for (let addButton of [addButtons[0], addButtons.length == 7 ? addButtons[2] : (addButtons.length == 5 ? addButtons[1] : addButtons[2])]) {
                let i = Math.floor(Math.random() * 5) + 1;

                for (let j = 1; j <= i; j++) {
                    await addButton.click();

                    await adminPage.waitForSelector('input[name="title"]:visible');

                    await adminPage.fill('input[name="title"]:visible', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

                    const inventories = await adminPage.$$('input[name="downloads"]:visible');
                    if (inventories.length > 0) {
                        await adminPage.fill('input[name="price"]:visible', (Math.floor(Math.random() * 500)).toString());
                        await adminPage.fill('input[name="downloads"]:visible', (Math.floor(Math.random() * 500)).toString());
                    }
                    const selects = await adminPage.$$('select[name="type"].custom-select:visible , select[name="sample_type"].custom-select:visible');

                    for (let select of selects) {
                        const option = Math.random() > 0.5 ? 'file' : 'url';

                        await select.selectOption({ value: option });
                    }

                    const files = await adminPage.$$('input[name="file"]:visible , input[name="sample_file"]:visible');
                    const urls = await adminPage.$$('input[name="url"]:visible , input[name="sample_url"]:visible');

                    const filePath = forms.getRandomImageFile();

                    for (let file of files) {
                        await file.setInputFiles(filePath);
                    }
                    for (let url of urls) {
                        await url.fill(forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));
                    }

                    await adminPage.click('p.text-xl.font-medium + button.primary-button:visible');

                    await adminPage.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

                    const crosses = await adminPage.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

                    if (crosses.length > 0) {
                        for (let cross of crosses) {
                            await cross.click();
                        }
                    }
                }
            }
        }

        await adminPage.click('.primary-button:visible');

        await expect(adminPage.getByText('Product updated successfully')).toBeVisible();
    });

    test('mass delete products',  async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/catalog/products`);
        await adminPage.waitForSelector('button.primary-button:has-text("Create Product")', { state: 'visible' });

        await adminPage.waitForSelector('.icon-uncheckbox');

        const checkboxs = await adminPage.$$('.icon-uncheckbox');

        for (let checkbox of checkboxs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await checkbox.click();
            }
        }

        await adminPage.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

        await adminPage.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
        await adminPage.click('a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

        await adminPage.click('button.transparent-button + button.primary-button:visible');

        await expect(adminPage.getByText('Selected Products Deleted Successfully')).toBeVisible();
    });

    test('mass update products',  async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/catalog/products`);
        await adminPage.waitForSelector('button.primary-button:has-text("Create Product")', { state: 'visible' });

        await adminPage.waitForSelector('.icon-uncheckbox');

        const checkboxs = await adminPage.$$('.icon-uncheckbox');

        for (let checkbox of checkboxs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await checkbox.click();
            }
        }

        await adminPage.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

        await adminPage.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
        await adminPage.hover('a[class="whitespace-no-wrap flex cursor-not-allowed justify-between gap-1.5 rounded-t px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

        const buttons = await adminPage.$$('a[class="whitespace-no-wrap block rounded-t px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await buttons[1].click();
        } else {
            await buttons[0].click();
        }

        await adminPage.click('button.transparent-button + button.primary-button:visible');

        await expect(adminPage.getByText('Selected Products Updated Successfully')).toBeVisible();
    });
});
