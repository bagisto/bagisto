import { Page } from "playwright/test";
export class ChannelActionPage {
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

    get promotionRuleDescription() {
        return this.page.locator("#description");
    }

    get inventoryToggle() {
        return this.page.locator('label[for="inventory_sources_1"]');
    }

    get categoryID() {
        return this.page.locator("#root_category_id");
    }

    get hostname() {
        return this.page.locator("#hostname");
    }

    get selectLocale() {
        return this.page.locator('label[for="locales_1"]');
    }

    get selectCurrency() {
        return this.page.locator('label[for="currencies_1"]');
    }

    get baseCurrencyID() {
        return this.page.locator("#base_currency_id");
    }

    get defaultLocaleID() {
        return this.page.locator("#default_locale_id");
    }

    get metaTitleChannel() {
        return this.page.locator("#meta_title");
    }

    get seoKeywords() {
        return this.page.locator("#seo_keywords");
    }

    get metaDescription() {
        return this.page.locator("#meta_description");
    }

    get channleCreateSuccess() {
        return this.page.getByText("Channel created successfully.");
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get channlUpdateSuccess() {
        return this.page.getByText("Update Channel Successfully");
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    get channelDeleteSuccess() {
        return this.page.getByText("Channel deleted successfully.");
    }
}
