import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class RmaCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get newRequestButton() {
        return this.page.getByText("New RMA Request");
    }

    private orderRow(orderId: string) {
        return this.page.locator("div.row").filter({
            has: this.page.locator("p", {
                hasText: new RegExp(`^\\s*#?${orderId}\\s*$`),
            }),
        });
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

    private get agreementLabel() {
        return this.page.locator("label:has(input#agreement)");
    }

    private get submitButton() {
        return this.page.getByRole("button", { name: "Submit request" });
    }

    private get agreementError() {
        return this.page
            .locator("div.mb-4")
            .filter({ has: this.page.locator("input#agreement") })
            .locator("span.text-red-600");
    }

    private async openRequestForm(orderId: string): Promise<void> {
        await this.visit("customer/account/rma");
        await this.newRequestButton.click();

        await expect(this.orderRow(orderId)).toHaveCount(1);

        await this.orderRow(orderId).locator("a.icon-edit").filter({ visible: true }).click();

        await expect(this.itemCheckbox).toBeVisible();
    }

    private async fillRequest(orderId: string, quantity: string): Promise<void> {
        await this.openRequestForm(orderId);
        await this.itemCheckbox.check();
        await this.resolutionSelect.selectOption("return");
        await this.resolutionSelect.dispatchEvent("change");

        await expect(this.reasonSelect).toBeVisible();

        await this.reasonSelect.selectOption({ index: 1 });
        await this.quantityInput.fill(quantity);
    }

    async requestReturn(orderId: string): Promise<void> {
        await this.fillRequest(orderId, "1");
        await this.packageConditionSelect.selectOption({ value: "open" });
        await this.informationInput.fill("Changed my mind.");
        await this.agreementLabel.check();
        await this.submitButton.click();

        await expect(
            this.page.getByText("Request created successfully").first(),
        ).toBeVisible();
    }

    async attemptReturnWithExcessQuantity(orderId: string): Promise<void> {
        await this.fillRequest(orderId, "4");
    }

    async attemptReturnWithoutAcceptingTerms(orderId: string): Promise<void> {
        await this.fillRequest(orderId, "1");
        await this.packageConditionSelect.selectOption({ value: "open" });
        await this.informationInput.fill("Changed my mind.");
        await this.submitButton.click();
    }

    async expectTermsRejected(): Promise<void> {
        await expect(this.agreementError).toBeVisible();

        await expect(this.page).toHaveURL(/rma\/create/);
    }

    async expectQuantityRejected(): Promise<void> {
        await expect(
            this.page.getByText("The RMA Qty field must be 1 or less").first(),
        ).toBeVisible();
    }

    async expectRequestListedFor(productName: string): Promise<void> {
        await expect(
            this.page.getByText(productName, { exact: true }),
        ).toBeVisible();
    }

    async expectOrderNotOffered(orderId: string): Promise<void> {
        await this.visit("customer/account/rma");
        await this.newRequestButton.click();

        await expect(this.page).toHaveURL(/rma\/create/);
        await expect(this.orderRow(orderId)).toHaveCount(0);
    }

    async expectNoRequestForOrder(orderId: string): Promise<void> {
        await this.visit("customer/account/rma");

        await expect(this.page.getByText(`#${orderId}`)).toHaveCount(0);
    }
}
