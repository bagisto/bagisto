// import { test, expect } from '../../setup';

// test.describe('review management', () => {
//     test('update status of review', async ({ adminPage }) => {
//         await adminPage.goto('admin/customers/reviews');

//         const iconRight = await adminPage.$$('span[class="icon-sort-right rtl:icon-sort-left cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"]');

//         await iconRight[Math.floor(Math.random() * ((iconRight.length - 1) - 0 + 1)) + 0].click();

//         await adminPage.waitForSelector('select.custom-select:visible');
//         const select = await adminPage.$('select.custom-select:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 3 == 1) {
//             const options = await select.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await select.selectOption(options[randomIndex]);
//             }
//         }

//         await adminPage.click('button[class="primary-button ltr:mr-11 rtl:ml-11"]:visible');

//         await expect(adminPage.getByText('Reviews').first()).toBeVisible();
//     });

//     test('mass update reviews', async ({ adminPage }) => {
//         await adminPage.goto('admin/customers/reviews');

//         const checkboxs = await adminPage.$$('.icon-uncheckbox');

//         await checkboxs[1].click();

//         await adminPage.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

//         await adminPage.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
//         await adminPage.hover('a[class="whitespace-no-wrap flex cursor-not-allowed justify-between gap-1.5 rounded-t px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

//         const buttons = await adminPage.$$('a[class="whitespace-no-wrap block rounded-t px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

//         await buttons[Math.floor(Math.random() * ((buttons.length - 1) - 0 + 1)) + 0].click();

//         await adminPage.click('button.transparent-button + button.primary-button:visible');

//         await expect(adminPage.getByText('Selected Review Updated Successfully')).toBeVisible();
//     });

//     test('delete review', async ({ adminPage }) => {
//         await adminPage.goto('admin/customers/reviews');

//         const iconDelete = await adminPage.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"]');

//         await iconDelete[0].click();

//         await adminPage.click('button.transparent-button + button.primary-button:visible');

//         await expect(adminPage.getByText('Review Deleted Successfully')).toBeVisible();
//     });

//     test('mass delete reviews', async ({ adminPage }) => {
//         await adminPage.goto('admin/customers/reviews');

//         const checkboxs = await adminPage.$$('.icon-uncheckbox');

//         await checkboxs[1].click();

//         await adminPage.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

//         await adminPage.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
//         await adminPage.click('a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

//         await adminPage.click('button.transparent-button + button.primary-button:visible');

//         await expect(adminPage.getByText('Selected Review Deleted Successfully')).toBeVisible();
//     });
// });
