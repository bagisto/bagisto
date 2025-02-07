import { test, expect, config } from '../setup';
import  * as forms from '../utils/form';

test.describe('cms management', () => {
    test('create page', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/cms`);
        await adminPage.click('a.primary-button:visible');

        await adminPage.waitForSelector('iframe');
        const iframe = await adminPage.$('iframe');

        const frame = await iframe.contentFrame();

        const randomHtmlContent = await forms.fillParagraphWithRandomHtml(50);

        await frame.$eval('body[data-id="content"] > p', (el, content) => {
            el.innerHTML = content;
        }, randomHtmlContent);

        await adminPage.evaluate((content) => {
            const htmlContent = document.querySelector('textarea[name="html_content"]');

            if (htmlContent instanceof HTMLTextAreaElement) {
                htmlContent.style.display = content;
            }
        }, 'block');

        await adminPage.fill('textarea[name="html_content"]', forms.fillParagraphWithRandomHtml(50).toString());

        await adminPage.evaluate((content) => {
            const htmlContent = document.querySelector('textarea[name="html_content"]');

            if (htmlContent instanceof HTMLTextAreaElement) {
                htmlContent.style.display = content;
            }
        }, 'none');

        const textareas = await adminPage.$$('textarea:visible');

        for (let textarea of textareas) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await textarea.fill(forms.generateRandomStringWithSpaces(200));
            }
        }

        const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const checkboxs = await adminPage.$$('input[type="checkbox"] + label.icon-uncheckbox:visible');

        for (let checkbox of checkboxs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (
                i % 2 == 1
                || checkboxs.length == 1
            ) {
                await checkbox.click();
            }
        }

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(' ')
            .replaceAll(' ', '-')
            .replaceAll('--', '-')
            .replaceAll('--', '-');

        await inputs[1].fill(concatenatedNames);

        await inputs[0].press('Enter');

        await expect(adminPage.getByText('CMS created successfully.')).toBeVisible();
    });

    test('edit page', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/cms`);

        await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        const iconEdit = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

        await iconEdit[0].click();

        await adminPage.waitForSelector('iframe');
        const iframe = await adminPage.$('iframe');

        const frame = await iframe.contentFrame();

        const randomHtmlContent = await forms.fillParagraphWithRandomHtml(50);

        await frame.$eval('body#tinymce', (el, content) => {
            el.innerHTML = content;
        }, randomHtmlContent);

        await adminPage.evaluate((content) => {
            const htmlContent = document.querySelector('textarea[name="en[html_content]"]');

            if (htmlContent instanceof HTMLTextAreaElement) {
                htmlContent.style.display = content;
            }
        }, 'block');

        await adminPage.fill('textarea[name="en[html_content]"]', forms.fillParagraphWithRandomHtml(50).toString());

        await adminPage.evaluate((content) => {
            const htmlContent = document.querySelector('textarea[name="en[html_content]"]');

            if (htmlContent instanceof HTMLTextAreaElement) {
                htmlContent.style.display = content;
            }
        }, 'none');

        const textareas = await adminPage.$$('textarea:visible');

        for (let textarea of textareas) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await textarea.fill(forms.generateRandomStringWithSpaces(200));
            }
        }

        const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(' ')
            .replaceAll(' ', '-')
            .replaceAll('--', '-')
            .replaceAll('--', '-');

        await inputs[1].fill(concatenatedNames);

        await adminPage.click('button[type="submit"][class="primary-button"]')

        await expect(adminPage.getByText('CMS updated successfully.')).toBeVisible();
    });

    test('delete page', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/cms`);

        await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        const iconDelete = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        await iconDelete[0].click();

        await adminPage.click('button.transparent-button + button.primary-button:visible');

        await expect(adminPage.getByText('CMS deleted successfully.')).toBeVisible();
    });

    test('mass delete pages', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/cms`);

        await adminPage.waitForSelector('.icon-uncheckbox');

        const checkboxs = await adminPage.$$('.icon-uncheckbox');

        await checkboxs[1].click();

        await adminPage.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

        await adminPage.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
        await adminPage.click('a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

        await adminPage.click('button.transparent-button + button.primary-button:visible');

        await expect(adminPage.getByText('Selected Data Deleted Successfully')).toBeVisible();
    });
});
