import { test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/product";
import { CreateRules } from "../../../../pages/rules";

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
        const createRules = new CreateRules(adminPage);
        await createRules.deleteRuleAndProduct();
    },
);

test.describe("cart rules", () => {
    test.describe("product attribute conditions", () => {
        test("should apply coupon when category of product condition is -> contains", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|category_ids",
                operator: "{}",
                checkboxSelect: "Men",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
        });

        test("should apply coupon when category of product condition is -> does not contains", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|category_ids",
                operator: "!{}",
                checkboxSelect: "Winter Wear",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
        });
    });
});
