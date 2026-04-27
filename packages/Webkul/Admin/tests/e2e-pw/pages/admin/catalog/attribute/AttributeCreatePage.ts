import { expect, Locator, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export interface AttributeCreateData {
    adminName: string;
    code: string;
    type: string;
    localeName?: string;
    shouldEnableDefaultConfiguration?: boolean;
    shouldEnableWysiwyg?: boolean;
    defaultValue?: string;
    swatchType?: string;
    options?: AttributeOptionData[];
    shouldAddToDefaultFamily?: boolean;
}

export interface AttributeOptionData {
    adminLabel: string;
    localeLabel?: string;
    color?: string;
}

export class AttributeCreatePage extends BasePage {
    constructor(protected page: Page) {
        super(page);
    }

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get fillname() {
        return this.page.locator('input[name="admin_name"]');
    }

    get fillCode() {
        return this.page.locator('input[name="code"]');
    }

    get selectTypeAttribute() {
        return this.page.locator('select[name="type"]');
    }

    private get localeNameInput() {
        return this.page.locator('input[name="en[name]"]');
    }

    private get valuePerLocaleToggle() {
        return this.page.locator('label[for="value_per_locale"]');
    }

    private get valuePerChannelToggle() {
        return this.page.locator('label[for="value_per_channel"]');
    }

    private get visibleOnFrontToggle() {
        return this.page.locator('label[for="is_visible_on_front"]');
    }

    private get comparableToggle() {
        return this.page.locator('label[for="is_comparable"]');
    }

    private get valuePerLocaleInput() {
        return this.page.locator("input#value_per_locale");
    }

    private get valuePerChannelInput() {
        return this.page.locator("input#value_per_channel");
    }

    private get visibleOnFrontInput() {
        return this.page.locator("input#is_visible_on_front");
    }

    private get comparableInput() {
        return this.page.locator("input#is_comparable");
    }

    private get wysiwygToggle() {
        return this.page.locator(".relative > label").first();
    }

    private get submitButton() {
        return this.page.locator('button[type="submit"]');
    }

    private get swatchTypeSelect() {
        return this.page.locator("#swatchType");
    }

    private get addRowButton() {
        return this.page.getByText("Add Row");
    }

    private get adminOptionInput() {
        return this.page.getByRole("textbox", { name: "Admin" }).nth(1);
    }

    private get localeOptionInput() {
        return this.page.locator('input[name="en"]');
    }

    private get saveOptionButton() {
        return this.page.getByRole("button", { name: "Save Option" });
    }

    private get colorOptionInput() {
        return this.page.getByPlaceholder("Color");
    }

    private get defaultValueInput() {
        return this.page.locator('input[name="default_value"]');
    }

    private get attributeGroupLists() {
        return this.page.locator(
            "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l",
        );
    }

    private get defaultFamilyEditIcon() {
        return this.page
            .locator('div.row:has-text("default")')
            .locator("span.icon-edit");
    }

    private get familySaveButton() {
        return this.page.locator("button.primary-button:visible");
    }

    async waitForPageToLoad() {
        await expect(this.createBtn.first()).toBeVisible();
    }

    async openCreateForm() {
        await this.createBtn.first().click();
        await expect(this.fillname).toBeVisible();
    }

    async validateRequiredFields() {
        await this.visit("admin/catalog/attributes");
        await this.waitForPageToLoad();
        await this.openCreateForm();
        await this.submitButton.first().click();
    }

    async fillBasicDetails(data: AttributeCreateData) {
        await this.fillname.fill(data.adminName);
        await this.localeNameInput.fill(data.localeName ?? data.adminName);
        await this.fillCode.fill(data.code);
        await this.selectTypeAttribute.selectOption(data.type);
    }

    async enableDefaultConfiguration() {
        const toggles: Locator[] = [
            this.valuePerLocaleToggle,
            this.valuePerChannelToggle,
            this.visibleOnFrontToggle,
            this.comparableToggle,
        ];

        for (const toggle of toggles) {
            await toggle.first().click();
        }

        await expect(this.valuePerLocaleInput).toBeChecked();
        await expect(this.valuePerChannelInput).toBeChecked();
        await expect(this.visibleOnFrontInput).toBeChecked();
        await expect(this.comparableInput).toBeChecked();
    }

    async enableWysiwyg() {
        await this.wysiwygToggle.click();
    }

    async selectSwatchType(swatchType: string) {
        await this.swatchTypeSelect.selectOption(swatchType);
    }

    async fillDefaultValue(defaultValue: string) {
        await this.defaultValueInput.fill(defaultValue);
    }

    async addOption(option: AttributeOptionData) {
        await this.addRowButton.click();
        await this.adminOptionInput.fill(option.adminLabel);
        await this.localeOptionInput.fill(option.localeLabel ?? option.adminLabel);

        if (option.color) {
            await this.colorOptionInput.fill(option.color);
        }

        await this.saveOptionButton.click();
    }

    async addOptions(options: AttributeOptionData[]) {
        for (const option of options) {
            await this.addOption(option);
        }
    }

    async saveAttribute() {
        await this.submitButton.first().click();
    }

    async verifyAttributeCreated() {
        await expect(this.page.locator("#app")).toContainText(
            "Attribute Created Successfully",
        );
    }

    async addAttributeToDefaultFamily() {
        await super.visit("admin/catalog/families");
        await this.page.waitForSelector("span.cursor-pointer.icon-edit");

        if (!(await this.defaultFamilyEditIcon.isVisible())) {
            await this.page.locator("span.icon-sort-down").click();
            await this.page.locator("ul.py-4 >> li", { hasText: "20" }).click();
            await this.page.waitForLoadState("networkidle");
        }

        await this.defaultFamilyEditIcon.click();
        await expect(this.attributeGroupLists.first()).toBeVisible();

        const targetCount = await this.attributeGroupLists.count();
        expect(targetCount).toBeGreaterThan(0);

        const targetColumns = Math.min(2, targetCount);
        const targetPoints: { x: number; y: number }[] = [];

        for (let index = 0; index < targetColumns; index++) {
            const target = this.attributeGroupLists.nth(index);
            await target.scrollIntoViewIfNeeded();

            if (!(await target.isVisible())) {
                continue;
            }

            const targetBox = await target.boundingBox();

            if (!targetBox) {
                continue;
            }

            targetPoints.push({
                x: targetBox.x + targetBox.width / 2,
                y: targetBox.y + targetBox.height / 2,
            });
        }

        expect(targetPoints.length).toBeGreaterThan(0);

        const unassignedItems = await this.page.$$("#unassigned-attributes i.icon-drag");

        for (const [index, item] of unassignedItems.entries()) {
            const itemBox = await item.boundingBox();

            if (!itemBox) {
                continue;
            }

            const targetPoint = targetPoints[index % targetPoints.length];

            await this.page.mouse.move(
                itemBox.x + itemBox.width / 2,
                itemBox.y + itemBox.height / 2,
            );
            await this.page.mouse.down();
            await this.page.mouse.move(targetPoint.x, targetPoint.y, {
                steps: 20,
            });
            await this.page.mouse.up();
            await this.page.waitForTimeout(200);
        }

        await this.familySaveButton.click();
        await expect(
            this.page.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    }

    async createAttribute(data: AttributeCreateData) {
        await this.visit("admin/catalog/attributes");
        await this.waitForPageToLoad();
        await this.openCreateForm();
        await this.fillBasicDetails(data);

        if (data.shouldEnableWysiwyg) {
            await this.enableWysiwyg();
        }

        if (data.swatchType) {
            await this.selectSwatchType(data.swatchType);
        }

        if (data.options?.length) {
            await this.addOptions(data.options);
        }

        if (data.defaultValue) {
            await this.fillDefaultValue(data.defaultValue);
        }

        if (data.shouldEnableDefaultConfiguration ?? true) {
            await this.enableDefaultConfiguration();
        }

        await this.saveAttribute();
        await this.verifyAttributeCreated();

        if (data.shouldAddToDefaultFamily ?? true) {
            await this.addAttributeToDefaultFamily();
        }
    }
}
