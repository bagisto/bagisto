import { test, expect } from '../../../setup';
import {
    generateName,
    generateRandomNumericString,
    getImageFile,
} from '../../../utils/faker';
import { InvoiceSettingsConfigurationPage } from '../../../pages/admin/configuration/sales/InvoiceSettingsConfigurationPage';

test.describe('Invoice Settings Configuration', () => {
    test.beforeEach(async ({ adminPage }) => {
        await new InvoiceSettingsConfigurationPage(adminPage).open();
    });

    test('should update invoice number settings', async ({ adminPage }) => {
        const page = new InvoiceSettingsConfigurationPage(adminPage);

        await page.fillInvoiceNumberSettings(
            generateName(),
            generateRandomNumericString(1, 10),
            generateName(),
            generateRandomNumericString(2),
        );
        await page.saveAndVerify();
    });

    test('should update payment due duration', async ({ adminPage }) => {
        const page = new InvoiceSettingsConfigurationPage(adminPage);

        await page.setPaymentDueDuration(generateRandomNumericString(2));
        await page.saveAndVerify();
    });

    test('should configure PDF print outs ', async ({ adminPage }) => {
        const page = new InvoiceSettingsConfigurationPage(adminPage);

        await page.configurePdfPrintOuts(getImageFile());
        await page.saveAndVerify();
    });

    test('should configure the invoice reminders', async ({ adminPage }) => {
        const page = new InvoiceSettingsConfigurationPage(adminPage);

        await page.configureInvoiceReminders(generateRandomNumericString(2), 'P2D');
        await expect(await page.getInvoiceReminderIntervalValue()).toBe('P2D');
        await page.saveAndVerify();
    });
});
