import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { generateName } from "../../../../utils/faker";

export interface CartRuleData {
    name: string;
    couponCode: string;
    discountPercent: number;
}

/**
 * Page object for creating a coupon-based cart rule
 * (admin/marketing/promotions/cart-rules).
 *
 * Intentionally creates a rule with NO conditions so it applies to any cart the
 * coupon is entered on, which keeps the discount deterministic for the tax
 * before/after-discount assertions.
 */
export class CartRuleCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createButton() {
        return this.page.getByRole("link", { name: "Create Cart Rule" });
    }

    private get nameInput() {
        return this.page.locator("#name");
    }

    private get descriptionInput() {
        return this.page.locator("#description");
    }

    private get couponTypeSelect() {
        return this.page.locator("#coupon_type");
    }

    private get autoGenerationSelect() {
        return this.page.locator("#use_auto_generation");
    }

    private get couponCodeInput() {
        return this.page.locator("#coupon_code");
    }

    private get actionTypeSelect() {
        return this.page.locator("#action_type");
    }

    private get discountAmountInput() {
        return this.page.locator('input[name="discount_amount"]');
    }

    private get sortOrderInput() {
        return this.page.locator('input[name="sort_order"]');
    }

    private get statusCheckbox() {
        return this.page.locator('input[name="status"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Cart Rule" });
    }

    private get searchInput() {
        return this.page.locator('input[name="search"]');
    }

    private get deleteIcons() {
        return this.page.locator("span.cursor-pointer.icon-delete");
    }

    private get agreeButton() {
        return this.page.locator('button.primary-button:has-text("Agree")');
    }

    private channelCheckbox(id: number) {
        return this.page.locator(`input[name="channels[]"][value="${id}"]`);
    }

    private customerGroupCheckbox(id: number) {
        return this.page.locator(
            `input[name="customer_groups[]"][value="${id}"]`,
        );
    }

    /**
     * Create a percentage-discount cart rule fired by a specific coupon code.
     * Applies to the default channel and to the Guest + General customer groups
     * so guest checkout qualifies. Returns the rule data.
     */
    async createCouponPercentageRule(
        couponCode: string,
        discountPercent: number,
    ): Promise<CartRuleData> {
        const name = `cart-rule-${generateName()}-${Date.now()}`;

        await this.visit("admin/marketing/promotions/cart-rules");
        await this.createButton.click();
        await this.nameInput.waitFor();

        await this.nameInput.fill(name);
        await this.descriptionInput.fill("Discount rule for tax e2e suite.");

        // Specific coupon, no auto-generation, fixed code.
        await this.couponTypeSelect.selectOption("1");
        await this.autoGenerationSelect.selectOption("0");
        await this.couponCodeInput.fill(couponCode);

        // Percentage discount off the whole (condition-less) cart.
        await this.actionTypeSelect.selectOption("by_percent");
        await this.discountAmountInput.fill(`${discountPercent}`);
        await this.sortOrderInput.fill("1");

        // Eligibility — default channel + Guest and General customer groups.
        // The visual toggle shares its `for` with a text label, so the
        // underlying checkbox is checked directly (forced, as it is peer-hidden).
        await this.channelCheckbox(1).check({ force: true });
        await this.customerGroupCheckbox(1).check({ force: true });
        await this.customerGroupCheckbox(2).check({ force: true });
        await this.statusCheckbox.check({ force: true });
        await expect(this.statusCheckbox).toBeChecked();

        await this.saveButton.click();

        await expect(
            this.page.getByText("Cart rule created successfully").first(),
        ).toBeVisible();

        return { name, couponCode, discountPercent };
    }

    /**
     * Delete the cart rule matching `name` so repeated runs stay clean.
     */
    async deleteCartRule(name: string): Promise<void> {
        await this.visit("admin/marketing/promotions/cart-rules");
        await this.searchInput.fill(name);
        await this.searchInput.press("Enter");
        await this.page.waitForLoadState("networkidle");

        await this.deleteIcons.first().click();
        await expect(this.agreeButton).toBeVisible();
        await this.agreeButton.click();

        await expect(
            this.page.getByText(/Cart Rule Deleted Successfully/i).first(),
        ).toBeVisible();
    }
}
