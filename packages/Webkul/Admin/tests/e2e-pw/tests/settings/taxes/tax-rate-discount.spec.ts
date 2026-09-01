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

            const couponCode = `TAX${Date.now()}`;
            const cartRule = await new CartRuleCreatePage(
                adminPage,
            ).createCouponPercentageRule(couponCode, DISCOUNT_PERCENT);

            const configPage = new TaxConfigurationPage(adminPage);
            await configPage.setProductPricesMode("excluding_tax");
            await configPage.setApplyTaxOn(mode);

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

            await new CartRuleCreatePage(adminPage).deleteCartRule(
                cartRule.name,
            );
            await new TaxRateListPage(adminPage).deleteTaxRate(rate.identifier);
        });
    }
});
