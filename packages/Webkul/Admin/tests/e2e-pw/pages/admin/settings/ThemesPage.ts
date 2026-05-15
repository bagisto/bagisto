import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import {
    generateDescription,
    generateHostname,
    generateName,
    getImageFile,
} from "../../../utils/faker";

export class ThemesPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createThemeButton() {
        return this.page.getByRole("button", { name: "Create Theme" });
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get sortOrderInput() {
        return this.page.locator('input[name="sort_order"]');
    }

    private get typeSelect() {
        return this.page.locator('select[name="type"]');
    }

    private get channelSelect() {
        return this.page.locator('select[name="channel_id"]');
    }

    private get themeCodeSelect() {
        return this.page.locator('select[name="theme_code"]');
    }

    private get saveThemeButton() {
        return this.page.getByRole("button", { name: "Save Theme" });
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save" });
    }

    private get statusToggleLabel() {
        return this.page.locator('label[for="status"]');
    }

    private get statusToggleInput() {
        return this.page.locator('input[name="status"]');
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
        await this.visit("admin/settings/themes");
    }

    async createBaseTheme(type: string): Promise<void> {
        await this.open();
        await this.createThemeButton.click();
        await this.nameInput.fill(generateName());
        await this.sortOrderInput.fill("1");
        await this.typeSelect.selectOption(type);
        await this.channelSelect.selectOption("1");
        await this.themeCodeSelect.selectOption("default");
        await this.saveThemeButton.click();
        await this.page.waitForSelector(
            'form[action*="/settings/themes/edit"]',
        );
    }

    async createProductCarouselTheme(): Promise<void> {
        await this.createBaseTheme("product_carousel");
        await this.page
            .locator('input[name="en[options][title]"]')
            .fill(generateName());
        await this.page
            .locator('select[name="en[options][filters][sort]"]')
            .selectOption("name-asc");
        await this.page
            .locator('select[name="en[options][filters][limit]"]')
            .selectOption("12");
        await this.statusToggleLabel.click();
        await expect(this.statusToggleInput).toBeChecked();
        await this.saveButton.click();
        await expect(
            this.page.getByText("Theme updated successfully"),
        ).toBeVisible();
    }

    async createCategoryCarouselTheme(): Promise<void> {
        await this.createBaseTheme("category_carousel");
        await this.page
            .locator('select[name="en[options][filters][sort]"]')
            .selectOption("asc");
        await this.page
            .locator('input[name="en[options][filters][limit]"]')
            .fill("10");
        await this.statusToggleLabel.click();
        await expect(this.statusToggleInput).toBeChecked();
        await this.saveButton.click();
        await expect(
            this.page.getByText("Theme updated successfully"),
        ).toBeVisible();
    }

    async createStaticContentTheme(): Promise<void> {
        await this.createBaseTheme("static_content");
        const content = generateDescription();
        const codeMirrorTextarea = await this.page.locator(
            ".CodeMirror textarea",
        );
        await codeMirrorTextarea.focus();
        await this.page.keyboard.type(content);
        const codeMirrorContent = await this.page
            .locator(".CodeMirror-code")
            .innerText();
        expect(codeMirrorContent).toContain(content);
        await this.statusToggleLabel.click();
        await expect(this.statusToggleInput).toBeChecked();
        await this.saveButton.click();
        await expect(
            this.page.getByText("Theme updated successfully"),
        ).toBeVisible();
    }

    async createImageCarouselTheme(): Promise<void> {
        await this.createBaseTheme("image_carousel");
        await this.page
            .locator('div.secondary-button:has-text("Add Slider")')
            .click();
        await this.page.locator('input[name="en[title]"]').fill(generateName());
        await this.page
            .locator('input[name="en[link]"]')
            .fill(generateHostname());

        const [fileChooser] = await Promise.all([
            this.page.waitForEvent("filechooser"),
            this.page
                .locator("label")
                .filter({ hasText: "Add Image png, jpeg, jpg" })
                .last()
                .click(),
        ]);
        await fileChooser.setFiles(getImageFile());

        await this.saveButton.nth(1).click();
        await this.statusToggleLabel.click();
        await expect(this.statusToggleInput).toBeChecked();
        await this.saveButton.click();
        await expect(
            this.page.getByText("Theme updated successfully"),
        ).toBeVisible();
    }

    async createFooterLinkTheme(): Promise<void> {
        await this.createBaseTheme("footer_links");
        await this.page
            .locator('div.secondary-button:has-text("Add Link")')
            .click();
        await this.page.locator('select[name="column"]').selectOption("1");
        await this.page.locator('input[name="title"]').fill(generateName());
        await this.page.locator('input[name="url"]').fill(generateHostname());
        await this.page
            .getByRole("textbox", { name: "Sort Order" })
            .first()
            .fill("1");
        await this.statusToggleLabel.click();
        await expect(this.statusToggleInput).toBeChecked();
        await this.saveButton.click();
        await expect(
            this.page.getByText("Theme updated successfully"),
        ).toBeVisible();
    }

    async createServicesContentTheme(): Promise<void> {
        await this.createBaseTheme("services_content");
        await this.page
            .locator('div.secondary-button:has-text("Add Services")')
            .click();
        await this.page
            .getByRole("textbox", { name: "Title" })
            .fill(generateName());
        await this.page
            .getByRole("textbox", { name: "Description" })
            .fill(generateDescription());
        await this.page
            .getByRole("textbox", { name: "Service Icon Class" })
            .fill("icon-truck");
        await this.saveButton.nth(1).click();
        await this.statusToggleLabel.click();
        await expect(this.statusToggleInput).toBeChecked();
        await this.saveButton.click();
        await expect(
            this.page.getByText("Theme updated successfully"),
        ).toBeVisible();
    }

    async deleteFirstTheme(): Promise<void> {
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
            this.page.getByText("Theme deleted successfully"),
        ).toBeVisible();
    }
}
