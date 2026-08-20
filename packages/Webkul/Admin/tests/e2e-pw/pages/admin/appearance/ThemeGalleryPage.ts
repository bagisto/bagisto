import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class ThemeGalleryPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    /**
     * A heading sits beside its count in a row of its own, and that row sits alongside
     * the grid of cards, so the group is two steps up from the heading itself.
     */
    private group(heading: string) {
        return this.page
            .getByText(heading, { exact: true })
            .locator("../..");
    }

    async open(): Promise<void> {
        await this.visit("admin/appearance/themes");
        await this.page.waitForLoadState("networkidle");
    }

    /**
     * The gallery has to say which themes are already installed and which are only on
     * offer, so a theme that is ready to edit is not mistaken for one still to be bought.
     */
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
