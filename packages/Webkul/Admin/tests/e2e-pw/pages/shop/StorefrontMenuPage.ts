import { expect, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class StorefrontMenuPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get header() {
        return this.page.locator("header");
    }

    private get sidebarToggle() {
        return this.header.getByText("All", { exact: true });
    }

    private get sidebarCloseButton() {
        return this.page.getByRole("button", { name: "Close drawer" });
    }

    private get sidebarDrawer() {
        return this.page.locator("div.fixed").filter({ has: this.sidebarCloseButton });
    }

    async expectSidebarMenuLists(categoryName: string): Promise<void> {
        await this.visit("");

        await expect(this.sidebarToggle).toBeVisible();

        await this.sidebarToggle.click();

        await expect(this.sidebarCloseButton).toBeVisible();
        await expect(this.sidebarDrawer.getByText(categoryName, { exact: true })).toBeVisible();

        await this.sidebarCloseButton.click();

        await expect(this.sidebarCloseButton).toBeHidden();
    }

    async expectDefaultMenuLists(categoryName: string): Promise<void> {
        await this.visit("");

        await expect(this.sidebarToggle).toHaveCount(0);
        await expect(
            this.header.getByRole("link", { name: categoryName, exact: true }),
        ).toBeVisible();
    }
}
