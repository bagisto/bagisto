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
        price: 199,
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
    test.describe("product attribute conditions", () => {
        test("should apply coupon when category of product condition is -> contains", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|category_ids",
                operator: "{}",
                checkboxSelect: "Mens",
                couponType: "fixed",
            });
            await ruleCreatePage.saveCartRule();
            await page.goto("admin/catalog/products");
            await page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1)
                .click();
            await page.waitForLoadState("networkidle");
            const mensLabel = page.locator("label", {
                hasText: /^Mens$/,
            });
            const mensCheckbox = mensLabel.locator('input[type="checkbox"]');
            await expect(mensCheckbox).toBeAttached();

            if (!(await mensCheckbox.isChecked())) {
                await mensLabel.click();
            }
            await expect(mensCheckbox).toBeChecked();

            await page
                .locator('button:has-text("Save Product")')
                .first()
                .click();
            await expect(
                page.getByText("Product updated successfully").first(),
            ).toBeVisible();

            await ruleApplyPage.applyCoupon("yes");
        });

        test("should apply coupon when category of product condition is -> does not contains", async ({
            page,
        }) => {
            const ruleCreatePage = new RuleCreatePage(page);
            const ruleApplyPage = new RuleApplyPage(page);
            await loginAsAdmin(page);
            await ruleCreatePage.cartRuleCreationFlow();
            await ruleCreatePage.addCondition({
                attribute: "product|category_ids",
                operator: "!{}",
                checkboxSelect: "Mens",
                couponType: "fixed",
            });
            await ruleCreatePage.saveCartRule();
            await page.goto("admin/catalog/products");
            await page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1)
                .click();
            await page.waitForLoadState("networkidle");
            const mensLabel = page.locator("label", {
                hasText: /^Womens$/,
            });
            const mensCheckbox = mensLabel.locator('input[type="checkbox"]');
            await expect(mensCheckbox).toBeAttached();

            if (!(await mensCheckbox.isChecked())) {
                await mensLabel.click();
            }
            await expect(mensCheckbox).toBeChecked();

            await page
                .locator('button:has-text("Save Product")')
                .first()
                .click();
            await expect(
                page.getByText("Product updated successfully").first(),
            ).toBeVisible();

            await ruleApplyPage.applyCoupon("yes");
        });
    });
});
