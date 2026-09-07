import { test } from "../../../setup";
import {
    InvoiceSettingsConfigurationPage,
    type InvoiceSettings,
} from "../../../pages/admin/configuration/sales/InvoiceSettingsConfigurationPage";

function other(current: string, first: string, second: string): string {
    return current === first ? second : first;
}

test.describe("invoice settings configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: InvoiceSettingsConfigurationPage;
    let original: InvoiceSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new InvoiceSettingsConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist the invoice number format after reload", async () => {
        const changed = {
            invoiceNumberPrefix: other(original.invoiceNumberPrefix, "INV", "E2E"),
            invoiceNumberLength: other(original.invoiceNumberLength, "6", "8"),
            invoiceNumberSuffix: other(original.invoiceNumberSuffix, "X", "Y"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the payment due duration after reload", async () => {
        const changed = {
            paymentDueDuration: other(original.paymentDueDuration, "15", "30"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the pdf print out settings after reload", async () => {
        const changed = {
            printInvoiceId: !original.printInvoiceId,
            printOrderId: !original.printOrderId,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the invoice reminder settings after reload", async () => {
        const changed = {
            remindersLimit: other(original.remindersLimit, "3", "5"),
            remindersInterval: other(original.remindersInterval, "P2D", "P3D"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
