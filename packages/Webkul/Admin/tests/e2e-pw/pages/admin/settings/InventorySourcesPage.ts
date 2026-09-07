import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";

export interface InventorySourceData {
    code: string;
    name: string;
    contactName: string;
    contactEmail: string;
    contactNumber: string;
    street: string;
    city: string;
    postcode: string;
}

export class InventorySourcesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/settings/inventory-sources";
    }

    private get createLink() {
        return this.page.getByRole("link", { name: "Create Inventory Source" });
    }

    private get codeInput() {
        return this.page.locator('input[name="code"]');
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get contactNameInput() {
        return this.page.locator('input[name="contact_name"]');
    }

    private get contactEmailInput() {
        return this.page.locator('input[name="contact_email"]');
    }

    private get contactNumberInput() {
        return this.page.locator('input[name="contact_number"]');
    }

    private get countrySelect() {
        return this.page.locator('select[name="country"]');
    }

    private get stateSelect() {
        return this.page.locator('select[name="state"]');
    }

    private get cityInput() {
        return this.page.locator('input[name="city"]');
    }

    private get streetInput() {
        return this.page.locator('input[name="street"]');
    }

    private get postcodeInput() {
        return this.page.locator('input[name="postcode"]');
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", {
            name: "Save Inventory Sources",
        });
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

        await expect(this.nameInput).toHaveValue(name);
    }

    private async fillCreateForm(data: InventorySourceData): Promise<void> {
        await this.codeInput.fill(data.code);
        await this.nameInput.fill(data.name);
        await this.contactNameInput.fill(data.contactName);
        await this.contactEmailInput.fill(data.contactEmail);
        await this.contactNumberInput.fill(data.contactNumber);
        await this.countrySelect.selectOption("IN");
        await this.stateSelect.selectOption("DL");
        await this.cityInput.fill(data.city);
        await this.streetInput.fill(data.street);
        await this.postcodeInput.fill(data.postcode);
        await this.setSwitch(this.statusToggle, this.statusInput, true);
    }

    async createInventorySource(data: InventorySourceData): Promise<void> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Inventory Source Created Successfully"),
        ).toBeVisible();
    }

    async attemptCreateInventorySource(
        data: InventorySourceData,
    ): Promise<void> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateForm();
        await this.saveButton.click();
    }

    async renameInventorySource(name: string, newName: string): Promise<void> {
        await this.openEditForm(name);
        await this.nameInput.fill(newName);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Inventory Sources Updated Successfully"),
        ).toBeVisible();
    }

    async deleteInventorySource(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Inventory Sources Deleted Successfully");
    }

    async deleteInventorySourcesIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(
            names,
            "Inventory Sources Deleted Successfully",
        );
    }

    async expectInventorySourceListed(
        data: Pick<InventorySourceData, "name" | "code">,
    ): Promise<void> {
        await this.expectSearchedRowCount(data.name, 1);

        await expect(this.row(data.name)).toContainText(data.code);
        await expect(this.row(data.name)).toContainText("Active");
    }

    async expectInventorySourceAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectInventorySourceCodeListedOnce(code: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(code);

        await expect(this.rowWithCell(code)).toHaveCount(1);
    }

    async expectNameInEditForm(name: string): Promise<void> {
        await this.openEditForm(name);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectStillOnCreateForm(): Promise<void> {
        await expect(this.page).toHaveURL(/inventory-sources\/create/);
    }
}
