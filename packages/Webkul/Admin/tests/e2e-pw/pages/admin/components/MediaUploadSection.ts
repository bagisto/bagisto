import { expect, type Page } from "@playwright/test";

export class MediaUploadSection {
    constructor(
        private readonly page: Page,
        private readonly field: string,
    ) {}

    private get fileInput() {
        return this.page.locator(`input[type="file"][name="${this.field}"]`);
    }

    private get tile() {
        return this.fileInput.locator("xpath=../div[1]");
    }

    private get addButton() {
        return this.fileInput.locator("xpath=../label");
    }

    async upload(filePath: string): Promise<void> {
        await expect(this.fileInput).toBeAttached();

        await this.fileInput.setInputFiles(filePath);

        await expect(this.tile.locator("img")).toBeVisible();
    }

    async remove(): Promise<void> {
        await this.tile.scrollIntoViewIfNeeded();
        await this.tile.hover();
        await this.tile.locator(".icon-delete").click();

        await expect(this.tile).toBeHidden();
    }

    async expectMediaStored(): Promise<void> {
        await expect(this.tile.locator("img")).toBeVisible();
    }

    async expectMediaAbsent(): Promise<void> {
        await expect(this.tile).toBeHidden();
        await expect(this.addButton).toBeVisible();
    }
}
