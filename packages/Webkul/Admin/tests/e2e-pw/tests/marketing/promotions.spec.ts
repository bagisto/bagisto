import { test } from "../../setup";
import {
    buildCartRule,
    CartRulePage,
} from "../../pages/admin/marketing/promotion/CartRulePage";
import {
    buildCatalogRule,
    CatalogRulePage,
} from "../../pages/admin/marketing/promotion/CatalogRulePage";
import { generateName, uniqueStamp } from "../../utils/faker";

test.describe("promotion management", () => {
    test.describe("cart rule management", () => {
        let cartRulePage: CartRulePage;
        let created: string[];

        test.beforeEach(async ({ adminPage }) => {
            cartRulePage = new CartRulePage(adminPage);
            created = [];
        });

        test.afterEach(async () => {
            await cartRulePage.deleteCartRulesIfPresent(created);
        });

        test("should create a cart rule and list it with its coupon code", async () => {
            const rule = buildCartRule();
            created.push(rule.name);

            await cartRulePage.createCartRule(rule);

            await cartRulePage.expectCartRuleListed(rule);
        });

        test("should reject a cart rule without its required fields", async () => {
            await cartRulePage.submitEmptyCreateForm();

            await cartRulePage.expectValidationError("The Name field is required");
            await cartRulePage.expectValidationError(
                "The Channels field is required",
            );
            await cartRulePage.expectValidationError(
                "The Customer Groups field is required",
            );
            await cartRulePage.expectStillOnCreateForm();
        });

        test("should reject a cart rule whose coupon code is already used", async () => {
            const existing = buildCartRule();
            const duplicate = buildCartRule({ couponCode: existing.couponCode });
            created.push(existing.name, duplicate.name);

            await cartRulePage.createCartRule(existing);
            await cartRulePage.attemptCreateCartRule(duplicate);

            await cartRulePage.expectValidationError(
                "The coupon code has already been taken.",
            );
            await cartRulePage.expectCartRuleAbsent(duplicate.name);
            await cartRulePage.expectCouponCodeListedOnce(existing.couponCode);
        });

        test("should rename a cart rule and keep the new name after reload", async () => {
            const rule = buildCartRule();
            const newName = `${generateName()} ${uniqueStamp()}`;
            created.push(rule.name, newName);

            await cartRulePage.createCartRule(rule);
            await cartRulePage.renameCartRule(rule.name, newName);

            await cartRulePage.expectCartRuleListed({ ...rule, name: newName });
            await cartRulePage.expectCartRuleAbsent(rule.name);
            await cartRulePage.expectNameInEditForm(newName);
        });

        test("should delete a cart rule and remove it from the grid", async () => {
            const rule = buildCartRule();
            const untouched = buildCartRule({ couponCode: `KEEP${uniqueStamp()}` });
            created.push(rule.name, untouched.name);

            await cartRulePage.createCartRule(rule);
            await cartRulePage.createCartRule(untouched);
            await cartRulePage.deleteCartRule(rule.name);

            await cartRulePage.expectCartRuleAbsent(rule.name);
            await cartRulePage.expectCartRuleListed(untouched);
        });
    });

    test.describe("catalog rule management", () => {
        let catalogRulePage: CatalogRulePage;
        let created: string[];

        test.beforeEach(async ({ adminPage }) => {
            catalogRulePage = new CatalogRulePage(adminPage);
            created = [];
        });

        test.afterEach(async () => {
            await catalogRulePage.deleteCatalogRulesIfPresent(created);
        });

        test("should create a catalog rule and list it as active", async () => {
            const rule = buildCatalogRule();
            created.push(rule.name);

            await catalogRulePage.createCatalogRule(rule);

            await catalogRulePage.expectCatalogRuleListed(rule.name);
        });

        test("should reject a catalog rule without its required fields", async () => {
            await catalogRulePage.submitEmptyCreateForm();

            await catalogRulePage.expectValidationError(
                "The Name field is required",
            );
            await catalogRulePage.expectValidationError(
                "The Channels field is required",
            );
            await catalogRulePage.expectValidationError(
                "The Customer Groups field is required",
            );
            await catalogRulePage.expectStillOnCreateForm();
        });

        test("should rename a catalog rule and keep the new name after reload", async () => {
            const rule = buildCatalogRule();
            const newName = `${generateName()} ${uniqueStamp()}`;
            created.push(rule.name, newName);

            await catalogRulePage.createCatalogRule(rule);
            await catalogRulePage.renameCatalogRule(rule.name, newName);

            await catalogRulePage.expectCatalogRuleListed(newName);
            await catalogRulePage.expectCatalogRuleAbsent(rule.name);
            await catalogRulePage.expectNameInEditForm(newName);
        });

        test("should delete a catalog rule and remove it from the grid", async () => {
            const rule = buildCatalogRule();
            const untouched = buildCatalogRule();
            created.push(rule.name, untouched.name);

            await catalogRulePage.createCatalogRule(rule);
            await catalogRulePage.createCatalogRule(untouched);
            await catalogRulePage.deleteCatalogRule(rule.name);

            await catalogRulePage.expectCatalogRuleAbsent(rule.name);
            await catalogRulePage.expectCatalogRuleListed(untouched.name);
        });
    });
});
