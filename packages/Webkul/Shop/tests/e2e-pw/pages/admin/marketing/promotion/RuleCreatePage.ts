import { expect, Page } from "@playwright/test";
import { generateName } from "../../../../utils/faker";
import { BasePage } from "../../../BasePage";

export class RuleCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    get createCartRuleButton() {
        return this.page.locator(
            'a.primary-button:has-text("Create Cart Rule")',
        );
    }

    get cartRuleForm() {
        return this.page.locator(
            'form[action*="/promotions/cart-rules/create"]',
        );
    }

    get createCatalogRuleButton() {
        return this.page.locator(
            'a.primary-button:has-text("Create Catalog Rule")',
        );
    }

    get catalogRuleButton() {
        return this.page.locator(
            'button.primary-button:has-text("Save Catalog Rule")',
        );
    }

    // General
    get nameInput() {
        return this.page.locator("#name");
    }

    get descriptionInput() {
        return this.page.locator("#description");
    }

    get couponTypeSelect() {
        return this.page.locator("#coupon_type");
    }

    get autoGenerationSelect() {
        return this.page.locator("#use_auto_generation");
    }

    get couponCodeInput() {
        return this.page.getByRole("textbox", { name: "Coupon Code" });
    }

    get usesPerCouponInput() {
        return this.page.getByRole("textbox", { name: "Uses Per Coupon" });
    }

    get usesPerCustomerInput() {
        return this.page.getByRole("textbox", { name: "Uses Per Customer" });
    }

    // Conditions
    get addConditionButton() {
        return this.page.locator(
            'div.secondary-button:has-text("Add Condition")',
        );
    }

    get conditionAttributeSelect() {
        return this.page.locator(
            'select[id="conditions\\[0\\]\\[attribute\\]"]',
        );
    }

    get conditionOperatorSelect() {
        return this.page.locator(
            'select[name="conditions\\[0\\]\\[operator\\]"]',
        );
    }

    get conditionValueInput() {
        return this.page.locator('input[name="conditions\\[0\\]\\[value\\]"]');
    }

    get selectConditionOption() {
        return this.page.locator('select[name="conditions[0][value]"]');
    }

    get actionTypeSelect() {
        return this.page.locator("#action_type");
    }

    get discountAmountInput() {
        return this.page.locator('input[name="discount_amount"]');
    }

    get sortOrderInput() {
        return this.page.locator('input[name="sort_order"]');
    }

    get channelCheckbox() {
        return this.page.locator('label[for="channel__1"]');
    }

    get customerGroupCheckbox() {
        return this.page.locator("#customer_group__1");
    }

    get customerGroupCheckbox2() {
        return this.page.locator('label[for="customer_group__2"]');
    }

    get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    get validationErrors() {
        return this.page.locator("p.text-red-600");
    }

    get saveCartRuleButton() {
        return this.page.locator(
            'button.primary-button:has-text("Save Cart Rule")',
        );
    }

    get successMessage() {
        return this.page.locator("#app");
    }

    get applyToShipping() {
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
        const discountValue = Math.floor(Math.random() * 1000);
        const discountPercentage = Math.floor(Math.random() * 100);

        await this.addConditionButton.click();
        await this.conditionAttributeSelect.waitFor();
        await this.conditionAttributeSelect.selectOption(attribute);
        await this.conditionOperatorSelect.selectOption(operator);

        if (optionSelect) {
            await this.selectConditionOption.waitFor();
            await this.selectConditionOption.selectOption(optionSelect);
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
            await this.discountAmountInput.fill(discountValue.toString());
            result = discountValue;
        }

        if (couponType == "percentage") {
            await this.actionTypeSelect.selectOption("by_percent");
            await this.discountAmountInput.fill(discountPercentage.toString());
            result = discountPercentage;
        }

        if (couponType == "fixedAmmountWholeCart") {
            await this.actionTypeSelect.selectOption("cart_fixed");
            await this.discountAmountInput.fill(discountValue.toString());
            result = discountValue;
        }

        if (allowShipping == "yes") {
            await this.applyToShipping.selectOption("1");
        }

        return result;
    }

    private async configureSettings() {
        await this.sortOrderInput.fill("1");
        await this.channelCheckbox.first().click();
        await this.customerGroupCheckbox.nth(1).click();
        await this.customerGroupCheckbox2.first().click();
        await this.statusToggle.first().click();
    }

    public async saveCartRule() {
        await this.saveCartRuleButton.click();
        await expect(this.successMessage).toContainText(
            "Cart rule created successfully",
        );
    }

    public async saveCatalogRule() {
        await this.catalogRuleButton.click();
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
}
