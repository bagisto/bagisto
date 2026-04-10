import { expect, Page } from "@playwright/test";
import { RulesAdminLocators } from "../locators/admin/rules-admin";
import { generateName } from "../utils/faker";
import { RulesShopLocators } from "../locators/shop/rules-shop";

export class CreateRules {
    readonly page: Page;
    readonly rulesAdminLocators: RulesAdminLocators;
    readonly rulesShopLocators: RulesShopLocators;
    readonly couponCode: string;

    constructor(page: Page) {
        this.page = page;
        this.rulesAdminLocators = new RulesAdminLocators(page);
        this.rulesShopLocators = new RulesShopLocators(page);
        this.couponCode = `CP-${Date.now()}`;
    }

    private async fillGeneralCartDetails() {
        await this.rulesAdminLocators.createCartRuleButton.waitFor();
        await this.rulesAdminLocators.createCartRuleButton.click();
        await this.rulesAdminLocators.cartRuleForm.waitFor();
        await this.rulesAdminLocators.nameInput.fill(generateName());
        await this.rulesAdminLocators.descriptionInput.fill(generateName());
        await this.rulesAdminLocators.couponTypeSelect.selectOption("1");
        await this.rulesAdminLocators.autoGenerationSelect.selectOption("0");
        await this.rulesAdminLocators.couponCodeInput.fill(this.couponCode);
        await this.rulesAdminLocators.usesPerCouponInput.fill("100");
        await this.rulesAdminLocators.usesPerCustomerInput.fill("100");
    }

    private async fillGeneralCatalogDetails() {
        await this.rulesAdminLocators.createCatalogRuleButton.click();
        await this.rulesAdminLocators.nameInput.fill(generateName());
        await this.rulesAdminLocators.descriptionInput.fill(generateName());
    }

    public async addCondition({
        attribute,
        operator,
        value,
        optionSelect,
        checkboxSelect,
    }: {
        attribute: string;
        operator: string;
        value?: string;
        optionSelect?: string;
        checkboxSelect?: string;
    }) {
        await this.rulesAdminLocators.addConditionButton.click();
        await this.rulesAdminLocators.conditionAttributeSelect.waitFor();
        await this.rulesAdminLocators.conditionAttributeSelect.selectOption(attribute);
        await this.rulesAdminLocators.conditionOperatorSelect.selectOption(operator);

        if (optionSelect) {
            await this.rulesAdminLocators.selectCodintionOption.waitFor();
            await this.rulesAdminLocators.selectCodintionOption.selectOption(
                optionSelect,
            );
        } else if (value) {
            await this.rulesAdminLocators.conditionValueInput.fill(value);
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

        await this.rulesAdminLocators.actionTypeSelect.selectOption("by_percent");
        await this.rulesAdminLocators.discountAmountInput.fill("50");
    }

    private async configureSettings() {
        await this.rulesAdminLocators.sortOrderInput.fill("1");
        await this.rulesAdminLocators.channelCheckbox.first().click();
        await this.rulesAdminLocators.customerGroupCheckbox.nth(1).click();
        await this.rulesAdminLocators.customerGroupCheckbox2.first().click();
        await this.rulesAdminLocators.statusToggle.first().click();
    }

    public async saveCartRule() {
        await this.rulesAdminLocators.saveCartRuleButton.click();
        await expect(this.rulesAdminLocators.successMessage).toContainText(
            "Cart rule created successfully",
        );
    }

    public async saveCatalogRule() {
        await this.rulesAdminLocators.catalogRuleButton.click();
        await expect(this.rulesAdminLocators.successMessage).toContainText(
            "Catalog rule created successfully",
        );
    }

    public async cartRuleCreationFlow() {
        await this.page.goto("admin/marketing/promotions/cart-rules");
        await this.fillGeneralCartDetails();
        await this.configureSettings();
    }

    public async catalogRuleCreationFlow() {
        await this.page.goto("admin/marketing/promotions/catalog-rules");
        await this.fillGeneralCatalogDetails();
        await this.configureSettings();
    }

    async applyCoupon(incrementTimes?: number) {
        await this.page.goto("");

        await this.rulesShopLocators.searchInput.fill("simple");
        await this.rulesShopLocators.searchInput.press("Enter");

        await this.rulesShopLocators.addToCartButton.first().click();
        await expect(
            this.rulesShopLocators.addToCartSuccessMessage.first(),
        ).toBeVisible();

        await this.page.goto("checkout/cart");

        if (incrementTimes && incrementTimes > 0) {
            for (let i = 0; i < incrementTimes; i++) {
                await this.rulesShopLocators.incrementQtyButton.first().click();
            }

            await this.rulesShopLocators.updateCart.click();
            await expect(this.rulesShopLocators.cartUpdateSuccess.first()).toBeVisible();
        }

        await this.rulesShopLocators.applyCouponButton.click();
        await this.rulesShopLocators.couponInput.first().fill(this.couponCode);
        await this.rulesShopLocators.applyButton.click();
        await expect(this.rulesShopLocators.couponSuccessMessage).toBeVisible();
    }

    async verifyCatalogRule() {
        await this.page.goto("");

        await this.rulesShopLocators.searchInput.fill("simple");
        await this.rulesShopLocators.searchInput.press("Enter");

        const actualPrice = 199;
        const expectedDiscountedPrice = `$${(actualPrice * 0.5).toFixed(2)}`;

        await expect(
            this.page
                .locator("div.flex.items-center")
                .locator("p")
                .filter({ hasText: "$" })
                .last(),
        ).toHaveText(expectedDiscountedPrice);
    }

    async applyCouponAtCheckout() {
        await this.page.goto("");

        await this.rulesShopLocators.searchInput.fill("simple");
        await this.rulesShopLocators.searchInput.press("Enter");
        await this.rulesShopLocators.addToCartButton.first().click();
        await expect(
            this.rulesShopLocators.addToCartSuccessMessage.first(),
        ).toBeVisible();

        await this.rulesShopLocators.ShoppingCartIcon.click();
        await this.rulesShopLocators.ContinueButton.click();
        await this.rulesShopLocators.companyName.fill("Web");
        await this.rulesShopLocators.firstName.fill("demo");
        await this.rulesShopLocators.lastName.fill("guest");
        await this.rulesShopLocators.shippingEmail.fill("demo@example.com");
        await this.rulesShopLocators.streetAddress.fill("north street");
        await this.rulesShopLocators.billingCountry.selectOption({ value: "IN" });
        await this.rulesShopLocators.billingState.selectOption({ value: "UP" });
        await this.rulesShopLocators.billingCity.fill("test city");
        await this.rulesShopLocators.billingZip.fill("123456");
        await this.rulesShopLocators.billingTelephone.fill("2365432789");
        await this.rulesShopLocators.clickProcessButton.click();
        await this.rulesShopLocators.chooseShippingMethod.click();
        await this.rulesShopLocators.choosePaymentMethod.click();

        await this.rulesShopLocators.applyCouponButton.click();
        await this.page.waitForTimeout(1000);
        await this.rulesShopLocators.couponInput.fill(this.couponCode);
        await this.rulesShopLocators.applyButton.click();
        await expect(this.rulesShopLocators.couponSuccessMessage).toBeVisible();
    }

    async deleteRuleAndProduct() {
        await this.page.goto("admin/marketing/promotions/cart-rules");
        await this.rulesAdminLocators.deleteIcon.first().click();
        await this.rulesAdminLocators.agree.click();
        await expect(
            this.page.getByText("Cart Rule Deleted Successfully"),
        ).toBeVisible();
        await this.page.goto("admin/catalog/products");
        await this.rulesAdminLocators.selectRowBtn.nth(2).click();
        await this.rulesAdminLocators.selectAction.click();
        await this.rulesAdminLocators.selectDelete.click();
        await this.rulesAdminLocators.agree.click();
        await expect(this.rulesAdminLocators.productDeleteSuccess).toBeVisible();
    }

    async deleteCatalogRuleAndProduct() {
        await this.page.goto("admin/marketing/promotions/catalog-rules");
        await this.rulesAdminLocators.deleteIcon.first().click();
        await this.rulesAdminLocators.agree.click();
        await expect(
            this.page.getByText("Catalog Rule Deleted Successfully"),
        ).toBeVisible();
        await this.page.goto("admin/catalog/products");
        await this.rulesAdminLocators.selectRowBtn.nth(2).click();
        await this.rulesAdminLocators.selectAction.click();
        await this.rulesAdminLocators.selectDelete.click();
        await this.rulesAdminLocators.agree.click();
        await expect(this.rulesAdminLocators.productDeleteSuccess).toBeVisible();
    }
}
