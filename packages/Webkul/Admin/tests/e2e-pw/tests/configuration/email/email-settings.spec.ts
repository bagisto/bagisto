// import { test, expect } from '../../../setup';
// import { generateName, generateEmail } from '../../../utils/faker';


// test.describe('email settings configuration', () => {
//     test('should configure the email settings', async ({ adminPage }) => {
//         await adminPage.goto('admin/configuration/emails/configure');

//         await adminPage.locator('input[name="emails[configure][email_settings][sender_name]"]').fill(generateName());
//         await adminPage.locator('input[name="emails[configure][email_settings][shop_email_from]"]').fill(generateEmail());
//         await adminPage.locator('input[name="emails[configure][email_settings][admin_name]"]').fill(generateName());
//         await adminPage.locator('input[name="emails[configure][email_settings][admin_email]"]').fill(generateEmail());
//         await adminPage.locator('input[name="emails[configure][email_settings][contact_name]"]').fill(generateName());
//         await adminPage.locator('input[name="emails[configure][email_settings][contact_email]"]').fill(generateEmail());

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
//     });
// });
