import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';
import { time } from 'console';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Create Product(simple, virtual, downloadable)', async () => {
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/catalog/products`);

    console.log('Create Product(simple, virtual, downloadable)');

    await page.click('button.primary-button:visible');

    const option = Math.random() > 0.3 ? 'simple' : (Math.random() > 0.5 ? 'downloadable' : 'virtual');

    await page.selectOption('select[name="type"]:visible', { value: option });

    const options = await page.$$eval('select[name="attribute_family_id"]:visible option', (options) => {
        return options.map(option => option.value);
    });

    const randomIndex = Math.floor(Math.random() * options.length);

    await page.selectOption('select[name="attribute_family_id"]:visible', options[randomIndex]);

    await page.fill('input[name="sku"]:visible', Math.random().toString(36).substring(7));
    await page.click('.box-shadow.absolute button.primary-button:visible');
    await forms.testForm(page);

    await page.waitForSelector('input[name="name"]#name');

    await page.fill('input[name="name"]#name', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

    await page.waitForSelector('iframe');
    const iframe = await page.$$('iframe');

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
        await page.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="images[files][]" accept="image/*">`);
    }

    const images = await page.$$('input[type="file"][name="images[files][]"]');

    const filePath = forms.getRandomImageFile();

    for (let image of images) {
        await image.setInputFiles(filePath);
    }

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="short_description"]', randomHtmlContent.toString());
    await page.fill('textarea[name="description"]', randomHtmlContent1.toString());

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'none');

    await page.fill('input[name="price"]#price', (Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());

    const weight = await page.$$('input[name="weight"]#weight');

    if (weight.length > 0) {
        await page.fill('input[name="weight"]#weight', (Math.floor(Math.random() * 10) * Math.floor(Math.random() * 10)).toString());
    }

    const checkboxs = await page.$$('input[type="checkbox"] + label, input[name="categories[]"] + span');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await checkbox.click();
        }
    }

    const inventories = await page.$$('input[name="inventories[1]"]:visible');
    if (inventories.length > 0) {
        await page.fill('input[name="inventories[1]"]', (Math.floor(Math.random() * 10) * Math.floor(Math.random() * 10)).toString());
    }

    const selects = await page.$$('select.custom-select');

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

    const textareas = await page.$$('textarea:visible');

    for (let textarea of textareas) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await textarea.fill(forms.generateRandomStringWithSpaces(200));
        }
    }

    const inputs = await page.$$('input[name="cost"], input[name="length"], input[name="width"], input[name="height"], input[name="product_number"]');

    for (let input of inputs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await input.fill((Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());
        }
    }

    const addButtons = await page.$$('div.secondary-button:not(:has-text("Add Option"))');

    for (let addButton of addButtons.slice(-3)) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await addButton.click();

            const randomProduct = forms.generateRandomProductName();
            await page.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

            const exists = await page.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

            if (exists) {
                const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox');

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

            page.click('div.primary-button:visible');

            await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

            const crosses = await page.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

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

                await page.waitForSelector('input[name="title"]:visible');

                await page.fill('input[name="title"]:visible', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

                const inventories = await page.$$('input[name="downloads"]:visible');
                if (inventories.length > 0) {
                    await page.fill('input[name="price"]:visible', (Math.floor(Math.random() * 500)).toString());
                    await page.fill('input[name="downloads"]:visible', (Math.floor(Math.random() * 500)).toString());
                }
                const selects = await page.$$('select[name="type"].custom-select:visible , select[name="sample_type"].custom-select:visible');

                for (let select of selects) {
                    const option = Math.random() > 0.5 ? 'file' : 'url';

                    await select.selectOption({ value: option });
                }

                const files = await page.$$('input[name="file"]:visible , input[name="sample_file"]:visible');
                const urls = await page.$$('input[name="url"]:visible , input[name="sample_url"]:visible');

                const filePath = forms.getRandomImageFile();

                for (let file of files) {
                    await file.setInputFiles(filePath);
                }
                for (let url of urls) {
                    await url.fill(forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));
                }

                await page.click('p.text-xl.font-medium + button.primary-button:visible');

                await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

                const crosses = await page.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

                if (crosses.length > 0) {
                    for (let cross of crosses) {
                        await cross.click();
                    }
                }
            }
        }
    }

    await page.click('.primary-button:visible');

    await expect(page.getByText('Product updated successfully')).toBeVisible();
});

test('Edit Product(simple, virtual, downloadable)',  async () => {
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/catalog/products`);

    await page.waitForSelector('a > span.icon-sort-right.cursor-pointer.text-2xl:visible', { timeout: 5000 }).catch(() => null);

    const iconRight = await page.$$('a > span.icon-sort-right.cursor-pointer.text-2xl');

    await iconRight[0].click();

    await page.waitForSelector('input[name="name"]#name');

    await page.fill('input[name="name"]#name', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

    await page.waitForSelector('iframe');
    const iframe = await page.$$('iframe');

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
        await page.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="images[files][]" accept="image/*">`);
    }

    const images = await page.$$('input[type="file"][name="images[files][]"]');

    const filePath = forms.getRandomImageFile();

    for (let image of images) {
        await image.setInputFiles(filePath);
    }

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="short_description"]', randomHtmlContent.toString());
    await page.fill('textarea[name="description"]', randomHtmlContent1.toString());

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'none');

    await page.fill('input[name="price"]#price', (Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());

    const weight = await page.$$('input[name="weight"]#weight');
    if (weight.length > 0) {
        await page.fill('input[name="weight"]#weight', (Math.floor(Math.random() * 10) * Math.floor(Math.random() * 10)).toString());
    }

    const checkboxs = await page.$$('input[type="checkbox"] + label, input[name="categories[]"] + span');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await checkbox.click();
        }
    }

    const inventories = await page.$$('input[name="inventories[1]"]:visible');
    if (inventories.length > 0) {
        await page.fill('input[name="inventories[1]"]', (Math.floor(Math.random() * 10) * Math.floor(Math.random() * 10)).toString());
    }

    const selects = await page.$$('select.custom-select');

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

    const textareas = await page.$$('textarea:visible');

    for (let textarea of textareas) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await textarea.fill(forms.generateRandomStringWithSpaces(200));
        }
    }

    const deleteBtns = await page.$$('p.cursor-pointer.text-red-600.transition-all');

    for (let deleteBtn of deleteBtns) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await deleteBtn.click();

            await page.click('button[type="button"].transparent-button + button[type="button"].primary-button');

            break;
        }
    }

    const inputs = await page.$$('input[name="cost"], input[name="length"], input[name="width"], input[name="height"], input[name="product_number"]');

    for (let input of inputs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await input.fill((Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());
        }
    }

    const addButtons = await page.$$('div.secondary-button:not(:has-text("Add Option"))');

    for (let addButton of addButtons.slice(-3)) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await addButton.click();

            const randomProduct = forms.generateRandomProductName();
            await page.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

            const exists = await page.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

            if (exists) {
                const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox');

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

            page.click('div.primary-button:visible');

            await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

            const crosses = await page.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

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

                await page.waitForSelector('input[name="title"]:visible');

                await page.fill('input[name="title"]:visible', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

                const inventories = await page.$$('input[name="downloads"]:visible');
                if (inventories.length > 0) {
                    await page.fill('input[name="price"]:visible', (Math.floor(Math.random() * 500)).toString());
                    await page.fill('input[name="downloads"]:visible', (Math.floor(Math.random() * 500)).toString());
                }
                const selects = await page.$$('select[name="type"].custom-select:visible , select[name="sample_type"].custom-select:visible');

                for (let select of selects) {
                    const option = Math.random() > 0.5 ? 'file' : 'url';

                    await select.selectOption({ value: option });
                }

                const files = await page.$$('input[name="file"]:visible , input[name="sample_file"]:visible');
                const urls = await page.$$('input[name="url"]:visible , input[name="sample_url"]:visible');

                const filePath = forms.getRandomImageFile();

                for (let file of files) {
                    await file.setInputFiles(filePath);
                }
                for (let url of urls) {
                    await url.fill(forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));
                }

                await page.click('p.text-xl.font-medium + button.primary-button:visible');

                await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

                const crosses = await page.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

                if (crosses.length > 0) {
                    for (let cross of crosses) {
                        await cross.click();
                    }
                }
            }
        }
    }

    await page.click('.primary-button:visible');

    await expect(page.getByText('Product updated successfully')).toBeVisible();
});

test('Create Product(bundle)',  async () => {
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/catalog/products`);

    console.log('Create Product(bundle)');

    await page.click('button.primary-button:visible');

    await page.selectOption('select[name="type"]:visible', 'bundle');

    const options = await page.$$eval('select[name="attribute_family_id"]:visible option', (options) => {
        return options.map(option => option.value);
    });

    const randomIndex = Math.floor(Math.random() * options.length);

    await page.selectOption('select[name="attribute_family_id"]:visible', options[randomIndex]);

    await page.fill('input[name="sku"]:visible', Math.random().toString(36).substring(7));
    await page.click('.box-shadow.absolute button.primary-button:visible');
    await forms.testForm(page);

    await page.waitForSelector('input[name="name"]#name', { timeout: 1000 }).catch(() => null);

    await page.fill('input[name="name"]#name', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

    await page.waitForSelector('iframe');
    const iframe = await page.$$('iframe');

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
        await page.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="images[files][]" accept="image/*">`);
    }

    const images = await page.$$('input[type="file"][name="images[files][]"]');

    const filePath = forms.getRandomImageFile();

    for (let image of images) {
        await image.setInputFiles(filePath);
    }

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="short_description"]', randomHtmlContent.toString());
    await page.fill('textarea[name="description"]', randomHtmlContent1.toString());

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'none');

    const checkboxs = await page.$$('input[type="checkbox"] + label, input[name="categories[]"] + span');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await checkbox.click();
        }
    }

    const selects = await page.$$('select.custom-select');

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

    const textareas = await page.$$('textarea:visible');

    for (let textarea of textareas) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await textarea.fill(forms.generateRandomStringWithSpaces(200));
        }
    }

    const input = await page.$('input[name="product_number"]');

    if (input != null) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await input.fill((Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());
        }
    }

    const addButtons = await page.$$('div.secondary-button:visible');

    for (let addButton of addButtons.slice(-3)) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await addButton.click();

            const randomProduct = forms.generateRandomProductName();
            await page.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

            const exists = await page.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

            if (exists) {
                const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox');

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

            page.click('div.primary-button:visible');

            await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

            const crosses = await page.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

            if (crosses.length > 0) {
                for (let cross of crosses) {
                    await cross.click({ timeout: 1000 }).catch(() => null);
                }
            }
        }
    }

    let i = Math.floor(Math.random() * 3) + 1;

    for (let j = 1; j <= i; j++) {
        await addButtons[0].click();

        await page.waitForSelector('input[name="label"]:visible');

        await page.fill('input[name="label"]:visible', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

        const selects = await page.$$('select[name="type"].custom-select:visible , select[name="is_required"].custom-select:visible');

        for (let select of selects) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            const randomIndex = Math.floor(Math.random() * options.length);

            await select.selectOption(options[randomIndex]);
        }

        await page.click('.box-shadow.absolute button.primary-button:visible');

        await page.waitForTimeout(1000);

        const addBtn = await page.$$('.flex.justify-between.gap-5.p-4 > .flex.items-center.gap-x-5 > p.cursor-pointer.font-semibold.text-blue-600.transition-all');

        let k = Math.floor(Math.random() * 2) + 1;

        for (let l = 1; l <= k; l++) {
            await addBtn[addBtn.length - 2].click();

            const randomProduct = forms.generateRandomProductName();
            await page.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

            await page.waitForTimeout(3000);

            const exists = await page.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

            if (exists) {
                const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox');

                for (let checkbox of checkboxs) {
                    let i = Math.floor(Math.random() * 10) + 1;

                    if (
                        i % 3 == 1
                        || checkboxs.length < 2
                    ) {
                        await checkbox.scrollIntoViewIfNeeded();
                        await checkbox.click();
                    }
                }
            }

            page.click('div.primary-button:visible');

            await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

            const crosses = await page.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

            if (crosses.length > 0) {
                for (let cross of crosses) {
                    await cross.click({ timeout: 1000 }).catch(() => null);
                }
            }
        }
    }

    await page.waitForTimeout(1000);

    const productBtns = await page.$$('input[type="radio"].peer.sr-only + label:visible, input[type="checkbox"].peer.sr-only + label:visible');

    for (let productBtn of productBtns) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await productBtn.click();
        }
    }

    const itemQty = await page.$$('input[class="min-h-[39px] w-[86px] rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"]:visible');

    for (let element of itemQty) {
        const qty = Math.floor(Math.random() * 10) + 1;

        await element.fill(qty.toString());
    }

    await page.click('.primary-button:visible');

    await expect(page.getByText('Product updated successfully')).toBeVisible();
});

test('Edit Product(bundle)',  async () => {
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/catalog/products`);

    console.log('Edit Product(bundle)');

    await page.waitForSelector('a > span.icon-sort-right.cursor-pointer.text-2xl:visible', { timeout: 5000 }).catch(() => null);

    const iconRight = await page.$$('a > span.icon-sort-right.cursor-pointer.text-2xl');

    await iconRight[0].click();

    await page.waitForSelector('input[name="name"]#name');

    await page.fill('input[name="name"]#name', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

    await page.waitForSelector('iframe');
    const iframe = await page.$$('iframe');

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
        await page.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="images[files][]" accept="image/*">`);
    }

    const images = await page.$$('input[type="file"][name="images[files][]"]');

    const filePath = forms.getRandomImageFile();

    for (let image of images) {
        await image.setInputFiles(filePath);
    }

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="short_description"]', randomHtmlContent.toString());
    await page.fill('textarea[name="description"]', randomHtmlContent1.toString());

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'none');

    const checkboxs = await page.$$('input[type="checkbox"] + label, input[name="categories[]"] + span');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await checkbox.click();
        }
    }

    const selects = await page.$$('select.custom-select');

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

    const textareas = await page.$$('textarea:visible');

    for (let textarea of textareas) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await textarea.fill(forms.generateRandomStringWithSpaces(200));
        }
    }

    const deleteBtns = await page.$$('p.cursor-pointer.text-red-600.transition-all');

    for (let deleteBtn of deleteBtns) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await deleteBtn.click();

            await page.click('button[type="button"].transparent-button + button[type="button"].primary-button');

            break;
        }
    }

    const input = await page.$('input[name="product_number"]');

    if (input != null) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await input.fill((Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());
        }
    }

    const addButtons = await page.$$('div.secondary-button:visible');

    for (let addButton of addButtons.slice(-3)) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await addButton.click();

            const randomProduct = forms.generateRandomProductName();
            await page.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

            const exists = await page.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

            if (exists) {
                const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox');

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

            page.click('div.primary-button:visible');

            await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

            const crosses = await page.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

            if (crosses.length > 0) {
                for (let cross of crosses) {
                    await cross.click({ timeout: 1000 }).catch(() => null);
                }
            }
        }
    }

    let i = Math.floor(Math.random() * 3) + 1;

    for (let j = 1; j <= i; j++) {
        await addButtons[0].click();

        await page.waitForSelector('input[name="label"]:visible');

        await page.fill('input[name="label"]:visible', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

        const selects = await page.$$('select[name="type"].custom-select:visible , select[name="is_required"].custom-select:visible');

        for (let select of selects) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            const randomIndex = Math.floor(Math.random() * options.length);

            await select.selectOption(options[randomIndex]);
        }

        await page.click('.box-shadow.absolute button.primary-button:visible');

        await page.waitForTimeout(1000);

        const addBtn = await page.$$('.flex.justify-between.gap-5.p-4 > .flex.items-center.gap-x-5 > p.cursor-pointer.font-semibold.text-blue-600.transition-all');

        let k = Math.floor(Math.random() * 2) + 1;

        for (let l = 1; l <= k; l++) {
            await addBtn[addBtn.length - 2].click();

            const randomProduct = forms.generateRandomProductName();

            await page.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

            await page.waitForTimeout(3000);

            const exists = await page.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

            if (exists) {
                const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox');

                for (let checkbox of checkboxs) {
                    let i = Math.floor(Math.random() * 10) + 1;

                    if (
                        i % 3 == 1
                        || checkboxs.length < 2
                    ) {
                        await checkbox.scrollIntoViewIfNeeded();
                        await checkbox.click();
                    }
                }
            }

            page.click('div.primary-button:visible');

            await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

            const crosses = await page.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

            if (crosses.length > 0) {
                for (let cross of crosses) {
                    await cross.click({ timeout: 1000 }).catch(() => null);
                }
            }
        }
    }

    await page.waitForTimeout(1000);

    const productBtns = await page.$$('input[type="radio"].peer.sr-only + label:visible, input[type="checkbox"].peer.sr-only + label:visible');

    for (let productBtn of productBtns) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await productBtn.click();
        }
    }

    const itemQty = await page.$$('input[class="min-h-[39px] w-[86px] rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"]:visible');

    for (let element of itemQty) {
        const qty = Math.floor(Math.random() * 10) + 1;

        await element.fill(qty.toString());
    }

    await page.click('.primary-button:visible');

    await expect(page.getByText('Product updated successfully')).toBeVisible();
});

test('Create Product(grouped)',  async () => {
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/catalog/products`);

    console.log('Create Product(grouped)');

    await page.click('button.primary-button:visible');

    await page.selectOption('select[name="type"]:visible', 'grouped');

    const options = await page.$$eval('select[name="attribute_family_id"]:visible option', (options) => {
        return options.map(option => option.value);
    });

    const randomIndex = Math.floor(Math.random() * options.length);

    await page.selectOption('select[name="attribute_family_id"]:visible', options[randomIndex]);

    await page.fill('input[name="sku"]:visible', Math.random().toString(36).substring(7));
    await page.click('.box-shadow.absolute button.primary-button:visible');
    await forms.testForm(page);

    await page.waitForSelector('input[name="name"]#name', { timeout: 1000 }).catch(() => null);

    await page.fill('input[name="name"]#name', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

    await page.waitForSelector('iframe');
    const iframe = await page.$$('iframe');

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
        await page.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="images[files][]" accept="image/*">`);
    }

    const images = await page.$$('input[type="file"][name="images[files][]"]');

    const filePath = forms.getRandomImageFile();

    for (let image of images) {
        await image.setInputFiles(filePath);
    }

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="short_description"]', randomHtmlContent.toString());
    await page.fill('textarea[name="description"]', randomHtmlContent1.toString());

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'none');

    const checkboxs = await page.$$('input[type="checkbox"] + label, input[name="categories[]"] + span');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await checkbox.click();
        }
    }

    const selects = await page.$$('select.custom-select');

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

    const textareas = await page.$$('textarea:visible');

    for (let textarea of textareas) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await textarea.fill(forms.generateRandomStringWithSpaces(200));
        }
    }

    const input = await page.$('input[name="product_number"]');

    if (input != null) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await input.fill((Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());
        }
    }

    const addButtons = await page.$$('div.secondary-button:visible');

    for (let addButton of addButtons.slice(-3)) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await addButton.click();

            const randomProduct = forms.generateRandomProductName();
            await page.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

            const exists = await page.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

            if (exists) {
                const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox');

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

            page.click('div.primary-button:visible');

            await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

            const crosses = await page.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

            if (crosses.length > 0) {
                for (let cross of crosses) {
                    await cross.click({ timeout: 1000 }).catch(() => null);
                }
            }
        }
    }

    let i = Math.floor(Math.random() * 5) + 1;

    for (let j = 1; j <= i; j++) {
        await addButtons[0].click();

        const randomProduct = forms.generateRandomProductName();
        await page.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

        await page.waitForTimeout(1000);

        const exists = await page.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

        if (exists) {
            const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox');

            for (let checkbox of checkboxs) {
                let i = Math.floor(Math.random() * 10) + 1;

                if (
                    i % 3 == 1
                    || checkboxs.length < 2
                ) {
                    await checkbox.scrollIntoViewIfNeeded();
                    await checkbox.click();
                }
            }
        }

        page.click('div.primary-button:visible');
    }

    await page.waitForTimeout(1000);

    const itemQty = await page.$$('input[class="min-h-[39px] w-[86px] rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"]:visible');

    for (let element of itemQty) {
        const qty = Math.floor(Math.random() * 10) + 1;

        await element.fill(qty.toString());
    }

    await page.click('.primary-button:visible');

    await expect(page.getByText('Product updated successfully')).toBeVisible();
});

test('Edit Product(grouped)',  async () => {
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/catalog/products`);

    console.log('Edit Product(grouped)');

    await page.waitForSelector('a > span.icon-sort-right.cursor-pointer.text-2xl:visible', { timeout: 5000 }).catch(() => null);

    const iconRight = await page.$$('a > span.icon-sort-right.cursor-pointer.text-2xl');

    await iconRight[0].click();

    await page.waitForSelector('input[name="name"]#name');

    await page.fill('input[name="name"]#name', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

    await page.waitForSelector('iframe');
    const iframe = await page.$$('iframe');

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
        await page.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="images[files][]" accept="image/*">`);
    }

    const images = await page.$$('input[type="file"][name="images[files][]"]');

    const filePath = forms.getRandomImageFile();

    for (let image of images) {
        await image.setInputFiles(filePath);
    }

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="short_description"]', randomHtmlContent.toString());
    await page.fill('textarea[name="description"]', randomHtmlContent1.toString());

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'none');

    const checkboxs = await page.$$('input[type="checkbox"] + label, input[name="categories[]"] + span');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await checkbox.click();
        }
    }

    const selects = await page.$$('select.custom-select');

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

    const textareas = await page.$$('textarea:visible');

    for (let textarea of textareas) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await textarea.fill(forms.generateRandomStringWithSpaces(200));
        }
    }

    const deleteBtns = await page.$$('p.cursor-pointer.text-red-600.transition-all');

    for (let deleteBtn of deleteBtns) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await deleteBtn.click();

            await page.click('button[type="button"].transparent-button + button[type="button"].primary-button');

            break;
        }
    }

    const input = await page.$('input[name="product_number"]');

    if (input != null) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await input.fill((Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());
        }
    }

    const addButtons = await page.$$('div.secondary-button:visible');

    for (let addButton of addButtons.slice(-3)) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await addButton.click();

            const randomProduct = forms.generateRandomProductName();
            await page.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

            const exists = await page.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

            if (exists) {
                const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox');

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

            page.click('div.primary-button:visible');

            await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

            const crosses = await page.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

            if (crosses.length > 0) {
                for (let cross of crosses) {
                    await cross.click({ timeout: 1000 }).catch(() => null);
                }
            }
        }
    }

    let i = Math.floor(Math.random() * 5) + 1;

    for (let j = 1; j <= i; j++) {
        await addButtons[0].click();

        const randomProduct = forms.generateRandomProductName();
        await page.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

        await page.waitForTimeout(1000);

        const exists = await page.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

        if (exists) {
            const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox');

            for (let checkbox of checkboxs) {
                let i = Math.floor(Math.random() * 10) + 1;

                if (
                    i % 3 == 1
                    || checkboxs.length < 2
                ) {
                    await checkbox.scrollIntoViewIfNeeded();
                    await checkbox.click();
                }
            }
        }

        page.click('div.primary-button:visible');
    }

    await page.waitForTimeout(1000);

    const itemQty = await page.$$('input[class="min-h-[39px] w-[86px] rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"]:visible');

    for (let element of itemQty) {
        const qty = Math.floor(Math.random() * 10) + 1;

        await element.fill(qty.toString());
    }

    await page.click('.primary-button:visible');

    await expect(page.getByText('Product updated successfully')).toBeVisible();
});

test('Create Product(configurable)',  async () => {
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/catalog/products`);

    console.log('Create Product(configurable)');

    await page.click('button.primary-button:visible');

    await page.selectOption('select[name="type"]:visible', 'configurable');

    const options = await page.$$eval('select[name="attribute_family_id"]:visible option', (options) => {
        return options.map(option => option.value);
    });

    const randomIndex = Math.floor(Math.random() * options.length);

    await page.selectOption('select[name="attribute_family_id"]:visible', options[randomIndex]);

    await page.fill('input[name="sku"]:visible', Math.random().toString(36).substring(7));
    await page.click('.box-shadow.absolute button.primary-button:visible');

    await forms.testForm(page);

    await page.waitForSelector('span[class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"]');

    const varients = await page.$$('span[class="icon-cross cursor-pointer text-lg text-white ltr:ml-1.5 rtl:mr-1.5"]');

    for (let varient of varients) {
        let number = Math.floor(Math.random() * 15) + 1;

        if (number % 3 == 1) {
            await varient.click({timeout: 500}).catch(() => null);
        }
    }

    await page.click('.box-shadow.absolute button.primary-button:visible');

    const exists = await page.waitForSelector('input[name="name"]#name');

    await page.fill('input[name="name"]#name', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

    await page.waitForSelector('iframe');
    const iframe = await page.$$('iframe');

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
        await page.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="images[files][]" accept="image/*">`);
    }

    const images = await page.$$('input[type="file"][name="images[files][]"]');

    const filePath = forms.getRandomImageFile();

    for (let image of images) {
        await image.setInputFiles(filePath);
    }

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="short_description"]', randomHtmlContent.toString());
    await page.fill('textarea[name="description"]', randomHtmlContent1.toString());

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'none');

    const checkboxs = await page.$$('input[type="checkbox"] + label:visible, input[name="categories[]"] + span:visible');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await checkbox.click();
        }
    }

    const selects = await page.$$('select.custom-select');

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

    const textareas = await page.$$('textarea:visible');

    for (let textarea of textareas) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await textarea.fill(forms.generateRandomStringWithSpaces(200));
        }
    }

    const input = await page.$('input[name="product_number"]');

    if (input != null) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await input.fill((Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());
        }
    }

    const addButtons = await page.$$('div.secondary-button:visible');

    for (let addButton of addButtons.slice(-3)) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await addButton.click();

            const randomProduct = forms.generateRandomProductName();
            await page.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

            const exists = await page.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

            if (exists) {
                const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox');

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

            page.click('div.primary-button:visible');

            await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

            const crosses = await page.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

            if (crosses.length > 0) {
                for (let cross of crosses) {
                    await cross.click({ timeout: 1000 }).catch(() => null);
                }
            }
        }
    }

    const deleteBtns = await page.$$('p.cursor-pointer.text-red-600.transition-all');

    for (let deleteBtn of deleteBtns) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await deleteBtn.click();

            await page.click('button[type="button"].transparent-button + button[type="button"].primary-button');

            break;
        }
    }

    const editBtns = await page.$$('p.cursor-pointer.text-emerald-600.transition-all');

    for (let editBtn of editBtns) {
        await editBtn.click();

        await page.fill('div[class="fixed z-[10002] bg-white dark:bg-gray-900 max-sm:!w-full inset-y-0 ltr:right-0 rtl:left-0"] input[name="name"]:visible', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500) + 1));

        await page.fill('div[class="fixed z-[10002] bg-white dark:bg-gray-900 max-sm:!w-full inset-y-0 ltr:right-0 rtl:left-0"] input[name="sku"]:visible', Math.random().toString(36).substring(7));

        const inputs = await page.$$('input[name="inventories[1]"]:visible, input[name="price"]:visible, input[name="weight"]:visible');

        for (let input of inputs) {
            await input.fill((Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200) + 1).toString());
        }

        const select = await page.$('div[class="fixed z-[10002] bg-white dark:bg-gray-900 max-sm:!w-full inset-y-0 ltr:right-0 rtl:left-0"] select.custom-select:visible');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 0) {
            const randomIndex = Math.floor(Math.random() * options.length);

            await select.selectOption(options[randomIndex]);
        }

        await inputs[0].press('Enter');

        await page.waitForTimeout(2000);
    }

    await page.click('.primary-button:visible');

    await expect(page.getByText('Product updated successfully')).toBeVisible();
});

test('Edit Product(configurable)',  async () => {
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/catalog/products`);

    console.log('Edit Product(configurable)');

    await page.click('span[class="icon-filter text-2xl"]:visible');

    const clearBtn = await page.$$('p[class="cursor-pointer text-xs font-medium leading-6 text-blue-600"]:visible');

    for (let i = 0; i < clearBtn.length; i++) {
        await clearBtn[i].click();
    }

    const typeBtn = await page.$$('button[class="inline-flex w-full cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]');

    await typeBtn[typeBtn.length - 1].scrollIntoViewIfNeeded();
    await typeBtn[typeBtn.length - 1].click();

    await page.waitForSelector('div[class="absolute z-10 w-max rounded bg-white shadow-[0px_8px_10px_0px_rgba(0,0,0,0.20),0px_6px_30px_0px_rgba(0,0,0,0.12),0px_16px_24px_0px_rgba(0,0,0,0.14)] dark:bg-gray-900"]:visible');

    const typeSelect = await page.$$('div[class="absolute z-10 w-max rounded bg-white shadow-[0px_8px_10px_0px_rgba(0,0,0,0.20),0px_6px_30px_0px_rgba(0,0,0,0.12),0px_16px_24px_0px_rgba(0,0,0,0.14)] dark:bg-gray-900"]:visible');

    const liSelects = await typeSelect[typeSelect.length - 1].$$('ul > li');

    liSelects[liSelects.length - 1].scrollIntoViewIfNeeded();
    liSelects[1].click();

    const button = await page.$('button[type="button"][class="secondary-button w-full"]:visible');

    if (button) {
        await button.scrollIntoViewIfNeeded();
    } else {
        throw new Error('Button not found');
    }

    await page.click('button[type="button"][class="secondary-button w-full"]:visible');

    await page.waitForSelector('a > span.icon-sort-right.cursor-pointer.text-2xl:visible', { timeout: 5000 }).catch(() => null);

    const iconRight = await page.$$('a > span.icon-sort-right.cursor-pointer.text-2xl');

    await iconRight[0].click();

    await page.waitForSelector('input[name="name"]#name');

    await page.fill('input[name="name"]#name', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500)));

    await page.waitForSelector('iframe');
    const iframe = await page.$$('iframe');

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
        await page.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="images[files][]" accept="image/*">`);
    }

    const images = await page.$$('input[type="file"][name="images[files][]"]');

    const filePath = forms.getRandomImageFile();

    for (let image of images) {
        await image.setInputFiles(filePath);
    }

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="short_description"]', randomHtmlContent.toString());
    await page.fill('textarea[name="description"]', randomHtmlContent1.toString());

    await page.evaluate((content) => {
        const shortDescription = document.querySelector('textarea[name="short_description"]#short_description');
        const description = document.querySelector('textarea[name="description"]#description');

        if (shortDescription instanceof HTMLTextAreaElement) {
            shortDescription.style.display = content;
        }

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'none');

    const checkboxs = await page.$$('input[type="checkbox"] + label:visible, input[name="categories[]"] + span:visible');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await checkbox.click();
        }
    }

    const selects = await page.$$('select.custom-select');

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

    const textareas = await page.$$('textarea:visible');

    for (let textarea of textareas) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await textarea.fill(forms.generateRandomStringWithSpaces(200));
        }
    }

    const deleteBtns = await page.$$('p.cursor-pointer.text-red-600.transition-all');

    for (let deleteBtn of deleteBtns) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await deleteBtn.click();

            await page.click('button[type="button"].transparent-button + button[type="button"].primary-button');

            break;
        }
    }

    const editBtns = await page.$$('p.cursor-pointer.text-emerald-600.transition-all');

    for (let editBtn of editBtns) {
        await editBtn.click();

        await page.fill('div[class="fixed z-[10002] bg-white dark:bg-gray-900 max-sm:!w-full inset-y-0 ltr:right-0 rtl:left-0"] input[name="name"]:visible', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 500) + 1));

        await page.fill('div[class="fixed z-[10002] bg-white dark:bg-gray-900 max-sm:!w-full inset-y-0 ltr:right-0 rtl:left-0"] input[name="sku"]:visible', Math.random().toString(36).substring(7));

        const inputs = await page.$$('input[name="inventories[1]"]:visible, input[name="price"]:visible, input[name="weight"]:visible');

        for (let input of inputs) {
            await input.fill((Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200) + 1).toString());
        }

        const select = await page.$('div[class="fixed z-[10002] bg-white dark:bg-gray-900 max-sm:!w-full inset-y-0 ltr:right-0 rtl:left-0"] select.custom-select:visible');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 0) {
            const randomIndex = Math.floor(Math.random() * options.length);

            await select.selectOption(options[randomIndex]);
        }

        await inputs[0].press('Enter');

        await page.waitForTimeout(2000);
    }

    const input = await page.$('input[name="product_number"]');

    if (input != null) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await input.fill((Math.floor(Math.random() * 200) * Math.floor(Math.random() * 200)).toString());
        }
    }

    const addButtons = await page.$$('div.secondary-button:visible');

    for (let addButton of addButtons.slice(-3)) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await addButton.click();

            const randomProduct = forms.generateRandomProductName();
            await page.fill('input[type="text"][class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', randomProduct);

            const exists = await page.waitForSelector('input[type="checkbox"] + label.icon-uncheckbox', { timeout: 5000 }).catch(() => null);

            if (exists) {
                const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox');

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

            page.click('div.primary-button:visible');

            await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

            const crosses = await page.$$('div.absolute.top-3 > span.icon-cross.cursor-pointer.text-3xl:visible');

            if (crosses.length > 0) {
                for (let cross of crosses) {
                    await cross.click({ timeout: 1000 }).catch(() => null);
                }
            }
        }
    }

    await page.waitForTimeout(1000);

    await page.click('.primary-button:visible');

    await expect(page.getByText('Product updated successfully')).toBeVisible();
});

test('Mass Delete Products',  async () => {
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/catalog/products`);

    console.log('Mass Delete Products');

    await page.waitForTimeout(5000);

    const checkboxs = await page.$$('.icon-uncheckbox');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await checkbox.click();
        }
    }

    await page.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

    await page.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
    await page.click('a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Selected Products Deleted Successfully')).toBeVisible();
});

test('Mass Update Products',  async () => {
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/catalog/products`);

    console.log('Mass Update Products');

    await page.waitForTimeout(5000);

    const checkboxs = await page.$$('.icon-uncheckbox');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await checkbox.click();
        }
    }

    await page.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

    await page.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
    await page.hover('a[class="whitespace-no-wrap flex cursor-not-allowed justify-between gap-1.5 rounded-t px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

    const buttons = await page.$$('a[class="whitespace-no-wrap block rounded-t px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

    let i = Math.floor(Math.random() * 10) + 1;

    if (i % 2 == 1) {
        await buttons[1].click();
    } else {
        await buttons[0].click();
    }

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Selected Products Updated Successfully')).toBeVisible();
});
