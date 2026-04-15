import { Page } from "@playwright/test";

export class FamilyActionPage {
    constructor(private page: Page) {}

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get editBtn() {
        return this.page.locator("span.cursor-pointer.icon-sort-right").nth(1);
    }

    get familyDeleteSuccess() {
        return this.page.getByText(/Family deleted successfully./);
    }

    get familyName() {
        return this.page.locator('input[name="name"]');
    }

    get familySuccess() {
        return this.page.getByText("Family created successfully.");
    }

    get familyUpdateSuccess() {
        return this.page.getByText("Family updated successfully.");
    }

    get fillCode() {
        return this.page.locator('input[name="code"]');
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
