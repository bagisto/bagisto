import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";

export type RedirectType = "301" | "302";

export interface UrlRewriteData {
    requestPath: string;
    targetPath: string;
    redirectType: RedirectType;
}

export const REDIRECT_LABELS: Record<RedirectType, string> = {
    "301": "Permanent (301)",
    "302": "Temporary (302)",
};

export class UrlRewritesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/marketing/search-seo/url-rewrites";
    }

    private get createButton() {
        return this.primaryTrigger("Create URL Rewrite");
    }

    private get entityTypeSelect() {
        return this.page.locator('select[name="entity_type"]');
    }

    private get requestPathInput() {
        return this.page.locator('input[name="request_path"]');
    }

    private get targetPathInput() {
        return this.page.locator('input[name="target_path"]');
    }

    private get redirectTypeSelect() {
        return this.page.locator('select[name="redirect_type"]');
    }

    private get localeSelect() {
        return this.page.locator('select[name="locale"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save URL Rewrite" });
    }

    private async openCreateModal(): Promise<void> {
        await this.openGrid();
        await this.createButton.click();

        await expect(this.requestPathInput).toBeVisible();
    }

    private async openEditModal(requestPath: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(requestPath);
        await this.editIcon(requestPath).click();

        await expect(this.requestPathInput).toHaveValue(requestPath);
    }

    async createRewrite(data: UrlRewriteData): Promise<void> {
        await this.openCreateModal();
        await this.entityTypeSelect.selectOption("cms_page");
        await this.requestPathInput.fill(data.requestPath);
        await this.targetPathInput.fill(data.targetPath);
        await this.redirectTypeSelect.selectOption(data.redirectType);
        await this.localeSelect.selectOption("en");
        await this.saveButton.click();

        await expect(
            this.flashMessage("URL Rewrite created successfully"),
        ).toBeVisible();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateModal();
        await this.saveButton.click();
    }

    async updateRewrite(
        requestPath: string,
        changes: Partial<UrlRewriteData>,
    ): Promise<void> {
        await this.openEditModal(requestPath);

        if (changes.requestPath !== undefined) {
            await this.requestPathInput.fill(changes.requestPath);
        }

        if (changes.targetPath !== undefined) {
            await this.targetPathInput.fill(changes.targetPath);
        }

        if (changes.redirectType !== undefined) {
            await this.redirectTypeSelect.selectOption(changes.redirectType);
        }

        await this.saveButton.click();

        await expect(
            this.flashMessage("URL Rewrite updated successfully"),
        ).toBeVisible();
    }

    async deleteRewrite(requestPath: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(requestPath);
        await this.deleteRow(requestPath, "URL Rewrite deleted successfully");
    }

    async deleteRewritesIfPresent(requestPaths: string[]): Promise<void> {
        await this.deleteRowsIfPresent(
            requestPaths,
            "URL Rewrite deleted successfully",
        );
    }

    async massDeleteRewrites(requestPaths: string[]): Promise<void> {
        await this.openGrid();
        await this.selectRows(requestPaths);
        await this.applyMassAction("Delete");

        await expect(
            this.flashMessage("Selected URL Rewrites Deleted Successfully"),
        ).toBeVisible();
    }

    async expectRewriteListed(data: UrlRewriteData): Promise<void> {
        await this.expectSearchedRowCount(data.requestPath, 1);

        await expect(this.row(data.requestPath)).toContainText(data.targetPath);
        await expect(
            this.row(data.requestPath).locator("p", {
                hasText: new RegExp(`^\\s*${data.redirectType}\\s*$`),
            }),
        ).toHaveCount(1);
    }

    async expectRewriteAbsent(requestPath: string): Promise<void> {
        await this.expectSearchedRowCount(requestPath, 0);
    }

    async expectStorefrontRedirect(
        requestPath: string,
        targetPath: string,
        redirectType: RedirectType,
    ): Promise<void> {
        const response = await this.page.request.get(`page/${requestPath}`, {
            maxRedirects: 0,
        });

        expect(response.status()).toBe(Number(redirectType));
        expect(response.headers()["location"]).toContain(targetPath);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }
}
