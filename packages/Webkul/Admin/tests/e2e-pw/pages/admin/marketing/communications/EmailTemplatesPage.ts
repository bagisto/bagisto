import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";
import type { AdminPage } from "../../../../setup";

export interface EmailTemplateData {
    name: string;
    content: string;
}

export class EmailTemplatesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/marketing/communications/email-templates";
    }

    private get createLink() {
        return this.page.getByRole("link", { name: "Create Template" });
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get statusSelect() {
        return this.page.locator('select[name="status"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Template" });
    }

    private async openCreateForm(): Promise<void> {
        await this.openGrid();
        await this.createLink.click();
        await this.openFormPage();

        await expect(this.nameInput).toBeVisible();
    }

    private async openEditForm(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.editIcon(name).click();
        await this.openFormPage();

        await expect(this.nameInput).toHaveValue(name);
    }

    async createTemplate(data: EmailTemplateData): Promise<void> {
        await this.openCreateForm();
        await (this.page as AdminPage).fillInTinymce("#content_ifr", data.content);
        await this.nameInput.fill(data.name);
        await this.statusSelect.selectOption("active");
        await this.saveButton.click();

        await expect(
            this.flashMessage("Email template created successfully."),
        ).toBeVisible();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateForm();
        await this.saveButton.click();
    }

    async renameTemplate(name: string, newName: string): Promise<void> {
        await this.openEditForm(name);
        await this.nameInput.fill(newName);
        await this.saveButton.click();

        await expect(this.flashMessage("Updated successfully")).toBeVisible();
    }

    async deleteTemplate(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Template Deleted successfully");
    }

    async deleteTemplatesIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(names, "Template Deleted successfully");
    }

    async expectTemplateListed(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 1);

        await expect(this.row(name)).toContainText("Active");
    }

    async expectTemplateAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectNameInEditForm(name: string): Promise<void> {
        await this.openEditForm(name);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectStillOnCreateForm(): Promise<void> {
        await expect(this.page).toHaveURL(/email-templates\/create/);
    }
}
