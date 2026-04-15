import { Page } from "@playwright/test";

export class SearchSynonymsActionPage {
    constructor(private page: Page) {}

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get name() {
        return this.page.locator('input[name="name"]');
    }

    get terms() {
        return this.page.getByRole("textbox", { name: "Terms" });
    }

    get searchSynonymCreateSuccess() {
        return this.page.getByText("Search Synonym created successfully");
    }

    get searchSynonymUpdateSuccess() {
        return this.page.getByText("Search Synonym updated successfully");
    }

    get searchSynonymDeleteSuccess() {
        return this.page.getByText("Search Synonym deleted successfully");
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
}
