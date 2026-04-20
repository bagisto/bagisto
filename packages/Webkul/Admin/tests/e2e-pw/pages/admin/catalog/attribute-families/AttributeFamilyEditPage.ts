import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { generateName } from "../../../../utils/faker";

const OPEN_CREATE_BUTTON = "div.primary-button:visible";
const EDIT_ICON = "span.cursor-pointer.icon-edit";
const SAVE_BUTTON = "button.primary-button:visible";
const DRAG_HANDLES = "i.icon-drag";
const GROUP_LISTS =
    "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l";

export class AttributeFamilyEditPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async visit() {
        await super.visit("admin/catalog/families");
        await this.page.waitForSelector(OPEN_CREATE_BUTTON, { state: "visible" });
    }

    private async dragAttributesToBothGroups() {
        const dragHandles = await this.page.$$(DRAG_HANDLES);
        const targets = await this.page.$$(GROUP_LISTS);

        expect(dragHandles.length).toBeGreaterThan(0);
        expect(targets.length).toBeGreaterThan(0);

        const targetColumns = targets.slice(0, Math.min(2, targets.length));
        const targetPoints: { x: number; y: number }[] = [];

        for (const target of targetColumns) {
            const targetBox = await target.boundingBox();

            if (!targetBox) {
                continue;
            }

            targetPoints.push({
                x: targetBox.x + targetBox.width / 2,
                y: targetBox.y + targetBox.height / 2,
            });
        }

        expect(targetPoints.length).toBeGreaterThan(0);

        for (const [index, handle] of dragHandles.entries()) {
            const handleBox = await handle.boundingBox();

            if (!handleBox) {
                continue;
            }

            const targetPoint = targetPoints[index % targetPoints.length];

            await this.page.mouse.move(
                handleBox.x + handleBox.width / 2,
                handleBox.y + handleBox.height / 2,
            );
            await this.page.mouse.down();
            await this.page.mouse.move(targetPoint.x, targetPoint.y, {
                steps: 20,
            });
            await this.page.mouse.up();
        }
    }

    async editAttributeFamily() {
        await this.visit();
        await this.page.waitForSelector(EDIT_ICON);
        const editIcons = await this.page.$$(EDIT_ICON);
        expect(editIcons.length).toBeGreaterThan(0);
        await editIcons[0].click();
        await this.page.waitForSelector('input[name="name"]');
        await this.page.fill('input[name="name"]', generateName());
        await this.dragAttributesToBothGroups();
        await this.page.click(SAVE_BUTTON);
        await expect(
            this.page.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    }
}
