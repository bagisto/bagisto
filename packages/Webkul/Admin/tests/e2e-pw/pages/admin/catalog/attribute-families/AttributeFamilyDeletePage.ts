import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

const OPEN_CREATE_BUTTON = "div.primary-button:visible";
const DELETE_ICON = "span.cursor-pointer.icon-delete";

export class AttributeFamilyDeletePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async visit() {
        await super.visit("admin/catalog/families");
        await this.page.waitForSelector(OPEN_CREATE_BUTTON, { state: "visible" });
    }

    async deleteAttributeFamily() {
        await this.visit();
        await this.page.waitForSelector(DELETE_ICON);
        const deleteIcons = await this.page.$$(DELETE_ICON);
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
