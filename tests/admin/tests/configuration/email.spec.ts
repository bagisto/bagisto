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
    });

    test.afterEach(async () => {
        await browser.close();
    });

    test('Settings of Email', async () => {
        await page.goto(`${config.baseUrl}/admin/configuration/emails/configure`);

        await page.click('input[type="text"].rounded-md:visible');

        const inputs = await page.$$('input[type="text"].rounded-md:visible');

        let i = 0;

        for (let input of inputs) {
            if (i % 2 == 0) {
                await input.fill(forms.generateRandomStringWithSpaces(50));
            } else {
                await input.fill(forms.form.email);
            }

            i++;
        }

        await page.click('button[type="submit"].primary-button:visible');

        await expect(page.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('Notifications of Email', async () => {
        await page.goto(`${config.baseUrl}/admin/configuration/emails/general`);

        await page.click('button[type="submit"].primary-button:visible');

        await expect(page.getByText('Configuration saved successfully')).toBeVisible();
    });
});
