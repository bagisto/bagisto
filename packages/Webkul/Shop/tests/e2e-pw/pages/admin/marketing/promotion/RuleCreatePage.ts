import { expect, Locator, Page } from "@playwright/test";
import { generateName, uniqueStamp } from "../../../../utils/faker";
import { BasePage } from "../../../BasePage";

export interface CreatedRule {
    name: string;
    couponCode: string;
}

export class RuleCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createCartRuleButton() {
        return this.page.getByRole("link", { name: "Create Cart Rule" });
    }

    private get cartRuleForm() {
        return this.page.locator('form[action*="/promotions/cart-rules/create"]');
    }

    private get createCatalogRuleButton() {
        return this.page.getByRole("link", { name: "Create Catalog Rule" });
    }

    private get catalogRuleForm() {
        return this.page.locator('form[action*="/promotions/catalog-rules/create"]');
    }

    private get saveCatalogRuleButton() {
        return this.page.getByRole("button", { name: "Save Catalog Rule" });
    }

    private get nameInput() {
        return this.page.locator("#name");
    }

    private get descriptionInput() {
        return this.page.locator("#description");
    }

    private get couponTypeSelect() {
        return this.page.locator("#coupon_type");
    }

    private get autoGenerationSelect() {
        return this.page.locator("#use_auto_generation");
    }

    private get couponCodeInput() {
        return this.page.getByRole("textbox", { name: "Coupon Code" });
    }

    private get usesPerCouponInput() {
        return this.page.getByRole("textbox", { name: "Uses Per Coupon" });
    }

    private get usesPerCustomerInput() {
        return this.page.getByRole("textbox", { name: "Uses Per Customer" });
    }

    private get addConditionButton() {
        return this.page.locator('div.secondary-button:has-text("Add Condition")');
    }

    private conditionAttributeSelect(index = 0) {
        return this.page.locator(`select[id="conditions[${index}][attribute]"]`);
    }

    private conditionOperatorSelect(index = 0) {
        return this.page.locator(`select[name="conditions[${index}][operator]"]`);
    }

    private conditionValueInput(index = 0) {
        return this.page.locator(`input[name="conditions[${index}][value]"]`);
    }

    private conditionValueSelect(index = 0) {
        return this.page.locator(`select[name="conditions[${index}][value]"]`);
    }

    private get actionTypeSelect() {
        return this.page.locator("#action_type");
    }

    private get discountAmountInput() {
        return this.page.locator('input[name="discount_amount"]');
    }

    private get discountStepInput() {
        return this.page.locator("#discount_step");
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

    private get validationErrors() {
        return this.page.locator("p.text-red-600");
    }

    private get saveCartRuleButton() {
        return this.page.getByRole("button", { name: "Save Cart Rule" });
    }

    private get applyToShippingSelect() {
        return this.page.locator("select[name='apply_to_shipping']");
    }

    private checkboxLabel(forId: string) {
        return this.page.locator(`label[for="${forId}"]`).filter({ hasText: /\S/ });
    }

    private checkboxInput(forId: string) {
        return this.page.locator(`input#${forId.replace(/([^\w-])/g, "\\$1")}`);
    }

    private async check(forId: string): Promise<void> {
        if (!(await this.checkboxInput(forId).isChecked())) {
            await this.checkboxLabel(forId).click();
        }

        await expect(this.checkboxInput(forId)).toBeChecked();
    }

    private async fillGeneralCartDetails(couponCode: string): Promise<string> {
        const name = `${generateName()} ${uniqueStamp()}`;

        await this.createCartRuleButton.click();
        await this.cartRuleForm.waitFor();
        await this.waitForVueMount();
        await this.nameInput.fill(name);
        await this.descriptionInput.fill(generateName());
        await this.couponTypeSelect.selectOption("1");
        await this.autoGenerationSelect.selectOption("0");
        await this.couponCodeInput.fill(couponCode);
        await this.usesPerCouponInput.fill("100");
        await this.usesPerCustomerInput.fill("100");

        return name;
    }

    private async fillGeneralCatalogDetails(): Promise<string> {
        const name = `${generateName()} ${uniqueStamp()}`;

        await this.createCatalogRuleButton.click();
        await this.catalogRuleForm.waitFor();
        await this.waitForVueMount();
        await this.nameInput.fill(name);
        await this.descriptionInput.fill(generateName());

        return name;
    }

    private async configureSettings(): Promise<void> {
        await this.sortOrderInput.fill("1");
        await this.check("channel__1");
        await this.check("customer_group__1");
        await this.check("customer_group__2");
        await this.check("customer_group__3");

        if (!(await this.statusInput.isChecked())) {
            await this.statusToggle.click();
        }

        await expect(this.statusInput).toBeChecked();
    }

    private async centerInViewport(locator: Locator): Promise<void> {
        await locator.evaluate((element) =>
            element.scrollIntoView({ block: "center" }),
        );
    }

    private async setDiscount(actionType: string, amount: number): Promise<void> {
        await this.actionTypeSelect.selectOption(actionType);

        await expect(this.discountAmountInput).toBeVisible();
        await expect(this.discountAmountInput).toBeEditable();

        await this.discountAmountInput.fill(amount.toString());

        await expect(this.discountAmountInput).toHaveValue(amount.toString());
    }

    private async addSkuScope(sku: string): Promise<void> {
        await this.addConditionButton.click();
        await this.conditionAttributeSelect(1).waitFor();
        await this.conditionAttributeSelect(1).selectOption("product|sku");
        await this.conditionOperatorSelect(1).selectOption("==");
        await this.conditionValueInput(1).fill(sku);

        await expect(this.conditionValueInput(1)).toHaveValue(sku);
    }

    async addCondition({
        attribute,
        operator,
        value,
        optionSelect,
        checkboxSelect,
        couponType,
        allowShipping,
        scopeSku,
    }: {
        attribute: string;
        operator: string;
        value?: string;
        optionSelect?: string;
        checkboxSelect?: string;
        couponType?: string;
        allowShipping?: string;
        scopeSku?: string;
    }): Promise<number | undefined> {
        const discountValue = 40;
        const discountPercentage = 15;

        await this.addConditionButton.click();
        await this.conditionAttributeSelect().waitFor();
        await this.conditionAttributeSelect().selectOption(attribute);
        await this.conditionOperatorSelect().selectOption(operator);

        if (optionSelect) {
            await this.conditionValueSelect().waitFor();
            await this.conditionValueSelect().selectOption({ label: optionSelect });
        } else if (value) {
            await this.conditionValueInput().fill(value);
        } else if (checkboxSelect) {
            const label = this.page.locator(`label:has(div:text-is("${checkboxSelect}"))`);
            const input = label.locator("input");

            await expect(input).toBeAttached();

            if (!(await input.isChecked())) {
                await label.click();
            }

            await expect(input).toBeChecked();
        }

        if (scopeSku) {
            await this.addSkuScope(scopeSku);
        }

        let result: number | undefined;

        if (couponType === "fixed") {
            await this.setDiscount("by_fixed", discountValue);
            result = discountValue;
        }

        if (couponType === "percentage") {
            await this.setDiscount("by_percent", discountPercentage);
            result = discountPercentage;
        }

        if (couponType === "fixedAmmountWholeCart") {
            await this.setDiscount("cart_fixed", discountValue);
            result = discountValue;
        }

        if (allowShipping === "yes") {
            await this.applyToShippingSelect.selectOption("1");
        }

        return result;
    }

    async setBuyXGetYAction(discountAmount: number, discountStep: number): Promise<void> {
        await this.actionTypeSelect.selectOption("buy_x_get_y");
        await this.discountAmountInput.fill(discountAmount.toString());
        await this.discountStepInput.fill(discountStep.toString());
    }

    async saveCartRule(): Promise<void> {
        await this.centerInViewport(this.saveCartRuleButton);
        await this.saveCartRuleButton.click();

        await expect(
            this.page.getByText("Cart rule created successfully"),
        ).toBeVisible();
    }

    async saveCatalogRule(): Promise<void> {
        await this.centerInViewport(this.saveCatalogRuleButton);
        await this.saveCatalogRuleButton.click({ timeout: 60000 });

        await expect(
            this.page.getByText("Catalog rule created successfully"),
        ).toBeVisible();
    }

    async cartRuleCreationFlow(): Promise<CreatedRule> {
        const couponCode = `CP${uniqueStamp()}`;

        await this.visit("admin/marketing/promotions/cart-rules");

        const name = await this.fillGeneralCartDetails(couponCode);

        await this.configureSettings();

        return { name, couponCode };
    }

    async catalogRuleCreationFlow(): Promise<CreatedRule> {
        await this.visit("admin/marketing/promotions/catalog-rules");

        const name = await this.fillGeneralCatalogDetails();

        await this.configureSettings();

        return { name, couponCode: "" };
    }

    async saveCartRuleWithoutRequiredFields(): Promise<void> {
        await this.visit("admin/marketing/promotions/cart-rules");
        await this.createCartRuleButton.click();
        await this.cartRuleForm.waitFor();
        await this.saveCartRuleButton.click();
    }

    async saveCatalogRuleWithoutRequiredFields(): Promise<void> {
        await this.visit("admin/marketing/promotions/catalog-rules");
        await this.createCatalogRuleButton.click();
        await this.catalogRuleForm.waitFor();
        await this.saveCatalogRuleButton.click();
    }

    async createFixedCartRuleWithCoupon(
        couponCode: string,
        discountAmount: string = "10",
    ): Promise<string> {
        await this.visit("admin/marketing/promotions/cart-rules");

        const name = await this.fillGeneralCartDetails(couponCode);

        await this.addConditionButton.click();
        await this.conditionAttributeSelect().waitFor();
        await this.conditionAttributeSelect().selectOption("cart_item|quantity");
        await this.conditionOperatorSelect().selectOption(">=");
        await this.conditionValueInput().fill("1");
        await this.setDiscount("by_fixed", Number(discountAmount));
        await this.configureSettings();
        await this.saveCartRule();

        return name;
    }

    async expectRequiredFieldErrors(): Promise<void> {
        await expect(this.validationErrors).not.toHaveCount(0);

        for (const field of ["Name", "Channels", "Customer Groups"]) {
            await expect(
                this.page.getByText(`The ${field} field is required`).first(),
            ).toBeVisible();
        }
    }

    async expectStillOnCreateForm(): Promise<void> {
        await expect(this.page).toHaveURL(/promotions\/(cart|catalog)-rules\/create/);
    }
}
