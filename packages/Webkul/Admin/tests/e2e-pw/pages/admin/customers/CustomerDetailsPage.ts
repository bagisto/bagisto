import { expect, type Locator, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export interface CustomerAddressData {
    firstName: string;
    lastName: string;
    email: string;
    street: string;
    city: string;
    postcode: string;
    phone: string;
}

export class CustomerDetailsPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get customerCardHeader() {
        return this.page.locator('div:has(> p:text-is("Customer"))');
    }

    private get editCustomerTrigger() {
        return this.customerCardHeader.getByText("Edit", { exact: true });
    }

    private get createAddressTrigger() {
        return this.page.getByText("Create", { exact: true });
    }

    private modalForm(title: string): Locator {
        return this.page
            .locator("form")
            .filter({ has: this.page.locator(`p:text-is("${title}")`) });
    }

    private get customerForm() {
        return this.modalForm("Edit Customer");
    }

    private get createAddressForm() {
        return this.modalForm("Create Address");
    }

    private get editAddressForm() {
        return this.modalForm("Edit Address");
    }

    private firstNameInput(form: Locator) {
        return form.locator('input[name="first_name"]');
    }

    private lastNameInput(form: Locator) {
        return form.locator('input[name="last_name"]');
    }

    private emailInput(form: Locator) {
        return form.locator('input[name="email"]');
    }

    private phoneInput(form: Locator) {
        return form.locator('input[name="phone"]');
    }

    private streetInput(form: Locator) {
        return form.locator('input[name="address[0]"]');
    }

    private countrySelect(form: Locator) {
        return form.locator('select[name="country"]');
    }

    private stateSelect(form: Locator) {
        return form.locator('select[name="state"]');
    }

    private cityInput(form: Locator) {
        return form.locator('input[name="city"]');
    }

    private postcodeInput(form: Locator) {
        return form.locator('input[name="postcode"]');
    }

    private get saveCustomerButton() {
        return this.customerForm.getByRole("button", { name: "Save customer" });
    }

    private saveAddressButton(form: Locator) {
        return form.getByRole("button", { name: "Save Address" });
    }

    private get noteInput() {
        return this.page.locator('textarea[name="note"]');
    }

    private get submitNoteButton() {
        return this.page.getByRole("button", { name: "Submit Note" });
    }

    private get deleteAccountTrigger() {
        return this.page.getByText("Delete Account", { exact: true });
    }

    private get createOrderTrigger() {
        return this.page.getByText("Create Order", { exact: true });
    }

    private addressCard(street: string): Locator {
        return this.page.locator(`div:has(> p:has-text("${street}"))`);
    }

    private async fillAddressForm(
        form: Locator,
        data: CustomerAddressData,
    ): Promise<void> {
        await this.firstNameInput(form).fill(data.firstName);
        await this.lastNameInput(form).fill(data.lastName);
        await this.emailInput(form).fill(data.email);
        await this.streetInput(form).fill(data.street);
        await this.countrySelect(form).selectOption("IN");
        await this.stateSelect(form).selectOption("UP");
        await this.cityInput(form).fill(data.city);
        await this.postcodeInput(form).fill(data.postcode);
        await this.phoneInput(form).fill(data.phone);
    }

    async updateProfile(changes: {
        firstName: string;
        lastName: string;
    }): Promise<void> {
        await this.editCustomerTrigger.click();
        await expect(this.firstNameInput(this.customerForm)).toBeVisible();
        await this.firstNameInput(this.customerForm).fill(changes.firstName);
        await this.lastNameInput(this.customerForm).fill(changes.lastName);
        await this.waitForBackgroundRequestsToSettle();
        await this.saveCustomerButton.click();

        await expect(
            this.page.getByText("Customer Updated Successfully"),
        ).toBeVisible();
    }

    async addAddress(data: CustomerAddressData): Promise<void> {
        await this.createAddressTrigger.click();
        await expect(this.streetInput(this.createAddressForm)).toBeVisible();
        await this.fillAddressForm(this.createAddressForm, data);
        await this.waitForBackgroundRequestsToSettle();
        await this.saveAddressButton(this.createAddressForm).click();

        await expect(
            this.page.getByText("Address Created Successfully"),
        ).toBeVisible();
    }

    async updateAddressStreet(street: string, newStreet: string): Promise<void> {
        await this.addressCard(street).getByText("Edit", { exact: true }).click();
        await expect(this.streetInput(this.editAddressForm)).toHaveValue(street);
        await this.streetInput(this.editAddressForm).fill(newStreet);
        await this.waitForBackgroundRequestsToSettle();
        await this.saveAddressButton(this.editAddressForm).click();

        await expect(
            this.page.getByText("Address Updated Successfully"),
        ).toBeVisible();
    }

    async setDefaultAddress(street: string): Promise<void> {
        await this.waitForBackgroundRequestsToSettle();
        await this.addressCard(street)
            .getByRole("button", { name: "Set as Default" })
            .click();

        await expect(
            this.page.getByText("Default Address Updated Successfully"),
        ).toBeVisible();
    }

    async deleteAddress(street: string): Promise<void> {
        await this.waitForBackgroundRequestsToSettle();
        await this.addressCard(street).getByText("Delete", { exact: true }).click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Address Deleted Successfully"),
        ).toBeVisible();
    }

    async addNote(note: string): Promise<void> {
        await this.noteInput.fill(note);
        await this.waitForBackgroundRequestsToSettle();
        await this.submitNoteButton.click();

        await expect(
            this.page.getByText("Note Created Successfully"),
        ).toBeVisible();
    }

    async deleteAccount(): Promise<void> {
        await this.waitForBackgroundRequestsToSettle();
        await this.deleteAccountTrigger.click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Customer Deleted Successfully"),
        ).toBeVisible();
    }

    async createOrder(): Promise<void> {
        await this.createOrderTrigger.click();
        await this.agreeButton.click();

        await expect(this.page).toHaveURL(/sales\/orders\/create\/\d+/);
    }

    async reload(): Promise<void> {
        await this.page.reload();
        await this.waitForVueMount();
    }

    async expectCustomerName(fullName: string): Promise<void> {
        await expect(
            this.page.getByRole("heading", { name: fullName, exact: true }),
        ).toBeVisible();
    }

    async expectAddressShown(data: CustomerAddressData): Promise<void> {
        const card = this.addressCard(data.street);

        await expect(card).toHaveCount(1);
        await expect(card).toContainText(`${data.firstName} ${data.lastName}`);
        await expect(card).toContainText(data.city);
        await expect(card).toContainText(data.postcode);
    }

    async expectAddressAbsent(street: string): Promise<void> {
        await expect(this.addressCard(street)).toHaveCount(0);
    }

    async expectDefaultAddress(street: string): Promise<void> {
        await expect(
            this.addressCard(street).getByText("Default Address", { exact: true }),
        ).toBeVisible();
    }

    async expectNotDefaultAddress(street: string): Promise<void> {
        await expect(
            this.addressCard(street).getByText("Default Address", { exact: true }),
        ).toHaveCount(0);
    }

    async expectNoteShown(note: string): Promise<void> {
        await expect(this.page.getByText(note)).toBeVisible();
    }

    async expectOrderCreationStarted(): Promise<void> {
        await expect(this.page.getByText("Cart Items").first()).toBeVisible();
    }

    async expectValidationError(message: string): Promise<void> {
        await expect(
            this.page.locator("p.text-red-600").filter({ hasText: message }),
        ).toBeVisible();
    }
}
