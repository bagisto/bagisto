import { Page } from "@playwright/test";

export class CustomerActionPage {
    constructor(private page: Page) {}

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get editBtn() {
        return this.page.locator("span.cursor-pointer.icon-sort-right").nth(1);
    }

    get customercreatedsuccess() {
        return this.page.getByText("Customer created successfully");
    }

    get customerDeleteSuccess() {
        return this.page.getByText("Selected data successfully deleted");
    }

    get customeremail() {
        return this.page.locator('input[name="email"]');
    }

    get customerfirstname() {
        return this.page.locator('input[name="first_name"]');
    }

    get customergender() {
        return this.page.locator('select[name="gender"]');
    }

    get customerGroup() {
        return this.page.locator("select[name='customer_group_id']");
    }

    get customerGroupSelect() {
        return this.page.locator('label[for="customer_group__2"]');
    }

    get customerlastname() {
        return this.page.locator('input[name="last_name"]');
    }

    get customerNumber() {
        return this.page.locator('input[name="phone"]');
    }

    get viewIcon() {
        return this.page.locator("a.icon-sort-right.cursor-pointer");
    }

    get selectRowBtn() {
        return this.page.locator(".icon-uncheckbox");
    }

    get selectAction() {
        return this.page.getByRole("button", { name: "Select Action" });
    }

    get deleteBtn() {
        return this.page.getByRole("link", { name: "Delete" });
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }
}
