import type { Page } from "@playwright/test";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductEditPage } from "../../pages/admin/catalog/products/ProductEditPage";
import { RuleCreatePage } from "../../pages/admin/marketing/promotion/RuleCreatePage";
import type { BaseProduct } from "../../pages/types/product.types";
import { loginAsCustomer } from "../../utils/customer";
import { uniqueStamp } from "../../utils/faker";

export const RULE_PRODUCT_PRICE = 199;

export type CouponType = "fixed" | "percentage";

export type ConditionOperator =
    | "=="
    | "!="
    | ">="
    | "<="
    | ">"
    | "<"
    | "{}"
    | "!{}";

export type ShopSession = "guest" | "customer";

export type Lazy<T> = T | (() => T);

export type ProductChange =
    | { kind: "input"; code: string; value: string }
    | { kind: "select"; code: string; label: string }
    | { kind: "category"; name: string };

export interface RuleConditionRow {
    operator: ConditionOperator;
    value?: Lazy<string>;
    optionSelect?: Lazy<string>;
    checkboxSelect?: string;
    productBeforeRule?: Lazy<ProductChange>;
    productAfterRule?: Lazy<ProductChange>;
    cartQuantityIncrements?: number;
    shopSession?: ShopSession;
}

export interface RuleConditionGroup {
    conditionLabel: string;
    attribute: string;
    rows: RuleConditionRow[];
}

export interface RuleConditionCase extends RuleConditionRow {
    conditionLabel: string;
    attribute: string;
    couponType: CouponType;
}

const COUPON_TYPES: CouponType[] = ["fixed", "percentage"];

const OPERATOR_LABELS: Record<ConditionOperator, string> = {
    "==": "is equal to",
    "!=": "is not equal to",
    ">=": "is greater than or equal to",
    "<=": "is less than or equal to",
    ">": "is greater than",
    "<": "is less than",
    "{}": "contains",
    "!{}": "does not contain",
};

function unwrap<T>(value: Lazy<T> | undefined): T | undefined {
    if (typeof value === "function") {
        return (value as () => T)();
    }

    return value;
}

export function buildRuleConditionCases(
    groups: RuleConditionGroup[],
): RuleConditionCase[] {
    return groups.flatMap(({ conditionLabel, attribute, rows }) =>
        rows.flatMap((row) =>
            COUPON_TYPES.map((couponType) => ({
                ...row,
                conditionLabel,
                attribute,
                couponType,
            })),
        ),
    );
}

export function caseTitle(testCase: RuleConditionCase, outcome: string): string {
    const operatorLabel = OPERATOR_LABELS[testCase.operator];

    return `should ${outcome} when the ${testCase.conditionLabel} condition is -> ${operatorLabel} (${testCase.couponType})`;
}

export async function createRuleProduct(adminPage: Page): Promise<BaseProduct> {
    return new ProductCreatePage(adminPage).createProduct({
        type: "simple",
        sku: `SKU-${uniqueStamp()}`,
        name: `Simple-${uniqueStamp()}`,
        shortDescription: "Short desc",
        description: "Full desc",
        price: RULE_PRODUCT_PRICE,
        weight: 1,
        inventory: 100,
    });
}

export async function applyProductChange(
    adminPage: Page,
    productName: string,
    change: Lazy<ProductChange> | undefined,
): Promise<void> {
    const resolved = unwrap(change);

    if (!resolved) {
        return;
    }

    const productEditPage = new ProductEditPage(adminPage);

    await productEditPage.openProduct(productName);

    if (resolved.kind === "input") {
        await productEditPage.fillInput(resolved.code, resolved.value);
    }

    if (resolved.kind === "select") {
        await productEditPage.selectOption(resolved.code, resolved.label);
    }

    if (resolved.kind === "category") {
        await productEditPage.assignCategory(resolved.name);
    }

    await productEditPage.save();
}

export async function addRuleCondition(
    ruleCreatePage: RuleCreatePage,
    testCase: RuleConditionCase,
    scopeSku?: string,
): Promise<number> {
    const discountValue = await ruleCreatePage.addCondition({
        attribute: testCase.attribute,
        operator: testCase.operator,
        value: unwrap(testCase.value),
        optionSelect: unwrap(testCase.optionSelect),
        checkboxSelect: testCase.checkboxSelect,
        couponType: testCase.couponType,
        scopeSku,
    });

    if (discountValue === undefined) {
        throw new Error(
            `No discount was configured for the ${testCase.conditionLabel} condition.`,
        );
    }

    return discountValue;
}

export async function openShopSession(
    shopPage: Page,
    session: ShopSession | undefined,
): Promise<void> {
    if (session === "customer") {
        await loginAsCustomer(shopPage);
    }
}
