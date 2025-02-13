import { test, expect } from '../../setup';
import  * as forms from '../../utils/form';

test.describe('locale management', () => {
    test('create locale', async ({ adminPage }) => {
        await adminPage.goto('admin/settings/locales');

        await adminPage.click('button[type="button"].primary-button:visible');

        await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        const select = await adminPage.$('select[name="direction"]');

        const option = Math.random() > 0.5 ? 'ltr' : 'rtl';

        await select.selectOption({ value: option });

        const concatenatedNames = Array(5)
        .fill(null)
        .map(() => forms.generateRandomProductName())
        .join(' ')
        .replaceAll(' ', '');

        await adminPage.fill('input[name="code"]', concatenatedNames);

        await adminPage.$eval('label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="logo_path[]" accept="image/*">`);

        const image = await adminPage.$('input[type="file"][name="logo_path[]"]');

        const filePath = forms.getRandomImageFile();

        await image.setInputFiles(filePath);

        await adminPage.press('input[name="code"]', 'Enter');

        await expect(adminPage.getByText('Locale created successfully.')).toBeVisible();
    });

    test('edit locale', async ({ adminPage }) => {
        await adminPage.goto('admin/settings/locales');

        await adminPage.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconEdit = await adminPage.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconEdit[0].click();

        await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        const select = await adminPage.$('select[name="direction"]');

        const option = Math.random() > 0.5 ? 'ltr' : 'rtl';

        await select.selectOption({ value: option });

        await adminPage.$eval('label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="logo_path[]" accept="image/*">`);

        const image = await adminPage.$('input[type="file"][name="logo_path[]"]');

        const filePath = forms.getRandomImageFile();

        await image.setInputFiles(filePath);

        await adminPage.press('input[name="name"]', 'Enter');

        await expect(adminPage.getByText('Locale updated successfully.')).toBeVisible();
    });

    test('delete locale', async ({ adminPage }) => {
        await adminPage.goto('admin/settings/locales');

        await adminPage.waitForSelector('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconDelete = await adminPage.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconDelete[0].click();

        await adminPage.click('button.transparent-button + button.primary-button:visible');

        await expect(adminPage.getByText('Locale deleted successfully.')).toBeVisible();
    });
});
