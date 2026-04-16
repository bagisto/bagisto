import { Page } from "@playwright/test";

export class SiteMapActionPage {
    constructor(private page: Page) {}

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    get fileName() {
        return this.page.locator('input[name="file_name"]');
    }

    get path() {
        return this.page.locator('input[name="path"]');
    }

    get sitemapCreateSuccess() {
        return this.page.getByText("Sitemap created successfully");
    }

    get sitemapDeleteSuccess() {
        return this.page.getByText("Sitemap deleted successfully");
    }

    get sitemapUpdateSuccess() {
        return this.page.getByText("Sitemap updated successfully");
    }
}
