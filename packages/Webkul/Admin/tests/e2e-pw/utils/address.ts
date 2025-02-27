import * as forms from './form';
import { generateRandomNumericString }  from './faker';

const address = async (page) => {
    await page.fill('input[name="billing.company_name"]', forms.form.lastName);
    await page.fill('input[name="billing.first_name"]', forms.form.firstName);
    await page.fill('input[name="billing.last_name"]', forms.form.lastName);
    await page.fill('input[name="billing.email"]', forms.form.email);
    await page.fill('input[name="billing.address.[0]"]', forms.form.firstName);
    await page.selectOption('select[name="billing.country"]', 'IN');
    await page.selectOption('select[name="billing.state"]', 'UP');
    await page.fill('input[name="billing.city"]', forms.form.lastName);
    await page.fill('input[name="billing.postcode"]', generateRandomNumericString(6));
    await page.fill('input[name="billing.phone"]', forms.form.phone);

    const exists = await page.$$('input[name="billing.save_address"]');

    if (exists.length != 0) {
        await page.click('input[name="billing.save_address"] + label');
        await page.press('input[name="billing.phone"]', 'Enter');
    } else {
        const checkbox = await page.$$('input[name="billing.use_for_shipping"]');

        if (Math.floor(Math.random() * 20) % 3 == 1 ? false : true) {
            if (! checkbox[0].isChecked()) {
                await page.click('input[name="billing.use_for_shipping"] + label');
            }
        } else {
            if (checkbox[0].isChecked()) {
                await page.click('input[name="billing.use_for_shipping"] + label');
            }

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
        }

        const nextButton = await page.$$('button.primary-button:visible');
        await nextButton[0].click();
    }

    const output = await forms.testForm(page)

    if (output == null) {
        return address(page);
    } else {
        return 'done';
    }
}

export default address;
