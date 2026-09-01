import { test } from "../../../setup";
import { TaxRateCreatePage } from "../../../pages/admin/settings/taxes/TaxRateCreatePage";
import { TaxRateListPage } from "../../../pages/admin/settings/taxes/TaxRateListPage";
import { TaxCategoryPage } from "../../../pages/admin/settings/taxes/TaxCategoryPage";
import { TaxConfigurationPage } from "../../../pages/admin/configuration/sales/TaxConfigurationPage";
import { TaxRateApplyPage } from "../../../pages/shop/taxes/TaxRateApplyPage";
import {
    assignTaxCategoryToProduct,
    createSimpleTaxableProduct,
    TaxPricingMode,
    TAX_PRODUCT_PRICE,
    TAX_REGIONS,
} from "../../../utils/tax";

test.describe.configure({ mode: "serial" });

test.describe("tax pricing modes", () => {
    test.setTimeout(240000);

    const TAX_PERCENT = 18;
    const region = TAX_REGIONS.india;

    test.afterEach(async ({ adminPage }) => {
        await new TaxConfigurationPage(adminPage).resetToDefault();
    });

    const modes: { label: string; mode: TaxPricingMode }[] = [
        { label: "tax-exclusive product prices", mode: "excluding_tax" },
        { label: "tax-inclusive product prices", mode: "including_tax" },
    ];

    for (const { label, mode } of modes) {
        test(`should apply the correct ${TAX_PERCENT}% tax and final price for ${label}`, async ({
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

            await new TaxConfigurationPage(adminPage).setProductPricesMode(mode);

            await new TaxRateApplyPage(shopPage).verifyTaxApplicationForMode(
                productName,
                TAX_PRODUCT_PRICE,
                TAX_PERCENT,
                {
                    country: region.country,
                    checkoutState: region.checkoutState,
                },
                mode,
            );

            await new TaxRateListPage(adminPage).deleteTaxRate(rate.identifier);
        });
    }
});
