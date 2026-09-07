import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class CustomerGDPRPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createRequestButton() {
        return this.page.getByRole("button", { name: "Create Request" });
    }

    private get typeSelect() {
        return this.page.locator('select[name="type"]');
    }

    private get messageTextarea() {
        return this.page.locator('textarea[name="message"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save" });
    }

    async openCustomerPage(): Promise<void> {
        await this.visit("customer/account/gdpr");
    }

    async openAdminPage(): Promise<void> {
        await this.visit("admin/customers/gdpr");
    }

    async createRequest(
        type: "update" | "delete",
        message: string,
    ): Promise<void> {
        await this.openCustomerPage();
        await this.createRequestButton.click();
        await this.typeSelect.selectOption(type);
        await this.messageTextarea.fill(message);
        await this.saveButton.click();
        const requestRow = this.page
            .locator("#main .row")
            .filter({ hasText: message });

        await expect(requestRow).toHaveCount(1);
        await expect(requestRow).toContainText("Pending");
    }

    async expectRequestState(
        message: string,
        expectedStatus: string,
        expectedType: string,
    ): Promise<void> {
        await this.openCustomerPage();
        const requestRow = this.page
            .locator("#main .row")
            .filter({ hasText: message });

        await expect(requestRow).toHaveCount(1);
        await expect(requestRow).toContainText(expectedStatus);
        await expect(requestRow).toContainText(expectedType);
    }

    async updateRequestStatus(
        message: string,
        status: "processing" | "completed" | "declined",
    ): Promise<void> {
        await this.openAdminPage();
        const requestRow = this.page
            .locator(".row")
            .filter({ hasText: message });

        await expect(requestRow).toHaveCount(1);

        await requestRow.locator("span.icon-edit").click();

        await expect(this.page.locator("#status")).toBeVisible();

        await this.page.selectOption("#status", status);
        await this.saveButton.click();
        await expect(
            this.page.getByText("Data Request updated successfully").first(),
        ).toBeVisible();
    }

    async deleteRequest(message: string): Promise<void> {
        await this.openAdminPage();
        const requestRow = this.page
            .locator(".row")
            .filter({ hasText: message });

        await expect(requestRow).toHaveCount(1);

        await requestRow.locator("span.icon-delete").click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
    }

    async expectRequestAbsent(message: string): Promise<void> {
        await this.openCustomerPage();

        await expect(
            this.page.locator("#main .row").filter({ hasText: message }),
        ).toHaveCount(0);
    }
}
