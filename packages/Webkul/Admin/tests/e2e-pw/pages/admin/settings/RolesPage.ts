import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import { generateName, generateDescription } from "../../../utils/faker";

export class RolesPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createRoleButton() {
        return this.page.getByRole("link", { name: "Create Role" });
    }

    private get permissionTypeSelect() {
        return this.page.locator("#permission_type");
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get descriptionTextArea() {
        return this.page.locator('textarea[name="description"]');
    }

    private get saveRoleButton() {
        return this.page.locator(
            'button.primary-button:visible:has-text("Save Role")',
        );
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
        await this.visit("admin/settings/roles");
    }

    async createRole(): Promise<void> {
        await this.open();
        await this.createRoleButton.click();
        await this.permissionTypeSelect.selectOption("all");
        await this.nameInput.fill(generateName());
        await this.descriptionTextArea.fill(generateDescription());
        await this.saveRoleButton.click();

        await expect(
            this.page.getByText("Roles Created Successfully"),
        ).toBeVisible();
    }

    async editFirstRole(): Promise<void> {
        await this.open();
        await this.editIcons.first().waitFor({ state: "visible" });
        await this.editIcons.first().click();
        await this.saveRoleButton.click();

        await expect(
            this.page.getByText("Roles is updated successfully"),
        ).toBeVisible();
    }

    async deleteFirstRole(): Promise<void> {
        await this.open();
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
            this.page.getByText("Roles is deleted successfully"),
        ).toBeVisible();
    }
}
