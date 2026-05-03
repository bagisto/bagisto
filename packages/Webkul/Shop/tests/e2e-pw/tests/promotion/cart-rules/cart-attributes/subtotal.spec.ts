import { test } from "../../../../setup";
import { expect } from "@playwright/test";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";

test.beforeEach("should create simple product", async ({ adminPage }) => {
    const productCreation = new ProductCreation(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: `SKU-${Date.now()}`,
        name: `Simple-${Date.now()}`,
        shortDescription: "Short desc",
        description: "Full desc",
        price: Math.floor(Math.random() * 1000),
        weight: 1,
        inventory: 100,
    });
});

test.afterEach(
    "should delete the created product and rule",
    async ({ adminPage }) => {
        const ruleDeletePage = new RuleDeletePage(adminPage);
        await ruleDeletePage.deleteRuleAndProduct();
    },
);

test.describe("cart rules", () => {
    test.describe("cart attribute conditions", () => {
        test("should apply coupon when subtotal condition is -> is equal to (fixed)", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            const discountValue = await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: "==",
                value: "199",
                couponType: "fixed",
            });
            if (discountValue === undefined) {
                throw new Error("Discount value was not created.");
            }

            await ruleCreatePage.saveCartRule();
            const discountedAmount =
                await ruleApplyPage.calculateDiscountedAmmount(
                    discountValue,
                    "fixed",
                );
            await ruleApplyPage.applyCouponAtCheckout2();

            await expect(
                page.getByText("Coupon code applied successfully.").first(),
            ).toBeVisible();

            if (discountedAmount == 0) {
                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText("$0.00");
            } else {
                await expect(
                    page.getByText("Grand Total").locator(".."),
                ).toContainText(discountedAmount.toString());
            }
        });

        test("should apply coupon when subtotal condition is -> is equal to (percentage)", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            const discountValue = await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: "==",
                value: "199",
                couponType: "percentage",
            });
            if (discountValue === undefined) {
                throw new Error("Discount value was not created.");
            }

            await ruleCreatePage.saveCartRule();
            const discountedAmount =
                await ruleApplyPage.calculateDiscountedAmmount(
                    discountValue,
                    "percentage",
                );
            const grandTotal = Number(discountedAmount.toFixed(2));
            await ruleApplyPage.applyCouponAtCheckout();

            await expect(
                page.getByText("Coupon code applied successfully.").first(),
            ).toBeVisible();
            await page.waitForTimeout(2000);

            if (grandTotal == 0) {
                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText("$0.00");
            } else {
                const formattedAmount = new Intl.NumberFormat("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(grandTotal);

                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText(`$${formattedAmount}`);
            }
        });

        test("should apply coupon when subtotal condition is -> is not equal to (fixed)", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            const discountValue = await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: "!=",
                value: "100",
                couponType: "fixed",
            });
            if (discountValue === undefined) {
                throw new Error("Discount value was not created.");
            }

            await ruleCreatePage.saveCartRule();
            const discountedAmount =
                await ruleApplyPage.calculateDiscountedAmmount(
                    discountValue,
                    "fixed",
                );
            const grandTotal = Number(discountedAmount.toFixed(2));
            await ruleApplyPage.applyCouponAtCheckout();

            await expect(
                page.getByText("Coupon code applied successfully.").first(),
            ).toBeVisible();
            await page.waitForTimeout(2000);

            if (grandTotal == 0) {
                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText("$0.00");
            } else {
                const formattedAmount = new Intl.NumberFormat("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(grandTotal);

                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText(`$${formattedAmount}`);
            }
        });

        test("should apply coupon when subtotal condition is -> is not equal to (percentage)", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            const discountValue = await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: "!=",
                value: "100",
                couponType: "percentage",
            });
            if (discountValue === undefined) {
                throw new Error("Discount value was not created.");
            }

            await ruleCreatePage.saveCartRule();
            const discountedAmount =
                await ruleApplyPage.calculateDiscountedAmmount(
                    discountValue,
                    "percentage",
                );
            const grandTotal = Number(discountedAmount.toFixed(2));
            await ruleApplyPage.applyCouponAtCheckout();

            await expect(
                page.getByText("Coupon code applied successfully.").first(),
            ).toBeVisible();
            await page.waitForTimeout(2000);

            if (grandTotal == 0) {
                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText("$0.00");
            } else {
                const formattedAmount = new Intl.NumberFormat("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(grandTotal);

                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText(`$${formattedAmount}`);
            }
        });

        test("should apply coupon when subtotal condition is -> equals or greater then (fixed)", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            const discountValue = await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: ">=",
                value: "199",
                couponType: "fixed",
            });
            if (discountValue === undefined) {
                throw new Error("Discount value was not created.");
            }

            await ruleCreatePage.saveCartRule();
            const discountedAmount =
                await ruleApplyPage.calculateDiscountedAmmount(
                    discountValue,
                    "fixed",
                );
            const grandTotal = Number(discountedAmount.toFixed(2));
            await ruleApplyPage.applyCouponAtCheckout();

            await expect(
                page.getByText("Coupon code applied successfully.").first(),
            ).toBeVisible();
            await page.waitForTimeout(2000);

            if (grandTotal == 0) {
                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText("$0.00");
            } else {
                const formattedAmount = new Intl.NumberFormat("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(grandTotal);

                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText(`$${formattedAmount}`);
            }
        });

        test("should apply coupon when subtotal condition is -> equals or greater then (percentage)", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            const discountValue = await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: ">=",
                value: "199",
                couponType: "fixed",
            });
            if (discountValue === undefined) {
                throw new Error("Discount value was not created.");
            }

            await ruleCreatePage.saveCartRule();
            const discountedAmount =
                await ruleApplyPage.calculateDiscountedAmmount(
                    discountValue,
                    "fixed",
                );
            const grandTotal = Number(discountedAmount.toFixed(2));
            await ruleApplyPage.applyCouponAtCheckout();

            await expect(
                page.getByText("Coupon code applied successfully.").first(),
            ).toBeVisible();
            await page.waitForTimeout(2000);

            if (grandTotal == 0) {
                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText("$0.00");
            } else {
                const formattedAmount = new Intl.NumberFormat("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(grandTotal);

                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText(`$${formattedAmount}`);
            }
        });

        test("should apply coupon when subtotal condition is -> equals or less than (percentage)", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            const discountValue = await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: "<=",
                value: "200",
                couponType: "percentage",
            });
            if (discountValue === undefined) {
                throw new Error("Discount value was not created.");
            }

            await ruleCreatePage.saveCartRule();
            const discountedAmount =
                await ruleApplyPage.calculateDiscountedAmmount(
                    discountValue,
                    "percentage",
                );
            const grandTotal = Number(discountedAmount.toFixed(2));
            await ruleApplyPage.applyCouponAtCheckout();

            await expect(
                page.getByText("Coupon code applied successfully.").first(),
            ).toBeVisible();
            await page.waitForTimeout(2000);

            if (grandTotal == 0) {
                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText("$0.00");
            } else {
                const formattedAmount = new Intl.NumberFormat("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(grandTotal);

                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText(`$${formattedAmount}`);
            }
        });

        test("should apply coupon when subtotal condition is -> greater than (fixed)", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            const discountValue = await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: ">",
                value: "198",
                couponType: "fixed",
            });
            if (discountValue === undefined) {
                throw new Error("Discount value was not created.");
            }

            await ruleCreatePage.saveCartRule();
            const discountedAmount =
                await ruleApplyPage.calculateDiscountedAmmount(
                    discountValue,
                    "fixed",
                );
            await ruleApplyPage.applyCouponAtCheckout2();

            await expect(
                page.getByText("Coupon code applied successfully.").first(),
            ).toBeVisible();

            if (discountedAmount == 0) {
                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText("$0.00");
            } else {
                await expect(
                    page.getByText("Grand Total").locator(".."),
                ).toContainText(discountedAmount.toString());
            }
        });

        test("should apply coupon when subtotal condition is -> greater than (percentage)", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            const discountValue = await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: ">",
                value: "198",
                couponType: "percentage",
            });
            if (discountValue === undefined) {
                throw new Error("Discount value was not created.");
            }

            await ruleCreatePage.saveCartRule();
            const discountedAmount =
                await ruleApplyPage.calculateDiscountedAmmount(
                    discountValue,
                    "percentage",
                );
            const grandTotal = Number(discountedAmount.toFixed(2));
            await ruleApplyPage.applyCouponAtCheckout();

            await expect(
                page.getByText("Coupon code applied successfully.").first(),
            ).toBeVisible();
            await page.waitForTimeout(2000);

            if (grandTotal == 0) {
                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText("$0.00");
            } else {
                const formattedAmount = new Intl.NumberFormat("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(grandTotal);

                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText(`$${formattedAmount}`);
            }
        });

        test("should apply coupon when subtotal condition is -> less than (fixed)", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            const discountValue = await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: "<",
                value: "200",
                couponType: "fixed",
            });
            if (discountValue === undefined) {
                throw new Error("Discount value was not created.");
            }

            await ruleCreatePage.saveCartRule();
            const discountedAmount =
                await ruleApplyPage.calculateDiscountedAmmount(
                    discountValue,
                    "fixed",
                );
            const grandTotal = Number(discountedAmount.toFixed(2));
            await ruleApplyPage.applyCouponAtCheckout();

            await expect(
                page.getByText("Coupon code applied successfully.").first(),
            ).toBeVisible();
            await page.waitForTimeout(2000);

            if (grandTotal == 0) {
                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText("$0.00");
            } else {
                const formattedAmount = new Intl.NumberFormat("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(grandTotal);

                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText(`$${formattedAmount}`);
            }
        });

        test("should apply coupon when subtotal condition is -> less than (percentage)", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            const discountValue = await ruleCreatePage.addCondition({
                attribute: "cart|base_sub_total",
                operator: "<",
                value: "200",
                couponType: "percentage",
            });
            if (discountValue === undefined) {
                throw new Error("Discount value was not created.");
            }

            await ruleCreatePage.saveCartRule();
            const discountedAmount =
                await ruleApplyPage.calculateDiscountedAmmount(
                    discountValue,
                    "percentage",
                );
            const grandTotal = Number(discountedAmount.toFixed(2));
            await ruleApplyPage.applyCouponAtCheckout();

            await expect(
                page.getByText("Coupon code applied successfully.").first(),
            ).toBeVisible();
            await page.waitForTimeout(2000);

            if (grandTotal == 0) {
                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText("$0.00");
            } else {
                const formattedAmount = new Intl.NumberFormat("en-US", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(grandTotal);

                await expect(
                    page
                        .locator("text=Grand Total")
                        .locator("..")
                        .locator("p")
                        .nth(1),
                ).toContainText(`$${formattedAmount}`);
            }
        });
    });
});
