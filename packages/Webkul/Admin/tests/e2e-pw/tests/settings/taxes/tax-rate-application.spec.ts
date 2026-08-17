import { test } from "../../../setup";
import { TaxRateCreatePage } from "../../../pages/admin/settings/taxes/TaxRateCreatePage";
import { TaxRateListPage } from "../../../pages/admin/settings/taxes/TaxRateListPage";
import { TaxCategoryPage } from "../../../pages/admin/settings/taxes/TaxCategoryPage";
import { TaxRateApplyPage } from "../../../pages/shop/taxes/TaxRateApplyPage";
import {
    assignTaxCategoryToProduct,
    createSimpleTaxableProduct,
    TAX_PRODUCT_PRICE,
    TAX_REGIONS,
} from "../../../utils/tax";

const PRODUCT_PRICE = TAX_PRODUCT_PRICE;

/**
 * End-to-end tax application: rate -> category -> product -> storefront.
 *
 * Each storefront scenario uses a region whose wildcard (empty) state matches
 * the address filled during guest checkout, so the rate is guaranteed to apply
 * and the tax can be asserted mathematically.
 */
test.describe("tax application", () => {
    test.setTimeout(240000);

    test("should create a tax category from a tax rate and persist the assignment", async ({
        adminPage,
    }) => {
        const rate = await new TaxRateCreatePage(adminPage).createTaxRate();

        const categoryPage = new TaxCategoryPage(adminPage);
        const category = await categoryPage.createTaxCategory(rate.identifier);

        await categoryPage.expectRateAssigned(category.name, rate.identifier);
    });

    test("should assign a tax category to a product and save it", async ({
        adminPage,
    }) => {
        const rate = await new TaxRateCreatePage(adminPage).createTaxRate();
        const category = await new TaxCategoryPage(adminPage).createTaxCategory(
            rate.identifier,
        );

        await createSimpleTaxableProduct(adminPage);

        // Assertion lives inside the helper (success toast).
        await assignTaxCategoryToProduct(adminPage, category.name);
    });

    const scenarios = [
        {
            label: "18% in India",
            taxRate: "18",
            taxPercent: 18,
            region: TAX_REGIONS.india,
        },
        {
            label: "5% in India",
            taxRate: "5",
            taxPercent: 5,
            region: TAX_REGIONS.india,
        },
        {
            label: "10% in the United States",
            taxRate: "10",
            taxPercent: 10,
            region: TAX_REGIONS.unitedStates,
        },
    ];

    for (const scenario of scenarios) {
        test(`should apply ${scenario.label} on the storefront and include it in the grand total`, async ({
            adminPage,
            shopPage,
        }) => {
            /**
             * Admin setup — create the rate (wildcard state), bundle it in a
             * category, create a product and assign the category to it.
             */
            const rate = await new TaxRateCreatePage(adminPage).createTaxRate({
                country: scenario.region.country,
                state: "",
                taxRate: scenario.taxRate,
            });

            const category = await new TaxCategoryPage(
                adminPage,
            ).createTaxCategory(rate.identifier);

            const productName = await createSimpleTaxableProduct(adminPage);

            await assignTaxCategoryToProduct(adminPage, category.name);

            /**
             * Storefront verification — taxable subtotal in the cart, then the
             * applied tax and grand total at checkout.
             */
            await new TaxRateApplyPage(shopPage).verifyTaxApplication(
                productName,
                PRODUCT_PRICE,
                scenario.taxPercent,
                {
                    country: scenario.region.country,
                    checkoutState: scenario.region.checkoutState,
                },
            );

            /**
             * Cleanup — remove the created tax rate so repeated runs stay clean.
             */
            await new TaxRateListPage(adminPage).deleteTaxRate(rate.identifier);
        });
    }

    /**
     * Tax rates have no priority field in Bagisto's schema, so priority-based
     * resolution is out of scope (requirement scoped to "if supported").
     */
});
