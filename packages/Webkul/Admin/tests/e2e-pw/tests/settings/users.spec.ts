// import { test, expect, config } from '../../setup';
// import  * as forms from '../../utils/form';

// test.describe('user management', () => {
//     test('create users', async ({ adminPage }) => {
//         await adminPage.goto(`${config.baseUrl}/admin/settings/users`);

//         await adminPage.click('button[type="button"].primary-button:visible');

//         await adminPage.click('select[name="role_id"]');

//         const select = await adminPage.$('select[name="role_id"]');

//         const options = await select.$$eval('option', (options) => {
//             return options.map(option => option.value);
//         });

//         if (options.length > 1) {
//             const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

//             await select.selectOption(options[randomIndex]);
//         } else {
//             await select.selectOption(options[0]);
//         }

//         await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(200));

//         await adminPage.fill('input[type="email"].rounded-md:visible', forms.form.email);

//         const password = forms.generateRandomPassword(8, 20);

//         await adminPage.fill('input[name="password"].rounded-md:visible', password);

//         await adminPage.fill('input[name="password_confirmation"].rounded-md:visible', password);

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await adminPage.click('input[type="checkbox"] + label.peer');
//         }

//         await adminPage.$eval('label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]', (el, content) => {
//             el.innerHTML += content;
//         }, `<input type="file" name="image[]" accept="image/*">`);

//         const image = await adminPage.$('input[type="file"][name="image[]"]');

//         const filePath = forms.getRandomImageFile();

//         await image.setInputFiles(filePath);

//         await adminPage.press('input[name="name"]', 'Enter');

//         await expect(adminPage.getByText('User created successfully.')).toBeVisible();
//     });

//     test('edit users', async ({ adminPage }) => {
//         await adminPage.goto(`${config.baseUrl}/admin/settings/users`);

//         await adminPage.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

//         const iconEdit = await adminPage.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

//         await iconEdit[0].click();

//         await adminPage.click('select[name="role_id"]');

//         const select = await adminPage.$('select[name="role_id"]');

//         const options = await select.$$eval('option', (options) => {
//             return options.map(option => option.value);
//         });

//         if (options.length > 1) {
//             const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

//             await select.selectOption(options[randomIndex]);
//         } else {
//             await select.selectOption(options[0]);
//         }

//         await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(200));

//         await adminPage.fill('input[type="email"].rounded-md:visible', forms.form.email);

//         const password = forms.generateRandomPassword(8, 20);

//         await adminPage.fill('input[name="password"].rounded-md:visible', password);

//         await adminPage.fill('input[name="password_confirmation"].rounded-md:visible', password);

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await adminPage.click('input[type="checkbox"] + label.peer');
//         }

//         await adminPage.$eval('label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]', (el, content) => {
//             el.innerHTML += content;
//         }, `<input type="file" name="image[]" accept="image/*">`);

//         const image = await adminPage.$('input[type="file"][name="image[]"]');

//         const filePath = forms.getRandomImageFile();

//         await image.setInputFiles(filePath);

//         await adminPage.press('input[name="name"]', 'Enter');

//         await expect(adminPage.getByText('User updated successfully.')).toBeVisible();
//     });

//     test('delete Users', async ({ adminPage }) => {
//         await adminPage.goto(`${config.baseUrl}/admin/settings/users`);

//         await adminPage.waitForSelector('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

//         const iconDelete = await adminPage.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

//         await iconDelete[0].click();

//         await adminPage.click('button.transparent-button + button.primary-button:visible');

//         await expect(adminPage.getByText('User deleted successfully.')).toBeVisible();
//     });
// });
