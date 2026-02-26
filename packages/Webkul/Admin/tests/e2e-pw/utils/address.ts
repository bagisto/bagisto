
import { generateEmail, generateFirstName, generateLastName, generatePhoneNumber, generateRandomNumericString }  from './faker';

const address = async (page) => {
  await page.fill('input[name="billing.company_name"]', generateLastName());
  await page.fill('input[name="billing.first_name"]', generateFirstName());
  await page.fill('input[name="billing.last_name"]', generateLastName());
  await page.fill('input[name="billing.email"]', generateEmail());
  await page.fill('input[name="billing.address.[0]"]', generateFirstName());
  await page.selectOption('select[name="billing.country"]', 'IN');
  await page.selectOption('select[name="billing.state"]', 'UP');
  await page.fill('input[name="billing.city"]', generateLastName());
  await page.fill('input[name="billing.postcode"]', generateRandomNumericString(6));
  await page.fill('input[name="billing.phone"]', generatePhoneNumber());

  const exists = await page.$$('input[name="billing.save_address"]');

  if (exists.length !== 0) {
    await page.click('input[name="billing.save_address"] + label');
    await page.press('input[name="billing.phone"]', 'Enter');
  } else {
    const checkbox = await page.$$('input[name="billing.use_for_shipping"]');

    if (Math.random() < 0.67) {  // ~2/3 chance
      if (!(await checkbox[0].isChecked())) {
        await page.click('input[name="billing.use_for_shipping"] + label');
      }
    } else {
      if (await checkbox[0].isChecked()) {
        await page.click('input[name="billing.use_for_shipping"] + label');
      }

      await page.fill('input[name="shipping.company_name"]', generateLastName());
      await page.fill('input[name="shipping.first_name"]', generateFirstName());
      await page.fill('input[name="shipping.last_name"]', generateLastName());
      await page.fill('input[name="shipping.email"]', generateEmail());
      await page.fill('input[name="shipping.address.[0]"]', generateFirstName());
      await page.selectOption('select[name="shipping.country"]', 'IN');
      await page.selectOption('select[name="shipping.state"]', 'UP');
      await page.fill('input[name="shipping.city"]', generateLastName());
      await page.fill('input[name="shipping.postcode"]', '201301');
      await page.fill('input[name="shipping.phone"]', generatePhoneNumber());
    }

    const nextButton = await page.$('button.primary-button:visible');
    if (nextButton) await nextButton.click();
  }

  return "done";
};

export default address;
