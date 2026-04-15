import { Locator, Page } from "@playwright/test";

export class RoleActionPage {
    constructor(private page: Page) {}

    get createRole() {
        return this.page.locator("a.primary-button:visible");
    }

    get name() {
        return this.page.locator('input[name="name"]');
    }

    get selectRoleType() {
        return this.page.locator('select[name="permission_type"]');
    }

    get roleDescription() {
        return this.page.locator('textarea[name="description"]');
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get successEditRole() {
        return this.page.getByText("Roles is updated successfully");
    }

    get saveRole() {
        return this.page.locator('button.primary-button:has-text("Save Role")');
    }

    get successRole() {
        return this.page.getByText("Roles Created Successfully");
    }

    get successUpdateRole() {
        return this.page.getByText("Roles is updated successfully");
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    get successDeleteRole() {
        return this.page.getByText("Roles is deleted successfully");
    }
}
