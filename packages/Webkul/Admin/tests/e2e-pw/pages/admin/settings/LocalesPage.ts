import { expect, type Page } from "@playwright/test";
import type { MediaFile } from "@shared/media-files";
import { MediaGalleryField } from "../components/MediaGalleryField";
import { DatagridPage } from "../DatagridPage";

export interface LocaleData {
    code: string;
    name: string;
    direction: "ltr" | "rtl";
}

export class LocalesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/settings/locales";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create Locale" });
    }

    private get codeInput() {
        return this.page.locator('input[name="code"]');
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get directionSelect() {
        return this.page.locator('select[name="direction"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Locale" });
    }

    private get form() {
        return this.codeInput.locator("xpath=ancestor::form[1]");
    }

    private get closeFormIcon() {
        return this.form.locator("span.icon-close");
    }

    private async openCreateModal(): Promise<void> {
        await this.openGrid();
        await this.createButton.click();

        await expect(this.codeInput).toBeVisible();
    }

    private async openEditModal(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.editIcon(name).click();

        await expect(this.nameInput).toHaveValue(name);
    }

    private async fillForm(data: LocaleData): Promise<void> {
        await this.codeInput.fill(data.code);
        await this.nameInput.fill(data.name);
        await this.directionSelect.selectOption(data.direction);
    }

    logoField(): MediaGalleryField {
        return new MediaGalleryField(this.form);
    }

    async startCreatingLocale(data: LocaleData): Promise<void> {
        await this.openCreateModal();
        await this.fillForm(data);
    }

    async attemptCreateLocaleWithLogo(
        data: LocaleData,
        logo: MediaFile,
    ): Promise<void> {
        await this.startCreatingLocale(data);
        await this.logoField().attach(logo);
        await this.saveButton.click();
    }

    async createLocaleWithLogo(
        data: LocaleData,
        logo: MediaFile,
    ): Promise<void> {
        await this.attemptCreateLocaleWithLogo(data, logo);

        await expect(
            this.flashMessage("Locale created successfully."),
        ).toBeVisible();
    }

    async saveNewLocale(): Promise<void> {
        await this.saveButton.click();

        await expect(
            this.flashMessage("Locale created successfully."),
        ).toBeVisible();
    }

    async attemptReplaceLogo(name: string, logo: MediaFile): Promise<void> {
        await this.openEditModal(name);
        await this.logoField().removeAt(0);
        await this.logoField().attach(logo);
        await this.saveButton.click();
    }

    async closeAndReopenCreateForm(): Promise<void> {
        await this.closeFormIcon.click();

        await expect(this.codeInput).toBeHidden();

        await this.createButton.click();

        await expect(this.codeInput).toBeVisible();
    }

    async createLocale(data: LocaleData): Promise<void> {
        await this.openCreateModal();
        await this.fillForm(data);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Locale created successfully."),
        ).toBeVisible();
    }

    async attemptCreateLocale(data: LocaleData): Promise<void> {
        await this.openCreateModal();
        await this.fillForm(data);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateModal();
        await this.saveButton.click();
    }

    async updateLocale(
        name: string,
        changes: { name: string; direction: "ltr" | "rtl" },
    ): Promise<void> {
        await this.openEditModal(name);
        await this.nameInput.fill(changes.name);
        await this.directionSelect.selectOption(changes.direction);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Locale updated successfully."),
        ).toBeVisible();
    }

    async deleteLocale(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Locale deleted successfully.");
    }

    async deleteLocalesIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(names, "Locale deleted successfully.");
    }

    async expectLocaleListed(data: LocaleData): Promise<void> {
        await this.expectSearchedRowCount(data.name, 1);

        await expect(this.row(data.name)).toContainText(data.code);
        await expect(this.row(data.name)).toContainText(
            data.direction.toUpperCase(),
        );
    }

    async expectLocaleAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectLocaleCodeListedOnce(code: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(code);

        await expect(this.rowWithCell(code)).toHaveCount(1);
    }

    async expectDirectionInEditForm(
        name: string,
        direction: "ltr" | "rtl",
    ): Promise<void> {
        await this.openEditModal(name);

        await expect(this.directionSelect).toHaveValue(direction);
    }

    async expectLogoInEditForm(name: string): Promise<void> {
        await this.openEditModal(name);
        await this.logoField().expectStored();
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }
}
