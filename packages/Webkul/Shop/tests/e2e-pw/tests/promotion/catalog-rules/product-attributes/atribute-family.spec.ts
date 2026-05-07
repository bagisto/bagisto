import { test, expect } from "../../../../setup";
import { ProductCreation } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { RuleDeletePage } from "../../../../pages/admin/marketing/promotion/RuleDeletePage";
import { RuleCreatePage } from "../../../../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleApplyPage } from "../../../../pages/shop/rules/RuleApplyPage";
import { loginAsAdmin } from "../../../../utils/admin";
import { generateName, generateSlug } from "../../../../utils/faker";

test.beforeEach(async ({ adminPage }) => {
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

test.afterEach(async ({ page }) => {
    const ruleDeletePage = new RuleDeletePage(page);
    await ruleDeletePage.deleteCatalogRuleAndProduct();
});

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
        const randomTargetIndex = Math.floor(Math.random() * targets.length);
        const target = targets[randomTargetIndex];

        const attributeBox = await attribute.boundingBox();
        const targetBox = await target.boundingBox();

        if (attributeBox && targetBox) {
            const randomX = targetBox.x + Math.random() * targetBox.width;
            const randomY = targetBox.y + Math.random() * targetBox.height;

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

async function runCatalogRuleTest({
    page,
    operator,
    optionSelect,
}: {
    page: any;
    operator: string;
    optionSelect: string;
}) {
    const ruleCreatePage = new RuleCreatePage(page);
    const ruleApplyPage = new RuleApplyPage(page);

    await loginAsAdmin(page);

    await ruleCreatePage.catalogRuleCreationFlow();

    const discountValue = await ruleCreatePage.addCondition({
        attribute: "product|attribute_family_id",
        operator,
        optionSelect,
        couponType: "percentage",
    });

    await ruleCreatePage.saveCatalogRule();

    await ruleApplyPage.verifyCatalogRule(discountValue ?? 0);
}

const testCases = [
    {
        operator: "==",
        optionSelect: "1",
        label: "is equal to",
    },
    {
        operator: "!=",
        optionSelect: "2",
        label: "is not equal to",
    },
];

test.describe("catalog rules", () => {
    test.describe("product attribute conditions", () => {
        for (const tc of testCases) {
            test(`should apply coupon when attribute family condition is-> ${tc.label}`, async ({
                page,
            }) => {
                await runCatalogRuleTest({
                    page,
                    operator: tc.operator,
                    optionSelect: tc.optionSelect,
                });
            });
        }
    });
});
