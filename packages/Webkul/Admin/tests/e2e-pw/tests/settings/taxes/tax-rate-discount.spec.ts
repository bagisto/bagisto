import { test } from "../../../setup";
import { TaxRateCreatePage } from "../../../pages/admin/settings/taxes/TaxRateCreatePage";
import { TaxRateListPage } from "../../../pages/admin/settings/taxes/TaxRateListPage";
import { TaxCategoryPage } from "../../../pages/admin/settings/taxes/TaxCategoryPage";
import { TaxConfigurationPage } from "../../../pages/admin/configuration/sales/TaxConfigurationPage";
import { CartRuleCreatePage } from "../../../pages/admin/marketing/promotion/CartRuleCreatePage";
import { TaxRateApplyPage } from "../../../pages/shop/taxes/TaxRateApplyPage";
import {
    assignTaxCategoryToProduct,
    createSimpleTaxableProduct,
    TaxApplyOnMode,
    TAX_PRODUCT_PRICE,
    TAX_REGIONS,
} from "../../../utils/tax";

/**
 * Tax-before-discount vs tax-after-discount.
 *
 * A coupon cart rule (percentage off) is applied at checkout and the tax is
 * asserted under both `sales.taxes.calculation.apply_tax_on` modes:
 *
 *   - before_discount: tax is charged on the full price (199 @ 18% = 35.82),
 *     grand total = price - discount + tax.
 *   - after_discount:  tax is charged on the discounted price
 *     ((199 - 19.90) @ 18% = 32.24), grand total = discounted price + tax.
 *
 * Runs serially and restores the global tax calculation defaults afterwards.
 */
test.describe.configure({ mode: "serial" });

test.describe("tax before / after discount", () => {
    test.setTimeout(300000);

    const TAX_PERCENT = 18;
    const DISCOUNT_PERCENT = 10;
    const region = TAX_REGIONS.india;

    test.afterEach(async ({ adminPage }) => {
        await new TaxConfigurationPage(adminPage).resetCalculationDefaults();
    });

    const modes: { label: string; mode: TaxApplyOnMode }[] = [
        { label: "before the discount", mode: "before_discount" },
        { label: "after the discount", mode: "after_discount" },
    ];

    for (const { label, mode } of modes) {
        test(`should charge ${TAX_PERCENT}% tax ${label} when a cart rule is applied`, async ({
            adminPage,
            shopPage,
        }) => {
            /**
             * Admin setup — rate (wildcard state) -> category -> product ->
             * assignment.
             */
            const rate = await new TaxRateCreatePage(adminPage).createTaxRate({
                country: region.country,
                state: "",
                taxRate: `${TAX_PERCENT}`,
            });

            const category = await new TaxCategoryPage(
                adminPage,
            ).createTaxCategory(rate.identifier);

            const productName = await createSimpleTaxableProduct(adminPage);

            await assignTaxCategoryToProduct(adminPage, category.name);

            /**
             * Create the coupon cart rule that grants the discount.
             */
            const couponCode = `TAX${Date.now()}`;
            const cartRule = await new CartRuleCreatePage(
                adminPage,
            ).createCouponPercentageRule(couponCode, DISCOUNT_PERCENT);

            /**
             * Force tax-exclusive prices and the mode under test before
             * checking out.
             */
            const configPage = new TaxConfigurationPage(adminPage);
            await configPage.setProductPricesMode("excluding_tax");
            await configPage.setApplyTaxOn(mode);

            /**
             * Storefront — add to cart, guest checkout, apply the coupon and
             * assert the discounted tax figures.
             */
            await new TaxRateApplyPage(shopPage).verifyTaxWithCartRule({
                productName,
                price: TAX_PRODUCT_PRICE,
                taxPercent: TAX_PERCENT,
                discountPercent: DISCOUNT_PERCENT,
                couponCode,
                region: {
                    country: region.country,
                    checkoutState: region.checkoutState,
                },
                applyOn: mode,
            });

            /**
             * Cleanup — remove the cart rule and tax rate (config reset runs in
             * afterEach).
             */
            await new CartRuleCreatePage(adminPage).deleteCartRule(
                cartRule.name,
            );
            await new TaxRateListPage(adminPage).deleteTaxRate(rate.identifier);
        });
    }
});
