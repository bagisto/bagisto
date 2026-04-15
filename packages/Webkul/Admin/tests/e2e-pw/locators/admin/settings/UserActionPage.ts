import { Locator, Page } from "@playwright/test";

export class UserActionPage {
    constructor(private page: Page) {}

    get createUser() {
        return this.page.getByRole("button", { name: "Create User" });
    }

    get name() {
        return this.page.locator('input[name="name"]');
    }

    get selectRole() {
        return this.page.locator('select[name="role_id"]');
    }

    get userEmail() {
        return this.page.locator('input[name="email"]');
    }

    get userPassword() {
        return this.page.locator('input[name="password"]');
    }

    get confirmPassword() {
        return this.page.locator('input[name="password_confirmation"]');
    }

    get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    get saveUser() {
        return this.page.getByRole("button", { name: "Save User" });
    }

    get successUser() {
        return this.page.getByText("User created successfully.");
    }

    get unauthorized() {
        return this.page.getByText("401").first();
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get successUserUpdate() {
        return this.page.getByText("User updated successfully.");
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    get successUserDelete() {
        return this.page.getByText("User deleted successfully.");
    }
}
