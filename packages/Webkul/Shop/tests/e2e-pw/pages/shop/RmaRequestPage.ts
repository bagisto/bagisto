import { expect, Page, Response } from "@playwright/test";
import { BasePage } from "../BasePage";

export class RmaRequestPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get gridRows() {
        return this.page.locator(
            "div.row:not(.datagrid-head):not(:has(.shimmer))",
        );
    }

    private requestRow(orderId: string) {
        return this.gridRows.filter({ hasText: `#${orderId}` });
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get messageInput() {
        return this.page.locator('textarea[name="message"]');
    }

    private get sendMessageButton() {
        return this.page.getByRole("button", { name: "Send Message" });
    }

    private get itemsRequestedHeading() {
        return this.page.getByText("Item Requested for RMA");
    }

    private get conversationsHeading() {
        return this.page.getByText("Conversations");
    }

    private async openList(): Promise<void> {
        await this.visit("customer/account/rma");
        await this.waitForVueMount();

        await expect(this.gridRows.first()).toBeVisible();
    }

    private async openDetail(orderId: string): Promise<Response> {
        await this.openList();

        const [response] = await Promise.all([
            this.page.waitForResponse(
                (candidate) =>
                    /customer\/account\/rma\/view\/\d+/.test(candidate.url()) &&
                    candidate.request().resourceType() === "document",
            ),
            this.requestRow(orderId).locator("span.icon-eye").click(),
        ]);

        return response;
    }

    async expectRequestListed(orderId: string, status: string): Promise<void> {
        await this.openList();

        await expect(this.requestRow(orderId)).toHaveCount(1);
        await expect(this.requestRow(orderId)).toContainText(status);
    }

    async expectDetailPageServesTheRequest(
        orderId: string,
        productName: string,
        status: string,
    ): Promise<void> {
        const response = await this.openDetail(orderId);

        expect(
            response.status(),
            "the rma detail page answered a server error",
        ).toBe(200);

        await expect(this.page).toHaveURL(
            /customer\/account\/rma\/view\/\d+/,
        );
        await expect(this.itemsRequestedHeading).toBeVisible();
        await expect(this.page.getByText(productName).first()).toBeVisible();
        await expect(this.page.getByText(status).first()).toBeVisible();
    }

    async sendMessage(orderId: string, message: string): Promise<void> {
        await this.openDetail(orderId);
        await this.waitForVueMount();

        await expect(this.conversationsHeading).toBeVisible();

        await this.messageInput.fill(message);

        await Promise.all([
            this.page.waitForResponse(
                (candidate) =>
                    candidate.url().includes("rma/send-message") &&
                    candidate.request().method() === "POST",
            ),
            this.sendMessageButton.click(),
        ]);
    }

    async expectMessageInConversation(message: string): Promise<void> {
        await expect(this.page.getByText(message)).toBeVisible();
    }

    async cancelRequest(orderId: string): Promise<void> {
        await this.openList();
        await this.requestRow(orderId).locator("span.icon-cancel").click();

        if (await this.agreeButton.count()) {
            await this.agreeButton.click();
        }

        await expect(
            this.page.getByText("RMA status canceled successfully.").first(),
        ).toBeVisible();
    }

    async expectCancelNoLongerOffered(orderId: string): Promise<void> {
        await this.openList();

        await expect(
            this.requestRow(orderId).locator("span.icon-cancel"),
        ).toHaveCount(0);
    }
}
