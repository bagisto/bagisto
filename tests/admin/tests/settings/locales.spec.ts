import { test, expect, config } from '../../utils/setup';
import { launchBrowser } from '../../utils/admin/coreHelper';
import  * as forms from '../../utils/admin/formHelper';
import logIn from '../../utils/admin/loginHelper';

test.describe('attribute management', () => {
    let browser;
    let context;
    let page;

    test.beforeEach(async () => {
        browser = await launchBrowser();
        context = await browser.newContext();
        page = await context.newPage();

        await logIn(page);

        await page.goto(`${config.baseUrl}/admin/settings/locales`);
    });

    test.afterEach(async () => {
        await browser.close();
    });

    test('create locale', async () => {
        await page.click('button[type="button"].primary-button:visible');

        await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        const select = await page.$('select[name="direction"]');

        const option = Math.random() > 0.5 ? 'ltr' : 'rtl';

        await select.selectOption({ value: option });

        const concatenatedNames = Array(5)
        .fill(null)
        .map(() => forms.generateRandomProductName())
        .join(' ')
        .replaceAll(' ', '');

        await page.fill('input[name="code"]', concatenatedNames);

        await page.$eval('label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="logo_path[]" accept="image/*">`);

        const image = await page.$('input[type="file"][name="logo_path[]"]');

        const filePath = forms.getRandomImageFile();

        await image.setInputFiles(filePath);

        await page.press('input[name="code"]', 'Enter');

        await expect(page.getByText('Locale created successfully.')).toBeVisible();
    });

    test('edit locale', async () => {
        await page.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconEdit[0].click();

        await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        const select = await page.$('select[name="direction"]');

        const option = Math.random() > 0.5 ? 'ltr' : 'rtl';

        await select.selectOption({ value: option });

        await page.$eval('label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="logo_path[]" accept="image/*">`);

        const image = await page.$('input[type="file"][name="logo_path[]"]');

        const filePath = forms.getRandomImageFile();

        await image.setInputFiles(filePath);

        await page.press('input[name="name"]', 'Enter');

        await expect(page.getByText('Locale updated successfully.')).toBeVisible();
    });

    test('delete locale', async () => {
        await page.waitForSelector('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Locale deleted successfully.')).toBeVisible();
    });
});
