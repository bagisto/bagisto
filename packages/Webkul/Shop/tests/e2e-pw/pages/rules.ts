import { expect, Page } from "@playwright/test";
import { WebLocators } from "../locators/locator";
import { generateName } from "../utils/faker";

export class CreateRules {
    readonly page: Page;
    readonly locators: WebLocators;
    readonly couponCode: string;

    constructor(page: Page) {
        this.page = page;
        this.locators = new WebLocators(page);
        this.couponCode = `CP-${Date.now()}`;
    }

    async adminlogin() {
        /**
         * Login to admin panel.
         */
        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };
        await this.page.goto("admin/login");
        await this.page.waitForLoadState("networkidle");
        await this.locators.emailInput.fill(adminCredentials.email);
        await this.locators.passwordInput.fill(adminCredentials.password);
        await this.locators.signInButton.click();
        await this.page.waitForLoadState("networkidle");
    }

    private async fillGeneralCartDetails() {
        await this.locators.createCartRuleButton.waitFor();
        await this.locators.createCartRuleButton.click();
        await this.locators.cartRuleForm.waitFor();
        await this.locators.nameInput.fill(generateName());
        await this.locators.descriptionInput.fill(generateName());
        await this.locators.couponTypeSelect.selectOption("1");
        await this.locators.autoGenerationSelect.selectOption("0");
        await this.locators.couponCodeInput.fill(this.couponCode);
        await this.locators.usesPerCouponInput.fill("100");
        await this.locators.usesPerCustomerInput.fill("100");
    }

    private async fillGeneralCatalogDetails() {
        await this.locators.createCatalogRuleButton.click();
        await this.locators.nameInput.fill(generateName());
        await this.locators.descriptionInput.fill(generateName());
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
        await this.locators.addConditionButton.click();
        await this.locators.conditionAttributeSelect.waitFor();
        await this.locators.conditionAttributeSelect.selectOption(attribute);
        await this.locators.conditionOperatorSelect.selectOption(operator);

        if (optionSelect) {
            await this.locators.selectCodintionOption.waitFor();
            await this.locators.selectCodintionOption.selectOption(
                optionSelect,
            );
        } else if (value) {
            await this.locators.conditionValueInput.fill(value);
        } else if (checkboxSelect) {
            const label = this.page.locator(
                `label:has-text("${checkboxSelect}")`,
            );
            const input = label.locator("input");
            await expect(input).toBeAttached();
            const isChecked = await input.isChecked();
            if (!isChecked) {
                await label.click();
            }
        }

        await this.locators.actionTypeSelect.selectOption("by_percent");
        await this.locators.discountAmountInput.fill("50");
    }

    private async configureSettings() {
        await this.locators.sortOrderInput.fill("1");
        await this.locators.channelCheckbox.first().click();
        await this.locators.customerGroupCheckbox.nth(1).click();
        await this.locators.customerGroupCheckbox2.first().click();
        await this.locators.statusToggle.first().click();
    }

    public async saveCartRule() {
        await this.locators.saveCartRuleButton.click();
        await expect(this.locators.successMessage).toContainText(
            "Cart rule created successfully",
        );
    }

    public async saveCatalogRule() {
        await this.locators.catalogRuleButton.click();
        await expect(this.locators.successMessage).toContainText(
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

        await this.locators.searchInput.fill("simple");
        await this.locators.searchInput.press("Enter");

        await this.locators.addToCartButton.first().click();
        await expect(
            this.locators.addToCartSuccessMessage.first(),
        ).toBeVisible();

        await this.page.goto("checkout/cart");

        if (incrementTimes && incrementTimes > 0) {
            for (let i = 0; i < incrementTimes; i++) {
                await this.locators.incrementQtyButton.first().click();
            }

            await this.locators.updateCart.click();
            await expect(this.locators.cartUpdateSuccess.first()).toBeVisible();
        }

        await this.locators.applyCouponButton.click();
        await this.locators.couponInput.first().fill(this.couponCode);
        await this.locators.applyButton.click();
        await expect(this.locators.couponSuccessMessage).toBeVisible();
    }

    async verifyCatalogRule() {
        await this.page.goto("");

        await this.locators.searchInput.fill("simple");
        await this.locators.searchInput.press("Enter");

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

        await this.locators.searchInput.fill("simple");
        await this.locators.searchInput.press("Enter");
        await this.locators.addToCartButton.first().click();
        await expect(
            this.locators.addToCartSuccessMessage.first(),
        ).toBeVisible();

        await this.locators.ShoppingCartIcon.click();
        await this.locators.ContinueButton.click();
        await this.locators.companyName.fill("Web");
        await this.locators.firstName.fill("demo");
        await this.locators.lastName.fill("guest");
        await this.locators.shippingEmail.fill("demo@example.com");
        await this.locators.streetAddress.fill("north street");
        await this.locators.billingCountry.selectOption({ value: "IN" });
        await this.locators.billingState.selectOption({ value: "UP" });
        await this.locators.billingCity.fill("test city");
        await this.locators.billingZip.fill("123456");
        await this.locators.billingTelephone.fill("2365432789");
        await this.locators.clickProcessButton.click();
        await this.locators.chooseShippingMethod.click();
        await this.locators.choosePaymentMethod.click();

        await this.locators.applyCouponButton.click();
        await this.page.waitForTimeout(1000);
        await this.locators.couponInput.fill(this.couponCode);
        await this.locators.applyButton.click();
        await expect(this.locators.couponSuccessMessage).toBeVisible();
    }

    async deleteRuleAndProduct() {
        await this.page.goto("admin/marketing/promotions/cart-rules");
        await this.locators.deleteIcon.first().click();
        await this.locators.agree.click();
        await expect(
            this.page.getByText("Cart Rule Deleted Successfully"),
        ).toBeVisible();
        await this.page.goto("admin/catalog/products");
        await this.locators.selectRowBtn.nth(2).click();
        await this.locators.selectAction.click();
        await this.locators.selectDelete.click();
        await this.locators.agree.click();
        await expect(this.locators.productDeleteSuccess).toBeVisible();
    }

    async deleteCatalogRuleAndProduct() {
        await this.page.goto("admin/marketing/promotions/catalog-rules");
        await this.locators.deleteIcon.first().click();
        await this.locators.agree.click();
        await expect(
            this.page.getByText("Catalog Rule Deleted Successfully"),
        ).toBeVisible();
        await this.page.goto("admin/catalog/products");
        await this.locators.selectRowBtn.nth(2).click();
        await this.locators.selectAction.click();
        await this.locators.selectDelete.click();
        await this.locators.agree.click();
        await expect(this.locators.productDeleteSuccess).toBeVisible();
    }
}
