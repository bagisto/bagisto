import { expect, config } from '../setup';

const baseUrl = config.baseUrl;

const logIn = async (page) => {
    await page.goto(`${baseUrl}/admin/login`);
    await page.fill('input[name="email"]', config.adminEmail);
    await page.fill('input[name="password"]', config.adminPassword);
    await page.press('input[name="password"]', 'Enter');

    await expect(page).toHaveURL(`${baseUrl}/admin/dashboard`);
}

export default logIn;
