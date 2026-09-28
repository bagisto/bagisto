import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class TwoFactorPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get codeInput() {
        return this.page.getByPlaceholder("Code");
    }

    async openVerificationPage(): Promise<void> {
        await this.visit("admin/two-factor/verify");
    }

    async expectSentToDashboard(): Promise<void> {
        await expect(this.page).toHaveURL(/admin\/dashboard/);

        await expect(this.codeInput).toHaveCount(0);
    }

    async expectSentToLogin(): Promise<void> {
        await expect(this.page).toHaveURL(/admin\/login/);

        await expect(this.codeInput).toHaveCount(0);
    }
}
