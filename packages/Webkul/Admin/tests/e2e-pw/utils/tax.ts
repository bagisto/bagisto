import { expect, Page } from "@playwright/test";
import { generateName, generateSKU, generateSlug } from "./faker";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";

/**
 * Default catalog price used by the tax e2e suite. Interpreted as net-of-tax in
 * `excluding_tax` mode and as gross-of-tax in `including_tax` mode.
 */
export const TAX_PRODUCT_PRICE = 199;

/**
 * Shape of a single tax rate used across the tax e2e suite.
 *
 * `state` is intentionally optional: leaving it empty creates a rate that
 * applies to every state of the country (Bagisto treats an empty/`*` state as
 * a wildcard), which keeps storefront tax-application tests deterministic.
 */
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

/**
 * Shape of a tax category that bundles one or more tax rates.
 */
export interface TaxCategoryData {
    code: string;
    name: string;
    description: string;
}

/**
 * Region presets whose `country`/`state` line up with the address Bagisto fills
 * during guest checkout, so a rate created for the region is guaranteed to be
 * applied on the storefront. `state` is left empty to act as a wildcard.
 */
export const TAX_REGIONS = {
    india: { country: "IN", checkoutState: "UP" },
    unitedStates: { country: "US", checkoutState: "CA" },
} as const;

/**
 * Build a unique tax rate payload. Every field can be overridden so a single
 * generator serves creation, validation and storefront scenarios.
 */
export function generateTaxRateData(
    overrides: Partial<TaxRateData> = {},
): TaxRateData {
    return {
        identifier: generateSlug("_"),
        country: TAX_REGIONS.india.country,
        state: "",
        taxRate: "18",
        isZip: false,
        ...overrides,
    };
}

/**
 * Build a unique tax category payload.
 */
export function generateTaxCategoryData(
    overrides: Partial<TaxCategoryData> = {},
): TaxCategoryData {
    return {
        code: generateSlug("_"),
        // Suffix keeps the name globally unique so the product datagrid's tax
        // category dropdown never has two same-named options (which would break
        // an exact-text selection across repeated runs).
        name: `${generateName()} ${Date.now().toString(36)}`,
        description: "Tax category created by the e2e suite.",
        ...overrides,
    };
}

/**
 * Whether catalog prices are entered net of tax (`excluding_tax`) or gross of
 * tax (`including_tax`). Maps to `sales.taxes.calculation.product_prices`.
 */
export type TaxPricingMode = "excluding_tax" | "including_tax";

/**
 * Tax applied on a net amount for the given percentage, rounded to 2 decimals
 * the same way Bagisto formats money in the storefront summary. This is the
 * tax-EXCLUSIVE case where the entered price is the net amount.
 */
export function expectedTaxAmount(netAmount: number, taxPercent: number): number {
    return Math.round(netAmount * taxPercent) / 100;
}

/**
 * Net amount plus its tax — the grand total a tax-exclusive cart should show
 * when shipping is free and no discount applies.
 */
export function expectedGrandTotal(netAmount: number, taxPercent: number): number {
    return (
        Math.round((netAmount + expectedTaxAmount(netAmount, taxPercent)) * 100) /
        100
    );
}

/**
 * Tax extracted from a tax-INCLUSIVE (gross) price. Bagisto pulls the tax out of
 * the gross with `gross * rate / (100 + rate)`, so the customer still pays the
 * gross price as the grand total.
 */
export function inclusiveTaxAmount(grossAmount: number, taxPercent: number): number {
    return Math.round((grossAmount * taxPercent * 100) / (100 + taxPercent)) / 100;
}

/**
 * Expected tax for the given pricing mode.
 */
export function expectedTaxForMode(
    enteredPrice: number,
    taxPercent: number,
    mode: TaxPricingMode,
): number {
    return mode === "including_tax"
        ? inclusiveTaxAmount(enteredPrice, taxPercent)
        : expectedTaxAmount(enteredPrice, taxPercent);
}

/**
 * Expected grand total for the given pricing mode. With tax-inclusive prices the
 * grand total equals the entered (gross) price; with tax-exclusive prices the
 * tax is added on top.
 */
export function expectedGrandTotalForMode(
    enteredPrice: number,
    taxPercent: number,
    mode: TaxPricingMode,
): number {
    return mode === "including_tax"
        ? enteredPrice
        : expectedGrandTotal(enteredPrice, taxPercent);
}

/**
 * Reverse-derive the percentage that was actually applied from the summary's
 * tax and grand-total figures: `tax / net * 100`, where `net = grand - tax`.
 * Used to assert the applied rate is correct regardless of pricing mode.
 */
export function appliedPercentage(grandTotal: number, tax: number): number {
    const net = grandTotal - tax;

    return net === 0 ? 0 : (tax / net) * 100;
}

/**
 * Whether tax is calculated on the full price (`before_discount`) or on the
 * discounted price (`after_discount`). Maps to
 * `sales.taxes.calculation.apply_tax_on` (Bagisto default: `after_discount`).
 */
export type TaxApplyOnMode = "before_discount" | "after_discount";

/**
 * Expected summary figures for a tax-exclusive cart that has a cart-rule
 * discount applied, for the given "apply tax on" mode.
 *
 *   - before_discount: tax is charged on the full price, then the discount is
 *     subtracted from the total.
 *   - after_discount:  the discount is removed from the taxable base first, so
 *     tax is charged on the discounted price.
 */
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

/**
 * Format a number the way the storefront prints money (e.g. `$218.90`).
 */
export function formatPrice(amount: number): string {
    return `$${amount.toFixed(2)}`;
}

/**
 * Create a tax-exclusive-priced simple product (priced at
 * {@link TAX_PRODUCT_PRICE}) and return its searchable name. The price is
 * stored verbatim; the active tax mode decides whether it is treated as net or
 * gross at checkout.
 */
export async function createSimpleTaxableProduct(
    adminPage: Page,
    price: number = TAX_PRODUCT_PRICE,
): Promise<string> {
    const name = `Simple-${generateName()}-${Date.now()}`;

    await new ProductCreatePage(adminPage).createSimpleProduct({
        name,
        productNumber: generateSKU(),
        shortDescription: "Short description for tax product.",
        description: "Full description for tax product.",
        price: `${price}`,
        weight: "1",
        inventory: "100",
    });

    return name;
}

/**
 * Assign an existing tax category to the most recently created product through
 * the product datagrid's inline editor. This mirrors the proven flow used by
 * the Shop tax suite and avoids depending on the searchable select widget on
 * the full product edit form.
 */
export async function assignTaxCategoryToProduct(
    page: Page,
    taxCategoryName: string,
): Promise<void> {
    await page.goto("admin/catalog/products");
    await page.locator("span.cursor-pointer.icon-sort-right").nth(1).click();
    await page.waitForLoadState("networkidle");
    await page.locator('span:text-is("Tax Category")').click();
    await page.locator(`span:text-is("${taxCategoryName}")`).click();
    await page.locator('button:has-text("Save Product")').first().click();

    await expect(
        page.getByText("Product updated successfully").first(),
    ).toBeVisible();
}
