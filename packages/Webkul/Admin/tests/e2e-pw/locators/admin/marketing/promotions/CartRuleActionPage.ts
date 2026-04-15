import { Page } from "@playwright/test";

export class CartRuleActionPage {
    constructor(private page: Page) {}

    get ruleName() {
        return this.page.locator("#name");
    }

    get promotionRuleDescription() {
        return this.page.locator("#description");
    }

    get addConditionBtn() {
        return this.page.locator(
            'div.secondary-button:has-text("Add Condition")',
        );
    }

    get discountAmmount() {
        return this.page.locator('input[name="discount_amount"]');
    }

    get sortOrder() {
        return this.page.locator('input[name="sort_order"]');
    }

    get channelDeleteSuccess() {
        return this.page.getByText("Channel deleted successfully.");
    }

    get channelSelect() {
        return this.page.locator('label[for="channel__1"]');
    }

    get channleCreateSuccess() {
        return this.page.getByText("Channel created successfully.");
    }

    get customerGroupSelect() {
        return this.page.locator('label[for="customer_group__2"]');
    }

    get toggleInput() {
        return this.page.locator('input[name="status"]');
    }

    get cartRuleCopySuccess() {
        return this.page.getByText("cart rule copied successfully");
    }

    get cartRuleDeleteSuccess() {
        return this.page.getByText(/Cart Rule Deleted Successfully/i);
    }

    get cartRuleEditSuccess() {
        return this.page.getByText("Cart rule updated successfully");
    }

    get copyBtn() {
        return this.page.locator("span.icon-copy");
    }

    get saveCartRuleBTN() {
        return this.page.getByRole("button", { name: " Save Cart Rule " });
    }

    get cartRuleSuccess() {
        return this.page.getByText("Cart rule created successfully");
    }

    get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    get channlUpdateSuccess() {
        return this.page.getByText("Update Channel Successfully");
    }

    get selectCondition() {
        return this.page.locator('select[id="conditions[0][attribute]"]');
    }

    get conditionName() {
        return this.page.locator('input[name="conditions[0][value]"]');
    }

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }
}
