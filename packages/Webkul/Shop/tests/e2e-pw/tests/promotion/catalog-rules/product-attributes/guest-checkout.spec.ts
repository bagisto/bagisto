import { expect, test } from "../../../../setup";
import { ProductCreation } from "../../../../pages/product";
import { CreateRules } from "../../../../pages/rules";
import { loginAsCustomer } from "../../../../utils/customer";

let generatedName: string;
generatedName = `Simple-${Date.now()}`;

test.beforeEach("should create simple product", async ({ adminPage }) => {
    const productCreation = new ProductCreation(adminPage);

    await productCreation.createProduct({
        type: "simple",
        sku: `SKU-${Date.now()}`,
        name: generatedName,
        shortDescription: "Short desc",
        description: "Full desc",
        price: 199,
        weight: 1,
        inventory: 199,
    });
});

test.afterEach(
    "should delete the created product and rule",
    async ({ adminPage }) => {
        const createRules = new CreateRules(adminPage);
        await createRules.deleteCatalogRuleAndProduct();
    },
);

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        test("should apply coupon when product guest checkout condition is -> is equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.catalogRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|guest_checkout",
                operator: "==",
                optionSelect: "1",
            });
            await createRules.saveCatalogRule();
            await loginAsCustomer(page);
            await createRules.verifyCatalogRule();
        });

        test("should apply coupon when product guest checkout condition is -> is not equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.catalogRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|guest_checkout",
                operator: "!=",
                optionSelect: "0",
            });
            await createRules.saveCatalogRule();
            await loginAsCustomer(page);
            await createRules.verifyCatalogRule();
        });
    });
});
