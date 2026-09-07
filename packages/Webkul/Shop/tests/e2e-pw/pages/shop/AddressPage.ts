import { expect, type Locator, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export interface AddressData {
    companyName?: string;
    firstName: string;
    lastName: string;
    email: string;
    streetAddress: string;
    country: string;
    state: string;
    city: string;
    postCode: string;
    phone: string;
    vatId?: string;
}

export class AddressPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get companyNameInput() {
        return this.page.getByPlaceholder("Company Name");
    }

    private get firstNameInput() {
        return this.page.getByPlaceholder("First Name");
    }

    private get lastNameInput() {
        return this.page.getByPlaceholder("Last Name");
    }

    private get emailInput() {
        return this.page.getByPlaceholder("Email", { exact: true });
    }

    private get vatIdInput() {
        return this.page.getByPlaceholder("Vat ID");
    }

    private get streetAddressInput() {
        return this.page.getByPlaceholder("Street Address");
    }

    private get countrySelect() {
        return this.page.getByLabel("Country");
    }

    private get stateSelect() {
        return this.page.locator("select#state");
    }

    private get stateInput() {
        return this.page.locator("input#state");
    }

    private get cityInput() {
        return this.page.getByPlaceholder("City");
    }

    private get postCodeInput() {
        return this.page.getByPlaceholder("Post Code");
    }

    private get phoneInput() {
        return this.page.getByPlaceholder("Phone");
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save" });
    }

    private get updateButton() {
        return this.page.getByRole("button", { name: "Update" });
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private addressCard(street: string): Locator {
        return this.page
            .locator("div.rounded-xl.border")
            .filter({ hasText: street });
    }

    private async fillForm(data: Partial<AddressData>): Promise<void> {
        if (data.companyName !== undefined) {
            await this.companyNameInput.fill(data.companyName);
        }

        if (data.firstName !== undefined) {
            await this.firstNameInput.fill(data.firstName);
        }

        if (data.lastName !== undefined) {
            await this.lastNameInput.fill(data.lastName);
        }

        if (data.email !== undefined) {
            await this.emailInput.fill(data.email);
        }

        if (data.vatId !== undefined) {
            await this.vatIdInput.fill(data.vatId);
        }

        if (data.streetAddress !== undefined) {
            await this.streetAddressInput.fill(data.streetAddress);
        }

        if (data.country !== undefined) {
            await this.countrySelect.selectOption(data.country);
        }

        if (data.state !== undefined) {
            if (await this.stateSelect.count()) {
                await this.stateSelect.selectOption(data.state);
            } else {
                await this.stateInput.fill(data.state);
            }
        }

        if (data.city !== undefined) {
            await this.cityInput.fill(data.city);
        }

        if (data.postCode !== undefined) {
            await this.postCodeInput.fill(data.postCode);
        }

        if (data.phone !== undefined) {
            await this.phoneInput.fill(data.phone);
        }
    }

    async open(): Promise<void> {
        await this.visit("customer/account/addresses");

        await expect(this.page).toHaveURL(/customer\/account\/addresses/);
    }

    async addAddress(data: AddressData): Promise<void> {
        await this.visit("customer/account/addresses/create");
        await this.fillForm(data);
        await this.saveButton.click();

        await expect(
            this.page.getByText("Address have been successfully added.").first(),
        ).toBeVisible();
    }

    async submitEmptyAddress(): Promise<void> {
        await this.visit("customer/account/addresses/create");
        await this.saveButton.click();
    }

    async editAddress(street: string, changes: Partial<AddressData>): Promise<void> {
        await this.open();
        await this.addressCard(street).getByLabel("More Options").click();
        await this.addressCard(street).getByRole("link", { name: "Edit" }).click();

        await expect(this.streetAddressInput).toHaveValue(street);

        await this.fillForm(changes);
        await this.updateButton.click();

        await expect(
            this.page.getByText("Address updated successfully.").first(),
        ).toBeVisible();
    }

    async setDefaultAddress(street: string): Promise<void> {
        await this.open();
        await this.addressCard(street).getByLabel("More Options").click();
        await this.addressCard(street)
            .getByRole("button", { name: "Set as Default" })
            .click();
        await this.agreeButton.click();

        await expect(this.addressCard(street)).toContainText("Default Address");
    }

    async deleteAddress(street: string): Promise<void> {
        await this.open();
        await this.addressCard(street).getByLabel("More Options").click();
        await this.addressCard(street).getByRole("button", { name: "Delete" }).click();
        await this.agreeButton.click();

        await expect(this.page.getByText("Address successfully deleted").first()).toBeVisible();
    }

    async expectAddressListed(data: AddressData): Promise<void> {
        await this.open();

        const card = this.addressCard(data.streetAddress);

        await expect(card).toHaveCount(1);
        await expect(card).toContainText(`${data.firstName} ${data.lastName}`);
        await expect(card).toContainText(data.city);
        await expect(card).toContainText(data.postCode);
    }

    async expectAddressAbsent(street: string): Promise<void> {
        await this.open();

        await expect(this.addressCard(street)).toHaveCount(0);
    }

    async expectDefaultAddress(street: string): Promise<void> {
        await this.open();

        await expect(this.addressCard(street)).toContainText("Default Address");
    }

    async expectNotDefaultAddress(street: string): Promise<void> {
        await this.open();

        await expect(this.addressCard(street)).not.toContainText("Default Address");
    }

    async expectValidationError(message: string): Promise<void> {
        await expect(this.page.getByText(message).first()).toBeVisible();
        await expect(this.page).toHaveURL(/addresses\/create/);
    }
}
