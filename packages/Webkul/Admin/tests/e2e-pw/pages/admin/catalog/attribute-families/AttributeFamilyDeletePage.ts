import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class AttributeFamilyDeletePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async visit() {
        await super.visit("admin/catalog/families");
        await this.page.waitForSelector('div.primary-button:visible', { state: "visible" });
    }

    async deleteAttributeFamily() {
        await this.visit();
        await this.page.waitForSelector("span.cursor-pointer.icon-delete");
        const deleteIcons = await this.page.$$("span.cursor-pointer.icon-delete");
        expect(deleteIcons.length).toBeGreaterThan(0);
        await deleteIcons[0].click();
        await this.page.click(
            "button.transparent-button + button.primary-button:visible",
        );
        await expect(
            this.page.getByText("Family deleted successfully.").first(),
        ).toBeVisible();
    }
}
