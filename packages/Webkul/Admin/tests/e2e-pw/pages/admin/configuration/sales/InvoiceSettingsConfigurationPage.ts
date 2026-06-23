import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class InvoiceSettingsConfigurationPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get saveButton() {
        return this.page.locator(
            'button[type="submit"].primary-button:visible',
        );
    }

    private get successNotification() {
        return this.page.getByText("Configuration saved successfully").first();
    }

    private get invoicePrefixInput() {
        return this.page.locator(
            'input[name="sales[invoice_settings][invoice_number][invoice_number_prefix]"]',
        );
    }

    private get invoiceLengthInput() {
        return this.page.locator(
            'input[name="sales[invoice_settings][invoice_number][invoice_number_length]"]',
        );
    }

    private get invoiceSuffixInput() {
        return this.page.locator(
            'input[name="sales[invoice_settings][invoice_number][invoice_number_suffix]"]',
        );
    }

    private get invoiceGeneratorInput() {
        return this.page.locator(
            'input[name="sales[invoice_settings][invoice_number][invoice_number_generator_class]"]',
        );
    }

    private get paymentDueDurationInput() {
        return this.page.locator(
            'input[name="sales[invoice_settings][payment_terms][due_duration]"]',
        );
    }

    private get invoiceIdToggle() {
        return this.page.locator(
            'label[for="sales[invoice_settings][pdf_print_outs][invoice_id]"]',
        );
    }

    private get orderIdToggle() {
        return this.page.locator(
            'label[for="sales[invoice_settings][pdf_print_outs][order_id]"]',
        );
    }

    private get logoUploader() {
        return this.page.locator(
            'input[name="sales[invoice_settings][pdf_print_outs][logo]"]',
        );
    }

    private get remindersLimitInput() {
        return this.page.locator(
            'input[name="sales[invoice_settings][invoice_reminders][reminders_limit]"]',
        );
    }

    private get remindersIntervalSelect() {
        return this.page.locator(
            'select[name="sales[invoice_settings][invoice_reminders][interval_between_reminders]"]',
        );
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/sales/invoice_settings");
    }

    async fillInvoiceNumberSettings(
        prefix: string,
        length: string,
        suffix: string,
        generator: string,
    ): Promise<void> {
        await this.invoicePrefixInput.fill(prefix);
        await this.invoiceLengthInput.fill(length);
        await this.invoiceSuffixInput.fill(suffix);
        await this.invoiceGeneratorInput.fill(generator);
    }

    async setPaymentDueDuration(value: string): Promise<void> {
        await this.paymentDueDurationInput.fill(value);
    }

    async configurePdfPrintOuts(logoPath: string): Promise<void> {
        await this.invoiceIdToggle.click();
        await this.orderIdToggle.click();
        await this.logoUploader.setInputFiles(logoPath);
    }

    async configureInvoiceReminders(
        limit: string,
        interval: string,
    ): Promise<void> {
        await this.remindersLimitInput.fill(limit);
        await this.remindersIntervalSelect.selectOption(interval);
    }

    async getInvoiceReminderIntervalValue(): Promise<string> {
        return this.remindersIntervalSelect.inputValue();
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
