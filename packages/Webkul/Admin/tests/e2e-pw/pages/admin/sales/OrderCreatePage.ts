import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class OrderCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createOrderButton() {
        return this.page.getByRole("button", { name: "Create Order" });
    }

    private get customerSearchInput() {
        return this.page.getByPlaceholder("Search by email or name");
    }

    private get addProductButton() {
        return this.page.getByRole("button", { name: "Add Product" });
    }

    private get productSearchInput() {
        return this.page.getByPlaceholder("Search by name");
    }

    private get billingAddressOptions() {
        return this.page.locator('input[name="billing.id"]');
    }

    private get proceedButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    private get placeOrderButton() {
        return this.page.getByRole("button", { name: "Place Order" });
    }

    private get orderHeading() {
        return this.page.locator("p").filter({ hasText: /^\s*Order #\s*\d+\s*$/ });
    }

    private customerResult(email: string) {
        return this.page.locator(`div:has(> p:text-is("${email}"))`);
    }

    private productResult(productName: string) {
        return this.page.locator("div.flex.justify-between").filter({
            has: this.page.locator("p", {
                hasText: new RegExp(`^\\s*${productName.replace(/[.*+?^${}()|[\]\\]/g, "\\$&")}\\s*$`),
            }),
        });
    }

    private billingAddressLabel(addressId: string) {
        return this.page.locator(`label[for="billing_address_id_${addressId}"]`);
    }

    private shippingMethodOption(method: string) {
        return this.page
            .locator(`label[for="${method}"]`)
            .filter({ hasText: /\S/ });
    }

    private paymentMethodOption(method: string) {
        return this.page
            .locator(`label[for="${method}"]`)
            .filter({ hasText: /\S/ });
    }

    private async readOrderId(): Promise<string> {
        await expect(this.page).toHaveURL(/sales\/orders\/view\/\d+/);

        const heading = await this.orderHeading.innerText();

        return heading.replace(/[^\d]/g, "");
    }

    async startOrderForCustomer(email: string): Promise<void> {
        await this.visit("admin/sales/orders");
        await this.waitForVueMount();
        await this.createOrderButton.click();
        await this.customerSearchInput.fill(email);
        await this.customerSearchInput.press("Enter");
        await this.customerResult(email).click();

        await expect(this.page).toHaveURL(/sales\/orders\/create\/\d+/);
        await this.waitForVueMount();
    }

    async addProduct(productName: string): Promise<void> {
        await this.addProductButton.click();
        await this.productSearchInput.fill(productName);

        const result = this.productResult(productName);

        await expect(result).toHaveCount(1);
        await result.locator("form").getByRole("button", { name: "Add to Cart" }).click();

        await expect(
            this.page.getByText("Product added to cart successfully"),
        ).toBeVisible();
    }

    async useSavedBillingAddress(): Promise<void> {
        await expect(this.billingAddressOptions).toHaveCount(1);

        const addressId = await this.billingAddressOptions.getAttribute("value");

        if (!addressId) {
            throw new Error("The saved billing address has no id to select");
        }

        await this.billingAddressLabel(addressId).filter({ hasText: /\S/ }).click();

        await expect(this.billingAddressOptions).toBeChecked();

        await this.proceedButton.click();
    }

    async chooseFreeShippingAndMoneyTransfer(): Promise<void> {
        await this.shippingMethodOption("free_free").click();
        await this.paymentMethodOption("moneytransfer").click();
    }

    async placeOrder(): Promise<string> {
        await this.placeOrderButton.click();

        return this.readOrderId();
    }

    async placeOrderForCustomer(
        email: string,
        productName: string,
    ): Promise<string> {
        await this.startOrderForCustomer(email);
        await this.addProduct(productName);
        await this.useSavedBillingAddress();
        await this.chooseFreeShippingAndMoneyTransfer();

        return this.placeOrder();
    }

    async completeReorder(): Promise<string> {
        await expect(this.page).toHaveURL(/sales\/orders\/create\/\d+/);
        await this.waitForVueMount();
        await this.useSavedBillingAddress();
        await this.chooseFreeShippingAndMoneyTransfer();

        return this.placeOrder();
    }
}
