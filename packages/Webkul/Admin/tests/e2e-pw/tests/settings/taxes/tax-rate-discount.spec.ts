import { test } from "../../../setup";
import { ProductEditPage } from "../../../pages/admin/catalog/products/ProductEditPage";
import { ProductListPage } from "../../../pages/admin/catalog/products/ProductListPage";
import {
    TaxConfigurationPage,
    type TaxSettings,
} from "../../../pages/admin/configuration/sales/TaxConfigurationPage";
import { CartRulePage } from "../../../pages/admin/marketing/promotion/CartRulePage";
import { TaxCategoriesPage } from "../../../pages/admin/settings/taxes/TaxCategoriesPage";
import { TaxRatesPage } from "../../../pages/admin/settings/taxes/TaxRatesPage";
import { TaxRateApplyPage } from "../../../pages/shop/taxes/TaxRateApplyPage";
import { uniqueStamp } from "../../../utils/faker";
import {
    createSimpleTaxableProduct,
    generateTaxCategoryData,
    generateTaxRateData,
    TaxApplyOnMode,
    TAX_PRODUCT_PRICE,
    TAX_REGIONS,
} from "../../../utils/tax";

test.describe("tax before and after discount", () => {
    test.setTimeout(300000);

    const TAX_PERCENT = 18;
    const DISCOUNT_PERCENT = 10;
    const region = TAX_REGIONS.india;

    let taxConfig: TaxConfigurationPage;
    let original: TaxSettings;
    let taxRatesPage: TaxRatesPage;
    let taxCategoriesPage: TaxCategoriesPage;
    let productListPage: ProductListPage;
    let cartRulePage: CartRulePage;
    let createdRates: string[];
    let createdCategories: string[];
    let createdProducts: string[];
    let createdCartRules: string[];

    test.beforeEach(async ({ adminPage }) => {
        taxConfig = new TaxConfigurationPage(adminPage);
        taxRatesPage = new TaxRatesPage(adminPage);
        taxCategoriesPage = new TaxCategoriesPage(adminPage);
        productListPage = new ProductListPage(adminPage);
        cartRulePage = new CartRulePage(adminPage);
        createdRates = [];
        createdCategories = [];
        createdProducts = [];
        createdCartRules = [];
        original = await taxConfig.readSettings();
    });

    test.afterEach(async () => {
        try {
            await taxConfig.applySettings(original);
        } finally {
            try {
                await cartRulePage.deleteCartRulesIfPresent(createdCartRules);
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
        }
    });

    const modes: { label: string; mode: TaxApplyOnMode }[] = [
        { label: "before the discount", mode: "before_discount" },
        { label: "after the discount", mode: "after_discount" },
    ];

    for (const { label, mode } of modes) {
        test(`should charge ${TAX_PERCENT}% tax ${label} when a coupon is applied`, async ({
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

            const couponCode = `TAX${uniqueStamp()}`;
            const cartRule = await cartRulePage.createCouponPercentageRule(
                couponCode,
                DISCOUNT_PERCENT,
            );
            createdCartRules.push(cartRule.name);

            await taxConfig.applySettings({
                productPrices: "excluding_tax",
                applyTaxOn: mode,
            });

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
        });
    }
});
