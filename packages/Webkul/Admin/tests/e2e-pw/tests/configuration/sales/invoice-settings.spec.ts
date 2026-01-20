// import { test, expect } from '../../../setup';

// import { 
//     generateName, 
//     generateRandomNumericString,
//     getImageFile,
// } from '../../../utils/faker';

// test.describe('Invoice Settings Configuration', () => {
//     /**
//      * Navigate to the configuration page.
//      */
//     test.beforeEach(async ({ adminPage }) => {
//         await adminPage.goto('admin/configuration/sales/invoice_settings');
//     });

//     /**
//      * Update the Invoice Number Configuration.
//      */
//     test('should update invoice number settings', async ({ adminPage }) => {
//         await adminPage.fill('input[name="sales[invoice_settings][invoice_number][invoice_number_prefix]"]', generateName());
//         await adminPage.fill('input[name="sales[invoice_settings][invoice_number][invoice_number_length]"]', generateRandomNumericString(1, 10));
//         await adminPage.fill('input[name="sales[invoice_settings][invoice_number][invoice_number_suffix]"]', generateName());
//         await adminPage.fill('input[name="sales[invoice_settings][invoice_number][invoice_number_generator_class]"]', generateRandomNumericString(2));

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
//     });

//     /**
//      * Update the Payment Term Configuration.
//      */
//     test('should update payment due duration', async ({ adminPage }) => {
//         await adminPage.fill('input[name="sales[invoice_settings][payment_terms][due_duration]"]', generateRandomNumericString(2));
//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
//     });

//     /**
//      * Configure PDF Print Outs.
//      */
//     test('should configure PDF print outs ', async ({ adminPage }) => {
//         await adminPage.click('label[for="sales[invoice_settings][pdf_print_outs][invoice_id]"]');
//         const adminReorderToggle = await adminPage.locator('input[name="sales[invoice_settings][pdf_print_outs][invoice_id]"]');
//         // await expect(adminReorderToggle).toBeChecked();

//         await adminPage.click('label[for="sales[invoice_settings][pdf_print_outs][order_id]"]');
//         const shopReorderToggle = await adminPage.locator('input[name="sales[invoice_settings][pdf_print_outs][order_id]"]');
//         // await expect(shopReorderToggle).toBeChecked();

//         const [fileChooser] = await Promise.all([
//             adminPage.waitForEvent('filechooser'),
//             adminPage.click('input[name="sales[invoice_settings][pdf_print_outs][logo]"]')
//         ]);

//         await fileChooser.setFiles(getImageFile());
        
//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
//     });

//     /**
//      * Invoice Reminders.
//      */
//     test('should configure the invoice reminders', async ({ adminPage }) => {
//         await adminPage.fill('input[name="sales[invoice_settings][invoice_reminders][reminders_limit]"]', generateRandomNumericString(2));

//         await adminPage.selectOption('select[name="sales[invoice_settings][invoice_reminders][interval_between_reminders]"]', 'P2D');
//         const reminderDuration = adminPage.locator('select[name="sales[invoice_settings][invoice_reminders][interval_between_reminders]"]');
//         await expect(reminderDuration).toHaveValue('P2D');

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
//     });
// });