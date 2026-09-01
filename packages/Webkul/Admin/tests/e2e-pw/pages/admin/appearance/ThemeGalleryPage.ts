import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class ThemeGalleryPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private group(heading: string) {
        return this.page
            .getByText(heading, { exact: true })
            .locator("../..");
    }

    async open(): Promise<void> {
        await this.visit("admin/appearance/themes");
        await this.page.waitForLoadState("networkidle");
    }

    async expectThemesGroupedByInstallState(): Promise<void> {
        await this.open();

        const installed = this.group("My Themes");

        const available = this.group("Buy Themes");

        await expect(installed).toBeVisible();

        await expect(available).toBeVisible();

        await expect(
            installed.getByRole("link", { name: "Customize" }).first(),
        ).toBeVisible();

        await expect(
            installed.getByRole("link", { name: "View & Buy" }),
        ).toHaveCount(0);

        await expect(
            available.getByRole("link", { name: "View & Buy" }).first(),
        ).toBeVisible();

        await expect(
            available.getByRole("link", { name: "Customize" }),
        ).toHaveCount(0);
    }
}
