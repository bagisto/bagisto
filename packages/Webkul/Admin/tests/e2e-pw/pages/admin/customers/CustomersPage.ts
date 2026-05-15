import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class CustomersPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createCustomerButton() {
        return this.page.locator("button.primary-button:visible");
    }

    private get customerDetailIcons() {
        return this.page.locator("a.cursor-pointer.icon-sort-right");
    }

    private get massActionCheckboxes() {
        return this.page.locator(".icon-uncheckbox:visible");
    }

    private get selectActionButton() {
        return this.page.getByRole("button", { name: "Select Action" });
    }

    private get deleteActionLink() {
        return this.page.getByRole("link", { name: "Delete" });
    }

    private get updateStatusLink() {
        return this.page.getByRole("link", { name: "Update Status" });
    }

    private get activeActionLink() {
        return this.page.getByRole("link", { name: "Active" });
    }

    private get inactiveActionLink() {
        return this.page.getByRole("link", { name: "Inactive" });
    }

    async open(): Promise<void> {
        await this.visit("admin/customers");
        await this.waitForLoad();
    }

    async waitForLoad(): Promise<void> {
        await this.page.waitForSelector("button.primary-button:visible", {
            state: "visible",
        });
    }

    async openFirstCustomerDetails(): Promise<void> {
        await this.open();
        const iconCount = await this.customerDetailIcons.count();
        expect(iconCount).toBeGreaterThan(0);
        await this.customerDetailIcons.first().click();
    }

    async createCustomer(
        firstName: string,
        lastName: string,
        email: string,
        gender: string,
        phone: string,
    ): Promise<void> {
        await this.open();
        await this.createCustomerButton.click();

        await this.page.fill('input[name="first_name"]:visible', firstName);
        await this.page.fill('input[name="last_name"]:visible', lastName);
        await this.page.fill('input[name="email"]:visible', email);
        await this.page.selectOption('select[name="gender"]:visible', gender);
        await this.page.fill('input[name="phone"]:visible', phone);
        await this.page.press('input[name="phone"]:visible', "Enter");

        await expect(
            this.page.getByText("Customer created successfully"),
        ).toBeVisible();
    }

    async openMassActionMenu(): Promise<void> {
        await this.open();
        const checkboxCount = await this.massActionCheckboxes.count();
        expect(checkboxCount).toBeGreaterThan(1);
        await this.massActionCheckboxes.nth(1).click();
        await this.selectActionButton.click();
    }

    async deleteSelectedCustomers(): Promise<void> {
        await this.deleteActionLink.click();
    }

    async updateSelectedCustomersStatusTo(
        status: "Active" | "Inactive",
    ): Promise<void> {
        await this.updateStatusLink.hover();
        await this.page.waitForSelector(
            'a:has-text("Active"), a:has-text("Inactive")',
            { state: "visible", timeout: 1000 },
        );
        await this.page.click(`a:has-text("${status}")`, { timeout: 1000 });
    }

    async confirmAgreeDialog(): Promise<void> {
        await this.page.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });
        const agreeButton = this.page.locator(
            'button.primary-button:has-text("Agree")',
        );
        await expect(agreeButton).toBeVisible();
        await agreeButton.click();
    }
}
