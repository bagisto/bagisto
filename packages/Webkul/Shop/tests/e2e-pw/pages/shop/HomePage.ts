import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class HomePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async gotoHome(): Promise<void> {
        await this.visit("");
    }

    async subscribeToNewsletter(email: string): Promise<void> {
        await this.gotoHome();
        await this.page.getByRole("textbox", { name: "Email" }).fill(email);
        await this.page.getByRole("button", { name: "Subscribe" }).click();
    }

    async expectSubscriptionMessage(expectedMessage: string): Promise<void> {
        await expect(
            this.page
                .getByRole("paragraph")
                .filter({ hasText: expectedMessage }),
        ).toBeVisible();
    }
}
