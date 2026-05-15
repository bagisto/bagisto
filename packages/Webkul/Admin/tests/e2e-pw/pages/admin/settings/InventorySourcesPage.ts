import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import {
    generateName,
    generateDescription,
    generateSlug,
    generateFullName,
    generateEmail,
    generatePhoneNumber,
} from "../../../utils/faker";

export class InventorySourcesPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createInventorySourceLink() {
        return this.page.getByRole("link", { name: "Create Inventory Source" });
    }

    private get codeInput() {
        return this.page.getByRole("textbox", { name: "Code", exact: true });
    }

    private get nameInput() {
        return this.page.locator("#name");
    }

    private get descriptionInput() {
        return this.page.getByRole("textbox", { name: "Description" });
    }

    private get contactNameInput() {
        return this.page.locator("#contact_name");
    }

    private get emailInput() {
        return this.page.getByRole("textbox", { name: "Email" });
    }

    private get contactNumberInput() {
        return this.page.getByRole("textbox", { name: "Contact Number" });
    }

    private get faxInput() {
        return this.page.getByRole("textbox", { name: "Fax" });
    }

    private get countrySelect() {
        return this.page.locator("#country");
    }

    private get stateSelect() {
        return this.page.locator("#state");
    }

    private get cityInput() {
        return this.page.getByRole("textbox", { name: "City" });
    }

    private get streetInput() {
        return this.page.getByRole("textbox", { name: "Street" });
    }

    private get postcodeInput() {
        return this.page.getByRole("textbox", { name: "Postcode" });
    }

    private get statusLabel() {
        return this.page.locator('label[for="status"]');
    }

    private get statusInput() {
        return this.page.getByPlaceholder("Status");
    }

    private get saveInventorySourcesButton() {
        return this.page.getByRole("button", {
            name: "Save Inventory Sources",
        });
    }

    private get editIcons() {
        return this.page.locator("span.cursor-pointer.icon-edit");
    }

    private get deleteIcons() {
        return this.page.locator("span.cursor-pointer.icon-delete");
    }

    private get agreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
    }

    async open(): Promise<void> {
        await this.visit("admin/settings/inventory-sources");
    }

    async createInventorySource(): Promise<void> {
        await this.open();
        await this.createInventorySourceLink.click();
        await this.page.waitForSelector(
            'form[action*="/settings/inventory-sources/create"]',
        );
        await this.codeInput.fill(generateSlug("_"));
        await this.nameInput.fill(generateName());
        await this.descriptionInput.fill(generateDescription());
        await this.contactNameInput.fill(generateFullName());
        await this.emailInput.fill(generateEmail());
        await this.contactNumberInput.fill(generatePhoneNumber());
        await this.faxInput.fill(generatePhoneNumber());
        await this.countrySelect.selectOption("IN");
        await this.stateSelect.selectOption("DL");
        await this.cityInput.fill("New Delhi");
        await this.streetInput.fill("Dwarka");
        await this.postcodeInput.fill("110045");
        // Clicking the status and verify the toggle state.
        await this.statusLabel.click();
        const toggleInput = this.statusInput;
        await expect(toggleInput).toBeChecked();
        await this.saveInventorySourcesButton.click();

        await expect(
            this.page.getByText("Inventory Source Created Successfully"),
        ).toBeVisible();
    }

    async editFirstInventorySource(): Promise<void> {
        await this.open();
        await this.createInventorySourceLink.waitFor({ state: "visible" });
        await this.editIcons.first().waitFor({ state: "visible" });
        await this.editIcons.first().click();

        await this.page.waitForSelector(
            'form[action*="/settings/inventory-sources/edit"]',
        );
        await this.page.click('button:has-text("Save Inventory Sources")');

        await expect(
            this.page.getByText("Inventory Sources Updated Successfully"),
        ).toBeVisible();
    }

    async deleteFirstInventorySource(): Promise<void> {
        await this.open();
        await this.createInventorySourceLink.waitFor({ state: "visible" });
        await this.deleteIcons.first().waitFor({ state: "visible" });
        await this.deleteIcons.first().click();

        await this.page.waitForSelector("text=Are you sure");
        const agreeButton = this.agreeButton;

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            this.page.getByText("Inventory Sources Deleted Successfully"),
        ).toBeVisible();
    }
}
