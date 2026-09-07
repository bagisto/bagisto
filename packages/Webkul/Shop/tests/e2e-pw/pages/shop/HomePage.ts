import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class HomePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get newsletterEmailInput() {
        return this.page.getByRole("textbox", { name: "Email" });
    }

    private get subscribeButton() {
        return this.page.getByRole("button", { name: "Subscribe" });
    }

    async open(): Promise<void> {
        await this.visit("");

        await expect(this.subscribeButton).toBeVisible();
    }

    async subscribeToNewsletter(email: string): Promise<void> {
        await this.open();
        await this.newsletterEmailInput.fill(email);
        await this.waitForBackgroundRequestsToSettle();
        await this.subscribeButton.click();
    }

    async expectSubscriptionMessage(expectedMessage: string): Promise<void> {
        await expect(
            this.page.getByRole("paragraph").filter({ hasText: expectedMessage }),
        ).toBeVisible();
    }
}
