import { test } from "../../../setup";
import { ProductEditPage } from "../../../pages/admin/catalog/products/ProductEditPage";
import { ProductListPage } from "../../../pages/admin/catalog/products/ProductListPage";
import { TaxCategoriesPage } from "../../../pages/admin/settings/taxes/TaxCategoriesPage";
import { TaxRatesPage } from "../../../pages/admin/settings/taxes/TaxRatesPage";
import { TaxRateApplyPage } from "../../../pages/shop/taxes/TaxRateApplyPage";
import {
    createSimpleTaxableProduct,
    generateTaxCategoryData,
    generateTaxRateData,
    TAX_PRODUCT_PRICE,
    TAX_REGIONS,
    type TaxCategoryData,
    type TaxRateData,
} from "../../../utils/tax";

test.describe("tax application", () => {
    test.setTimeout(240000);

    let taxRatesPage: TaxRatesPage;
    let taxCategoriesPage: TaxCategoriesPage;
    let productListPage: ProductListPage;
    let createdRates: string[];
    let createdCategories: string[];
    let createdProducts: string[];

    test.beforeEach(async ({ adminPage }) => {
        taxRatesPage = new TaxRatesPage(adminPage);
        taxCategoriesPage = new TaxCategoriesPage(adminPage);
        productListPage = new ProductListPage(adminPage);
        createdRates = [];
        createdCategories = [];
        createdProducts = [];
    });

    test.afterEach(async () => {
        try {
            await productListPage.deleteProductsIfPresent(createdProducts);
        } finally {
            try {
                await taxCategoriesPage.deleteTaxCategoriesIfPresent(createdCategories);
            } finally {
                await taxRatesPage.deleteTaxRatesIfPresent(createdRates);
            }
        }
    });

    async function createRateAndCategory(
        rateOverrides: Partial<TaxRateData>,
    ): Promise<{ rate: TaxRateData; category: TaxCategoryData }> {
        const rate = generateTaxRateData(rateOverrides);
        const category = generateTaxCategoryData();
        createdRates.push(rate.identifier);
        createdCategories.push(category.name);

        await taxRatesPage.createTaxRate(rate);
        await taxCategoriesPage.createTaxCategory(category, [rate.identifier]);

        return { rate, category };
    }

    test("should assign a tax category to a product and keep it after reload", async ({
        adminPage,
    }) => {
        const { category } = await createRateAndCategory({});
        const productName = await createSimpleTaxableProduct(adminPage);
        createdProducts.push(productName);

        const productEditPage = new ProductEditPage(adminPage);

        await productEditPage.assignTaxCategory(productName, category.name);

        await productEditPage.expectTaxCategoryAssigned(productName, category.name);
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
        test(`should charge ${scenario.label} at checkout and include it in the grand total`, async ({
            adminPage,
            shopPage,
        }) => {
            const { category } = await createRateAndCategory({
                country: scenario.region.country,
                state: "",
                taxRate: scenario.taxRate,
            });
            const productName = await createSimpleTaxableProduct(adminPage);
            createdProducts.push(productName);

            await new ProductEditPage(adminPage).assignTaxCategory(
                productName,
                category.name,
            );

            await new TaxRateApplyPage(shopPage).verifyTaxApplication(
                productName,
                TAX_PRODUCT_PRICE,
                scenario.taxPercent,
                {
                    country: scenario.region.country,
                    checkoutState: scenario.region.checkoutState,
                },
            );
        });
    }
});
