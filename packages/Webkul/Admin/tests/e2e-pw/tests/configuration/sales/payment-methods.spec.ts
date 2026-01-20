// import { test, expect } from "../../../setup";
// import {
//     generateEmail,
//     generateDescription,
//     generateCurrency,
//     getImageFile,
// } from "../../../utils/faker";

// test.describe("payment methods configuration", () => {
//     /**
//      * Navigate to the configuration page.
//      */
//     test.beforeEach(async ({ adminPage }) => {
//         await adminPage.goto("admin/configuration/sales/payment_methods");
//     });

//     /**
//      * Update the Cash On Delivery Payment Method Configuration.
//      */
//     test("should configure the cash on delivery payment method", async ({
//         adminPage,
//     }) => {
//         await adminPage.fill(
//             'textarea[name="sales[payment_methods][cashondelivery][description]"]',
//             generateDescription(200)
//         );

//         const [fileChooser] = await Promise.all([
//             adminPage.waitForEvent("filechooser"),
//             adminPage.click('label:has-text("Logo")'),
//         ]);

//         await fileChooser.setFiles(getImageFile());

//         await adminPage.fill(
//             'textarea[name="sales[payment_methods][cashondelivery][instructions]"]',
//             generateDescription(200)
//         );

//         await adminPage.click(
//             'label[for="sales[payment_methods][cashondelivery][generate_invoice]"]'
//         );
//         // const generateInvoiceToggle = await adminPage.locator('input[name="sales[payment_methods][cashondelivery][generate_invoice]"]');
//         // await expect(generateInvoiceToggle).toBeChecked();

//         // if (await generateInvoiceToggle.toBeChecked()) {
//         await adminPage.selectOption(
//             'select[name="sales[payment_methods][cashondelivery][invoice_status]"]',
//             "pending"
//         );
//         const invoiceStatus = adminPage.locator(
//             'select[name="sales[payment_methods][cashondelivery][invoice_status]"]'
//         );
//         await expect(invoiceStatus).toHaveValue("pending");
//         // }

//         await adminPage.selectOption(
//             'select[name="sales[payment_methods][cashondelivery][order_status]"]',
//             "pending"
//         );
//         const orderStatus = adminPage.locator(
//             'select[name="sales[payment_methods][cashondelivery][order_status]"]'
//         );
//         await expect(orderStatus).toHaveValue("pending");

//         // await adminPage.click(
//         //     'label[for="sales[payment_methods][cashondelivery][active]"]'
//         // );
//         // const cashondeliveryToggle = await adminPage.locator('input[name="sales[payment_methods][cashondelivery][active]"]');
//         // await expect(cashondeliveryToggle).toBeChecked();

//         // if (await cashondeliveryToggle.toBeChecked()) {
//         //     await adminPage.fill('input[name="sales[payment_methods][cashondelivery][title]"]', generateName());
//         // }

//         await adminPage.selectOption(
//             'select[name="sales[payment_methods][cashondelivery][sort]"]',
//             "2"
//         );
//         const sort = adminPage.locator(
//             'select[name="sales[payment_methods][cashondelivery][sort]"]'
//         );
//         await expect(sort).toHaveValue("2");

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();
//     });

//     /**
//      * Update the Money Transfer Payment Method Configuration.
//      */
//     test("should configure the money transfer payment method", async ({
//         adminPage,
//     }) => {
//         await adminPage.fill(
//             'textarea[name="sales[payment_methods][moneytransfer][description]"]',
//             generateDescription(200)
//         );

//         const [fileChooser] = await Promise.all([
//             adminPage.waitForEvent("filechooser"),
//             adminPage.click('label:has-text("Logo")'),
//         ]);

//         await fileChooser.setFiles(getImageFile());

//         await adminPage.click(
//             'label[for="sales[payment_methods][moneytransfer][generate_invoice]"]'
//         );
//         // const generateInvoiceToggle = await adminPage.locator('input[name="sales[payment_methods][moneytransfer][generate_invoice]"]');
//         // await expect(generateInvoiceToggle).toBeChecked();

//         // if (await generateInvoiceToggle.toBeChecked()) {
//         await adminPage.selectOption(
//             'select[name="sales[payment_methods][moneytransfer][invoice_status]"]',
//             "pending"
//         );
//         const invoiceStatus = adminPage.locator(
//             'select[name="sales[payment_methods][moneytransfer][invoice_status]"]'
//         );
//         await expect(invoiceStatus).toHaveValue("pending");
//         // }

//         await adminPage.selectOption(
//             'select[name="sales[payment_methods][moneytransfer][order_status]"]',
//             "pending"
//         );
//         const orderStatus = adminPage.locator(
//             'select[name="sales[payment_methods][moneytransfer][order_status]"]'
//         );
//         await expect(orderStatus).toHaveValue("pending");

//         await adminPage.fill(
//             'textarea[name="sales[payment_methods][moneytransfer][mailing_address]"]',
//             generateDescription(200)
//         );

//         // await adminPage.click(
//         //     'label[for="sales[payment_methods][moneytransfer][active]"]'
//         // );
//         // const moneyTransferToggle = await adminPage.locator('input[name="sales[payment_methods][moneytransfer][active]"]');
//         // await expect(moneyTransferToggle).toBeChecked();

//         // if (await moneyTransferToggle.toBeChecked()) {
//         //     await adminPage.fill('input[name="sales[payment_methods][moneytransfer][title]"]', generateName());
//         // }

//         await adminPage.selectOption(
//             'select[name="sales[payment_methods][moneytransfer][sort]"]',
//             "2"
//         );
//         const sort = adminPage.locator(
//             'select[name="sales[payment_methods][moneytransfer][sort]"]'
//         );
//         await expect(sort).toHaveValue("2");

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();
//     });

//     /**
//      * Update the PayPal Standard Payment Method Configuration.
//      */
//     test("should configure the paypal standard payment method", async ({
//         adminPage,
//     }) => {
//         await adminPage.fill(
//             'textarea[name="sales[payment_methods][paypal_standard][description]"]',
//             generateDescription(200)
//         );

//         const [fileChooser] = await Promise.all([
//             adminPage.waitForEvent("filechooser"),
//             adminPage.click('label:has-text("Logo")'),
//         ]);

//         await fileChooser.setFiles(getImageFile());

//         // await adminPage.click(
//         //     'label[for="sales[payment_methods][paypal_standard][active]"]'
//         // );
//         // const paypalStandardToggle = await adminPage.locator('input[name="sales[payment_methods][paypal_standard][active]"]');
//         // await expect(paypalStandardToggle).toBeChecked();

//         // if (await paypalStandardToggle.toBeChecked()) {
//         //     await adminPage.fill('input[name="sales[payment_methods][paypal_standard][title]"]', generateName());
//         //     await adminPage.fill('input[name="sales[payment_methods][paypal_standard][business_account]"]', generateEmail());
//         // }

//         await adminPage.click(
//             'label[for="sales[payment_methods][paypal_standard][sandbox]"]'
//         );
//         // const paypalToggle = await adminPage.locator('input[name="sales[payment_methods][paypal_standard][sandbox]"]');
//         // await expect(paypalToggle).toBeChecked();

//         await adminPage.selectOption(
//             'select[name="sales[payment_methods][paypal_standard][sort]"]',
//             "2"
//         );
//         const sort = adminPage.locator(
//             'select[name="sales[payment_methods][paypal_standard][sort]"]'
//         );
//         await expect(sort).toHaveValue("2");

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();
//     });

//     /**
//      * Update the PayPal Smart Button Payment Method Configuration.
//      */
//     test("should configure the paypal smart button payment method", async ({
//         adminPage,
//     }) => {
//         await adminPage.fill(
//             'textarea[name="sales[payment_methods][paypal_smart_button][description]"]',
//             generateDescription(200)
//         );

//         const [fileChooser] = await Promise.all([
//             adminPage.waitForEvent("filechooser"),
//             adminPage.click('label:has-text("Logo")'),
//         ]);

//         await fileChooser.setFiles(getImageFile());

//         // await adminPage.click(
//         //     'label[for="sales[payment_methods][paypal_smart_button][active]"]'
//         // );
//         // const paypalSmartButtonToggle = await adminPage.locator('input[name="sales[payment_methods][paypal_smart_button][active]"]');
//         // await expect(paypalSmartButtonToggle).toBeChecked();

//         // if (await paypalSmartButtonToggle.toBeChecked()) {
//         //     await adminPage.fill('input[name="sales[payment_methods][paypal_smart_button][title]"]', generateName());
//         //     await adminPage.fill('input[name="sales[payment_methods][paypal_smart_button][client_secret]"]', generateRandomPassword());
//         //     await adminPage.fill('input[name="sales[payment_methods][paypal_smart_button][accepted_currencies]"]', generateCurrency());
//         // }

//         await adminPage.click(
//             'label[for="sales[payment_methods][paypal_smart_button][sandbox]"]'
//         );
//         // const paypalSmartButtonToggle = await adminPage.locator('input[name="sales[payment_methods][paypal_smart_button][sandbox]"]');
//         // await expect(paypalSmartButtonToggle).toBeChecked();

//         await adminPage.selectOption(
//             'select[name="sales[payment_methods][paypal_standard][sort]"]',
//             "2"
//         );
//         const sort = adminPage.locator(
//             'select[name="sales[payment_methods][paypal_standard][sort]"]'
//         );
//         await expect(sort).toHaveValue("2");

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(
//             adminPage.getByText("Configuration saved successfully")
//         ).toBeVisible();
//     });
// });
