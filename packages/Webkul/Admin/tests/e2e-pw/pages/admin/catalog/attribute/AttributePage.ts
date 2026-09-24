import { expect, type Locator, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";

export interface AttributeOptionData {
    label: string;
    color?: string;
    swatchImage?: string;
}

export interface SwatchSeoData {
    altText: string;
    fileName: string;
}

export interface AttributeData {
    adminName: string;
    code: string;
    type: string;
    swatchType?: string;
    options?: AttributeOptionData[];
    wysiwyg?: boolean;
}

export class AttributePage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/catalog/attributes";
    }

    private get createLink() {
        return this.page.getByRole("link", { name: "Create Attribute" });
    }

    private get adminNameInput() {
        return this.page.locator('input[name="admin_name"]');
    }

    private get localeNameInput() {
        return this.page.locator('input[name="en[name]"]');
    }

    private get codeInput() {
        return this.page.locator('input[type="text"][name="code"]');
    }

    private get typeSelect() {
        return this.page.locator('select[name="type"]');
    }

    private get swatchTypeSelect() {
        return this.page.locator('select[name="swatch_type"]');
    }

    private get wysiwygInput() {
        return this.page.locator('input[type="checkbox"][name="enable_wysiwyg"]');
    }

    private get wysiwygToggle() {
        return this.page.locator('label[for="enable_wysiwyg"]');
    }

    private get addRowButton() {
        return this.page
            .locator(".secondary-button")
            .filter({ hasText: /^\s*Add Row\s*$/ });
    }

    private get optionModal() {
        return this.modalPanel("Add Option");
    }

    private get optionAdminNameInput() {
        return this.optionModal.locator('input[name="admin_name"]');
    }

    private get optionLocaleNameInput() {
        return this.optionModal.locator('input[name="en"]');
    }

    private get optionColorInput() {
        return this.optionModal.getByPlaceholder("Color");
    }

    private get optionSwatchImageUploader() {
        return this.optionModal
            .locator('label[for$="_imageInput"]')
            .filter({ visible: true });
    }

    private get optionStagedSwatchImage() {
        return this.optionModal.locator(
            'input[type="file"][name="swatch_value[]"]',
        );
    }

    private get saveOptionButton() {
        return this.page.getByRole("button", { name: "Save Option" });
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Attribute" });
    }

    private modalPanel(title: string): Locator {
        return this.page.locator(`div:has(> div > p:text-is("${title}"))`);
    }

    private optionRow(label: string): Locator {
        return this.page.locator("tr").filter({
            has: this.page.locator("td p", {
                hasText: new RegExp(`^\\s*${label}\\s*$`),
            }),
        });
    }

    private optionRowInput(label: string, field: string): Locator {
        return this.optionRow(label).locator(`input[name$="[${field}]"]`);
    }

    private async openCreateForm(): Promise<void> {
        await this.openGrid();
        await this.createLink.click();
        await this.openFormPage();

        await expect(this.codeInput).toBeVisible();
    }

    private async openEditForm(code: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(code);
        await this.editIcon(code).click();
        await this.openFormPage();

        await expect(this.codeInput).toHaveValue(code);
    }

    private async addOption(option: AttributeOptionData): Promise<void> {
        await this.addRowButton.click();
        await this.optionAdminNameInput.fill(option.label);
        await this.optionLocaleNameInput.fill(option.label);

        if (option.color) {
            await this.optionColorInput.fill(option.color);
        }

        if (option.swatchImage) {
            await this.stageSwatchImage(option.swatchImage);
        }

        await this.saveOptionButton.click();

        await expect(this.optionRow(option.label)).toBeVisible();
    }

    private async stageSwatchImage(filePath: string): Promise<void> {
        await this.optionSwatchImageUploader.setInputFiles(filePath);

        await expect
            .poll(() =>
                this.optionStagedSwatchImage.evaluate(
                    (input: HTMLInputElement) => input.files?.length ?? 0,
                ),
            )
            .toBe(1);
    }

    private async expectImageSwatchSeo(
        label: string,
        seo: SwatchSeoData,
    ): Promise<void> {
        await expect(this.optionRowInput(label, "swatch_alt")).toHaveValue(
            seo.altText,
        );
        await expect(
            this.optionRowInput(label, "swatch_file_name"),
        ).toHaveValue(seo.fileName);
    }

    private async fillCreateForm(data: AttributeData): Promise<void> {
        await this.adminNameInput.fill(data.adminName);
        await this.localeNameInput.fill(data.adminName);
        await this.codeInput.fill(data.code);
        await this.typeSelect.selectOption(data.type);

        if (data.wysiwyg) {
            await this.setSwitch(this.wysiwygToggle, this.wysiwygInput, true);
        }

        if (data.swatchType) {
            await this.swatchTypeSelect.selectOption(data.swatchType);
        }

        for (const option of data.options ?? []) {
            await this.addOption(option);
        }
    }

    async createAttribute(data: AttributeData): Promise<void> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Attribute Created Successfully"),
        ).toBeVisible();
    }

    async attemptCreateAttribute(data: AttributeData): Promise<void> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateForm();
        await this.saveButton.click();
    }

    async renameAttribute(code: string, newAdminName: string): Promise<void> {
        await this.openEditForm(code);
        await this.adminNameInput.fill(newAdminName);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Attribute Updated Successfully"),
        ).toBeVisible();
    }

    async updateImageSwatchSeo(
        code: string,
        label: string,
        seo: SwatchSeoData,
    ): Promise<void> {
        await this.openEditForm(code);

        await this.optionRowInput(label, "swatch_alt").fill(seo.altText);
        await this.optionRowInput(label, "swatch_file_name").fill(seo.fileName);

        await this.optionRow(label).locator("span.icon-edit").click();
        await this.saveOptionButton.click();

        await expect(this.saveOptionButton).toBeHidden();
        await this.expectImageSwatchSeo(label, seo);

        await this.saveButton.click();

        await expect(
            this.flashMessage("Attribute Updated Successfully"),
        ).toBeVisible();
    }

    async deleteAttribute(code: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(code);
        await this.deleteRow(code, "Attribute Deleted Successfully");
    }

    async attemptDeleteAttribute(code: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(code);
        await this.rowWithCell(code).locator("span.icon-delete").click();
        await this.agreeButton.click();
    }

    async deleteAttributesIfPresent(codes: string[]): Promise<void> {
        await this.deleteRowsIfPresent(codes, "Attribute Deleted Successfully");
    }

    async massDeleteAttributes(codes: string[]): Promise<void> {
        await this.openGrid();
        await this.selectRows(codes);
        await this.applyMassAction("Delete");

        await expect(
            this.flashMessage("Selected Attribute Deleted Successfully"),
        ).toBeVisible();
    }

    async expectAttributeListed(
        code: string,
        adminName: string,
        typeLabel: string,
    ): Promise<void> {
        await this.expectSearchedRowCount(code, 1);

        await expect(this.row(code)).toContainText(adminName);
        await expect(this.row(code)).toContainText(typeLabel);
    }

    async expectAttributeAbsent(code: string): Promise<void> {
        await this.expectSearchedRowCount(code, 0);
    }

    async expectSystemAttributeListed(code: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(code);

        await expect(this.rowWithCell(code)).toHaveCount(1);
    }

    async expectAdminNameInEditForm(code: string, adminName: string): Promise<void> {
        await this.openEditForm(code);

        await expect(this.adminNameInput).toHaveValue(adminName);
    }

    async expectOptionsInEditForm(code: string, labels: string[]): Promise<void> {
        await this.openEditForm(code);

        for (const label of labels) {
            await expect(this.optionRow(label)).toBeVisible();
        }
    }

    async expectColorSwatchesInEditForm(
        code: string,
        options: AttributeOptionData[],
    ): Promise<void> {
        await this.openEditForm(code);

        for (const option of options) {
            await expect(
                this.optionRowInput(option.label, "swatch_value"),
            ).toHaveValue(option.color ?? "");
        }
    }

    async expectImageSwatchesInEditForm(
        code: string,
        labels: string[],
    ): Promise<void> {
        await this.openEditForm(code);

        for (const label of labels) {
            await expect(this.optionRow(label).locator("img")).toHaveAttribute(
                "src",
                /\/attribute-options\//,
            );
        }
    }

    async expectImageSwatchSeoInEditForm(
        code: string,
        label: string,
        seo: SwatchSeoData,
    ): Promise<void> {
        await this.openEditForm(code);

        await this.expectImageSwatchSeo(label, seo);
    }

    async expectWysiwygEnabledInEditForm(code: string): Promise<void> {
        await this.openEditForm(code);

        await expect(this.wysiwygInput).toBeChecked();
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectErrorMessage(message: string): Promise<void> {
        await expect(this.flashMessage(message)).toBeVisible();
    }

    async expectStillOnCreateForm(): Promise<void> {
        await expect(this.page).toHaveURL(/catalog\/attributes\/create/);
    }
}
