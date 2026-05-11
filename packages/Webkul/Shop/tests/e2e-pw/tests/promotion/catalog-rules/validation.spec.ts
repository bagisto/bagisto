import { test } from "../../../setup";
import { expect } from "@playwright/test";
import { RuleCreatePage } from "../../../pages/admin/marketing/promotion/RuleCreatePage";

test.describe("catalog rules validation", () => {
    test("should show validation errors when saving cart rule without required fields", async ({
        adminPage,
    }) => {
        const ruleCreatePage = new RuleCreatePage(adminPage);

        await adminPage.goto("admin/marketing/promotions/catalog-rules");
        await ruleCreatePage.createCatalogRuleButton.click();
        await adminPage.waitForLoadState("networkidle");
        await ruleCreatePage.catalogRuleButton.click();

        await expect(ruleCreatePage.validationErrors).not.toHaveCount(0);
        await expect(
            adminPage.getByText("The Name field is required").first(),
        ).toBeVisible();
        await expect(
            adminPage.getByText("The Channels field is required").first(),
        ).toBeVisible();
        await expect(
            adminPage
                .getByText("The Customer Groups field is required")
                .first(),
        ).toBeVisible();
    });
});
