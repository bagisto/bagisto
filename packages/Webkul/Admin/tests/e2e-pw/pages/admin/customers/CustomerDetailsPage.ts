import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class CustomerDetailsPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get editCustomerLink() {
        return this.page.locator("div.text-blue-600", { hasText: "Edit" });
    }

    private get addAddressLink() {
        return this.page.locator("div.text-blue-600", { hasText: "Create" });
    }

    private get addressEditLinks() {
        return this.page.locator("p", { hasText: "Edit" });
    }

    private get setDefaultAddressButtons() {
        return this.page.locator(
            'button.flex:has-text("Set as Default"):visible',
        );
    }

    private get deleteAddressButton() {
        return this.page.locator("p.cursor-pointer.text-red-600").first();
    }

    private get noteTextarea() {
        return this.page.locator('textarea[name="note"]');
    }

    private get notifyCheckbox() {
        return this.page.locator('input[name="customer_notified"] + span');
    }

    private get submitNoteButton() {
        return this.page.locator(
            'button[type="submit"].secondary-button:visible',
        );
    }

    private get deleteAccountButton() {
        return this.page.locator(".icon-cancel:visible");
    }

    private get createOrderButton() {
        return this.page.locator(".icon-cart:visible");
    }

    private get confirmAgreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    async openFirstCustomerDetails(): Promise<void> {
        await this.visit("admin/customers");
        await this.page.waitForSelector("a.cursor-pointer.icon-sort-right", {
            state: "visible",
        });
        await this.page
            .locator("a.cursor-pointer.icon-sort-right")
            .first()
            .click();
    }

    async openEditCustomer(): Promise<void> {
        await expect(this.editCustomerLink.first()).toBeVisible();
        await this.editCustomerLink.first().click();
    }

    async editCustomerProfile(
        firstName: string,
        lastName: string,
        email: string,
        phone: string,
        gender = "Other",
    ): Promise<void> {
        await this.openEditCustomer();
        await this.page.fill('input[name="first_name"]:visible', firstName);
        await this.page.fill('input[name="last_name"]:visible', lastName);
        await this.page.fill('input[name="email"]:visible', email);
        await this.page.fill('input[name="phone"]:visible', phone);
        await this.page.selectOption('select[name="gender"]:visible', gender);
        await this.page.press('input[name="phone"]:visible', "Enter");
        await expect(
            this.page.getByText("Customer Updated Successfully"),
        ).toBeVisible();
    }

    async addAddress(
        company: string,
        firstName: string,
        lastName: string,
        email: string,
        address: string,
        country: string,
        state: string,
        city: string,
        postcode: string,
        phone: string,
    ): Promise<void> {
        await expect(this.addAddressLink).toBeVisible();
        await this.addAddressLink.click();

        await this.page.fill('input[name="company_name"]', company);
        await this.page.fill('input[name="first_name"]', firstName);
        await this.page.fill('input[name="last_name"]', lastName);
        await this.page.fill('input[name="email"]', email);
        await this.page.fill('input[name="address[0]"]', address);
        await this.page.selectOption('select[name="country"]', country);
        await this.page.selectOption('select[name="state"]', state);
        await this.page.fill('input[name="city"]', city);
        await this.page.fill('input[name="postcode"]', postcode);
        await this.page.fill('input[name="phone"]', phone);
        await this.page.press('input[name="phone"]', "Enter");

        await expect(
            this.page.getByText("Address Created Successfully"),
        ).toBeVisible();
    }

    async editAddress(
        company: string,
        firstName: string,
        lastName: string,
        email: string,
        address: string,
        country: string,
        state: string,
        city: string,
        postcode: string,
        phone: string,
    ): Promise<void> {
        await expect(this.addressEditLinks.first()).toBeVisible();
        await this.addressEditLinks.first().click();

        await this.page.fill('input[name="company_name"]', company);
        await this.page.fill('input[name="first_name"]', firstName);
        await this.page.fill('input[name="last_name"]', lastName);
        await this.page.fill('input[name="email"]', email);
        await this.page.fill('input[name="address[0]"]', address);
        await this.page.selectOption('select[name="country"]', country);
        await this.page.selectOption('select[name="state"]', state);
        await this.page.fill('input[name="city"]', city);
        await this.page.fill('input[name="postcode"]', postcode);
        await this.page.fill('input[name="phone"]', phone);
        await this.page.press('input[name="phone"]', "Enter");

        await expect(
            this.page.getByText("Address Updated Successfully"),
        ).toBeVisible();
    }

    async setDefaultAddress(): Promise<void> {
        await expect(this.setDefaultAddressButtons).toBeVisible();
        const buttons = await this.setDefaultAddressButtons.elementHandles();
        await buttons[buttons.length - 1].click();
        await expect(
            this.page.getByText("Default Address Updated Successfully"),
        ).toBeVisible();
    }

    async deleteAddress(): Promise<void> {
        await expect(this.deleteAddressButton).toBeVisible();
        await this.deleteAddressButton.click();
        await this.page.click(
            'button[type="button"].transparent-button + button[type="button"].primary-button',
        );
        await expect(
            this.page.getByText("Address Deleted Successfully"),
        ).toBeVisible();
    }

    async addNote(note: string): Promise<void> {
        await this.page.waitForSelector('textarea[name="note"]', {
            state: "visible",
        });
        await this.noteTextarea.fill(note);
        await this.notifyCheckbox.click();
        await expect(this.submitNoteButton).toBeVisible({ timeout: 5000 });
        await this.submitNoteButton.click();
        await this.page.waitForTimeout(5000);
        await expect(this.page.getByText(note)).toBeVisible();
    }

    async deleteAccount(): Promise<void> {
        await this.deleteAccountButton.click();
        await this.confirmAgreeButton.click();
        await this.page.waitForSelector("text=Customer Deleted Successfully", {
            timeout: 3000,
        });
        await expect(
            this.page
                .locator("#app")
                .filter({ hasText: "Customer Deleted Successfully" }),
        ).toBeVisible();
    }

    async createOrder(): Promise<void> {
        await this.createOrderButton.click();
        await this.page.click(
            'button[type="button"].transparent-button + button[type="button"].primary-button',
        );
        await expect(this.page.getByText("Cart Items").first()).toBeVisible();
    }
}
