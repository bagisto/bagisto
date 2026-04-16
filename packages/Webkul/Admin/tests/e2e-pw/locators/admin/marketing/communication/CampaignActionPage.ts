import { Page } from "@playwright/test";

export class CampaignActionPage {
    constructor(private page: Page) {}

    get name() {
        return this.page.locator('input[name="name"]');
    }

    get subject() {
        return this.page.locator('input[name="subject"]');
    }

    get event() {
        return this.page.locator("select[name='marketing_event_id']");
    }

    get emailTemplate() {
        return this.page.locator("select[name='marketing_template_id']");
    }

    get selectChannel() {
        return this.page.locator("select[name='channel_id']");
    }

    get customerGroup() {
        return this.page.locator("select[name='customer_group_id']");
    }

    get campaignStatus() {
        return this.page.locator(".peer.h-5");
    }

    get campaignCreateSuccess() {
        return this.page.getByText("Campaign created successfully.");
    }

    get campaignDeleteSuccess() {
        return this.page.getByText("Campaign deleted successfully");
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get campaignUpdateSuccess() {
        return this.page.getByText("Campaign updated successfully.");
    }

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }
}
