import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export interface CMSCreateData {
    name: string;
    slug: string;
    shortDescription: string;
}

export class CMSCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createButton() {
        return this.page.getByRole("link", { name: "Create Page" });
    }

    private get pageTitleInput() {
        return this.page.locator("input[name='page_title']");
    }

    private get channelsCheckbox() {
        return this.page.locator('label[for="channels_1"]');
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
        await expect(this.createButton).toBeVisible();
    }

    async openCreateForm() {
        await this.visit();
        await this.createButton.click();
        await this.page.waitForLoadState("networkidle");
    }

    async fillGeneralDetails(data: CMSCreateData) {
        await this.pageTitleInput.fill(data.name);
        await this.channelsCheckbox.first().click();
        await expect(this.page.locator("input#channels_1").first()).toBeChecked();
    }

    async fillDescription(description: string) {
        await (this.page as any).fillInTinymce("#content_ifr", description);
    }

    async fillSEO(data: CMSCreateData) {
        await this.metaTitleInput.fill(data.name);
        await this.urlKeyInput.fill(data.slug);
        await this.metaKeywordsInput.fill(data.name);
        await this.metaDescriptionInput.fill(data.shortDescription);
    }

    async savePage() {
        await this.saveButton.click();
    }

    async verifyPageCreated(data: CMSCreateData) {
        await expect(
            this.page.locator("#app p", { hasText: "CMS created successfully." })
        ).toBeVisible();
        await expect(this.page.getByText(data.name)).toBeVisible();
        await expect(this.page.getByText(data.slug)).toBeVisible();
    }

    async createPage(data: CMSCreateData) {
        await this.openCreateForm();
        await this.fillDescription(data.shortDescription);
        await this.fillGeneralDetails(data);
        await this.fillSEO(data);
        await this.savePage();
        await this.verifyPageCreated(data);
    }
}
