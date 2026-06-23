import { test } from "../../../setup";
import { expect } from "@playwright/test";
import { RuleCreatePage } from "../../../pages/admin/marketing/promotion/RuleCreatePage";
import { loginAsAdmin } from "../../../utils/admin";

test.describe("cart rules validation", () => {
    test("should show validation errors when saving cart rule without required fields", async ({
        page,
    }) => {
        await loginAsAdmin(page);

        const ruleCreatePage = new RuleCreatePage(page);

        await page.goto("admin/marketing/promotions/cart-rules");
        await ruleCreatePage.createCartRuleButton.click();
        await ruleCreatePage.cartRuleForm.waitFor();
        await ruleCreatePage.saveCartRuleButton.click();

        await expect(ruleCreatePage.validationErrors).not.toHaveCount(0);
        await expect(
            page.getByText("The Name field is required").first(),
        ).toBeVisible();
        await expect(
            page.getByText("The Channels field is required").first(),
        ).toBeVisible();
        await expect(
            page.getByText("The Customer Groups field is required").first(),
        ).toBeVisible();
    });
});
