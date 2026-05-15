import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class CustomerGroupsPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createButton() {
        return this.page.locator("button.primary-button:visible");
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get codeInput() {
        return this.page.locator('input[name="code"]');
    }

    private get iconEdit() {
        return this.page.locator("span.cursor-pointer.icon-edit");
    }

    private get iconDelete() {
        return this.page.locator("span.cursor-pointer.icon-delete");
    }

    private get confirmDeleteButton() {
        return this.page.locator(
            "button.transparent-button + button.primary-button:visible",
        );
    }

    async open(): Promise<void> {
        await this.visit("admin/customers/groups");
        await this.page.waitForSelector("button.primary-button:visible", {
            state: "visible",
        });
    }

    async createGroup(name: string, code: string): Promise<void> {
        await this.open();
        await this.createButton.click();
        await this.nameInput.fill(name);
        await this.codeInput.fill(code);
        await this.page.press('input[name="code"]:visible', "Enter");
        await expect(
            this.page.getByText("Group created successfully"),
        ).toBeVisible();
    }

    async editFirstGroup(name: string, code: string): Promise<void> {
        await this.open();
        await expect(this.iconEdit.first()).toBeVisible();
        await this.iconEdit.first().click();
        await this.nameInput.fill(name);
        await this.codeInput.fill(code);
        await this.page.press('input[name="code"]:visible', "Enter");
        await expect(
            this.page.getByText("Group Updated Successfully"),
        ).toBeVisible();
    }

    async deleteFirstGroup(): Promise<void> {
        await this.open();
        await expect(this.iconDelete.first()).toBeVisible();
        await this.iconDelete.first().click();
        await this.confirmDeleteButton.click();
        await expect(
            this.page.getByText("Group Deleted Successfully"),
        ).toBeVisible();
    }
}
