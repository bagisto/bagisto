import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";

export class RmaReasonsPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/sales/rma/reasons";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create RMA Reason" });
    }

    private get titleInput() {
        return this.page.locator('input[name="title"]');
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get positionInput() {
        return this.page.locator('input[name="position"]');
    }

    private get resolutionTypeSelect() {
        return this.page.locator('select[name="resolution_type[]"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Reason" });
    }

    async createReason(title: string): Promise<void> {
        await this.openGrid();
        await this.createButton.click();
        await expect(this.titleInput).toBeVisible();
        await this.titleInput.fill(title);
        await this.setSwitch(this.statusToggle, this.statusInput, true);
        await this.positionInput.fill("1");
        await this.resolutionTypeSelect.selectOption("return");
        await this.saveButton.click();

        await expect(
            this.flashMessage("Reason created successfully."),
        ).toBeVisible();
    }

    async deleteReasonsIfPresent(titles: string[]): Promise<void> {
        await this.deleteRowsIfPresent(titles, "Reason deleted successfully.");
    }

    async expectReasonListed(title: string): Promise<void> {
        await this.expectSearchedRowCount(title, 1);

        await expect(this.row(title)).toContainText("Active");
    }
}
