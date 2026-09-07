import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";

export interface CategoryData {
    name: string;
    slug: string;
    position?: string;
    filterableAttribute?: string;
    parent?: string;
}

export class CategoryPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/catalog/categories";
    }

    private get createLink() {
        return this.page.getByRole("link", { name: "Create Category" });
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get slugInput() {
        return this.page.locator('input[name="slug"]');
    }

    private get localeNameInput() {
        return this.page.locator('input[name="en[name]"]');
    }

    private get positionInput() {
        return this.page.locator('input[name="position"]');
    }

    private get displayModeSelect() {
        return this.page.locator('select[name="display_mode"]');
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get rootParentOption() {
        return this.parentOption("Root");
    }

    private parentOption(name: string) {
        return this.page.getByText(name, { exact: true });
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Category" });
    }

    private filterableAttributeOption(name: string) {
        return this.checkboxLabel(name);
    }

    private async waitForSlugSuggestedFromName(): Promise<void> {
        await expect(this.slugInput).not.toHaveValue("");
    }

    private async openCreateForm(): Promise<void> {
        await this.openGrid();
        await this.createLink.click();
        await this.openFormPage();

        await expect(this.rootParentOption).toBeVisible();
    }

    private async openEditForm(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.editIcon(name).click();
        await this.openFormPage();

        await expect(this.rootParentOption).toBeVisible();
    }

    private async fillCreateForm(data: CategoryData): Promise<void> {
        await this.nameInput.fill(data.name);
        await this.waitForSlugSuggestedFromName();
        await this.slugInput.fill(data.slug);
        await this.parentOption(data.parent ?? "Root").click();
        await this.positionInput.fill(data.position ?? "1");
        await this.displayModeSelect.selectOption("products_only");
        await this.setSwitch(this.statusToggle, this.statusInput, true);
        await this.filterableAttributeOption(
            data.filterableAttribute ?? "Price",
        ).click();

        await expect(this.slugInput).toHaveValue(data.slug);
    }

    async createCategory(data: CategoryData): Promise<void> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Category created successfully."),
        ).toBeVisible();
    }

    async attemptCreateCategory(data: CategoryData): Promise<void> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateForm();
        await this.saveButton.click();
    }

    async renameCategory(name: string, newName: string): Promise<void> {
        await this.openEditForm(name);
        await this.localeNameInput.fill(newName);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Category updated successfully."),
        ).toBeVisible();
    }

    async deleteCategory(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(
            name,
            "The category has been successfully deleted.",
        );
    }

    async attemptDeleteCategory(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.rowWithCell(name).locator("span.icon-delete").click();
        await this.agreeButton.click();
    }

    async deleteCategoriesIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(
            names,
            "The category has been successfully deleted.",
        );
    }

    async massUpdateStatus(
        names: string[],
        status: "Active" | "Inactive",
    ): Promise<void> {
        await this.openGrid();
        await this.selectRows(names);
        await this.applyMassAction("Update Status", status);

        await expect(
            this.flashMessage("Category updated successfully."),
        ).toBeVisible();
    }

    async massDeleteCategories(names: string[]): Promise<void> {
        await this.openGrid();
        await this.selectRows(names);
        await this.applyMassAction("Delete");

        await expect(
            this.flashMessage("The category has been successfully deleted."),
        ).toBeVisible();
    }

    async expectCategoryListed(
        name: string,
        status: "Active" | "Inactive",
    ): Promise<void> {
        await this.expectSearchedRowCount(name, 1);

        await expect(this.row(name)).toContainText(status);
    }

    async expectCategoryAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectRootCategoryListed(): Promise<void> {
        await this.openGrid();
        await this.searchFor("Root");

        await expect(this.rowWithCell("Root")).toHaveCount(1);
    }

    async expectNameInEditForm(name: string): Promise<void> {
        await this.openEditForm(name);

        await expect(this.localeNameInput).toHaveValue(name);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectErrorMessage(message: string): Promise<void> {
        await expect(this.flashMessage(message)).toBeVisible();
    }

    async expectStillOnCreateForm(): Promise<void> {
        await expect(this.page).toHaveURL(/catalog\/categories\/create/);
    }
}
