import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class PaymentMethodsConfigurationPage extends BasePage {
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

    private getLogoUploadButton() {
        return this.page.locator('label:has-text("Logo")');
    }

    private getCashOnDeliveryDescription() {
        return this.page.locator(
            'textarea[name="sales[payment_methods][cashondelivery][description]"]',
        );
    }

    private getCashOnDeliveryInstructions() {
        return this.page.locator(
            'textarea[name="sales[payment_methods][cashondelivery][instructions]"]',
        );
    }

    private getCashOnDeliveryInvoiceStatus() {
        return this.page.locator(
            'select[name="sales[payment_methods][cashondelivery][invoice_status]"]',
        );
    }

    private getCashOnDeliveryOrderStatus() {
        return this.page.locator(
            'select[name="sales[payment_methods][cashondelivery][order_status]"]',
        );
    }

    private getCashOnDeliverySort() {
        return this.page.locator(
            'input[name="sales[payment_methods][cashondelivery][sort]"]',
        );
    }

    private getMoneyTransferDescription() {
        return this.page.locator(
            'textarea[name="sales[payment_methods][moneytransfer][description]"]',
        );
    }

    private getMoneyTransferInvoiceStatus() {
        return this.page.locator(
            'select[name="sales[payment_methods][moneytransfer][invoice_status]"]',
        );
    }

    private getMoneyTransferOrderStatus() {
        return this.page.locator(
            'select[name="sales[payment_methods][moneytransfer][order_status]"]',
        );
    }

    private getMoneyTransferMailingAddress() {
        return this.page.locator(
            'textarea[name="sales[payment_methods][moneytransfer][mailing_address]"]',
        );
    }

    private getMoneyTransferSort() {
        return this.page.locator(
            'input[name="sales[payment_methods][moneytransfer][sort]"]',
        );
    }

    private getPaypalStandardDescription() {
        return this.page.locator(
            'textarea[name="sales[payment_methods][paypal_standard][description]"]',
        );
    }

    private getPaypalStandardSandboxToggle() {
        return this.page.locator(
            'label[for="sales[payment_methods][paypal_standard][sandbox]"]',
        );
    }

    private getPaypalStandardSort() {
        return this.page.locator(
            'input[name="sales[payment_methods][paypal_standard][sort]"]',
        );
    }

    private getPaypalSmartButtonDescription() {
        return this.page.locator(
            'textarea[name="sales[payment_methods][paypal_smart_button][description]"]',
        );
    }

    private getPaypalSmartButtonSandboxToggle() {
        return this.page.locator(
            'label[for="sales[payment_methods][paypal_smart_button][sandbox]"]',
        );
    }

    private getPaypalSmartButtonSort() {
        return this.page.locator(
            'input[name="sales[payment_methods][paypal_smart_button][sort]"]',
        );
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/sales/payment_methods");
    }

    async uploadLogo(filePath: string): Promise<void> {
        const [fileChooser] = await Promise.all([
            this.page.waitForEvent("filechooser"),
            this.getLogoUploadButton().click(),
        ]);

        await fileChooser.setFiles(filePath);
    }

    async configureCashOnDelivery(
        description: string,
        instructions: string,
        invoiceStatus: string,
        orderStatus: string,
        sort: string,
        logoPath: string,
    ): Promise<void> {
        await this.getCashOnDeliveryDescription().fill(description);
        await this.uploadLogo(logoPath);
        await this.getCashOnDeliveryInstructions().fill(instructions);
        await this.getCashOnDeliveryInvoiceStatus().selectOption(invoiceStatus);
        await this.getCashOnDeliveryOrderStatus().selectOption(orderStatus);
        await this.getCashOnDeliverySort().fill(sort);
    }

    async configureMoneyTransfer(
        description: string,
        mailingAddress: string,
        invoiceStatus: string,
        orderStatus: string,
        sort: string,
        logoPath: string,
    ): Promise<void> {
        await this.getMoneyTransferDescription().fill(description);
        await this.uploadLogo(logoPath);
        await this.getMoneyTransferInvoiceStatus().selectOption(invoiceStatus);
        await this.getMoneyTransferOrderStatus().selectOption(orderStatus);
        await this.getMoneyTransferMailingAddress().fill(mailingAddress);
        await this.getMoneyTransferSort().fill(sort);
    }

    async configurePaypalStandard(
        description: string,
        sandbox: boolean,
        sort: string,
        logoPath: string,
    ): Promise<void> {
        await this.getPaypalStandardDescription().fill(description);
        await this.uploadLogo(logoPath);
        if (sandbox) {
            await this.getPaypalStandardSandboxToggle().click();
        }
        await this.getPaypalStandardSort().fill(sort);
    }

    async configurePaypalSmartButton(
        description: string,
        sandbox: boolean,
        sort: string,
        logoPath: string,
    ): Promise<void> {
        await this.getPaypalSmartButtonDescription().fill(description);
        await this.uploadLogo(logoPath);
        if (sandbox) {
            await this.getPaypalSmartButtonSandboxToggle().click();
        }
        await this.getPaypalSmartButtonSort().fill(sort);
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
