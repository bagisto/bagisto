import { test } from "../../../setup";
import { RuleCreatePage } from "../../../pages/admin/marketing/promotion/RuleCreatePage";

test.describe("catalog rules validation", () => {
    test("should show validation errors when saving cart rule without required fields", async ({
        adminPage,
    }) => {
        const ruleCreatePage = new RuleCreatePage(adminPage);

        await ruleCreatePage.saveCatalogRuleWithoutRequiredFields();
        await ruleCreatePage.expectRequiredFieldErrors();
    });
});
