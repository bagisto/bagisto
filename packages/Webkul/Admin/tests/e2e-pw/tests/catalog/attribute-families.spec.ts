import { test, expect } from "../../setup";
import { generateName, generateSlug } from "../../utils/faker";
import type { Page } from "@playwright/test";

const OPEN_CREATE_BUTTON = "div.primary-button:visible";
const SAVE_BUTTON = "button.primary-button:visible";
const EDIT_ICON = "span.cursor-pointer.icon-edit";
const DELETE_ICON = "span.cursor-pointer.icon-delete";
const DRAG_HANDLES = "i.icon-drag";
const GROUP_LISTS =
    "div.flex.gap-5.justify-between.px-4 > div > div.h-\\[calc\\(100vh-285px\\)\\].overflow-auto.border-gray-200.pb-4.ltr\\:border-r.rtl\\:border-l";

async function openAttributeFamilies(page: Page) {
    await page.goto("admin/catalog/families");
    await page.waitForSelector(OPEN_CREATE_BUTTON, { state: "visible" });
}

async function dragAttributesToBothGroups(page: Page) {
    const dragHandles = await page.$$(DRAG_HANDLES);
    const targets = await page.$$(GROUP_LISTS);

    expect(dragHandles.length).toBeGreaterThan(0);
    expect(targets.length).toBeGreaterThan(0);

    // Prefer first two columns so attributes are distributed across left and right.
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

        await page.mouse.move(
            handleBox.x + handleBox.width / 2,
            handleBox.y + handleBox.height / 2
        );
        await page.mouse.down();
        await page.mouse.move(targetPoint.x, targetPoint.y, { steps: 20 });
        await page.mouse.up();
    }
}

test.describe("attribute family management", () => {
    test("should be able to create attribute family", async ({ adminPage }) => {
        await openAttributeFamilies(adminPage);

        await adminPage.click(OPEN_CREATE_BUTTON);
        await adminPage
            .waitForSelector("div#not_avaliable", { timeout: 1000 })
            .catch(() => null);

        await adminPage.fill('input[name="name"]', generateName());
        await adminPage.fill('input[name="code"]', generateSlug("_"));

        await dragAttributesToBothGroups(adminPage);

        await adminPage.click(SAVE_BUTTON);
        await expect(
            adminPage.getByText("Family created successfully.").first()
        ).toBeVisible();
    });

    test("should be able to edit attribute family", async ({ adminPage }) => {
        await openAttributeFamilies(adminPage);

        await adminPage.waitForSelector(EDIT_ICON);
        const editIcons = await adminPage.$$(EDIT_ICON);
        expect(editIcons.length).toBeGreaterThan(0);
        await editIcons[0].click();

        await adminPage.waitForSelector('input[name="name"]');
        await adminPage.fill('input[name="name"]', generateName());

        await dragAttributesToBothGroups(adminPage);

        await adminPage.click(SAVE_BUTTON);
        await expect(
            adminPage.getByText("Family updated successfully.").first()
        ).toBeVisible();
    });

    test("should be able to delete attribute family", async ({ adminPage }) => {
        await openAttributeFamilies(adminPage);

        await adminPage.waitForSelector(DELETE_ICON);
        const deleteIcons = await adminPage.$$(DELETE_ICON);
        expect(deleteIcons.length).toBeGreaterThan(0);
        await deleteIcons[0].click();

        await adminPage.click(
            "button.transparent-button + button.primary-button:visible"
        );
        await expect(
            adminPage.getByText("Family deleted successfully.").first()
        ).toBeVisible();
    });
});
