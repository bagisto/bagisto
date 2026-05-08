import { test } from "../../../setup";
import { expect, Page } from "@playwright/test";
import { ProductCreation } from "../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../utils/admin";

function calculateFreeQty(
    totalQty: number,
    discountStep: number,
    discountAmount: number,
): number {
    if (!discountStep || discountAmount > discountStep) return 0;

    const cycleSize = discountStep + discountAmount;

    const fullCycles = Math.floor(totalQty / cycleSize);

    const remainder = totalQty - fullCycles * cycleSize;

    let freeQty = fullCycles * discountAmount;

    if (remainder > discountStep) {
        freeQty += remainder - discountStep;
    }

    return freeQty;
}

async function createBuyXGetYRule(
    page: Page,
    discountStep: number,
    discountAmount: number,
) {
    const ruleCreatePage = new RuleCreatePage(page);

    await loginAsAdmin(page);
    await ruleCreatePage.cartRuleCreationFlow();
    await ruleCreatePage.actionTypeSelect.selectOption("buy_x_get_y");
    await ruleCreatePage.discountAmountInput.fill(discountAmount.toString());
    await ruleCreatePage.discountStepInput.fill(discountStep.toString());
    await ruleCreatePage.saveCartRule();
}

async function verifyBuyXGetYAtCheckout(
    page: Page,
    ruleApplyPage: RuleApplyPage,
    discountStep: number,
    discountAmount: number,
    qty: number,
) {
    await ruleApplyPage.visit("");

    const product = ruleApplyPage.getSavedProduct();

    await ruleApplyPage.searchInput.fill(product.name);
    await ruleApplyPage.searchInput.press("Enter");
    await ruleApplyPage.addToCartButton.first().click();
    await expect(ruleApplyPage.addToCartSuccessMessage).toBeVisible();

    await ruleApplyPage.visit("checkout/cart");

    if (qty > 1) {
        for (let i = 1; i < qty; i++) {
            await ruleApplyPage.incrementQtyButton.first().click();
        }

        await ruleApplyPage.updateCart.click();
        await expect(ruleApplyPage.cartUpdateSuccess.first()).toBeVisible();
    }

    const subtotal = await ruleApplyPage.getSubTotalValue();

    const unitPrice = subtotal / qty;

    const freeQty = calculateFreeQty(qty, discountStep, discountAmount);

    const expectedDiscount = freeQty * unitPrice;

    const expectedGrandTotal = Math.max(subtotal - expectedDiscount, 0);

    await ruleApplyPage.applyCouponAtCheckout();

    await expect(
        page.getByText("Coupon code applied successfully.").first(),
    ).toBeVisible();

    const grandTotalEl = page
        .getByText("Grand Total")
        .locator("..")
        .locator("p")
        .last();

    await expect(grandTotalEl).toBeVisible();

    const actualGrandTotalText = await grandTotalEl.innerText();

    const actualGrandTotal = parseFloat(
        actualGrandTotalText.replace(/[^0-9.]/g, ""),
    );

    expect(actualGrandTotal).toBeCloseTo(expectedGrandTotal, 2);
}

const cases: {
    desc: string;
    step: number;
    amount: number;
    qty: number;
}[] = [
    {
        desc: "Buy 1 Get 1 Free with 2 items",
        step: 1,
        amount: 1,
        qty: 2,
    },
    {
        desc: "Buy 2 Get 1 Free with 3 items",
        step: 2,
        amount: 1,
        qty: 3,
    },
    {
        desc: "Buy 2 Get 1 Free with 6 items (2 full cycles)",
        step: 2,
        amount: 1,
        qty: 6,
    },
    {
        desc: "Buy 3 Get 2 Free with 10 items (2 full cycles)",
        step: 3,
        amount: 2,
        qty: 10,
    },
    {
        desc: "Buy 3 Get 2 Free with 14 items (leftover exceeds step)",
        step: 3,
        amount: 2,
        qty: 14,
    },
    {
        desc: "No discount when quantity is below one cycle",
        step: 1,
        amount: 1,
        qty: 1,
    },
    {
        desc: "No discount when discount amount exceeds discount step",
        step: 1,
        amount: 2,
        qty: 2,
    },
];

test.beforeEach(async ({ adminPage }) => {
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

test.afterEach(async ({ adminPage }) => {
    const ruleDeletePage = new RuleDeletePage(adminPage);
    await ruleDeletePage.deleteRuleAndProduct();
});

test.describe("buy x get y free cart rules", () => {
    for (const { desc, step, amount, qty } of cases) {
        test(desc, async ({ page }) => {
            await createBuyXGetYRule(page, step, amount);

            const ruleApplyPage = new RuleApplyPage(page);

            await verifyBuyXGetYAtCheckout(
                page,
                ruleApplyPage,
                step,
                amount,
                qty,
            );
        });
    }
});
