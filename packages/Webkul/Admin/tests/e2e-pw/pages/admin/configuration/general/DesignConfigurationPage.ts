import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class DesignConfigurationPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get saveButton() {
        return this.page.locator(
            'button[type="submit"].primary-button:visible',
        );
    }

    private get successNotification() {
        return this.page.getByText("Configuration saved successfully");
    }

    private logoInput() {
        return this.page.locator(
            'input[type="file"][name="general[design][admin_logo][logo_image]"]',
        );
    }

    private faviconInput() {
        return this.page.locator(
            'input[type="file"][name="general[design][admin_logo][favicon]"]',
        );
    }

    private deleteLogoButton() {
        return this.page
            .locator(
                '[id="general\\[design\\]\\[admin_logo\\]\\[logo_image\\]\\[delete\\]"]',
            )
            .nth(1);
    }

    private deleteFaviconButton() {
        return this.page
            .locator(
                '[id="general\\[design\\]\\[admin_logo\\]\\[favicon\\]\\[delete\\]"]',
            )
            .nth(1);
    }

    private categoryViewSelect() {
        return this.page.locator(
            '[name="general[design][categories][category_view]"]',
        );
    }

    private previewSidebarButton() {
        return this.page.getByRole("button", {
            name: " Preview Sidebar Menu ",
        });
    }

    private previewDefaultButton() {
        return this.page.getByRole("button", { name: "Preview Default Menu" });
    }

    private previewModal() {
        return this.page.locator(
            ".flex.items-center.justify-between.gap-2\\.5",
        );
    }

    private closePreviewButton() {
        return this.page.locator(".icon-cancel-1");
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/general/design");
    }

    async uploadLogo(filePath: string): Promise<void> {
        const input = this.logoInput();
        await expect(input).toBeVisible();
        await input.setInputFiles(filePath);
    }

    async deleteLogo(): Promise<void> {
        await this.deleteLogoButton().click();
    }

    async uploadFavicon(filePath: string): Promise<void> {
        const input = this.faviconInput();
        await expect(input).toBeVisible();
        await input.setInputFiles(filePath);
    }

    async deleteFavicon(): Promise<void> {
        await this.deleteFaviconButton().click();
    }

    async selectCategoryView(mode: "sidebar" | "default"): Promise<void> {
        await this.categoryViewSelect().selectOption(mode);
    }

    async previewSidebarMenu(): Promise<void> {
        await this.previewSidebarButton().click();
        await expect(this.previewModal()).toBeVisible();
        await this.closePreviewButton().click();
    }

    async previewDefaultMenu(): Promise<void> {
        await this.previewDefaultButton().click();
        await expect(this.previewModal()).toBeVisible();
        await this.closePreviewButton().click();
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
