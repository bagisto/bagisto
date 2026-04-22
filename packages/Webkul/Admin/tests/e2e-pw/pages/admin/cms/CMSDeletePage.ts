import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class CMSDeletePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get deleteIcons() {
        return this.page.locator("span.cursor-pointer.icon-delete");
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree" });
    }

    private get massDeleteCheckbox() {
        return this.page.locator(".icon-uncheckbox").first();
    }

    private get selectActionButton() {
        return this.page.getByRole("button", { name: "Select Action " });
    }

    private get deleteActionLink() {
        return this.page.getByRole("link", { name: "Delete" });
    }

    async visit() {
        await super.visit("admin/cms");
    }

    async deleteFirstPage() {
        await this.visit();
        await this.page.waitForLoadState("networkidle");
        await expect(this.deleteIcons.first()).toBeVisible();
        await this.deleteIcons.first().click();
        await this.page.waitForSelector("text=Are you sure");
        if (await this.agreeButton.first().isVisible()) {
            await this.page.waitForTimeout(1000);
            await this.agreeButton.nth(1).click();
        }
        await expect(
            this.page.getByText("CMS deleted successfully.").first(),
        ).toBeVisible();
    }

    async massDeletePages() {
        await this.visit();
        await this.massDeleteCheckbox.click();
        await this.selectActionButton.click();
        await this.deleteActionLink.click();
        await this.agreeButton.nth(1).click();
        await expect(
            this.page.getByText("Selected Data Deleted"),
        ).toBeVisible();
    }
}
