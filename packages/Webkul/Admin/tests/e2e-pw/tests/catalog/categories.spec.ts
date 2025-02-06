import { test, expect, config } from '../../setup';
import { launchBrowser } from '../../utils/core';
import  * as forms from '../../utils/form';
import logIn from '../../utils/login';

test.describe('category management', () => {
    let browser;
    let context;
    let page;

    test.beforeEach(async () => {
        browser = await launchBrowser();
        context = await browser.newContext();
        page = await context.newPage();

        await logIn(page);
        await page.goto(`${config.baseUrl}/admin/catalog/categories`);
        await page.waitForSelector('div.primary-button', { state: 'visible' });
    });

    test.afterEach(async () => {
        await browser.close();
    });

    test('create category', async () => {
        await page.click('div.primary-button:visible');

        await page.waitForSelector('input[name="name"]#name');
        await page.fill('input[name="position"]', (Math.floor(Math.random() * 100)).toString());

        const parents = await page.$$('input[name="parent_id"] + span[class="icon-radio-normal peer-checked:icon-radio-selected mr-1 cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"]');
        await parents[Math.floor(Math.random() * ((parents.length - 1) - 0 + 1)) + 0].click();

        await page.waitForSelector('iframe');
        const iframe = await page.$('iframe');
        const frame = await iframe.contentFrame();
        const randomHtmlContent = await forms.fillParagraphWithRandomHtml(50);
        await frame.waitForSelector('body[data-id="description"] > p');
        await frame.$eval('body[data-id="description"] > p', (el, content) => {
            el.innerHTML = content;
        }, randomHtmlContent);

        await page.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="logo_path[]" accept="image/*"><input type="file" name="banner_path[]" accept="image/*">`);
        const images = await page.$$('input[type="file"][name="logo_path[]"], input[type="file"][name="banner_path[]"]');
        const filePath = forms.getRandomImageFile();
        for (let image of images) {
            await image.setInputFiles(filePath);
        }

        await page.evaluate((content) => {
            const description = document.querySelector('textarea[name="description"]#description');

            if (description instanceof HTMLTextAreaElement) {
                description.style.display = content;
            }
        }, 'block');
        await page.fill('textarea[name="description"]', randomHtmlContent.toString());
        await page.evaluate((content) => {
            const description = document.querySelector('textarea[name="description"]#description');

            if (description instanceof HTMLTextAreaElement) {
                description.style.display = content;
            }
        }, 'none');

        const textareas = await page.$$('textarea:visible, input[name="meta_title"], input[name="meta_keywords"]');
        for (let textarea of textareas) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await textarea.fill(forms.generateRandomStringWithSpaces(200));
            }
        }

        const selects = await page.$$('select.custom-select');
        for (let select of selects) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 0) {
                const randomIndex = Math.floor(Math.random() * options.length);

                await select.selectOption(options[randomIndex]);
            }
        }

        const checkboxes = await page.$$('input[type="checkbox"] + label');
        for (let checkbox of checkboxes) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 == 1) {
                await checkbox.click();
            }
        }

        await page.fill('input[name="name"]#name', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));
        await page.fill('input[name="slug"]#slug', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));

        await page.click('.primary-button:visible');

        await expect(page.getByText('Category created successfully.')).toBeVisible();
    });

    test('edit category', async () => {
        await page.waitForSelector('span.cursor-pointer.icon-edit', { visible: true });
        const iconEdit = await page.$$('span.cursor-pointer.icon-edit');
        await iconEdit[0].click();

        await page.waitForSelector('input[name="en[name]"]');

        await page.fill('input[name="position"]', (Math.floor(Math.random() * 100)).toString());

        const parents = await page.$$('input[name="parent_id"] + span[class="icon-radio-normal peer-checked:icon-radio-selected mr-1 cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"]');
        await parents[Math.floor(Math.random() * ((parents.length - 1) - 0 + 1)) + 0].click();

        await page.waitForSelector('iframe');
        const iframe = await page.$('iframe');
        const frame = await iframe.contentFrame();
        const randomHtmlContent = await forms.fillParagraphWithRandomHtml(50);
        await frame.waitForSelector('body[data-id="description"] > p');
        await frame.$eval('body[data-id="description"] > p', (el, content) => {
            el.innerHTML = content;
        }, randomHtmlContent);

        await page.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="logo_path[]" accept="image/*"><input type="file" name="banner_path[]" accept="image/*">`);
        const images = await page.$$('input[type="file"][name="logo_path[]"], input[type="file"][name="banner_path[]"]');
        const filePath = forms.getRandomImageFile();
        for (let image of images) {
            await image.setInputFiles(filePath);
        }

        await page.evaluate((content) => {
            const description = document.querySelector('textarea[name="en[description]"]#description');

            if (description instanceof HTMLTextAreaElement) {
                description.style.display = content;
            }
        }, 'block');
        await page.fill('textarea[name="en[description]"]', randomHtmlContent.toString());
        await page.evaluate((content) => {
            const description = document.querySelector('textarea[name="en[description]"]#description');

            if (description instanceof HTMLTextAreaElement) {
                description.style.display = content;
            }
        }, 'none');

        const textareas = await page.$$('textarea:visible, input[name="en[meta_title]"], input[name="en[meta_keywords]"]');
        for (let textarea of textareas) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await textarea.fill(forms.generateRandomStringWithSpaces(200));
            }
        }

        const selects = await page.$$('select.custom-select');
        for (let select of selects) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 0) {
                const randomIndex = Math.floor(Math.random() * options.length);

                await select.selectOption(options[randomIndex]);
            }
        }

        const checkboxes = await page.$$('input[type="checkbox"] + label');
        for (let checkbox of checkboxes) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 == 1) {
                await checkbox.click();
            }
        }

        await page.fill('input[name="en[name]"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));
        await page.fill('input[name="en[slug]"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));

        await page.click('.primary-button:visible');

        await expect(page.getByText('Category updated successfully.')).toBeVisible();
    });

    test('delete category', async () => {
        await page.waitForSelector('span.cursor-pointer.icon-delete', { visible: true });
        const iconDelete = await page.$$('span.cursor-pointer.icon-delete');
        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('The category has been successfully deleted.')).toBeVisible();
    });

    test('mass update categories', async () => {
        await page.waitForSelector('.icon-uncheckbox:visible', { visible: true });
        const checkboxes = await page.$$('.icon-uncheckbox:visible');
        await checkboxes[1].click();

        let selectActionButton = await page.waitForSelector('button:has-text("Select Action")', { timeout: 1000 });
        await selectActionButton.click();

        await page.hover('a:has-text("Update Status")', { timeout: 1000 });
        await page.waitForSelector('a:has-text("Active"), a:has-text("Inactive")', { visible: true, timeout: 1000 });
        await page.click('a:has-text("Active")');

        await page.waitForSelector('text=Are you sure', { visible: true, timeout: 1000 });
        const agreeButton = await page.locator('button.primary-button:has-text("Agree")');

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(page.getByText('Category updated successfully.')).toBeVisible();
    });

    test('mass delete categories', async () => {
        await page.waitForSelector('.icon-uncheckbox:visible', { visible: true });
        const checkboxes = await page.$$('.icon-uncheckbox:visible');
        await checkboxes[1].click();

        let selectActionButton = await page.waitForSelector('button:has-text("Select Action")', { timeout: 1000 });
        await selectActionButton.click();

        await page.click('a:has-text("Delete")', { timeout: 1000 });

        await page.waitForSelector('text=Are you sure', { visible: true, timeout: 1000 });
        const agreeButton = await page.locator('button.primary-button:has-text("Agree")');

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(page.getByText('The category has been successfully deleted.')).toBeVisible();
    });
});
