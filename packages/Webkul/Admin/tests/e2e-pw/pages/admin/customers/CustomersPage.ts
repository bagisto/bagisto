import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../DatagridPage";

export interface CustomerData {
    firstName: string;
    lastName: string;
    email: string;
    phone: string;
    gender: "Male" | "Female" | "Other";
}

export class CustomersPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/customers";
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create Customer" });
    }

    private get firstNameInput() {
        return this.page.locator('input[name="first_name"]');
    }

    private get lastNameInput() {
        return this.page.locator('input[name="last_name"]');
    }

    private get emailInput() {
        return this.page.locator('input[name="email"]');
    }

    private get phoneInput() {
        return this.page.locator('input[name="phone"]');
    }

    private get genderSelect() {
        return this.page.locator('select[name="gender"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save customer" });
    }

    private customerViewLink(email: string) {
        return this.row(email).locator("a.icon-sort-right");
    }

    private async openCreateModal(): Promise<void> {
        await this.openGrid();
        await this.createButton.click();

        await expect(this.firstNameInput).toBeVisible();
    }

    private async fillCreateForm(data: CustomerData): Promise<void> {
        await this.firstNameInput.fill(data.firstName);
        await this.lastNameInput.fill(data.lastName);
        await this.emailInput.fill(data.email);
        await this.phoneInput.fill(data.phone);
        await this.genderSelect.selectOption(data.gender);
    }

    async createCustomer(data: CustomerData): Promise<void> {
        await this.openCreateModal();
        await this.fillCreateForm(data);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Customer created successfully"),
        ).toBeVisible();
    }

    async attemptCreateCustomer(data: CustomerData): Promise<void> {
        await this.openCreateModal();
        await this.fillCreateForm(data);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateModal();
        await this.saveButton.click();
    }

    async openCustomer(email: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(email);
        await this.customerViewLink(email).click();
        await this.waitForVueMount();

        await expect(this.page).toHaveURL(/customers\/view\/\d+/);
    }

    async searchFor(term: string): Promise<void> {
        await super.searchFor(term);
    }

    async massDeleteCustomers(emails: string[]): Promise<void> {
        await this.openGrid();
        await this.selectRows(emails);
        await this.applyMassAction("Delete");

        await expect(
            this.flashMessage("Selected data successfully deleted"),
        ).toBeVisible();
    }

    async massUpdateStatus(
        emails: string[],
        status: "Active" | "Inactive",
    ): Promise<void> {
        await this.openGrid();
        await this.selectRows(emails);
        await this.applyMassAction("Update Status", status);

        await expect(
            this.flashMessage("Selected Customers successfully updated"),
        ).toBeVisible();
    }

    async deleteCustomersIfPresent(emails: string[]): Promise<void> {
        const failures: string[] = [];

        for (const email of emails) {
            try {
                await this.openGrid();
                await this.searchFor(email);

                if (await this.row(email).count()) {
                    await this.selectRows([email]);
                    await this.applyMassAction("Delete");

                    await expect(
                        this.flashMessage("Selected data successfully deleted"),
                    ).toBeVisible();
                }
            } catch (error) {
                failures.push(`${email}: ${error}`);
            }
        }

        if (failures.length) {
            throw new Error(`Cleanup failed for:\n${failures.join("\n")}`);
        }
    }

    async expectCustomerListed(
        email: string,
        fullName: string,
        status: "Active" | "Inactive" = "Active",
    ): Promise<void> {
        await this.expectSearchedRowCount(email, 1);

        await expect(this.row(email)).toContainText(fullName);
        await expect(this.row(email)).toContainText(status);
    }

    async expectCustomerAbsent(email: string): Promise<void> {
        await this.expectSearchedRowCount(email, 0);
    }

    async expectRowVisible(text: string): Promise<void> {
        await expect(this.row(text)).toHaveCount(1);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }
}
