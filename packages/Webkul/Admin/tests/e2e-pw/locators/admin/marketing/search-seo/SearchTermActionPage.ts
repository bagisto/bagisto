import { Page } from "@playwright/test";

export class SearchTermActionPage {
    constructor(private page: Page) {}

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get entityType() {
        return this.page.locator('select[name="entity_type"]');
    }

    get requestPath() {
        return this.page.getByRole("textbox", { name: "Request Path" });
    }

    get targetPath() {
        return this.page.getByRole("textbox", { name: "Target Path" });
    }

    get redirectPath() {
        return this.page.locator('select[name="redirect_type"]');
    }
    get redirectURL() {
        return this.page.getByRole("textbox", { name: "Redirect Url" });
    }

    get locale() {
        return this.page.locator('select[name="locale"]');
    }

    get saveRedirectSuccess() {
        return this.page.getByText("URL Rewrite created successfully");
    }

    get saveRedirectUpdatedSuccess() {
        return this.page.getByText("URL Rewrite updated successfully");
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

    get deleteRedirectSuccess() {
        return this.page.getByText("URL Rewrite deleted");
    }

    get searchQuery() {
        return this.page.getByRole("textbox", { name: "Search Query" });
    }

    get selectChannel() {
        return this.page.locator("select[name='channel_id']");
    }

    get searchTermCreateSuccess() {
        return this.page.getByText("Search Term created successfully");
    }

    get searchTermDeleteSuccess() {
        return this.page.getByText("Search Term deleted successfully");
    }
    get searchTermUpdateSuccess() {
        return this.page.getByText("Search Term updated successfully");
    }
}
