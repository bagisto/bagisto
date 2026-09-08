import { Page } from "@playwright/test";
import { generateDescription, generateSKU, generateSlug, uniqueStamp } from "./faker";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductEditPage } from "../pages/admin/catalog/products/ProductEditPage";
export { formatPrice } from "@shared/prices";

export const TAX_PRODUCT_PRICE = 199;

export interface TaxRateData {
    identifier: string;
    country: string;
    state?: string;
    taxRate: string;
    isZip?: boolean;
    zipCode?: string;
    zipFrom?: string;
    zipTo?: string;
}

export interface TaxCategoryData {
    code: string;
    name: string;
    description: string;
}

export const TAX_REGIONS = {
    india: { country: "IN", checkoutState: "UP" },
    unitedStates: { country: "US", checkoutState: "CA" },
} as const;

export function generateTaxRateData(
    overrides: Partial<TaxRateData> = {},
): TaxRateData {
    return {
        identifier: `${generateSlug("_")}_${uniqueStamp()}`,
        country: TAX_REGIONS.india.country,
        state: "",
        taxRate: "18",
        isZip: false,
        ...overrides,
    };
}

export function generateTaxCategoryData(
    overrides: Partial<TaxCategoryData> = {},
): TaxCategoryData {
    return {
        code: `${generateSlug("_")}_${uniqueStamp()}`,
        name: `Tax Category ${uniqueStamp()}`,
        description: "Tax category created by the e2e suite.",
        ...overrides,
    };
}

export type TaxPricingMode = "excluding_tax" | "including_tax";

export function expectedTaxAmount(netAmount: number, taxPercent: number): number {
    return Math.round(netAmount * taxPercent) / 100;
}

export function expectedGrandTotal(netAmount: number, taxPercent: number): number {
    return (
        Math.round((netAmount + expectedTaxAmount(netAmount, taxPercent)) * 100) /
        100
    );
}

export function inclusiveTaxAmount(grossAmount: number, taxPercent: number): number {
    return Math.round((grossAmount * taxPercent * 100) / (100 + taxPercent)) / 100;
}

export function expectedTaxForMode(
    enteredPrice: number,
    taxPercent: number,
    mode: TaxPricingMode,
): number {
    return mode === "including_tax"
        ? inclusiveTaxAmount(enteredPrice, taxPercent)
        : expectedTaxAmount(enteredPrice, taxPercent);
}

export function expectedGrandTotalForMode(
    enteredPrice: number,
    taxPercent: number,
    mode: TaxPricingMode,
): number {
    return mode === "including_tax"
        ? enteredPrice
        : expectedGrandTotal(enteredPrice, taxPercent);
}

export function appliedPercentage(grandTotal: number, tax: number): number {
    const net = grandTotal - tax;

    return net === 0 ? 0 : (tax / net) * 100;
}

export type TaxApplyOnMode = "before_discount" | "after_discount";

export function expectedDiscountedTotals(
    price: number,
    taxPercent: number,
    discountAmount: number,
    applyOn: TaxApplyOnMode,
): { discount: number; taxBase: number; tax: number; grandTotal: number } {
    const discount = Math.round(discountAmount * 100) / 100;
    const net = price - discount;
    const taxBase = applyOn === "before_discount" ? price : net;
    const tax = expectedTaxAmount(taxBase, taxPercent);
    const grandTotal = Math.round((net + tax) * 100) / 100;

    return { discount, taxBase, tax, grandTotal };
}


export async function createSimpleTaxableProduct(
    adminPage: Page,
    price: number = TAX_PRODUCT_PRICE,
): Promise<string> {
    const name = `Taxable ${uniqueStamp()}`;

    await new ProductCreatePage(adminPage).createSimpleProduct({
        name,
        productNumber: generateSKU(),
        shortDescription: generateDescription(80),
        description: generateDescription(120),
        price: `${price}`,
        weight: "1",
        inventory: "100",
    });

    return name;
}

export async function assignTaxCategoryToProduct(
    adminPage: Page,
    productName: string,
    taxCategoryName: string,
): Promise<void> {
    await new ProductEditPage(adminPage).assignTaxCategory(
        productName,
        taxCategoryName,
    );
}
