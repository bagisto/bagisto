import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

const BUY_NOW_FIELD =
    "catalog[products][product_view_page][buy_now_button_display]";

export class ProductViewConfigurationPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async setBuyNowButton(enabled: boolean): Promise<void> {
        await this.visit("admin/configuration/catalog/products");
        await this.waitForVueMount();

        const checkbox = this.page.locator(
            `input[type="checkbox"][name="${BUY_NOW_FIELD}"]`,
        );

        await checkbox.waitFor({ state: "attached" });

        if ((await checkbox.isChecked()) !== enabled) {
            await this.page
                .locator(
                    `label:has(> input[type="checkbox"][name="${BUY_NOW_FIELD}"])`,
                )
                .click();
        }

        await expect(checkbox).toBeChecked({ checked: enabled });

        await this.page
            .getByRole("button", { name: "Save Configuration" })
            .click();

        await expect(
            this.page.getByText("Configuration saved successfully"),
        ).toBeVisible();
    }
}
