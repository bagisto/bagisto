import { test, expect, config } from '../utils/setup';

test('Login', async ({page}) => {
  await page.goto(`${config.baseUrl}/admin/login`);
  await page.getByPlaceholder('Email Address').click();
  await page.getByPlaceholder('Email Address').fill(config.adminEmail);
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill(config.adminPassword);
  await page.getByLabel('Sign In').click();

    try {
        const getError = await page.waitForSelector('.text-red-600.text-xs.italic', { timeout: 2000 }).catch(() => null);

        if (getError) {
            const errors = await page.$$eval('.text-red-600.text-xs.italic', els => els.map(el => el.innerText));
            errors.forEach(message => console.log(message));
        } else {
            await page.waitForSelector('.icon-toast-done', { timeout: 5000 });
            const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
            await page.click('.cursor-pointer.underline');
            console.log(message);
        }
    } catch(e) {
        console.log(page.url());
    }
});

test('Logout', async ({page}) => {
  await page.goto(`${config.baseUrl}/admin/login`);
  await page.getByPlaceholder('Email Address').click();
  await page.getByPlaceholder('Email Address').fill(config.adminEmail);
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill(config.adminPassword);
  await page.getByLabel('Sign In').click();
  await page.getByRole('button', { name: 'E' }).click();
  await page.getByRole('link', { name: 'Logout' }).click();

    try {
        const getError = await page.waitForSelector('.text-red-600.text-xs.italic', { timeout: 2000 }).catch(() => null);

        if (getError) {
            const errors = await page.$$eval('.text-red-600.text-xs.italic', els => els.map(el => el.innerText));
            errors.forEach(message => console.log(message));
        } else {
            await page.waitForSelector('.icon-toast-done', { timeout: 5000 });
            const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
            await page.click('.cursor-pointer.underline');
            console.log(message);
        }
    } catch(e) {
        console.log(page.url());
    }
});
