import { Page } from "@playwright/test";

export class TaxCategoryActionPage {
    constructor(private page: Page) {}

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get fillCode() {
        return this.page.locator('input[name="code"]');
    }

    get name() {
        return this.page.locator('input[name="name"]');
    }

    get description() {
        return this.page.getByRole("textbox", { name: "Description" });
    }

    get selectTaxRate() {
        return this.page.locator('select[name="taxrates[]"]');
    }

    get successCreateTaxCategory() {
        return this.page.getByText("Tax category created successfully.");
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get successUpdateTaxCategory() {
        return this.page.getByText("Tax category updated successfully.");
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    get successDeleteTaxCategory() {
        return this.page.getByText("Tax category deleted successfully.");
    }
}
