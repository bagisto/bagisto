import { Page } from "@playwright/test";

export class TaxRateActionPage {
    constructor(private page: Page) {}

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get identifier() {
        return this.page.locator('input[name="identifier"]');
    }

    get selectCountry() {
        return this.page.locator('select[name="country"]');
    }

    get taxRate() {
        return this.page.locator('input[name="tax_rate"]');
    }

    get successCreateTaxRate() {
        return this.page.getByText("Tax Rate created successfully.");
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get successUpdateTaxRate() {
        return this.page.getByText("Tax Rate Update Successfully");
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    get successDeleteTaxRate() {
        return this.page.getByText("Tax Rate delete successfully");
    }
}
