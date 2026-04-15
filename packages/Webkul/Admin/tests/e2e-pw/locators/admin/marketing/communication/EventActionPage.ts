import { Page } from "@playwright/test";

export class EventActionPage {
    constructor(private page: Page) {}

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get date() {
        return this.page.locator('input[name="date"]');
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get event() {
        return this.page.locator("select[name='marketing_event_id']");
    }

    get eventCreateSuccess() {
        return this.page.getByText("Events Created Successfully");
    }

    get eventDeleteSuccess() {
        return this.page.getByText("Events Deleted Successfully");
    }

    get eventUpdateSuccess() {
        return this.page.getByText("Events Updated Successfully");
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }
}
