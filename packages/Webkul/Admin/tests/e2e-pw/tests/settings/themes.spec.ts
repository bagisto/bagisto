// import { test, expect } from '../../setup';
// import  * as forms from '../../utils/form';

// test.describe('theme management', () => {
//     test('create product carousel theme', async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/themes');

//         await adminPage.click('button[type="button"].primary-button:visible');

//         adminPage.click('select.custom-select:visible');

//         const selects = await adminPage.$$('select.custom-select:visible');

//         for (let select of selects) {
//             const options = await select.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await select.selectOption(options[randomIndex]);
//             } else {
//                 await select.selectOption(options[0]);
//             }
//         }

//         await adminPage.selectOption('select[name="type"]', 'product_carousel');

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await adminPage.click('input[type="checkbox"] + label.peer:visible');

//         const checkbox = await adminPage.$('input[type="checkbox"] + label.peer:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await checkbox.click();
//         }

//         const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

//         for (let input of inputs) {
//             await input.fill(forms.generateRandomStringWithSpaces(200));
//         }

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         const customSelects = await adminPage.$$('select.custom-select:visible');

//         for (let select of customSelects) {
//             const options = await select.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 1) {
//                 const randomIndex = Math.floor(Math.random() * (options.length - 1) + 1);

//                 await select.selectOption(options[randomIndex]);
//             } else {
//                 await select.selectOption(options[0]);
//             }
//         }

//         await adminPage.click('div[class="secondary-button"]:visible');

//         const key = await adminPage.$('select[name="key"]');

//         if (key) {
//             const options = await key.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await key.selectOption(options[randomIndex]);
//             }
//         }

//         const value = await adminPage.$('select[name="value"]');

//         if (value) {
//             const options = await value.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await value.selectOption(options[randomIndex]);
//             }
//         } else {
//             await adminPage.fill('input[name="value"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));
//         }

//         const errors = await adminPage.$$('input[type="text"][class="border !border-red-600 hover:border-red-600 w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');

//         for (let error of errors) {
//             await error.fill((Math.random() * 10).toString());
//         }

//         const newErrors = await adminPage.$$('input[class="border !border-red-600 hover:border-red-600 w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');

//         for (let error of newErrors) {
//             await error.fill((Math.floor(Math.random() * 10) + 1).toString());
//         }

//         await adminPage.click('button.primary-button.justify-center:visible');

//         const Errors = await adminPage.waitForSelector('.text-red-600.text-xs.italic', { timeout: 3000 }).catch(() => null);

//         if (Errors) {
//             throw new Error('Selected key have no any option');
//         }

//         await inputs[0].press('Enter');

//         await expect(adminPage.getByText('Theme updated successfully')).toBeVisible();
//     });

//     test('create category carousel theme', async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/themes');

//         await adminPage.click('button[type="button"].primary-button:visible');

//         adminPage.click('select.custom-select:visible');

//         const selects = await adminPage.$$('select.custom-select:visible');

//         for (let select of selects) {
//             const options = await select.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await select.selectOption(options[randomIndex]);
//             } else {
//                 await select.selectOption(options[0]);
//             }
//         }

//         await adminPage.selectOption('select[name="type"]', 'category_carousel');

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await adminPage.click('input[type="checkbox"] + label.peer:visible');

//         const checkbox = await adminPage.$('input[type="checkbox"] + label.peer:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await checkbox.click();
//         }

//         const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

//         for (let input of inputs) {
//             await input.fill(forms.generateRandomStringWithSpaces(200));
//         }

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());
//         await adminPage.fill('input[name="en[options][filters][limit]"]', (Math.floor(Math.random() * 1000000)).toString());

//         const customSelects = await adminPage.$$('select.custom-select:visible');

//         for (let select of customSelects) {
//             const options = await select.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 1) {
//                 const randomIndex = Math.floor(Math.random() * (options.length - 1) + 1);

//                 await select.selectOption(options[randomIndex]);
//             } else {
//                 await select.selectOption(options[0]);
//             }
//         }

//         await adminPage.click('div[class="secondary-button"]:visible');

//         const key = await adminPage.$('select[name="key"]');

//         if (key) {
//             const options = await key.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await key.selectOption(options[randomIndex]);
//             }
//         }

//         const value = await adminPage.$('select[name="value"]');

//         if (value) {
//             const options = await value.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await value.selectOption(options[randomIndex]);
//             }
//         } else {
//             await adminPage.fill('input[name="value"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));
//         }

//         const errors = await adminPage.$$('input[type="text"][class="border !border-red-600 hover:border-red-600 w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');

//         for (let error of errors) {
//             await error.fill((Math.random() * 10).toString());
//         }

//         const newErrors = await adminPage.$$('input[class="border !border-red-600 hover:border-red-600 w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');

//         for (let error of newErrors) {
//             await error.fill((Math.floor(Math.random() * 10) + 1).toString());
//         }

//         await adminPage.click('button.primary-button.justify-center:visible');

//         const Errors = await adminPage.waitForSelector('.text-red-600.text-xs.italic', { timeout: 3000 }).catch(() => null);

//         if (Errors) {
//             throw new Error('Selected key have no any option');
//         }

//         await inputs[0].press('Enter');

//         await expect(adminPage.getByText('Theme updated successfully')).toBeVisible();
//     });

//     test('create static content theme', async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/themes');

//         await adminPage.click('button[type="button"].primary-button:visible');

//         adminPage.click('select.custom-select:visible');

//         const selects = await adminPage.$$('select.custom-select:visible');

//         for (let select of selects) {
//             const options = await select.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await select.selectOption(options[randomIndex]);
//             } else {
//                 await select.selectOption(options[0]);
//             }
//         }

//         await adminPage.selectOption('select[name="type"]', 'static_content');

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await adminPage.click('input[type="checkbox"] + label.peer:visible');

//         const checkbox = await adminPage.$('input[type="checkbox"] + label.peer:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await checkbox.click();
//         }

//         const randomHtmlContent = await forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 1000));

//         await adminPage.evaluate((content) => {
//             const html = document.querySelector('input[name="en[options][html]"]');
//             const css = document.querySelector('input[name="en[options][css]"]');

//             if (html instanceof HTMLInputElement) {
//                 html.value = content;
//             }

//             if (css instanceof HTMLInputElement) {
//                 css.value = content;
//             }
//         }, randomHtmlContent.toString());

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await expect(adminPage.getByText('Theme updated successfully')).toBeVisible();
//     });

//     test('create image slider theme', async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/themes');

//         await adminPage.click('button[type="button"].primary-button:visible');

//         adminPage.click('select.custom-select:visible');

//         const selects = await adminPage.$$('select.custom-select:visible');

//         for (let select of selects) {
//             const options = await select.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await select.selectOption(options[randomIndex]);
//             } else {
//                 await select.selectOption(options[0]);
//             }
//         }

//         await adminPage.selectOption('select[name="type"]', 'image_carousel');

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await adminPage.click('input[type="checkbox"] + label.peer:visible');

//         const checkbox = await adminPage.$('input[type="checkbox"] + label.peer:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await checkbox.click();
//         }

//         for (i; i > 0; i--) {
//             await adminPage.click('div[class="secondary-button"]:visible');

//             const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

//             for (let input of inputs) {
//                 await input.fill(forms.generateRandomStringWithSpaces(200));
//             }

//             await adminPage.$eval('label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]', (el, content) => {
//                 el.innerHTML += content;
//             }, `<input type="file" name="slider_image[]" accept="image/*">`);

//             const image = await adminPage.$('input[type="file"][name="slider_image[]"]');

//             const filePath = forms.getRandomImageFile();

//             await image.setInputFiles(filePath);

//             await inputs[0].press('Enter');
//         }

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await expect(adminPage.getByText('Theme updated successfully')).toBeVisible();
//     });

//     test('create footer link theme', async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/themes');

//         await adminPage.click('button[type="button"].primary-button:visible');

//         adminPage.click('select.custom-select:visible');

//         const selects = await adminPage.$$('select.custom-select:visible');

//         for (let select of selects) {
//             const options = await select.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await select.selectOption(options[randomIndex]);
//             } else {
//                 await select.selectOption(options[0]);
//             }
//         }

//         await adminPage.selectOption('select[name="type"]', 'footer_links');

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await adminPage.click('input[type="checkbox"] + label.peer:visible');

//         const checkbox = await adminPage.$('input[type="checkbox"] + label.peer:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await checkbox.click();
//         }

//         for (i; i > 0; i--) {
//             await adminPage.click('div[class="secondary-button"]:visible');

//             const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

//             for (let input of inputs) {
//                 await input.fill(forms.generateRandomStringWithSpaces(200));
//             }

//             await adminPage.fill('input[name="url"]', forms.generateRandomUrl());
//             await adminPage.fill('input[name="sort_order"][class="border !border-red-600 hover:border-red-600 w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]', (Math.floor(Math.random() * 1000000)).toString());

//             const column = await adminPage.$('select[name="column"]');

//             if (column) {
//                 const options = await column.$$eval('option', (options) => {
//                     return options.map(option => option.value);
//                 });

//                 if (options.length > 0) {
//                     const randomIndex = Math.floor(Math.random() * options.length);

//                     await column.selectOption(options[randomIndex]);
//                 }
//             }
//             await inputs[0].press('Enter');
//         }

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await expect(adminPage.getByText('Theme updated successfully')).toBeVisible();
//     });

//     test('create services content theme', async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/themes');

//         await adminPage.click('button[type="button"].primary-button:visible');

//         adminPage.click('select.custom-select:visible');

//         const selects = await adminPage.$$('select.custom-select:visible');

//         for (let select of selects) {
//             const options = await select.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await select.selectOption(options[randomIndex]);
//             } else {
//                 await select.selectOption(options[0]);
//             }
//         }

//         await adminPage.selectOption('select[name="type"]', 'services_content');

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await adminPage.click('input[type="checkbox"] + label.peer:visible');

//         const checkbox = await adminPage.$('input[type="checkbox"] + label.peer:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await checkbox.click();
//         }

//         for (i; i > 0; i--) {
//             await adminPage.click('div[class="secondary-button"]:visible');

//             const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

//             for (let input of inputs) {
//                 await input.fill(forms.generateRandomStringWithSpaces(200));
//             }

//             await inputs[0].press('Enter');
//         }

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await expect(adminPage.getByText('Theme updated successfully')).toBeVisible();
//     });

//     test('edit product carousel theme', async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/themes');

//         await adminPage.waitForSelector('span[class="icon-filter text-2xl"]:visible');

//         await adminPage.click('span[class="icon-filter text-2xl"]:visible');

//         const clearBtn = await adminPage.$$('p[class="cursor-pointer text-xs font-medium leading-6 text-blue-600"]:visible');

//         for (let i = 0; i < clearBtn.length; i++) {
//             await clearBtn[i].click();
//         }

//         await adminPage.fill('input[name="type"]:visible', 'product_carousel');
//         await adminPage.press('input[name="type"]:visible', 'Enter');

//         const btnAdd = await adminPage.$('button[type="button"][class="secondary-button w-full"]:visible');

//         await btnAdd.scrollIntoViewIfNeeded();

//         await adminPage.click('button[type="button"][class="secondary-button w-full"]:visible');

//         await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]', { timeout: 5000 }).catch(() => null);

//         const iconEdit = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

//         await iconEdit[0].click();

//         await adminPage.click('input[type="checkbox"] + label.peer:visible');

//         const deleteBtns = await adminPage.$$('p.cursor-pointer.text-red-600.transition-all');

//         for (let deleteBtn of deleteBtns) {
//             let i = Math.floor(Math.random() * 10) + 1;

//             if (i % 3 == 1) {
//                 await deleteBtn.click();

//                 await adminPage.click('button[type="button"].transparent-button + button[type="button"].primary-button');

//                 break;
//             }
//         }

//         const checkbox = await adminPage.$('input[type="checkbox"] + label.peer:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await checkbox.click();
//         }

//         const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

//         for (let input of inputs) {
//             await input.fill(forms.generateRandomStringWithSpaces(200));
//         }

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         const customSelects = await adminPage.$$('select.custom-select:visible');

//         for (let select of customSelects) {
//             const options = await select.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 1) {
//                 const randomIndex = Math.floor(Math.random() * (options.length - 1) + 1);

//                 await select.selectOption(options[randomIndex]);
//             } else {
//                 await select.selectOption(options[0]);
//             }
//         }

//         await adminPage.click('div[class="secondary-button"]:visible');

//         const key = await adminPage.$('select[name="key"]');

//         if (key) {
//             const options = await key.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await key.selectOption(options[randomIndex]);
//             }
//         }

//         const value = await adminPage.$('select[name="value"]');

//         if (value) {
//             const options = await value.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await value.selectOption(options[randomIndex]);
//             }
//         } else {
//             await adminPage.fill('input[name="value"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));
//         }

//         const errors = await adminPage.$$('input[type="text"][class="border !border-red-600 hover:border-red-600 w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');

//         for (let error of errors) {
//             await error.fill((Math.random() * 10).toString());
//         }

//         const newErrors = await adminPage.$$('input[class="border !border-red-600 hover:border-red-600 w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');

//         for (let error of newErrors) {
//             await error.fill((Math.floor(Math.random() * 10) + 1).toString());
//         }

//         await adminPage.click('button.primary-button.justify-center:visible');

//         const Errors = await adminPage.waitForSelector('.text-red-600.text-xs.italic', { timeout: 3000 }).catch(() => null);

//         if (Errors) {
//             throw new Error('Selected key have no any option');
//         }

//         await inputs[0].press('Enter');

//         await expect(adminPage.getByText('Theme updated successfully')).toBeVisible();
//     });

//     test('edit category carousel theme', async ({ adminPage }) => {
//         await adminPage.waitForSelector('span[class="icon-filter text-2xl"]:visible');

//         await adminPage.click('span[class="icon-filter text-2xl"]:visible');

//         const clearBtn = await adminPage.$$('p[class="cursor-pointer text-xs font-medium leading-6 text-blue-600"]:visible');

//         for (let i = 0; i < clearBtn.length; i++) {
//             await clearBtn[i].click();
//         }

//         await adminPage.fill('input[name="type"]:visible', 'category_carousel');
//         await adminPage.press('input[name="type"]:visible', 'Enter');

//         const btnAdd = await adminPage.$('button[type="button"][class="secondary-button w-full"]:visible');

//         await btnAdd.scrollIntoViewIfNeeded();

//         await adminPage.click('button[type="button"][class="secondary-button w-full"]:visible');

//         await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]', { timeout: 5000 }).catch(() => null);

//         const iconEdit = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

//         await iconEdit[0].click();

//         await adminPage.click('input[type="checkbox"] + label.peer:visible');

//         const deleteBtns = await adminPage.$$('p.cursor-pointer.text-red-600.transition-all');

//         for (let deleteBtn of deleteBtns) {
//             let i = Math.floor(Math.random() * 10) + 1;

//             if (i % 3 == 1) {
//                 await deleteBtn.click();

//                 await adminPage.click('button[type="button"].transparent-button + button[type="button"].primary-button');

//                 break;
//             }
//         }

//         const checkbox = await adminPage.$('input[type="checkbox"] + label.peer:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await checkbox.click();
//         }

//         const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

//         for (let input of inputs) {
//             await input.fill(forms.generateRandomStringWithSpaces(200));
//         }

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());
//         await adminPage.fill('input[name="en[options][filters][limit]"]', (Math.floor(Math.random() * 1000000)).toString());

//         const customSelects = await adminPage.$$('select.custom-select:visible');

//         for (let select of customSelects) {
//             const options = await select.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 1) {
//                 const randomIndex = Math.floor(Math.random() * (options.length - 1) + 1);

//                 await select.selectOption(options[randomIndex]);
//             } else {
//                 await select.selectOption(options[0]);
//             }
//         }

//         await adminPage.click('div[class="secondary-button"]:visible');

//         const key = await adminPage.$('select[name="key"]');

//         if (key) {
//             const options = await key.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await key.selectOption(options[randomIndex]);
//             }
//         }

//         const value = await adminPage.$('select[name="value"]');

//         if (value) {
//             const options = await value.$$eval('option', (options) => {
//                 return options.map(option => option.value);
//             });

//             if (options.length > 0) {
//                 const randomIndex = Math.floor(Math.random() * options.length);

//                 await value.selectOption(options[randomIndex]);
//             }
//         } else {
//             await adminPage.fill('input[name="value"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));
//         }

//         const errors = await adminPage.$$('input[type="text"][class="border !border-red-600 hover:border-red-600 w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');

//         for (let error of errors) {
//             await error.fill((Math.random() * 10).toString());
//         }

//         const newErrors = await adminPage.$$('input[class="border !border-red-600 hover:border-red-600 w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');

//         for (let error of newErrors) {
//             await error.fill((Math.floor(Math.random() * 10) + 1).toString());
//         }

//         await adminPage.click('button.primary-button.justify-center:visible');

//         const Errors = await adminPage.waitForSelector('.text-red-600.text-xs.italic', { timeout: 3000 }).catch(() => null);

//         if (Errors) {
//             throw new Error('Selected key have no any option');
//         }

//         await inputs[0].press('Enter');

//         await expect(adminPage.getByText('Theme updated successfully')).toBeVisible();
//     });

//     test('edit static content theme', async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/themes');

//         await adminPage.waitForSelector('span[class="icon-filter text-2xl"]:visible');

//         await adminPage.click('span[class="icon-filter text-2xl"]:visible');

//         const clearBtn = await adminPage.$$('p[class="cursor-pointer text-xs font-medium leading-6 text-blue-600"]:visible');

//         for (let i = 0; i < clearBtn.length; i++) {
//             await clearBtn[i].click();
//         }

//         await adminPage.fill('input[name="type"]:visible', 'static_content');
//         await adminPage.press('input[name="type"]:visible', 'Enter');

//         const btnAdd = await adminPage.$('button[type="button"][class="secondary-button w-full"]:visible');

//         await btnAdd.scrollIntoViewIfNeeded();

//         await adminPage.click('button[type="button"][class="secondary-button w-full"]:visible');

//         await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]', { timeout: 5000 }).catch(() => null);

//         const iconEdit = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

//         await iconEdit[0].click();

//         await adminPage.click('input[type="checkbox"] + label.peer:visible');

//         const deleteBtns = await adminPage.$$('p.cursor-pointer.text-red-600.transition-all');

//         for (let deleteBtn of deleteBtns) {
//             let i = Math.floor(Math.random() * 10) + 1;

//             if (i % 3 == 1) {
//                 await deleteBtn.click();

//                 await adminPage.click('button[type="button"].transparent-button + button[type="button"].primary-button');

//                 break;
//             }
//         }

//         const checkbox = await adminPage.$('input[type="checkbox"] + label.peer:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await checkbox.click();
//         }

//         const randomHtmlContent = await forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 1000));

//         await adminPage.evaluate((content) => {
//             const html = document.querySelector('input[name="en[options][html]"]');
//             const css = document.querySelector('input[name="en[options][css]"]');

//             if (html instanceof HTMLInputElement) {
//                 html.value = content;
//             }

//             if (css instanceof HTMLInputElement) {
//                 css.value = content;
//             }
//         }, randomHtmlContent.toString());

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await expect(adminPage.getByText('Theme updated successfully')).toBeVisible();
//     });

//     test('edit image slider theme', async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/themes');

//         await adminPage.waitForSelector('span[class="icon-filter text-2xl"]:visible');

//         await adminPage.click('span[class="icon-filter text-2xl"]:visible');

//         const clearBtn = await adminPage.$$('p[class="cursor-pointer text-xs font-medium leading-6 text-blue-600"]:visible');

//         for (let i = 0; i < clearBtn.length; i++) {
//             await clearBtn[i].click();
//         }

//         await adminPage.fill('input[name="type"]:visible', 'image_carousel');
//         await adminPage.press('input[name="type"]:visible', 'Enter');

//         const btnAdd = await adminPage.$('button[type="button"][class="secondary-button w-full"]:visible');

//         await btnAdd.scrollIntoViewIfNeeded();

//         await adminPage.click('button[type="button"][class="secondary-button w-full"]:visible');

//         await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]', { timeout: 5000 }).catch(() => null);

//         const iconEdit = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

//         await iconEdit[0].click();

//         await adminPage.click('input[type="checkbox"] + label.peer:visible');

//         const deleteBtns = await adminPage.$$('p.cursor-pointer.text-red-600.transition-all');

//         for (let deleteBtn of deleteBtns) {
//             let i = Math.floor(Math.random() * 10) + 1;

//             if (i % 3 == 1) {
//                 await deleteBtn.click();

//                 await adminPage.click('button[type="button"].transparent-button + button[type="button"].primary-button');

//                 break;
//             }
//         }

//         const checkbox = await adminPage.$('input[type="checkbox"] + label.peer:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await checkbox.click();
//         }

//         for (i; i > 0; i--) {
//             await adminPage.click('div[class="secondary-button"]:visible');

//             const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

//             for (let input of inputs) {
//                 await input.fill(forms.generateRandomStringWithSpaces(200));
//             }

//             await adminPage.$eval('label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]', (el, content) => {
//                 el.innerHTML += content;
//             }, `<input type="file" name="slider_image[]" accept="image/*">`);

//             const image = await adminPage.$('input[type="file"][name="slider_image[]"]');

//             const filePath = forms.getRandomImageFile();

//             await image.setInputFiles(filePath);

//             await inputs[0].press('Enter');
//         }

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await expect(adminPage.getByText('Theme updated successfully')).toBeVisible();
//     });

//     test('edit footer link theme', async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/themes');

//         await adminPage.waitForSelector('span[class="icon-filter text-2xl"]:visible');

//         await adminPage.click('span[class="icon-filter text-2xl"]:visible');

//         const clearBtn = await adminPage.$$('p[class="cursor-pointer text-xs font-medium leading-6 text-blue-600"]:visible');

//         for (let i = 0; i < clearBtn.length; i++) {
//             await clearBtn[i].click();
//         }

//         await adminPage.fill('input[name="type"]:visible', 'footer_links');
//         await adminPage.press('input[name="type"]:visible', 'Enter');

//         const btnAdd = await adminPage.$('button[type="button"][class="secondary-button w-full"]:visible');

//         await btnAdd.scrollIntoViewIfNeeded();

//         await adminPage.click('button[type="button"][class="secondary-button w-full"]:visible');

//         await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]', { timeout: 5000 }).catch(() => null);

//         const iconEdit = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

//         await iconEdit[0].click();

//         await adminPage.click('input[type="checkbox"] + label.peer:visible');

//         const deleteBtns = await adminPage.$$('p.cursor-pointer.text-red-600.transition-all');

//         for (let deleteBtn of deleteBtns) {
//             let i = Math.floor(Math.random() * 10) + 1;

//             if (i % 3 == 1) {
//                 await deleteBtn.click();

//                 await adminPage.click('button[type="button"].transparent-button + button[type="button"].primary-button');

//                 break;
//             }
//         }

//         const checkbox = await adminPage.$('input[type="checkbox"] + label.peer:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await checkbox.click();
//         }

//         for (i; i > 0; i--) {
//             await adminPage.click('div[class="secondary-button"]:visible');

//             const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

//             for (let input of inputs) {
//                 await input.fill(forms.generateRandomStringWithSpaces(200));
//             }

//             await adminPage.fill('input[name="url"]', forms.generateRandomUrl());
//             await adminPage.fill('input[name="sort_order"][class="border !border-red-600 hover:border-red-600 w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]', (Math.floor(Math.random() * 1000000)).toString());

//             const column = await adminPage.$('select[name="column"]');

//             if (column) {
//                 const options = await column.$$eval('option', (options) => {
//                     return options.map(option => option.value);
//                 });

//                 if (options.length > 0) {
//                     const randomIndex = Math.floor(Math.random() * options.length);

//                     await column.selectOption(options[randomIndex]);
//                 }
//             }
//             await inputs[0].press('Enter');
//         }

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await expect(adminPage.getByText('Theme updated successfully')).toBeVisible();
//     });

//     test('edit services content theme', async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/themes');

//         await adminPage.waitForSelector('span[class="icon-filter text-2xl"]:visible');

//         await adminPage.click('span[class="icon-filter text-2xl"]:visible');

//         const clearBtn = await adminPage.$$('p[class="cursor-pointer text-xs font-medium leading-6 text-blue-600"]:visible');

//         for (let i = 0; i < clearBtn.length; i++) {
//             await clearBtn[i].click();
//         }

//         await adminPage.fill('input[name="type"]:visible', 'services_content');
//         await adminPage.press('input[name="type"]:visible', 'Enter');

//         const btnAdd = await adminPage.$('button[type="button"][class="secondary-button w-full"]:visible');

//         await btnAdd.scrollIntoViewIfNeeded();

//         await adminPage.click('button[type="button"][class="secondary-button w-full"]:visible');

//         await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]', { timeout: 5000 }).catch(() => null);

//         const iconEdit = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

//         await iconEdit[0].click();

//         await adminPage.click('input[type="checkbox"] + label.peer:visible');

//         const deleteBtns = await adminPage.$$('p.cursor-pointer.text-red-600.transition-all');

//         for (let deleteBtn of deleteBtns) {
//             let i = Math.floor(Math.random() * 10) + 1;

//             if (i % 3 == 1) {
//                 await deleteBtn.click();

//                 await adminPage.click('button[type="button"].transparent-button + button[type="button"].primary-button');

//                 break;
//             }
//         }

//         const checkbox = await adminPage.$('input[type="checkbox"] + label.peer:visible');

//         let i = Math.floor(Math.random() * 10) + 1;

//         if (i % 2 == 1) {
//             await checkbox.click();
//         }

//         for (i; i > 0; i--) {
//             await adminPage.click('div[class="secondary-button"]:visible');

//             const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

//             for (let input of inputs) {
//                 await input.fill(forms.generateRandomStringWithSpaces(200));
//             }

//             await inputs[0].press('Enter');
//         }

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await adminPage.fill('input[name="sort_order"]', (Math.floor(Math.random() * 1000000)).toString());

//         await adminPage.click('.box-shadow.absolute button.primary-button:visible');

//         await expect(adminPage.getByText('Theme updated successfully')).toBeVisible();
//     });

//     test('delete theme', async ({ adminPage }) => {
//         await adminPage.goto('admin/settings/themes');

//         await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

//         const iconDelete = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

//         await iconDelete[0].click();

//         await adminPage.click('button.transparent-button + button.primary-button:visible');

//         await expect(adminPage.getByText('Theme deleted successfully')).toBeVisible();
//     });
// });
