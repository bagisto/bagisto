import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";

export class RmaStatusesPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/sales/rma/rma-status";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create RMA Status" });
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

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save RMA Status" });
    }

    async createStatus(title: string): Promise<void> {
        await this.openGrid();
        await this.createButton.click();
        await expect(this.titleInput).toBeVisible();
        await this.titleInput.fill(title);
        await this.setSwitch(this.statusToggle, this.statusInput, true);
        await this.saveButton.click();

        await expect(
            this.flashMessage("RMA Status created successfully."),
        ).toBeVisible();
    }

    async deleteStatusesIfPresent(titles: string[]): Promise<void> {
        await this.deleteRowsIfPresent(titles, "RMA Status deleted successfully.");
    }

    async expectStatusListed(title: string): Promise<void> {
        await this.expectSearchedRowCount(title, 1);
    }
}
