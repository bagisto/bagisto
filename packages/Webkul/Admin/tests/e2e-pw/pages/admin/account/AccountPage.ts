import { expect, type Page } from "@playwright/test";
import type { MediaFile } from "@shared/media-files";
import { BasePage } from "../../BasePage";
import { MediaGalleryField } from "../components/MediaGalleryField";

export class AccountPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get currentPasswordInput() {
        return this.page.locator('input[name="current_password"]');
    }

    private get form() {
        return this.currentPasswordInput.locator("xpath=ancestor::form[1]");
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Account" });
    }

    async open(): Promise<void> {
        await this.visit("admin/account");
        await this.waitForVueMount();

        await expect(this.saveButton).toBeVisible();
    }

    avatarField(): MediaGalleryField {
        return new MediaGalleryField(this.form);
    }

    async attemptSaveAvatar(
        avatar: MediaFile,
        password: string,
    ): Promise<void> {
        await this.open();
        await this.avatarField().attach(avatar);
        await this.currentPasswordInput.fill(password);
        await this.saveButton.click();
    }

    async saveAvatar(avatar: MediaFile, password: string): Promise<void> {
        await this.attemptSaveAvatar(avatar, password);

        await expect(
            this.page.getByText("Account updated successfully"),
        ).toBeVisible();
    }
}
