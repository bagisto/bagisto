import { test, expect, config } from '../../setup';
import { launchBrowser } from '../../utils/core';
import * as forms from '../../utils/form';
import logIn from '../../utils/login';

test.describe('attribute family management', () => {
    let browser;
    let context;
    let page;

    test.beforeEach(async () => {
        browser = await launchBrowser();
        context = await browser.newContext();
        page = await context.newPage();

        await logIn(page);
        await page.goto(`${config.baseUrl}/admin/catalog/families`);
        await page.waitForSelector('div.primary-button', { state: 'visible' });
    });

    test.afterEach(async () => {
        await browser.close();
    });

    test('create attribute family', async () => {
        await page.click('div.primary-button:visible');
        await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(' ')
            .replaceAll(' ', '');

        await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));
        await page.fill('input[name="code"]', concatenatedNames);

        const attributes = await page.$$('i.icon-drag');
        const targets = await page.$$('div[class="flex [&>*]:flex-1 gap-5 justify-between px-4"] > div > div[class="h-[calc(100vh-285px)] overflow-auto border-gray-200 pb-4 ltr:border-r rtl:border-l"]');

        for (const attribute of attributes) {
            const randomTargetIndex = Math.floor(Math.random() * targets.length);
            const target = targets[randomTargetIndex];

            const attributeBox = await attribute.boundingBox();
            const targetBox = await target.boundingBox();

            if (
                attributeBox
                && targetBox
            ) {
                const randomX = targetBox.x + Math.random() * targetBox.width;
                const randomY = targetBox.y + Math.random() * targetBox.height;

                await page.mouse.move(attributeBox.x + attributeBox.width / 2, attributeBox.y + attributeBox.height / 2);
                await page.mouse.down();
                await page.mouse.move(randomX, randomY);
                await page.mouse.up();
            }
        }

        await page.click('.primary-button:visible');
        await expect(page.getByText('Family created successfully.')).toBeVisible();
    });

    test('edit attribute family', async () => {
        await page.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

        const iconEdit = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');
        await iconEdit[0].click();

        await page.waitForSelector('input[name="name"]');
        await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));

        const attributes = await page.$$('i.icon-drag');
        const targets = await page.$$('div[class="flex [&>*]:flex-1 gap-5 justify-between px-4"] > div > div[class="h-[calc(100vh-285px)] overflow-auto border-gray-200 pb-4 ltr:border-r rtl:border-l"]');

        for (const attribute of attributes) {

            const randomTargetIndex = Math.floor(Math.random() * targets.length);
            const target = targets[randomTargetIndex];

            const attributeBox = await attribute.boundingBox();
            const targetBox = await target.boundingBox();

            if (
                attributeBox
                && targetBox
            ) {
                const randomX = targetBox.x + Math.random() * targetBox.width;
                const randomY = targetBox.y + Math.random() * targetBox.height;

                await page.mouse.move(attributeBox.x + attributeBox.width / 2, attributeBox.y + attributeBox.height / 2);
                await page.mouse.down();
                await page.mouse.move(randomX, randomY);
                await page.mouse.up();
            }
        }

        await page.click('.primary-button:visible');
        await expect(page.getByText('Family updated successfully.')).toBeVisible();
    });

    test('delete attribute family', async () => {
        await page.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        const iconDelete = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');
        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');
        await expect(page.getByText('Family deleted successfully.')).toBeVisible({ timeout: 5000 });
    });
});
