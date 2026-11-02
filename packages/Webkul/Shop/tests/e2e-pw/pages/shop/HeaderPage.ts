import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class HeaderPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get localeToggle() {
        return this.page.getByRole("button", { name: "Default locale" });
    }

    private categoryLink(name: string) {
        return this.page
            .locator("header")
            .getByRole("link", { name, exact: true });
    }

    private localeOption(name: string) {
        return this.page.getByRole("button", { name, exact: true });
    }

    async open(path: string): Promise<void> {
        await this.visit(path);

        await expect(this.localeToggle).toBeVisible();
    }

    async switchLocale(name: string): Promise<void> {
        await this.localeToggle.click();
        await this.localeOption(name).click();

        await expect(this.localeToggle).toContainText(name);
    }

    async expectCategoryInMenu(name: string): Promise<void> {
        await expect(this.categoryLink(name)).toBeVisible();
    }

    async expectCategoryNotInMenu(name: string): Promise<void> {
        await expect(this.categoryLink(name)).toHaveCount(0);
    }
}
