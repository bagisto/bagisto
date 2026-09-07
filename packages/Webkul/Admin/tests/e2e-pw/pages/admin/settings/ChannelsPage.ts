import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";
import {
    generateDescription,
    generateHostname,
    generateName,
    generateSlug,
    uniqueStamp,
} from "../../../utils/faker";

export interface ChannelData {
    code: string;
    name: string;
    description: string;
    hostname: string;
}

export function buildChannel(overrides: Partial<ChannelData> = {}): ChannelData {
    const name = `${generateName()} ${uniqueStamp()}`;

    return {
        code: generateSlug("_"),
        name,
        description: generateDescription(),
        hostname: generateHostname(),
        ...overrides,
    };
}

export class ChannelsPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/settings/channels";
    }

    private get createLink() {
        return this.page.getByRole("link", { name: "Create Channel" });
    }

    private get codeInput() {
        return this.page.locator('input[name="code"]');
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get localeNameInput() {
        return this.page.locator('input[name="en[name]"]');
    }

    private get descriptionInput() {
        return this.page.locator('textarea[name="description"]');
    }

    private get inventorySourceOption() {
        return this.checkboxLabel("inventory_sources_1");
    }

    private get inventorySourceInput() {
        return this.page.locator("input#inventory_sources_1");
    }

    private get rootCategorySelect() {
        return this.page.locator('select[name="root_category_id"]');
    }

    private get hostnameInput() {
        return this.page.locator('input[name="hostname"]');
    }

    private get localeOption() {
        return this.checkboxLabel("locales_1");
    }

    private get localeInput() {
        return this.page.locator("input#locales_1");
    }

    private get defaultLocaleSelect() {
        return this.page.locator('select[name="default_locale_id"]');
    }

    private get currencyOption() {
        return this.checkboxLabel("currencies_1");
    }

    private get currencyInput() {
        return this.page.locator("input#currencies_1");
    }

    private get baseCurrencySelect() {
        return this.page.locator('select[name="base_currency_id"]');
    }

    private get seoTitleInput() {
        return this.page.locator('input[name="seo_title"]');
    }

    private get seoKeywordsInput() {
        return this.page.locator('textarea[name="seo_keywords"]');
    }

    private get seoDescriptionInput() {
        return this.page.locator('textarea[name="seo_description"]');
    }

    private get saveButton() {
        return this.page
            .locator('button[type="submit"]')
            .filter({ hasText: "Save Channel" });
    }

    private currencyLabel(currencyName: string) {
        return this.page
            .locator('label[for^="currencies_"]')
            .filter({ hasText: new RegExp(`^\\s*${currencyName}\\s*$`) });
    }

    private currencyCheckbox(currencyName: string) {
        return this.currencyLabel(currencyName)
            .locator("..")
            .locator('input[name="currencies[]"]');
    }

    private async openCreateForm(): Promise<void> {
        await this.openGrid();
        await this.createLink.click();
        await this.openFormPage();

        await expect(this.codeInput).toBeVisible();
    }

    private async openEditForm(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.editIcon(name).click();
        await this.openFormPage();

        await expect(this.localeNameInput).toHaveValue(name);
    }

    private async fillCreateForm(data: ChannelData): Promise<void> {
        await this.codeInput.fill(data.code);
        await this.nameInput.fill(data.name);
        await this.descriptionInput.fill(data.description);
        await this.inventorySourceOption.click();
        await expect(this.inventorySourceInput).toBeChecked();
        await this.rootCategorySelect.selectOption("1");
        await this.hostnameInput.fill(data.hostname);
        await this.localeOption.click();
        await expect(this.localeInput).toBeChecked();
        await this.defaultLocaleSelect.selectOption("1");
        await this.currencyOption.click();
        await expect(this.currencyInput).toBeChecked();
        await this.baseCurrencySelect.selectOption("1");
        await this.seoTitleInput.fill(data.name);
        await this.seoKeywordsInput.fill(data.name);
        await this.seoDescriptionInput.fill(data.description);
    }

    async createChannel(
        data: ChannelData = buildChannel(),
    ): Promise<ChannelData> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Channel created successfully."),
        ).toBeVisible();

        return data;
    }

    async attemptCreateChannel(data: ChannelData): Promise<void> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateForm();
        await this.saveButton.click();
    }

    async renameChannel(name: string, newName: string): Promise<void> {
        await this.openEditForm(name);
        await this.localeNameInput.fill(newName);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Update Channel Successfully"),
        ).toBeVisible();
    }

    async setChannelCurrency(
        channelName: string,
        currencyName: string,
        enabled: boolean,
    ): Promise<void> {
        await this.openEditForm(channelName);

        const checkbox = this.currencyCheckbox(currencyName);

        await expect(checkbox).toBeAttached();

        if ((await checkbox.isChecked()) !== enabled) {
            await this.currencyLabel(currencyName).click();
        }

        await expect(checkbox).toBeChecked({ checked: enabled });

        await this.saveButton.click();

        await expect(
            this.flashMessage("Update Channel Successfully"),
        ).toBeVisible();
    }

    async deleteChannel(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Channel deleted successfully.");
    }

    async attemptDeleteChannel(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.rowWithCell(name).locator("span.icon-delete").click();
        await this.agreeButton.click();
    }

    async deleteChannelsIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(names, "Channel deleted successfully.");
    }

    async expectChannelListed(data: ChannelData): Promise<void> {
        await this.expectSearchedRowCount(data.name, 1);

        await expect(this.row(data.name)).toContainText(data.code);
        await expect(this.row(data.name)).toContainText(data.hostname);
    }

    async expectChannelAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectChannelCodeListedOnce(code: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(code);

        await expect(this.rowWithCell(code)).toHaveCount(1);
    }

    async expectDefaultChannelListed(): Promise<void> {
        await this.openGrid();
        await this.searchFor("default");

        await expect(this.rowWithCell("default")).toHaveCount(1);
    }

    async expectNameInEditForm(name: string): Promise<void> {
        await this.openEditForm(name);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectErrorMessage(message: string): Promise<void> {
        await expect(this.flashMessage(message)).toBeVisible();
    }

    async expectStillOnCreateForm(): Promise<void> {
        await expect(this.page).toHaveURL(/settings\/channels\/create/);
    }
}
