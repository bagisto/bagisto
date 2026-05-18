import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import {
    generateName,
    generateSlug,
    generateDescription,
    generateHostname,
} from "../../../utils/faker";

export class ChannelsPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createChannelButton() {
        return this.page.locator('a.primary-button:has-text("Create Channel")');
    }

    private get codeInput() {
        return this.page.locator("#code");
    }

    private get nameInput() {
        return this.page.locator("#name");
    }

    private get descriptionInput() {
        return this.page.locator("#description");
    }

    private get inventorySourcesCheckbox() {
        return this.page.locator('label[for="inventory_sources_1"]');
    }

    private get inventorySourcesInput() {
        return this.page.locator("input#inventory_sources_1");
    }

    private get rootCategorySelect() {
        return this.page.locator("#root_category_id");
    }

    private get hostnameInput() {
        return this.page.locator("#hostname");
    }

    private get localesCheckbox() {
        return this.page.locator('label[for="locales_1"]');
    }

    private get localesInput() {
        return this.page.locator("input#locales_1");
    }

    private get defaultLocaleSelect() {
        return this.page.locator("#default_locale_id");
    }

    private get currenciesCheckbox() {
        return this.page.locator('label[for="currencies_1"]');
    }

    private get currenciesInput() {
        return this.page.locator("input#currencies_1");
    }

    private get baseCurrencySelect() {
        return this.page.locator("#base_currency_id");
    }

    private get metaTitleInput() {
        return this.page.locator("#meta_title");
    }

    private get seoKeywordsInput() {
        return this.page.locator("#seo_keywords");
    }

    private get metaDescriptionInput() {
        return this.page.locator("#meta_description");
    }

    private get saveChannelButton() {
        return this.page.locator(
            'button.primary-button:has-text("Save Channel")',
        );
    }

    private get editIcons() {
        return this.page.locator("span.cursor-pointer.icon-edit");
    }

    private get deleteIcons() {
        return this.page.locator("span.cursor-pointer.icon-delete");
    }

    private get agreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
    }

    async open(): Promise<void> {
        await this.visit("admin/settings/channels");
    }

    async createChannel(): Promise<void> {
        await this.open();
        await this.createChannelButton.waitFor({ state: "visible" });
        await this.createChannelButton.click();

        const code = generateSlug("_");
        const name = generateName();
        const description = generateDescription();

        await this.page.waitForSelector(
            'form[action*="/settings/channels/create"]',
        );
        await this.codeInput.fill(code);
        await this.nameInput.fill(name);
        await this.descriptionInput.fill(description);
        await this.inventorySourcesCheckbox.first().click();
        await expect(this.inventorySourcesInput).toBeChecked();
        await this.rootCategorySelect.selectOption("1");
        await this.hostnameInput.fill(generateHostname());
        await this.localesCheckbox.first().click();
        await expect(this.localesInput).toBeChecked();
        await this.defaultLocaleSelect.selectOption("1");
        await this.currenciesCheckbox.first().click();
        await expect(this.currenciesInput).toBeChecked();
        await this.baseCurrencySelect.selectOption("1");

        await this.metaTitleInput.fill(name);
        await this.seoKeywordsInput.fill(name);
        await this.metaDescriptionInput.fill(description);
        await this.saveChannelButton.click();

        await expect(
            this.page.getByText("Channel created successfully."),
        ).toBeVisible();
    }

    async editFirstChannel(): Promise<void> {
        await this.open();
        await this.editIcons.first().waitFor({ state: "visible" });
        await this.editIcons.first().click();
        await this.saveChannelButton.click();

        await expect(
            this.page.getByText("Update Channel Successfully"),
        ).toBeVisible();
    }

    async deleteFirstChannel(): Promise<void> {
        await this.open();
        await this.deleteIcons.first().waitFor({ state: "visible" });
        await this.deleteIcons.first().click();

        await this.page.waitForSelector("text=Are you sure");
        const agreeButton = this.agreeButton;

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            this.page.getByText("Channel deleted successfully."),
        ).toBeVisible();
    }
}
