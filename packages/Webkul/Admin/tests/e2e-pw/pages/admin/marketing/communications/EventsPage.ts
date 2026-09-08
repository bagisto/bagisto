import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";

export interface EventData {
    name: string;
    description: string;
    date: string;
}

export class EventsPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/marketing/communications/events";
    }

    private get createButton() {
        return this.primaryTrigger("Create Event");
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get descriptionInput() {
        return this.page.locator('[name="description"]');
    }

    private get dateInput() {
        return this.page.locator('input[name="date"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Event" });
    }

    private async openCreateModal(): Promise<void> {
        await this.openGrid();
        await this.createButton.click();

        await expect(this.nameInput).toBeVisible();
    }

    private async openEditModal(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.editIcon(name).click();

        await expect(this.nameInput).toHaveValue(name);
    }

    async createEvent(data: EventData): Promise<void> {
        await this.openCreateModal();
        await this.nameInput.fill(data.name);
        await this.descriptionInput.fill(data.description);
        await this.dateInput.fill(data.date);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Events Created Successfully"),
        ).toBeVisible();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateModal();
        await this.saveButton.click();
    }

    async renameEvent(name: string, newName: string): Promise<void> {
        await this.openEditModal(name);
        await this.nameInput.fill(newName);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Events Updated Successfully"),
        ).toBeVisible();
    }

    async deleteEvent(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Events Deleted Successfully");
    }

    async deleteEventsIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(names, "Events Deleted Successfully");
    }

    async expectEventListed(name: string, date: string): Promise<void> {
        await this.expectSearchedRowCount(name, 1);

        await expect(this.row(name)).toContainText(date);
    }

    async expectEventAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectNameInEditForm(name: string): Promise<void> {
        await this.openEditModal(name);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }
}
