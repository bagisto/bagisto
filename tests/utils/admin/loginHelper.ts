import config from '../../../config/setup';
import * as forms from '../admin/formHelper';

const baseUrl = config.baseUrl;

const logIn = async (page) => {
    await page.goto(`${baseUrl}/admin/login`);

    await page.fill('input[name="email"]', config.adminEmail);
    await page.fill('input[name="password"]', config.adminPassword);

    await page.press('input[name="password"]', 'Enter');

    if (await forms.testForm(page)) {
        console.log('Successfully login');
        return 'Successfully login';
    } else {
        return null;
    }
}

export default logIn;
