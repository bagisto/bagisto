import { test, expect, config } from '../utils/setup';
import { launchBrowser } from '../utils/admin/coreHelper';
import  * as forms from '../utils/admin/formHelper';
import logIn from '../utils/admin/loginHelper';
import address from '../utils/admin/addressHelper';

test.describe('attribute management', () => {
    let browser;
    let context;
    let page;

    test.beforeEach(async () => {
        browser = await launchBrowser();
        context = await browser.newContext();
        page = await context.newPage();

        await logIn(page);

        await page.goto(`${config.baseUrl}/admin/sales/orders`);
    });

    test.afterEach(async () => {
        await browser.close();
    });

    test('create orders', async () => {
        test.setTimeout(config.highTimeout);

        await page.click('button.primary-button:visible');

        await page.fill('input[class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', 'testxyat1k@example.com');

        const exists = await page.waitForSelector('.flex-1.overflow-auto.p-3 > .grid.overflow-y-auto > .grid.cursor-pointer.place-content-start.border-b.border-slate-300.p-4', { timeout: 5000 }).catch(() => null);

        if (exists) {
            const users = await page.$$('.flex-1.overflow-auto.p-3 > .grid.overflow-y-auto > .grid.cursor-pointer.place-content-start.border-b.border-slate-300.p-4');

            await users[Math.floor(Math.random() * ((users.length - 1) - 0 + 1)) + 0].click();

        } else {
            await page.click('div.flex.flex-col.items-center > button.secondary-button:visible');

            await page.fill('input[name="first_name"]:visible', forms.form.firstName);
            await page.fill('input[name="last_name"]:visible', forms.form.lastName);
            const email = forms.form.email;
            await page.fill('input[name="email"]:visible', email);
            await page.fill('input[name="phone"]:visible', forms.form.phone);
            await page.selectOption('select[name="gender"]:visible', 'Other');

            await page.press('input[name="phone"]:visible', 'Enter');

            const getError = await page.waitForSelector('.text-red-600.text-xs.italic', { timeout: 3000 }).catch(() => null);
            var message = '';

            if (getError) {
                const errors = await page.$$('.text-red-600.text-xs.italic');

                for (let error of errors) {
                    message = await error.evaluate(el => el.innerText);
                    console.log(message);
                }
            }
        }

        const itemExists = await page.waitForSelector('.grid > div.mt-2.flex > .cursor-pointer.text-emerald-600.transition-all', { timeout: 5000 }).catch(() => null);

        if (itemExists) {
            var items = await page.$$('.grid > div.mt-2.flex > .cursor-pointer.text-emerald-600.transition-all');
            await items[Math.floor(Math.random() * ((items.length - 1) - 0 + 1)) + 0].click();

            await page.click('button.primary-button:visible');
        } else {
            await page.click('p.flex.flex-col.gap-1.text-base.font-semibold + button.secondary-button');
            await page.fill('input[class="block w-full rounded-lg border bg-white py-1.5 leading-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3"]', 'arct');

            const exists = await page.waitForSelector('button.cursor-pointer.text-sm.text-blue-600.transition-all', { timeout: 5000 }).catch(() => null);

            if (exists) {
                const cartBtns = await page.$$('.grid.place-content-start.gap-2.text-right > button.cursor-pointer.text-sm.text-blue-600.transition-all');
                const inputQty = await page.$$('input[name="qty"]:visible');

                let count = 0;
                for (let cartBtn of cartBtns) {
                    let i = Math.floor(Math.random() * 10) + 1;

                    if (
                        i % 2 == 1
                        || cartBtns.length < 2
                    ) {
                        await inputQty[count].scrollIntoViewIfNeeded();
                        const qty = Math.floor(Math.random() * ((10) - 2 + 1)) + 2;

                        await inputQty[count].fill(qty.toString());
                        await cartBtn.click();

                        break;
                    }
                    count++;
                }
            }
        }

        const iconExists = await page.waitForSelector('.flex.items-center.break-all.text-sm > .icon-toast-done.rounded-full.bg-white.text-2xl', { timeout: 5000 }).catch(() => null);

        if (iconExists) {
            const messages = await page.$$('.flex.items-center.break-all.text-sm > .icon-toast-done.rounded-full.bg-white.text-2xl');
            const icons = await page.$$('.flex.items-center.break-all.text-sm + .cursor-pointer.underline');

            const message = await messages[0].evaluate(el => el.parentNode.innerText);
            await icons[0].click();
            console.log(message);
        } else {
            const checkboxs = await page.$$('input[type="checkbox"]:not(:checked) + label, input[type="radio"]:not(:checked) + label');

            for (let checkbox of checkboxs) {
                await checkbox.click();
            }

            await page.click('.flex.items-center.justify-between > button.primary-button:visible');

            const iconExists = await page.waitForSelector('.flex.items-center.break-all.text-sm > .icon-toast-done.rounded-full.bg-white.text-2xl', { timeout: 5000 }).catch(() => null);

            if (iconExists) {
                const messages = await page.$$('.flex.items-center.break-all.text-sm > .icon-toast-done.rounded-full.bg-white.text-2xl');
                const icons = await page.$$('.flex.items-center.break-all.text-sm + .cursor-pointer.underline');

                const message = await messages[0].evaluate(el => el.parentNode.innerText);
                await icons[0].click();
                console.info(message);
            } else {
            }
        }

        const radio = await page.$$('input[name="billing.id"]');

        if (radio.length > 0) {
            const addressNames = await page.$$('input[name="billing.id"] + label');

            const index = Math.floor(Math.random() * ((radio.length - 1) - 0 + 1)) + 0;

            if (
                index >= 0
                && index < radio.length
            ) {
                await addressNames[index].click();
            } else {
                return;
            }
        } else {
            await page.click('p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all');

            if (await address(page) != 'done') {
                return;
            }
        }
        const checkbox = await page.$$('input[name="billing.use_for_shipping"]');

        if (Math.floor(Math.random() * 20) % 3 == 1 ? false : true) {
            if (! checkbox[0].isChecked()) {
                await page.click('input[name="billing.use_for_shipping"] + label');
            }
        } else {
            if (checkbox[0].isChecked()) {
                await page.click('input[name="billing.use_for_shipping"] + label');
            }

            const radio = await page.$$('input[name="shipping.id"]');

            if (radio.length > 0) {
                const addressNames = await page.$$('input[name="shipping.id"] + label');

                const index = Math.floor(Math.random() * ((radio.length - 1) - 0 + 1)) + 0;

                if (
                    index >= 0
                    && index < radio.length
                ) {
                    await addressNames[index].click();
                } else {

                    return;
                }
            } else {
                await page.click('p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all:visible');

                await page.fill('input[name="shipping.company_name"]', forms.form.lastName);
                await page.fill('input[name="shipping.first_name"]', forms.form.firstName);
                await page.fill('input[name="shipping.last_name"]', forms.form.lastName);
                await page.fill('input[name="shipping.email"]', forms.form.email);
                await page.fill('input[name="shipping.address.[0]"]', forms.form.firstName);
                await page.selectOption('select[name="shipping.country"]', 'IN');
                await page.selectOption('select[name="shipping.state"]', 'UP');
                await page.fill('input[name="shipping.city"]', forms.form.lastName);
                await page.fill('input[name="shipping.postcode"]', '201301');
                await page.fill('input[name="shipping.phone"]', forms.form.phone);

                await page.press('input[name="shipping.phone"]', 'Enter');
            }
        }

        await page.click('.mt-4.flex.justify-end > button.primary-button:visible');

        const existsship = await page.waitForSelector('input[name="shipping_method"] + label', { timeout: 10000 }).catch(() => null);

        if (existsship) {
            const radio = await page.$$('input[name="shipping_method"] + label');

            const index = Math.floor(Math.random() * ((radio.length - 1) - 0 + 1)) + 0;

            if (
                index >= 0
                && index < radio.length
            ) {
                await radio[index].click();
            } else {

                return;
            }
        }

        const existspay = await page.waitForSelector('input[name="payment_method"] + label', { timeout: 10000 }).catch(() => null);

        if (existspay) {
            const radio = await page.$$('input[name="payment_method"] + label');

            await radio[1].click();

            const nextButton = await page.$$('button.primary-button.w-max.px-11.py-3');
            await nextButton[nextButton.length - 1].click();
        }

        await expect(page.getByText('Order Items')).toBeVisible();
    });

    test('comment on order', async () => {

        await page.waitForSelector('a > span.icon-sort-right.cursor-pointer.text-2xl');

        const iconRight = await page.$$('a > span.icon-sort-right.cursor-pointer.text-2xl');

        await iconRight[0].click();

        const lorem100 = forms.generateRandomStringWithSpaces(500);
        page.fill('textarea[name="comment"]', lorem100);

        const checkbox = await page.$$('input[name="customer_notified"]');

        if (! checkbox[0].isChecked()) {
            await page.click('input[name="customer_notified"] + label');
        }

        await page.click('button[type="submit"].secondary-button:visible');

        await expect(page.getByText('Comment added successfully.')).toBeVisible();
    });

    test('reorder', async () => {

        await page.waitForSelector('a > span.icon-sort-right.cursor-pointer.text-2xl');

        const iconRight = await page.$$('a > span.icon-sort-right.cursor-pointer.text-2xl');

        await iconRight[0].click();
        await page.waitForSelector('a.transparent-button.px-1 > .icon-cart.text-2xl:visible', { timeout: 1000 }).catch(() => null);

        await page.click('a.transparent-button.px-1 > .icon-cart.text-2xl:visible');

        await expect(page.getByText('Cart Items')).toBeVisible();
    });

    test('create invoice', async () => {

        await page.waitForSelector('a > span.icon-sort-right.cursor-pointer.text-2xl');

        const iconRight = await page.$$('a > span.icon-sort-right.cursor-pointer.text-2xl');

        await iconRight[0].click();
        await page.waitForSelector('div.transparent-button.px-1 > .icon-sales.text-2xl:visible', { timeout: 1000 }).catch(() => null);

        await page.click('div.transparent-button.px-1 > .icon-sales.text-2xl:visible');
        await page.waitForSelector('input[placeholder="Qty to invoiced"]:visible', { timeout: 1000 }).catch(() => null);

        const itemQty = await page.$$('input[placeholder="Qty to invoiced"]:visible');

        for (let element of itemQty) {
            await element.scrollIntoViewIfNeeded();

            const currentValue = await element.inputValue();

            const maxQty = parseInt(currentValue, 10);
            const qty = Math.floor(Math.random() * (maxQty - 1)) + 1;

            await element.fill(qty.toString());
        }

        await page.click('button[type="submit"].primary-button:visible');

        await expect(page.getByText('Invoice created successfully')).toBeVisible();
    });

    test('create shipment', async () => {

        await page.waitForSelector('a > span.icon-sort-right.cursor-pointer.text-2xl');

        const iconRight = await page.$$('a > span.icon-sort-right.cursor-pointer.text-2xl');

        await iconRight[0].click();
        const exists = await page.waitForSelector('div.transparent-button.px-1 > .icon-ship.text-2xl:visible', { timeout: 1000 }).catch(() => null);

        await page.click('div.transparent-button.px-1 > .icon-ship.text-2xl:visible');

        await page.fill('input[name="shipment[carrier_title]"]', forms.generateRandomStringWithSpaces(20));
        await page.fill('input[name="shipment[track_number]"]', forms.generateRandomStringWithSpaces(20));
        const options = await page.$$eval('select[name="shipment[source]"] option', (options) => {
            return options.map(option => option.value);
        });

        const randomIndex = Math.floor(Math.random() * options.length);

        await page.selectOption('select[name="shipment[source]"]', options[randomIndex]);

        await page.click('button[type="submit"].primary-button:visible');

        await expect(page.getByText('Shipment created successfully')).toBeVisible();
    });

    test('create refund', async () => {

        await page.waitForSelector('a > span.icon-sort-right.cursor-pointer.text-2xl');

        const iconRight = await page.$$('a > span.icon-sort-right.cursor-pointer.text-2xl');

        await iconRight[0].click();
        await page.waitForSelector('div.transparent-button.px-1 > .icon-cancel.text-2xl:visible', { timeout: 1000 }).catch(() => null);

        await page.click('div.transparent-button.px-1 > .icon-cancel.text-2xl:visible');
        await page.waitForSelector('input[type="text"].w-full.rounded-md.border.px-3.text-sm.text-gray-600.transition-all:visible', { timeout: 1000 }).catch(() => null);

        const itemQty = await page.$$('input[type="text"].w-full.rounded-md.border.px-3.text-sm.text-gray-600.transition-all:visible');
        let i = 1;
        for (let element of itemQty) {
            await element.scrollIntoViewIfNeeded();

            if (i > itemQty.length - 2) {
                let rand = Math.floor(Math.random() * (2000));
                await element.fill(rand.toString());
            }

            if (i > itemQty.length - 3) {
                continue;
            }

            const currentValue = await element.inputValue();

            const maxQty = parseInt(currentValue, 10);
            const qty = Math.floor(Math.random() * (maxQty - 1)) + 1;

            await element.fill(qty.toString());

            i++;
        }

        await page.click('button[type="submit"].primary-button:visible');

        await expect(page.getByText('Refund created successfully')).toBeVisible();
    });

    test('cancel order', async () => {

        await page.waitForSelector('a > span.icon-sort-right.cursor-pointer.text-2xl');

        const iconRight = await page.$$('a > span.icon-sort-right.cursor-pointer.text-2xl');

        await iconRight[0].click();
        await page.waitForSelector('.icon-cancel.text-2xl + a:visible', { timeout: 1000 }).catch(() => null);

        await page.click('.icon-cancel.text-2xl + a:visible');
        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Order cancelled successfully')).toBeVisible();
    });

    test('mail invoice', async () => {
        await page.goto(`${config.baseUrl}/admin/sales/invoices`);

        await page.waitForSelector('.cursor-pointer.rounded-md.text-2xl.transition-all.icon-view');

        const iconEye = await page.$$('.cursor-pointer.rounded-md.text-2xl.transition-all.icon-view');

        await iconEye[Math.floor(Math.random() * ((iconEye.length - 1) - 0 + 1)) + 0].click();

        await page.click('button[type="button"].inline-flex.w-full.max-w-max.cursor-pointer.items-center.justify-between.gap-x-2.px-1.text-center.font-semibold.text-gray-600.transition-all > .icon-mail.text-2xl:visible');

        const email = forms.form.email;
        await page.fill('input[type="email"][name="email"]#email:visible', email);

        await page.click('button.primary-button:visible');

        await expect(page.getByText('Invoice sent successfully')).toBeVisible();
    });

    test('print invoice', async () => {
        await page.goto(`${config.baseUrl}/admin/sales/invoices`);

        await page.waitForSelector('.cursor-pointer.rounded-md.text-2xl.transition-all.icon-view');

        const iconEye = await page.$$('.cursor-pointer.rounded-md.text-2xl.transition-all.icon-view');

        await iconEye[Math.floor(Math.random() * ((iconEye.length - 1) - 0 + 1)) + 0].click();

        await page.click('a.inline-flex.w-full.max-w-max.cursor-pointer.items-center.justify-between.gap-x-2.px-1.text-center.font-semibold.text-gray-600.transition-all > .icon-printer.text-2xl:visible');
        const downloadPromise = page.waitForEvent('download');
        await page.getByRole('link', { name: ' Print' }).click();

        const download = await downloadPromise;

        await download.saveAs('/home/users/kartikey.dubey/invoice.pdf');
    });
});
