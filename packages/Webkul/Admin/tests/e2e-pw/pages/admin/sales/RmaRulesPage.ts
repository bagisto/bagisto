import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";

export class RmaRulesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/sales/rma/rules";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create RMA Rules" });
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get descriptionInput() {
        return this.page.locator('textarea[name="description"]');
    }

    private get returnPeriodInput() {
        return this.page.locator('input[name="return_period"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save RMA Rules" });
    }

    async createRule(name: string, returnPeriod: string): Promise<void> {
        await this.openGrid();
        await this.createButton.click();
        await expect(this.nameInput).toBeVisible();
        await this.nameInput.fill(name);
        await this.setSwitch(this.statusToggle, this.statusInput, true);
        await this.descriptionInput.fill(`Rule ${name}`);
        await this.returnPeriodInput.fill(returnPeriod);
        await this.saveButton.click();

        await expect(
            this.flashMessage("RMA Rules created successfully."),
        ).toBeVisible();
    }

    async deleteRulesIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(names, "RMA Rules deleted successfully.");
    }

    async expectRuleListed(name: string, returnPeriod: string): Promise<void> {
        await this.expectSearchedRowCount(name, 1);

        await expect(this.row(name)).toContainText(returnPeriod);
    }
}
