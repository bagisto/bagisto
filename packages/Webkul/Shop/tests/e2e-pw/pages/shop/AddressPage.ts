import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class AddressPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    // Locators
    private get addAddressButton() {
        return this.page.getByRole("link", { name: "Add Address" });
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
        return this.page.locator("#state");
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

    private get moreOptionsButton() {
        return this.page.locator(".icon-more").first();
    }

    private get editLink() {
        return this.page.getByRole("link", { name: "Edit" });
    }

    private get deleteLink() {
        return this.page.getByRole("link", { name: "Delete" });
    }

    private get setDefaultButton() {
        return this.page.getByRole("button", { name: "Set as Default" });
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get successMessage() {
        return this.page.getByText("Address updated successfully.").first();
    }

    private get createdMessage() {
        return this.page
            .getByText("Address have been successfully added.")
            .first();
    }

    private get deletedMessage() {
        return this.page.getByText("Address successfully deleted").first();
    }

    private get defaultAddressText() {
        return this.page.getByText("Default Address").first();
    }

    async addAddress(data: {
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
    }): Promise<void> {
        await this.addAddressButton.click();
        await this.page.waitForLoadState("networkidle");

        if (data.companyName) {
            await this.companyNameInput.click();
            await this.companyNameInput.fill(data.companyName);
        }

        await this.firstNameInput.click();
        await this.firstNameInput.fill(data.firstName);

        await this.lastNameInput.click();
        await this.lastNameInput.fill(data.lastName);

        await this.emailInput.click();
        await this.emailInput.fill(data.email);

        if (data.vatId) {
            await this.vatIdInput.click();
            await this.vatIdInput.fill(data.vatId);
        }

        await this.streetAddressInput.click();
        await this.streetAddressInput.fill(data.streetAddress);

        await this.countrySelect.selectOption(data.country);
        await this.stateSelect.selectOption(data.state);

        await this.cityInput.click();
        await this.cityInput.fill(data.city);

        await this.postCodeInput.click();
        await this.postCodeInput.fill(data.postCode);

        await this.phoneInput.click();
        await this.phoneInput.fill(data.phone);

        await this.saveButton.click();
        await expect(this.createdMessage.first()).toBeVisible();
    }

    async editAddress(data: {
        companyName?: string;
        firstName?: string;
        lastName?: string;
        email?: string;
        streetAddress?: string;
        country?: string;
        state?: string;
        city?: string;
        postCode?: string;
        phone?: string;
    }): Promise<void> {
        await this.moreOptionsButton.click();
        await this.editLink.click();

        if (data.companyName) {
            await this.companyNameInput.click();
            await this.companyNameInput.clear();
            await this.companyNameInput.fill(data.companyName);
        }

        if (data.firstName) {
            await this.firstNameInput.click();
            await this.firstNameInput.clear();
            await this.firstNameInput.fill(data.firstName);
        }

        if (data.lastName) {
            await this.lastNameInput.click();
            await this.lastNameInput.clear();
            await this.lastNameInput.fill(data.lastName);
        }

        if (data.email) {
            await this.emailInput.click();
            await this.emailInput.clear();
            await this.emailInput.fill(data.email);
        }

        if (data.streetAddress) {
            await this.streetAddressInput.click();
            await this.streetAddressInput.clear();
            await this.streetAddressInput.fill(data.streetAddress);
        }

        if (data.country) {
            await this.countrySelect.selectOption(data.country);
        }

        if (data.state) {
            await this.stateSelect.selectOption(data.state);
        }

        if (data.city) {
            await this.cityInput.click();
            await this.cityInput.clear();
            await this.cityInput.fill(data.city);
        }

        if (data.postCode) {
            await this.postCodeInput.click();
            await this.postCodeInput.clear();
            await this.postCodeInput.fill(data.postCode);
        }

        if (data.phone) {
            await this.phoneInput.click();
            await this.phoneInput.clear();
            await this.phoneInput.fill(data.phone);
        }

        await this.updateButton.click();
        await expect(this.successMessage).toBeVisible();
    }

    async setDefaultAddress(): Promise<void> {
        await this.moreOptionsButton.click();
        await this.setDefaultButton.click();
        await this.agreeButton.click();
        await expect(this.defaultAddressText.first()).toBeVisible();
    }

    async deleteAddress(): Promise<void> {
        await this.moreOptionsButton.click();
        await this.deleteLink.click();
        await this.agreeButton.click();
        await expect(this.deletedMessage.first()).toBeVisible();
    }
}
