import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";

export interface CampaignData {
    name: string;
    subject: string;
    eventName: string;
    templateName: string;
}

export class CampaignsPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/marketing/communications/campaigns";
    }

    private get createLink() {
        return this.page.getByRole("link", { name: "Create Campaign" });
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get subjectInput() {
        return this.page.locator('input[name="subject"]');
    }

    private get eventSelect() {
        return this.page.locator('select[name="marketing_event_id"]');
    }

    private get templateSelect() {
        return this.page.locator('select[name="marketing_template_id"]');
    }

    private get channelSelect() {
        return this.page.locator('select[name="channel_id"]');
    }

    private get customerGroupSelect() {
        return this.page.locator('select[name="customer_group_id"]');
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Campaign" });
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

    async createCampaign(data: CampaignData): Promise<void> {
        await this.openCreateForm();
        await this.nameInput.fill(data.name);
        await this.subjectInput.fill(data.subject);
        await this.eventSelect.selectOption({ label: data.eventName });
        await this.templateSelect.selectOption({ label: data.templateName });
        await this.channelSelect.selectOption({ label: "Default" });
        await this.customerGroupSelect.selectOption({ label: "General" });
        await this.setSwitch(this.statusToggle, this.statusInput, true);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Campaign created successfully."),
        ).toBeVisible();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateForm();
        await this.saveButton.click();
    }

    async renameCampaign(name: string, newName: string): Promise<void> {
        await this.openEditForm(name);
        await this.nameInput.fill(newName);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Campaign updated successfully."),
        ).toBeVisible();
    }

    async deleteCampaign(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Campaign deleted successfully");
    }

    async deleteCampaignsIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(names, "Campaign deleted successfully");
    }

    async expectCampaignListed(name: string, subject: string): Promise<void> {
        await this.expectSearchedRowCount(name, 1);

        await expect(this.row(name)).toContainText(subject);
    }

    async expectCampaignAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectNameInEditForm(name: string): Promise<void> {
        await this.openEditForm(name);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectStillOnCreateForm(): Promise<void> {
        await expect(this.page).toHaveURL(/campaigns\/create/);
    }
}
