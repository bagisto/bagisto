import { Page } from "@playwright/test";

export class CMSActionPage {
    constructor(private page: Page) {}

    get pagetitle() {
        return this.page.locator("#page_title");
    }

    get urlKey() {
        return this.page.locator("#url_key");
    }

    get metaTitle() {
        return this.page.locator("#meta_title");
    }

    get metaKeywords() {
        return this.page.locator("#meta_keywords");
    }

    get metaDescription() {
        return this.page.locator("#meta_description");
    }

    get channelBTN() {
        return this.page.locator('label[for^="channels_"]')
    }

    get createBtn() {
        return this.page.locator(".primary-button");
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

    get successPageCreate() {
        return this.page.getByText("CMS created successfully.");
    }

    get successPageUpdate() {
        return this.page.getByText("CMS updated successfully.");
    }

    get successPageDelete() {
        return this.page.getByText("CMS deleted successfully.");
    }
}
