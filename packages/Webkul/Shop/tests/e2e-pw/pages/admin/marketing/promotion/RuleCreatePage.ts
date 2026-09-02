import { expect, Locator, Page } from "@playwright/test";
import { generateName } from "../../../../utils/faker";
import { BasePage } from "../../../BasePage";

export class RuleCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createCartRuleButton() {
        return this.page.locator(
            'a.primary-button:has-text("Create Cart Rule")',
        );
    }

    private get cartRuleForm() {
        return this.page.locator(
            'form[action*="/promotions/cart-rules/create"]',
        );
    }

    private get createCatalogRuleButton() {
        return this.page.locator(
            'a.primary-button:has-text("Create Catalog Rule")',
        );
    }

    private get catalogRuleButton() {
        return this.page.locator(
            'button.primary-button:has-text("Save Catalog Rule")',
        );
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
        return this.page.locator(
            'div.secondary-button:has-text("Add Condition")',
        );
    }

    private get conditionAttributeSelect() {
        return this.page.locator(
            'select[id="conditions\\[0\\]\\[attribute\\]"]',
        );
    }

    private get conditionOperatorSelect() {
        return this.page.locator(
            'select[name="conditions\\[0\\]\\[operator\\]"]',
        );
    }

    private get conditionValueInput() {
        return this.page.locator('input[name="conditions\\[0\\]\\[value\\]"]');
    }

    private get selectConditionOption() {
        return this.page.locator('select[name="conditions[0][value]"]');
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

    private get channelCheckbox() {
        return this.page.locator('label[for="channel__1"]');
    }

    private get customerGroupCheckbox() {
        return this.page.locator("#customer_group__1");
    }

    private get customerGroupCheckbox2() {
        return this.page.locator('label[for="customer_group__2"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get validationErrors() {
        return this.page.locator("p.text-red-600");
    }

    private get saveCartRuleButton() {
        return this.page.locator(
            'button.primary-button:has-text("Save Cart Rule")',
        );
    }

    private get successMessage() {
        return this.page.locator("#app");
    }

    private get applyToShipping() {
        return this.page.locator("select[name='apply_to_shipping']");
    }

    private async fillGeneralCartDetails() {
        await this.createCartRuleButton.waitFor();
        await this.createCartRuleButton.click();
        await this.cartRuleForm.waitFor();
        await this.nameInput.fill(generateName());
        await this.descriptionInput.fill(generateName());
        await this.couponTypeSelect.selectOption("1");
        await this.autoGenerationSelect.selectOption("0");
        await this.couponCodeInput.fill("TEST50");
        await this.usesPerCouponInput.fill("100");
        await this.usesPerCustomerInput.fill("100");
    }

    private async fillGeneralCatalogDetails() {
        await this.createCatalogRuleButton.click();
        await this.nameInput.fill(generateName());
        await this.descriptionInput.fill(generateName());
    }

    private async configureSettings() {
        await this.sortOrderInput.fill("1");
        await this.channelCheckbox.first().click();
        await this.customerGroupCheckbox.nth(1).click();
        await this.customerGroupCheckbox2.first().click();
        await this.statusToggle.first().click();
    }

    private async centerInViewport(locator: Locator) {
        await locator.evaluate((element) =>
            element.scrollIntoView({ block: "center" }),
        );
    }

    public async addCondition({
        attribute,
        operator,
        value,
        optionSelect,
        checkboxSelect,
        couponType,
        allowShipping,
    }: {
        attribute: string;
        operator: string;
        value?: string;
        optionSelect?: string;
        checkboxSelect?: string;
        couponType?: string;
        allowShipping?: string;
    }): Promise<number | undefined> {
        const discountValue = Math.floor(Math.random() * 1000) + 1;
        const discountPercentage = Math.floor(Math.random() * 99) + 1;

        await this.addConditionButton.click();
        await this.conditionAttributeSelect.waitFor();
        await this.conditionAttributeSelect.selectOption(attribute);
        await this.conditionOperatorSelect.selectOption(operator);

        if (optionSelect) {
            await this.selectConditionOption.waitFor();
            await this.selectConditionOption.selectOption({
                label: optionSelect,
            });
        } else if (value) {
            await this.conditionValueInput.fill(value);
        } else if (checkboxSelect) {
            const label = this.page.locator(
                `label:has(div:text-is("${checkboxSelect}"))`,
            );
            const input = label.locator("input");
            await expect(input).toBeAttached();
            const isChecked = await input.isChecked();
            if (!isChecked) {
                await label.click();
            }
        }
        let result;

        if (couponType == "fixed") {
            await this.actionTypeSelect.selectOption("by_fixed");
            await this.discountAmountInput.waitFor({
                state: "visible",
            });
            await this.page.waitForTimeout(1000);
            await this.discountAmountInput.click();
            await this.discountAmountInput.clear();
            await this.discountAmountInput.fill(discountValue.toString());
            result = discountValue;
        }

        if (couponType == "percentage") {
            await this.actionTypeSelect.selectOption("by_percent");
            await this.discountAmountInput.waitFor({
                state: "visible",
            });
            await this.page.waitForTimeout(1000);
            await this.discountAmountInput.click();
            await this.discountAmountInput.clear();
            await this.discountAmountInput.fill(discountPercentage.toString());
            result = discountPercentage;
        }

        if (couponType == "fixedAmmountWholeCart") {
            await this.actionTypeSelect.selectOption("cart_fixed");
            await this.discountAmountInput.waitFor({
                state: "visible",
            });
            await this.page.waitForTimeout(1000);
            await this.discountAmountInput.click();
            await this.discountAmountInput.clear();
            await this.discountAmountInput.fill(discountValue.toString());
            result = discountValue;
        }

        if (allowShipping == "yes") {
            await this.applyToShipping.selectOption("1");
        }

        return result;
    }

    public async setBuyXGetYAction(
        discountAmount: number,
        discountStep: number,
    ) {
        await this.actionTypeSelect.selectOption("buy_x_get_y");
        await this.discountAmountInput.fill(discountAmount.toString());
        await this.discountStepInput.fill(discountStep.toString());
    }

    public async saveCartRule() {
        await this.centerInViewport(this.saveCartRuleButton);
        await this.saveCartRuleButton.click();
        await expect(this.successMessage).toContainText(
            "Cart rule created successfully",
        );
    }

    public async saveCatalogRule() {
        await this.centerInViewport(this.catalogRuleButton);
        await this.catalogRuleButton.click({ timeout: 60000 });

        await expect(this.successMessage).toContainText(
            "Catalog rule created successfully",
        );
    }

    public async cartRuleCreationFlow() {
        await this.visit("admin/marketing/promotions/cart-rules");
        await this.fillGeneralCartDetails();
        await this.configureSettings();
    }

    public async catalogRuleCreationFlow() {
        await this.visit("admin/marketing/promotions/catalog-rules");
        await this.fillGeneralCatalogDetails();
        await this.configureSettings();
    }

    public async saveCartRuleWithoutRequiredFields() {
        await this.visit("admin/marketing/promotions/cart-rules");
        await this.createCartRuleButton.click();
        await this.cartRuleForm.waitFor();
        await this.saveCartRuleButton.click();
    }

    public async saveCatalogRuleWithoutRequiredFields() {
        await this.visit("admin/marketing/promotions/catalog-rules");
        await this.createCatalogRuleButton.click();
        await this.page.waitForLoadState("networkidle");
        await this.catalogRuleButton.click();
    }

    public async expectRequiredFieldErrors() {
        await expect(this.validationErrors).not.toHaveCount(0);

        for (const field of ["Name", "Channels", "Customer Groups"]) {
            await expect(
                this.page.getByText(`The ${field} field is required`).first(),
            ).toBeVisible();
        }
    }
}
