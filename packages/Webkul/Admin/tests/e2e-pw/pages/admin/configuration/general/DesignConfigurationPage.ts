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

    private mediaInput(field: string) {
        return this.page.locator(
            `input[type="file"][name="general[design][admin_logo][${field}]"]`,
        );
    }

    private mediaTile(field: string) {
        return this.mediaInput(field).locator("xpath=../div[1]");
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
        return this.page.locator("span.icon-close").first();
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/general/design");
    }

    async uploadLogo(filePath: string): Promise<void> {
        await this.uploadMedia("logo_image", filePath);
    }

    async deleteLogo(): Promise<void> {
        await this.deleteMedia("logo_image");
    }

    async uploadFavicon(filePath: string): Promise<void> {
        await this.uploadMedia("favicon", filePath);
    }

    async deleteFavicon(): Promise<void> {
        await this.deleteMedia("favicon");
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

    private async uploadMedia(field: string, filePath: string): Promise<void> {
        const input = this.mediaInput(field);
        await expect(input).toBeAttached();
        await input.setInputFiles(filePath);
        await expect(this.mediaTile(field).locator("img")).toBeVisible();
    }

    private async deleteMedia(field: string): Promise<void> {
        const tile = this.mediaTile(field);
        await tile.hover();
        await tile.locator(".icon-delete").click();
        await expect(tile).toBeHidden();
    }
}
