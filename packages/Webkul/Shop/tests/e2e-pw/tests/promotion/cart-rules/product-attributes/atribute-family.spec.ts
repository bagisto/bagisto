import { test } from "../../../../setup";
import { expect } from "@playwright/test";
import { ProductCreation } from "../../../../pages/product";
import { CreateRules } from "../../../../pages/rules";
import { generateName, generateSlug } from "../../../../utils/faker";

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
        test("should create attribute family for creating rule", async ({
            adminPage,
        }) => {
            await adminPage.goto("admin/catalog/families");
            await adminPage.waitForSelector("div.primary-button", {
                state: "visible",
            });

            await adminPage.click("div.primary-button:visible");
            await adminPage
                .waitForSelector("div#not_avaliable", { timeout: 1000 })
                .catch(() => null);

            await adminPage.fill('input[name="name"]', generateName());
            await adminPage.fill('input[name="code"]', generateSlug("_"));

            const attributes = await adminPage.$$("i.icon-drag");
            const targets = await adminPage.$$(
                'div[class="flex [&>*]:flex-1 gap-5 justify-between px-4"] > div > div[class="h-[calc(100vh-285px)] overflow-auto border-gray-200 pb-4 ltr:border-r rtl:border-l"]',
            );

            for (const attribute of attributes) {
                const randomTargetIndex = Math.floor(
                    Math.random() * targets.length,
                );
                const target = targets[randomTargetIndex];

                const attributeBox = await attribute.boundingBox();
                const targetBox = await target.boundingBox();

                if (attributeBox && targetBox) {
                    const randomX =
                        targetBox.x + Math.random() * targetBox.width;
                    const randomY =
                        targetBox.y + Math.random() * targetBox.height;

                    await adminPage.mouse.move(
                        attributeBox.x + attributeBox.width / 2,
                        attributeBox.y + attributeBox.height / 2,
                    );
                    await adminPage.mouse.down();
                    await adminPage.mouse.move(randomX, randomY);
                    await adminPage.mouse.up();
                }
            }

            await adminPage.click(".primary-button:visible");
            await expect(
                adminPage.getByText("Family created successfully.").first(),
            ).toBeVisible();
        });

        test("should apply coupon when attribute family of product condition is -> is equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|attribute_family_id",
                operator: "==",
                optionSelect: "1",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
        });

        test("should apply coupon when attribute family of product condition is -> is not equal to", async ({
            page,
        }) => {
            const createRules = new CreateRules(page);
            await createRules.adminlogin();
            await createRules.cartRuleCreationFlow();
            await createRules.addCondition({
                attribute: "product|attribute_family_id",
                operator: "!=",
                optionSelect: "2",
            });
            await createRules.saveCartRule();
            await createRules.applyCoupon();
        });
    });
});
