import { Page } from "@playwright/test";

export class GroupActionPage {
    constructor(private page: Page) {}

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get name() {
        return this.page.locator('input[name="name"]');
    }

    get fillCode() {
        return this.page.locator('input[name="code"]');
    }

    get successGroupMSG() {
        return this.page.getByText("Group created successfully");
    }

    get successUpdateMSG() {
        return this.page.getByText("Group Updated Successfully");
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

    get successGroupDeleteMSG() {
        return this.page.getByText(/Group deleted successfully/i);
    }
}
