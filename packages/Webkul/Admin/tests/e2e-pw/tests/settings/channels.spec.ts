import { test, expect, config } from '../../setup';
import  * as forms from '../../utils/form';

test.describe('channel management', () => {
    test('create channel', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/settings/channels`);

        await adminPage.click('a.primary-button:visible');

        await adminPage.click('select.custom-select');

        const selects = await adminPage.$$('select.custom-select');

        for (let select of selects) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 1) {
                const randomIndex = Math.floor(Math.random() * options.length) + 1;

                await select.selectOption(options[randomIndex]);
            } else {
                await select.selectOption(options[0]);
            }
        }

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(' ')
            .replaceAll(' ', '');

        await adminPage.fill('input[name="code"].rounded-md:visible', concatenatedNames);

        const checkboxs = await adminPage.$$('input[type="checkbox"] + label');

        for (let checkbox of checkboxs) {
            await checkbox.click();
        }

        await inputs[0].press('Enter');

        await expect(adminPage.getByText('Channel created successfully.')).toBeVisible();
    });

    test('edit channel', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/settings/channels`);

        await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

        const iconEdit = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

        await iconEdit[0].click();

        await adminPage.click('select.custom-select');

        const selects = await adminPage.$$('select.custom-select');

        for (let select of selects) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 1) {
                const randomIndex = Math.floor(Math.random() * options.length) + 1;

                await select.selectOption(options[randomIndex]);
            } else {
                await select.selectOption(options[0]);
            }
        }

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            if (input == inputs[0]) {
                continue;
            }

            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const checkboxs = await adminPage.$$('input[type="checkbox"] + label');

        for (let checkbox of checkboxs) {
            await checkbox.click();
        }

        await inputs[1].press('Enter');

        await expect(adminPage.getByText('Update Channel Successfully')).toBeVisible();
    });

    test('delete channel', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/settings/channels`);

        await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        const iconDelete = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        await iconDelete[0].click();

        await adminPage.click('button.transparent-button + button.primary-button:visible');

        await expect(adminPage.getByText('Channel deleted successfully.')).toBeVisible();
    });
});
