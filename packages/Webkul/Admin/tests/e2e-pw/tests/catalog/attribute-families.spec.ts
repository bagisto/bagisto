import { test, expect } from "../../setup";
import * as forms from "../../utils/form";

test.describe("attribute family management", () => {
    test("create attribute family", async ({ adminPage }) => {
        await adminPage.goto('admin/catalog/families');
        await adminPage.waitForSelector("div.primary-button", {
            state: "visible",
        });

        await adminPage.click("div.primary-button:visible");
        await adminPage
            .waitForSelector("div#not_avaliable", { timeout: 1000 })
            .catch(() => null);

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(" ")
            .replaceAll(" ", "");

        await adminPage.fill(
            'input[name="name"]',
            forms.generateRandomStringWithSpaces(
                Math.floor(Math.random() * 200)
            )
        );
        await adminPage.fill('input[name="code"]', concatenatedNames);

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
        await adminPage.goto('admin/catalog/families');
        await adminPage.waitForSelector("div.primary-button", {
            state: "visible",
        });

        await adminPage.waitForSelector(
            'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]'
        );

        const iconEdit = await adminPage.$$(
            'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]'
        );
        await iconEdit[0].click();

        await adminPage.waitForSelector('input[name="name"]');
        await adminPage.fill(
            'input[name="name"]',
            forms.generateRandomStringWithSpaces(
                Math.floor(Math.random() * 100)
            )
        );

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
        await adminPage.goto('admin/catalog/families');
        await adminPage.waitForSelector("div.primary-button", {
            state: "visible",
        });

        await adminPage.waitForSelector(
            'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]'
        );

        const iconDelete = await adminPage.$$(
            'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]'
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
