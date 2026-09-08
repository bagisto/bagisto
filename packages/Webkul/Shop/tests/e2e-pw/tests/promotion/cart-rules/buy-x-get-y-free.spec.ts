import { uniqueStamp } from "../../../utils/faker";
import { ProductListPage } from "../../../pages/admin/catalog/products/ProductListPage";
import type { BaseProduct } from "../../../pages/types/product.types";
import { test } from "../../../setup";
import type { Page } from "@playwright/test";
import { ProductCreatePage } from "../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../pages/shop/rules/RuleApplyPage";

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
    adminPage: Page,
    discountStep: number,
    discountAmount: number,
): Promise<string> {
    const ruleCreatePage = new RuleCreatePage(adminPage);
    const rule = await ruleCreatePage.cartRuleCreationFlow();

    createdRules.push(rule.name);

    await ruleCreatePage.setBuyXGetYAction(discountAmount, discountStep);
    await ruleCreatePage.saveCartRule();

    return rule.couponCode;
}

async function verifyBuyXGetYAtCheckout(
    ruleApplyPage: RuleApplyPage,
    couponCode: string,
    discountStep: number,
    discountAmount: number,
    qty: number,
) {
    const subtotal = await ruleApplyPage.addSavedProductToCart(product.name, qty);
    const unitPrice = subtotal / qty;
    const freeQty = calculateFreeQty(qty, discountStep, discountAmount);
    const expectedGrandTotal = Math.max(subtotal - freeQty * unitPrice, 0);

    await ruleApplyPage.proceedAsGuest();
    await ruleApplyPage.chooseShipping("free");
    await ruleApplyPage.choosePayment("moneytransfer");
    await ruleApplyPage.applyCoupon(couponCode);

    await ruleApplyPage.expectGrandTotal(expectedGrandTotal);
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

let product: BaseProduct;
let createdRules: string[];

test.beforeEach(async ({ adminPage }) => {
    createdRules = [];
    product = await new ProductCreatePage(adminPage).createProduct({
        type: "simple",
        sku: `SKU-${uniqueStamp()}`,
        name: `Simple-${uniqueStamp()}`,
        shortDescription: "Short desc",
        description: "Full desc",
        price: 199,
        weight: 1,
        inventory: 100,
    });
});

test.afterEach(async ({ adminPage }) => {
    try {
        await new RuleDeletePage(adminPage).deleteCartRulesIfPresent(createdRules);
    } finally {
        await new ProductListPage(adminPage).deleteProductsIfPresent([product.name]);
    }
});

test.describe("buy x get y free cart rules", () => {
    for (const { desc, step, amount, qty } of cases) {
        test(desc, async ({ adminPage, shopPage }) => {
            const couponCode = await createBuyXGetYRule(adminPage, step, amount);

            await verifyBuyXGetYAtCheckout(
                new RuleApplyPage(shopPage),
                couponCode,
                step,
                amount,
                qty,
            );
        });
    }
});
