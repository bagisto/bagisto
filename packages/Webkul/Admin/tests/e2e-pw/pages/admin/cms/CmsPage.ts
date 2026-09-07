import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";
import type { AdminPage } from "../../../setup";

export interface CmsPageData {
    title: string;
    urlKey: string;
    content: string;
}

export class CmsPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/cms";
    }

    private get createLink() {
        return this.page.getByRole("link", { name: "Create Page" });
    }

    private get titleInput() {
        return this.page.locator('input[name="page_title"]');
    }

    private get urlKeyInput() {
        return this.page.locator('input[name="url_key"]');
    }

    private get localeTitleInput() {
        return this.page.locator('input[name="en[page_title]"]');
    }

    private get localeUrlKeyInput() {
        return this.page.locator('input[name="en[url_key]"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Page" });
    }

    private get defaultChannelOption() {
        return this.checkboxLabel("channels_1");
    }

    private get defaultChannelInput() {
        return this.page.locator("input#channels_1");
    }

    private async openCreateForm(): Promise<void> {
        await this.openGrid();
        await this.createLink.click();
        await this.openFormPage();

        await expect(this.titleInput).toBeVisible();
    }

    private async openEditForm(title: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(title);
        await this.editIcon(title).click();
        await this.openFormPage();

        await expect(this.localeTitleInput).toHaveValue(title);
    }

    private async fillCreateForm(data: CmsPageData): Promise<void> {
        await (this.page as AdminPage).fillInTinymce(
            "#content_ifr",
            data.content,
        );
        await this.titleInput.fill(data.title);
        await this.urlKeyInput.fill(data.urlKey);
        await this.defaultChannelOption.click();

        await expect(this.defaultChannelInput).toBeChecked();
    }

    async createPage(data: CmsPageData): Promise<void> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();

        await expect(
            this.flashMessage("CMS created successfully."),
        ).toBeVisible();
    }

    async attemptCreatePage(data: CmsPageData): Promise<void> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateForm();
        await this.saveButton.click();
    }

    async updatePage(
        title: string,
        changes: { title: string; urlKey: string },
    ): Promise<void> {
        await this.openEditForm(title);
        await this.localeTitleInput.fill(changes.title);
        await this.localeUrlKeyInput.fill(changes.urlKey);
        await this.saveButton.click();

        await expect(
            this.flashMessage("CMS updated successfully."),
        ).toBeVisible();
    }

    async deletePage(title: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(title);
        await this.deleteRow(title, "CMS deleted successfully.");
    }

    async deletePagesIfPresent(titles: string[]): Promise<void> {
        await this.deleteRowsIfPresent(titles, "CMS deleted successfully.");
    }

    async massDeletePages(titles: string[]): Promise<void> {
        await this.openGrid();
        await this.selectRows(titles);
        await this.applyMassAction("Delete");

        await expect(
            this.flashMessage("Selected Data Deleted Successfully"),
        ).toBeVisible();
    }

    async expectPageListed(title: string, urlKey: string): Promise<void> {
        await this.expectSearchedRowCount(title, 1);

        await expect(this.row(title)).toContainText(urlKey);
    }

    async expectPageAbsent(title: string): Promise<void> {
        await this.expectSearchedRowCount(title, 0);
    }

    async expectTitleInEditForm(title: string): Promise<void> {
        await this.openEditForm(title);
    }

    async expectPublishedOnStorefront(
        urlKey: string,
        content: string,
    ): Promise<void> {
        const response = await this.page.goto(`page/${urlKey}`);

        expect(response?.status()).toBe(200);

        await expect(this.page.getByText(content)).toBeVisible();
    }

    async expectNotOnStorefront(urlKey: string): Promise<void> {
        const response = await this.page.goto(`page/${urlKey}`);

        expect(response?.status()).toBe(404);
    }

    async expectCreateUnavailable(): Promise<void> {
        await this.openGrid();

        await expect(this.createLink).toHaveCount(0);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectStillOnCreateForm(): Promise<void> {
        await expect(this.page).toHaveURL(/cms\/create/);
    }
}
