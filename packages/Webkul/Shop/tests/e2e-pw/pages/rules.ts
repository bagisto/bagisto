import { expect, Page } from "@playwright/test";
import { RulesEditPage } from "../locators/admin/RulesEditPage";
import { generateName } from "../utils/faker";
import { RulesShopPage } from "../locators/shop/RulesShopPage";

export class CreateRules {
    readonly page: Page;
    readonly rulesEditPage: RulesEditPage;
    readonly rulesShopPage: RulesShopPage;
    readonly couponCode: string;

    constructor(page: Page) {
        this.page = page;
        this.rulesEditPage = new RulesEditPage(page);
        this.rulesShopPage = new RulesShopPage(page);
        this.couponCode = `CP-${Date.now()}`;
    }

    private async fillGeneralCartDetails() {
        await this.rulesEditPage.createCartRuleButton.waitFor();
        await this.rulesEditPage.createCartRuleButton.click();
        await this.rulesEditPage.cartRuleForm.waitFor();
        await this.rulesEditPage.nameInput.fill(generateName());
        await this.rulesEditPage.descriptionInput.fill(generateName());
        await this.rulesEditPage.couponTypeSelect.selectOption("1");
        await this.rulesEditPage.autoGenerationSelect.selectOption("0");
        await this.rulesEditPage.couponCodeInput.fill(this.couponCode);
        await this.rulesEditPage.usesPerCouponInput.fill("100");
        await this.rulesEditPage.usesPerCustomerInput.fill("100");
    }

    private async fillGeneralCatalogDetails() {
        await this.rulesEditPage.createCatalogRuleButton.click();
        await this.rulesEditPage.nameInput.fill(generateName());
        await this.rulesEditPage.descriptionInput.fill(generateName());
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
        await this.rulesEditPage.addConditionButton.click();
        await this.rulesEditPage.conditionAttributeSelect.waitFor();
        await this.rulesEditPage.conditionAttributeSelect.selectOption(attribute);
        await this.rulesEditPage.conditionOperatorSelect.selectOption(operator);

        if (optionSelect) {
            await this.rulesEditPage.selectCodintionOption.waitFor();
            await this.rulesEditPage.selectCodintionOption.selectOption(
                optionSelect,
            );
        } else if (value) {
            await this.rulesEditPage.conditionValueInput.fill(value);
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

        await this.rulesEditPage.actionTypeSelect.selectOption("by_percent");
        await this.rulesEditPage.discountAmountInput.fill("50");
    }

    private async configureSettings() {
        await this.rulesEditPage.sortOrderInput.fill("1");
        await this.rulesEditPage.channelCheckbox.first().click();
        await this.rulesEditPage.customerGroupCheckbox.nth(1).click();
        await this.rulesEditPage.customerGroupCheckbox2.first().click();
        await this.rulesEditPage.statusToggle.first().click();
    }

    public async saveCartRule() {
        await this.rulesEditPage.saveCartRuleButton.click();
        await expect(this.rulesEditPage.successMessage).toContainText(
            "Cart rule created successfully",
        );
    }

    public async saveCatalogRule() {
        await this.rulesEditPage.catalogRuleButton.click();
        await expect(this.rulesEditPage.successMessage).toContainText(
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

        await this.rulesShopPage.searchInput.fill("simple");
        await this.rulesShopPage.searchInput.press("Enter");

        await this.rulesShopPage.addToCartButton.first().click();
        await expect(
            this.rulesShopPage.addToCartSuccessMessage.first(),
        ).toBeVisible();

        await this.page.goto("checkout/cart");

        if (incrementTimes && incrementTimes > 0) {
            for (let i = 0; i < incrementTimes; i++) {
                await this.rulesShopPage.incrementQtyButton.first().click();
            }

            await this.rulesShopPage.updateCart.click();
            await expect(this.rulesShopPage.cartUpdateSuccess.first()).toBeVisible();
        }

        await this.rulesShopPage.applyCouponButton.click();
        await this.rulesShopPage.couponInput.first().fill(this.couponCode);
        await this.rulesShopPage.applyButton.click();
        await expect(this.rulesShopPage.couponSuccessMessage).toBeVisible();
    }

    async verifyCatalogRule() {
        await this.page.goto("");

        await this.rulesShopPage.searchInput.fill("simple");
        await this.rulesShopPage.searchInput.press("Enter");

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

        await this.rulesShopPage.searchInput.fill("simple");
        await this.rulesShopPage.searchInput.press("Enter");
        await this.rulesShopPage.addToCartButton.first().click();
        await expect(
            this.rulesShopPage.addToCartSuccessMessage.first(),
        ).toBeVisible();

        await this.rulesShopPage.ShoppingCartIcon.click();
        await this.rulesShopPage.ContinueButton.click();
        await this.rulesShopPage.companyName.fill("Web");
        await this.rulesShopPage.firstName.fill("demo");
        await this.rulesShopPage.lastName.fill("guest");
        await this.rulesShopPage.shippingEmail.fill("demo@example.com");
        await this.rulesShopPage.streetAddress.fill("north street");
        await this.rulesShopPage.billingCountry.selectOption({ value: "IN" });
        await this.rulesShopPage.billingState.selectOption({ value: "UP" });
        await this.rulesShopPage.billingCity.fill("test city");
        await this.rulesShopPage.billingZip.fill("123456");
        await this.rulesShopPage.billingTelephone.fill("2365432789");
        await this.rulesShopPage.clickProcessButton.click();
        await this.rulesShopPage.chooseShippingMethod.click();
        await this.rulesShopPage.choosePaymentMethod.click();

        await this.rulesShopPage.applyCouponButton.click();
        await this.page.waitForTimeout(1000);
        await this.rulesShopPage.couponInput.fill(this.couponCode);
        await this.rulesShopPage.applyButton.click();
        await expect(this.rulesShopPage.couponSuccessMessage).toBeVisible();
    }

    async deleteRuleAndProduct() {
        await this.page.goto("admin/marketing/promotions/cart-rules");
        await this.rulesEditPage.deleteIcon.first().click();
        await this.rulesEditPage.agree.click();
        await expect(
            this.page.getByText("Cart Rule Deleted Successfully"),
        ).toBeVisible();
        await this.page.goto("admin/catalog/products");
        await this.rulesEditPage.selectRowBtn.nth(2).click();
        await this.rulesEditPage.selectAction.click();
        await this.rulesEditPage.selectDelete.click();
        await this.rulesEditPage.agree.click();
        await expect(this.rulesEditPage.productDeleteSuccess).toBeVisible();
    }

    async deleteCatalogRuleAndProduct() {
        await this.page.goto("admin/marketing/promotions/catalog-rules");
        await this.rulesEditPage.deleteIcon.first().click();
        await this.rulesEditPage.agree.click();
        await expect(
            this.page.getByText("Catalog Rule Deleted Successfully"),
        ).toBeVisible();
        await this.page.goto("admin/catalog/products");
        await this.rulesEditPage.selectRowBtn.nth(2).click();
        await this.rulesEditPage.selectAction.click();
        await this.rulesEditPage.selectDelete.click();
        await this.rulesEditPage.agree.click();
        await expect(this.rulesEditPage.productDeleteSuccess).toBeVisible();
    }
}
