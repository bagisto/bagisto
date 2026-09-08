import { expect, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";
import { addAddress, loginAsCustomer } from "../../utils/customer";

export class RmaShopPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.getByPlaceholder("Search products here");
    }

    private get addToCartButtons() {
        return this.page.getByRole("button", { name: "Add To Cart" });
    }

    private get cartButton() {
        return this.page.getByRole("button", { name: "Shopping Cart" });
    }

    private get continueToCheckoutLink() {
        return this.page.getByRole("link", { name: "Continue to Checkout" });
    }

    private get savedAddressOptions() {
        return this.page.locator(".icon-radio-unselect");
    }

    private get proceedButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    private get placeOrderButton() {
        return this.page.getByRole("button", { name: "Place Order" });
    }

    private get orderLink() {
        return this.page.locator('a[href*="/customer/account/orders/view/"]');
    }

    private get newRequestLink() {
        return this.page.getByText("New RMA Request");
    }

    private get itemCheckbox() {
        return this.page.locator('input[name^="isChecked["]');
    }

    private get resolutionSelect() {
        return this.page.locator('select[name^="resolution_type"]');
    }

    private get reasonSelect() {
        return this.page.locator('select[name="rma_reason_id"]');
    }

    private get quantityInput() {
        return this.page.locator('input[name^="rma_qty"]');
    }

    private get packageConditionSelect() {
        return this.page.locator('select[name="package_condition"]');
    }

    private get informationInput() {
        return this.page.locator('textarea[name="information"]');
    }

    private get agreementToggle() {
        return this.page.locator("label:has(input#agreement)");
    }

    private get submitRequestButton() {
        return this.page.getByRole("button", { name: "Submit request" });
    }

    private get multiselectCustomField() {
        return this.page.locator('select[name^="customAttributes"][multiple]');
    }

    private orderRow(incrementId: string) {
        return this.page.locator("div.row").filter({
            has: this.page.getByRole("link", { name: `#${incrementId}`, exact: true }),
        });
    }

    async registerAndAddAddress(): Promise<void> {
        await loginAsCustomer(this.page);
        await addAddress(this.page);
    }

    async placeOrder(productName: string): Promise<string> {
        await this.visit("");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");

        await expect(this.addToCartButtons).toHaveCount(1);
        await this.addToCartButtons.click();
        await expect(
            this.page.getByText("Item Added Successfully").first(),
        ).toBeVisible();

        await this.cartButton.click();
        await this.continueToCheckoutLink.click();
        await expect(this.savedAddressOptions).toHaveCount(1);
        await this.savedAddressOptions.click();
        await this.proceedButton.click();
        await this.page.locator('label[for="free_free"]').filter({ hasText: /\S/ }).click();
        await expect(this.page.locator("input#free_free")).toBeChecked();
        await Promise.all([
            this.page.waitForResponse((response) =>
                response.url().includes("checkout/onepage/payment-methods"),
            ),
            this.page.locator('label[for="moneytransfer"]').filter({ hasText: /\S/ }).click(),
        ]);
        await expect(this.page.locator("input#moneytransfer")).toBeChecked();
        await expect(this.placeOrderButton).toBeEnabled();

        const [orderResponse] = await Promise.all([
            this.page.waitForResponse(
                (response) =>
                    response.url().includes("checkout/onepage/orders") &&
                    response.request().method() === "POST",
            ),
            this.placeOrderButton.click(),
        ]);

        expect(orderResponse.ok(), `order placement failed with ${orderResponse.status()}`).toBe(true);

        await this.page.waitForURL(/checkout\/onepage\/success/);

        return (await this.orderLink.innerText()).replace(/[^\d]/g, "");
    }

    async requestReturn(
        orderIncrementId: string,
        reason: string,
        multiselectOptions: string[] = [],
    ): Promise<void> {
        await this.visit("customer/account/rma");
        await this.newRequestLink.click();
        await this.orderRow(orderIncrementId)
            .locator(".icon-edit")
            .filter({ visible: true })
            .click();
        await this.itemCheckbox.check();
        await this.resolutionSelect.selectOption("return");
        await this.resolutionSelect.dispatchEvent("change");

        await expect(this.reasonSelect).toBeVisible();

        await this.reasonSelect.selectOption({ label: reason });
        await this.quantityInput.fill("1");
        await this.packageConditionSelect.selectOption("open");
        await this.informationInput.fill("Changed my mind.");

        if (multiselectOptions.length) {
            await expect(
                this.multiselectCustomField,
                "the multiselect custom field is not posted under customAttributes",
            ).toBeVisible();

            await this.multiselectCustomField.selectOption(multiselectOptions);
        }

        await this.agreementToggle.click();
        await this.submitRequestButton.click();

        await expect(
            this.page.getByText("Request created successfully.").first(),
        ).toBeVisible();
    }
}
