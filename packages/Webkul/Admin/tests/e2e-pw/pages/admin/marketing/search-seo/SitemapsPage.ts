import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";

export interface SitemapData {
    fileName: string;
    path: string;
    channel: string;
    channelCode: string;
}

export class SitemapsPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/marketing/search-seo/sitemaps";
    }

    private get createButton() {
        return this.primaryTrigger("Create Sitemap");
    }

    private get fileNameInput() {
        return this.page.locator('input[name="file_name"]');
    }

    private get pathInput() {
        return this.page.locator('input[name="path"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Sitemap" });
    }

    private get closeModalButton() {
        return this.page.locator(
            'div.box-shadow:has(input[name="file_name"]) span.icon-close',
        );
    }

    private channelOption(channel: string) {
        return this.page
            .locator('label[for^="channels_"]')
            .filter({ hasText: new RegExp(`^\\s*${channel}\\s*$`, "i") });
    }

    private sitemapLink(fileName: string) {
        return this.row(fileName).locator('a[target="_blank"]');
    }

    private async openCreateModal(): Promise<void> {
        await this.openGrid();
        await this.createButton.click();

        await expect(this.fileNameInput).toBeVisible();
        await expect(this.fileNameInput).toHaveValue("");
    }

    private async openEditModal(fileName: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(fileName);
        await this.editIcon(fileName).click();

        await expect(this.fileNameInput).toHaveValue(fileName);
    }

    private async selectChannel(channel: string): Promise<void> {
        const option = this.channelOption(channel);
        const inputId = await option.getAttribute("for");

        await option.click();

        await expect(this.page.locator(`input#${inputId}`)).toBeChecked();
    }

    private async unselectChannel(channel: string): Promise<void> {
        const option = this.channelOption(channel);
        const inputId = await option.getAttribute("for");

        await option.click();

        await expect(this.page.locator(`input#${inputId}`)).not.toBeChecked();
    }

    async createSitemap(data: SitemapData): Promise<void> {
        await this.openCreateModal();
        await this.fileNameInput.fill(data.fileName);
        await this.pathInput.fill(data.path);
        await this.selectChannel(data.channel);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Sitemap created successfully"),
        ).toBeVisible();
    }

    async attemptCreateSitemapWithoutChannel(
        data: Omit<SitemapData, "channel">,
    ): Promise<void> {
        await this.openCreateModal();
        await this.fileNameInput.fill(data.fileName);
        await this.pathInput.fill(data.path);
        await this.saveButton.click();
    }

    async updateSitemap(
        fileName: string,
        changes: { fileName: string; path: string },
    ): Promise<void> {
        await this.openEditModal(fileName);
        await this.fileNameInput.fill(changes.fileName);
        await this.pathInput.fill(changes.path);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Sitemap Updated successfully"),
        ).toBeVisible();
    }

    async attemptUpdateWithoutChannel(
        fileName: string,
        channel: string,
    ): Promise<void> {
        await this.openEditModal(fileName);
        await this.unselectChannel(channel);
        await this.saveButton.click();
    }

    async closeEditModal(): Promise<void> {
        await this.closeModalButton.click();

        await expect(this.fileNameInput).toBeHidden();
    }

    async deleteSitemap(fileName: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(fileName);
        await this.deleteRow(fileName, "Sitemap Deleted successfully");
    }

    async deleteSitemapsIfPresent(fileNames: string[]): Promise<void> {
        await this.deleteRowsIfPresent(fileNames, "Sitemap Deleted successfully");
    }

    async expectSitemapListed(data: SitemapData): Promise<void> {
        await this.expectSearchedRowCount(data.fileName, 1);

        await expect(this.row(data.fileName)).toContainText(data.path);
        await expect(this.row(data.fileName)).toContainText(data.channelCode);
    }

    async expectSitemapAbsent(fileName: string): Promise<void> {
        await this.expectSearchedRowCount(fileName, 0);
    }

    async expectChannelPreselectedInEditForm(
        fileName: string,
        channel: string,
    ): Promise<void> {
        await this.openEditModal(fileName);

        const inputId = await this.channelOption(channel).getAttribute("for");

        await expect(this.page.locator(`input#${inputId}`)).toBeChecked();
    }

    async expectGeneratedSitemapOpens(fileName: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(fileName);

        const [sitemapPage] = await Promise.all([
            this.page.context().waitForEvent("page"),
            this.sitemapLink(fileName).click(),
        ]);

        await sitemapPage.waitForLoadState();

        const stem = fileName.replace(/\.xml$/, "");

        await expect(sitemapPage).toHaveURL(
            new RegExp(`storage/sitemaps/.*${stem}.*\\.xml$`),
        );

        const content = await sitemapPage.locator("body").textContent();

        expect(content).toMatch(/<urlset|<sitemapindex/);

        await sitemapPage.close();
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectNoSaveMessage(): Promise<void> {
        await expect(this.flashMessage("Sitemap created successfully")).toHaveCount(0);
        await expect(this.flashMessage("Sitemap Updated successfully")).toHaveCount(0);
    }
}
