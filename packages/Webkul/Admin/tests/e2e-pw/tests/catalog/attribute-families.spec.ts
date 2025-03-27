import { test, expect } from "../../setup";
import { generateName, generateSlug } from "../../utils/faker";

test.describe("attribute family management", () => {
    test("create attribute family", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("div.primary-button", {
            state: "visible",
        });

        await adminPage.click("div.primary-button:visible");
        await adminPage
            .waitForSelector("div#not_avaliable", { timeout: 1000 })
            .catch(() => null);

        await adminPage.fill('input[name="name"]', generateName());
        await adminPage.fill('input[name="code"]', generateSlug("_"));

        const attributes = await adminPage.$$("i.icon-drag");
        const targets = await adminPage.$$(
            'div[class="flex [&>*]:flex-1 gap-5 justify-between px-4"] > div > div[class="h-[calc(100vh-285px)] overflow-auto border-gray-200 pb-4 ltr:border-r rtl:border-l"]'
        );

        for (const attribute of attributes) {
            const randomTargetIndex = Math.floor(
                Math.random() * targets.length
            );
            const target = targets[randomTargetIndex];

            const attributeBox = await attribute.boundingBox();
            const targetBox = await target.boundingBox();

            if (attributeBox && targetBox) {
                const randomX = targetBox.x + Math.random() * targetBox.width;
                const randomY = targetBox.y + Math.random() * targetBox.height;

                await adminPage.mouse.move(
                    attributeBox.x + attributeBox.width / 2,
                    attributeBox.y + attributeBox.height / 2
                );
                await adminPage.mouse.down();
                await adminPage.mouse.move(randomX, randomY);
                await adminPage.mouse.up();
            }
        }

        await adminPage.click(".primary-button:visible");
        await expect(
            adminPage.getByText("Family created successfully.")
        ).toBeVisible();
    });

    test("edit attribute family", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("div.primary-button", {
            state: "visible",
        });

        await adminPage.waitForSelector("span.cursor-pointer.icon-edit");
        const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
        await iconEdit[0].click();

        await adminPage.waitForSelector('input[name="name"]');
        await adminPage.fill('input[name="name"]', generateName());

        const attributes = await adminPage.$$("i.icon-drag");
        const targets = await adminPage.$$(
            'div[class="flex [&>*]:flex-1 gap-5 justify-between px-4"] > div > div[class="h-[calc(100vh-285px)] overflow-auto border-gray-200 pb-4 ltr:border-r rtl:border-l"]'
        );

        for (const attribute of attributes) {
            const randomTargetIndex = Math.floor(
                Math.random() * targets.length
            );
            const target = targets[randomTargetIndex];

            const attributeBox = await attribute.boundingBox();
            const targetBox = await target.boundingBox();

            if (attributeBox && targetBox) {
                const randomX = targetBox.x + Math.random() * targetBox.width;
                const randomY = targetBox.y + Math.random() * targetBox.height;

                await adminPage.mouse.move(
                    attributeBox.x + attributeBox.width / 2,
                    attributeBox.y + attributeBox.height / 2
                );
                await adminPage.mouse.down();
                await adminPage.mouse.move(randomX, randomY);
                await adminPage.mouse.up();
            }
        }

        await adminPage.click(".primary-button:visible");
        await expect(
            adminPage.getByText("Family updated successfully.")
        ).toBeVisible();
    });

    test("delete attribute family", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/families");
        await adminPage.waitForSelector("div.primary-button", {
            state: "visible",
        });

        await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete"
        );
        await iconDelete[0].click();

        await adminPage.click(
            "button.transparent-button + button.primary-button:visible"
        );
        await expect(
            adminPage.getByText("Family deleted successfully.")
        ).toBeVisible({ timeout: 5000 });
    });
});
