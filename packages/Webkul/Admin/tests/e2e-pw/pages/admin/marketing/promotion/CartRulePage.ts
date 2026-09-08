import { expect, type Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";
import { generateName, uniqueStamp } from "../../../../utils/faker";

export interface CartRuleData {
    name: string;
    couponCode: string;
    discountPercent: number;
}

export function buildCartRule(overrides: Partial<CartRuleData> = {}): CartRuleData {
    const stamp = uniqueStamp();

    return {
        name: `${generateName()} ${stamp}`,
        couponCode: `CART${stamp}`,
        discountPercent: 10,
        ...overrides,
    };
}

export class CartRulePage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/marketing/promotions/cart-rules";
    }

    private get createLink() {
        return this.page.getByRole("link", { name: "Create Cart Rule" });
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get descriptionInput() {
        return this.page.locator('textarea[name="description"]');
    }

    private get couponTypeSelect() {
        return this.page.locator('select[name="coupon_type"]');
    }

    private get autoGenerationSelect() {
        return this.page.locator('select[name="use_auto_generation"]');
    }

    private get couponCodeInput() {
        return this.page.locator('input[name="coupon_code"]');
    }

    private get actionTypeSelect() {
        return this.page.locator('select[name="action_type"]');
    }

    private get discountAmountInput() {
        return this.page.locator('input[name="discount_amount"]');
    }

    private get sortOrderInput() {
        return this.page.locator('input[name="sort_order"]');
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Cart Rule" });
    }

    private channelOption(id: number) {
        return this.checkboxLabel(`channel__${id}`);
    }

    private customerGroupOption(id: number) {
        return this.checkboxLabel(`customer_group__${id}`);
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

    private async fillCreateForm(data: CartRuleData): Promise<void> {
        await this.nameInput.fill(data.name);
        await this.descriptionInput.fill(`Cart rule ${data.name}`);
        await this.couponTypeSelect.selectOption("1");
        await this.autoGenerationSelect.selectOption("0");
        await this.couponCodeInput.fill(data.couponCode);
        await this.actionTypeSelect.selectOption("by_percent");
        await this.discountAmountInput.fill(`${data.discountPercent}`);
        await this.sortOrderInput.fill("1");
        await this.channelOption(1).click();
        await this.customerGroupOption(1).click();
        await this.customerGroupOption(2).click();
        await this.setSwitch(this.statusToggle, this.statusInput, true);
    }

    async createCartRule(data: CartRuleData): Promise<CartRuleData> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Cart rule created successfully"),
        ).toBeVisible();

        return data;
    }

    async createCouponPercentageRule(
        couponCode: string,
        discountPercent: number,
    ): Promise<CartRuleData> {
        return this.createCartRule(buildCartRule({ couponCode, discountPercent }));
    }

    async attemptCreateCartRule(data: CartRuleData): Promise<void> {
        await this.openCreateForm();
        await this.fillCreateForm(data);
        await this.saveButton.click();
    }

    async submitEmptyCreateForm(): Promise<void> {
        await this.openCreateForm();
        await this.saveButton.click();
    }

    async renameCartRule(name: string, newName: string): Promise<void> {
        await this.openEditForm(name);
        await this.nameInput.fill(newName);
        await this.saveButton.click();

        await expect(
            this.flashMessage("Cart rule updated successfully"),
        ).toBeVisible();
    }

    async deleteCartRule(name: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(name);
        await this.deleteRow(name, "Cart Rule Deleted Successfully");
    }

    async deleteCartRulesIfPresent(names: string[]): Promise<void> {
        await this.deleteRowsIfPresent(names, "Cart Rule Deleted Successfully");
    }

    async expectCartRuleListed(data: CartRuleData): Promise<void> {
        await this.expectSearchedRowCount(data.name, 1);

        await expect(this.row(data.name)).toContainText(data.couponCode);
        await expect(this.row(data.name)).toContainText("Active");
    }

    async expectCartRuleAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }

    async expectCouponCodeListedOnce(couponCode: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(couponCode);

        await expect(this.rowWithCell(couponCode)).toHaveCount(1);
    }

    async expectNameInEditForm(name: string): Promise<void> {
        await this.openEditForm(name);
    }

    async expectValidationError(message: string): Promise<void> {
        await this.expectValidationMessage(message);
    }

    async expectStillOnCreateForm(): Promise<void> {
        await expect(this.page).toHaveURL(/cart-rules\/create/);
    }
}
