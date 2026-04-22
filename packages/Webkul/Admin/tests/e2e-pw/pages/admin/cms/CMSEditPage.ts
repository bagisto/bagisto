import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export interface CMSEditData {
    name: string;
    slug: string;
    shortDescription: string;
}

export class CMSEditPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get editIcons() {
        return this.page.locator("span.cursor-pointer.icon-edit");
    }

    private get pageTitleInput() {
        return this.page.getByRole("textbox", { name: "Page Title" });
    }

    private get metaTitleInput() {
        return this.page.locator("#meta_title");
    }

    private get urlKeyInput() {
        return this.page.locator("#url_key");
    }

    private get metaKeywordsInput() {
        return this.page.locator("#meta_keywords");
    }

    private get metaDescriptionInput() {
        return this.page.locator("#meta_description");
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Page" });
    }

    async visit() {
        await super.visit("admin/cms");
        await expect(this.editIcons.first()).toBeVisible();
    }

    async openFirstPageForEdit() {
        await this.visit();
        await this.editIcons.first().click();
        await expect(this.pageTitleInput).toBeVisible();
    }

    async fillGeneralDetails(data: CMSEditData) {
        await this.pageTitleInput.fill("");
        await this.pageTitleInput.fill(data.name);
    }

    async fillDescription(description: string) {
        const editorFrame = this.page.frameLocator("iframe.tox-edit-area__iframe");
        await editorFrame.locator("body").fill(description);
    }

    async fillSEO(data: CMSEditData) {
        await this.metaTitleInput.fill("");
        await this.metaTitleInput.fill(data.name);
        await this.urlKeyInput.fill("");
        await this.urlKeyInput.fill(data.slug);
        await this.metaKeywordsInput.fill("");
        await this.metaKeywordsInput.fill(data.name);
        await this.metaDescriptionInput.fill("");
        await this.metaDescriptionInput.fill(data.shortDescription);
    }

    async savePage() {
        await this.saveButton.click();
    }

    async verifyPageUpdated(data: CMSEditData) {
        await expect(
            this.page.locator("#app p", { hasText: "CMS updated successfully." })
        ).toBeVisible();
        await expect(this.page.getByText(data.name)).toBeVisible();
        await expect(this.page.getByText(data.slug)).toBeVisible();
    }

    async editPage(data: CMSEditData) {
        await this.openFirstPageForEdit();
        await this.fillGeneralDetails(data);
        await this.fillDescription(data.shortDescription);
        await this.fillSEO(data);
        await this.savePage();
        await this.verifyPageUpdated(data);
    }
}
