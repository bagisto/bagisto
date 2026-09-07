import { test } from "../../../setup";
import { ProductEditPage } from "../../../pages/admin/catalog/products/ProductEditPage";
import { ProductListPage } from "../../../pages/admin/catalog/products/ProductListPage";
import {
    TaxConfigurationPage,
    type TaxSettings,
} from "../../../pages/admin/configuration/sales/TaxConfigurationPage";
import { TaxCategoriesPage } from "../../../pages/admin/settings/taxes/TaxCategoriesPage";
import { TaxRatesPage } from "../../../pages/admin/settings/taxes/TaxRatesPage";
import { TaxRateApplyPage } from "../../../pages/shop/taxes/TaxRateApplyPage";
import {
    createSimpleTaxableProduct,
    generateTaxCategoryData,
    generateTaxRateData,
    TaxPricingMode,
    TAX_PRODUCT_PRICE,
    TAX_REGIONS,
} from "../../../utils/tax";

test.describe("tax pricing modes", () => {
    test.setTimeout(240000);

    const TAX_PERCENT = 18;
    const region = TAX_REGIONS.india;

    let taxConfig: TaxConfigurationPage;
    let original: TaxSettings;
    let taxRatesPage: TaxRatesPage;
    let taxCategoriesPage: TaxCategoriesPage;
    let productListPage: ProductListPage;
    let createdRates: string[];
    let createdCategories: string[];
    let createdProducts: string[];

    test.beforeEach(async ({ adminPage }) => {
        taxConfig = new TaxConfigurationPage(adminPage);
        taxRatesPage = new TaxRatesPage(adminPage);
        taxCategoriesPage = new TaxCategoriesPage(adminPage);
        productListPage = new ProductListPage(adminPage);
        createdRates = [];
        createdCategories = [];
        createdProducts = [];
        original = await taxConfig.readSettings();
    });

    test.afterEach(async () => {
        try {
            await taxConfig.applySettings(original);
        } finally {
            try {
                await productListPage.deleteProductsIfPresent(createdProducts);
            } finally {
                try {
                    await taxCategoriesPage.deleteTaxCategoriesIfPresent(
                        createdCategories,
                    );
                } finally {
                    await taxRatesPage.deleteTaxRatesIfPresent(createdRates);
                }
            }
        }
    });

    const modes: { label: string; mode: TaxPricingMode }[] = [
        { label: "tax-exclusive product prices", mode: "excluding_tax" },
        { label: "tax-inclusive product prices", mode: "including_tax" },
    ];

    for (const { label, mode } of modes) {
        test(`should charge ${TAX_PERCENT}% tax and the matching total for ${label}`, async ({
            adminPage,
            shopPage,
        }) => {
            const rate = generateTaxRateData({
                country: region.country,
                state: "",
                taxRate: `${TAX_PERCENT}`,
            });
            const category = generateTaxCategoryData();
            createdRates.push(rate.identifier);
            createdCategories.push(category.name);

            await taxRatesPage.createTaxRate(rate);
            await taxCategoriesPage.createTaxCategory(category, [rate.identifier]);

            const productName = await createSimpleTaxableProduct(adminPage);
            createdProducts.push(productName);

            await new ProductEditPage(adminPage).assignTaxCategory(
                productName,
                category.name,
            );
            await taxConfig.applySettings({ productPrices: mode });

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
        });
    }
});
