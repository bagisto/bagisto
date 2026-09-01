import { test } from "../../../setup";
import { RuleCreatePage } from "../../../pages/admin/marketing/promotion/RuleCreatePage";
import { loginAsAdmin } from "../../../utils/admin";

test.describe("cart rules validation", () => {
    test("should show validation errors when saving cart rule without required fields", async ({
        page,
    }) => {
        await loginAsAdmin(page);

        const ruleCreatePage = new RuleCreatePage(page);

        await ruleCreatePage.saveCartRuleWithoutRequiredFields();
        await ruleCreatePage.expectRequiredFieldErrors();
    });
});
