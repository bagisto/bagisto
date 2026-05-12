import { test, expect } from "../../../../../setup";
import { type Page } from "@playwright/test";
import { OmnibusAdminPage as OmnibusAdmin } from "../../../../../pages/admin/omnibus/OmnibusAdminPage";
import { OmnibusShopPage as OmnibusShop } from "../../../../../pages/shop/OmnibusShopPage";
import { ProductCreatePage } from "../../../../../pages/admin/catalog/products/ProductCreatePage";
import {
    generateDescription,
    generateName,
    generateRandomNumericString,
    generateSKU,
} from "../../../../../utils/faker";

test.describe.serial("omnibus price disclosure - storefront display", () => {
    test.setTimeout(240000);

    let productName: string;
    let basePrice: number;
    let specialPrice1: number;
    let specialPrice2: number;
    let specialPrice3: number;

    test.beforeAll(async ({ adminPage }) => {
        const omnibusAdmin = new OmnibusAdmin(adminPage);

        await omnibusAdmin.enableOmnibus();
        await omnibusAdmin.saveConfig();

        basePrice = parseInt(generateRandomNumericString(3, 200, 500));
        specialPrice1 =
            basePrice - parseInt(generateRandomNumericString(2, 16, 49));
        specialPrice2 =
            specialPrice1 - parseInt(generateRandomNumericString(2, 5, 30));
        specialPrice3 =
            basePrice - parseInt(generateRandomNumericString(2, 5, 14));
        productName = `omnibus-${generateName()}`;

        await new ProductCreatePage(adminPage).createSimpleProduct({
            name: productName,
            sku: generateSKU(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: basePrice.toString(),
            weight: "25",
            inventory: "5000",
        });
    });

    async function setSpecialPrice(page: Page, price: number) {
        await page.goto("admin/catalog/products");
        await page.waitForLoadState("networkidle");
        await page
            .locator("span.cursor-pointer.icon-sort-right")
            .nth(1)
            .click();
        await page.waitForLoadState("networkidle");
        await page
            .locator('input[name="special_price"]')
            .first()
            .fill(price.toString());
        await page.locator('button:has-text("Save Product")').first().click();
        await expect(
            page.getByText("Product updated successfully").first(),
        ).toBeVisible();
    }

    test("should display omnibus price info when first special price is set", async ({
        adminPage,
        shopPage,
    }) => {
        await setSpecialPrice(adminPage, specialPrice1);

        const omnibusShop = new OmnibusShop(shopPage);

        await omnibusShop.openProductPage(productName);
        await omnibusShop.verifyOmnibusVisible();

        const text = await omnibusShop.getOmnibusText();

        expect(text).toContain(basePrice.toString());
    });

    test("should still display omnibus price info when special price is lowered", async ({
        adminPage,
        shopPage,
    }) => {
        await setSpecialPrice(adminPage, specialPrice2);

        const omnibusShop = new OmnibusShop(shopPage);

        await omnibusShop.openProductPage(productName);
        await omnibusShop.verifyOmnibusVisible();

        const text = await omnibusShop.getOmnibusText();

        expect(text).toContain(specialPrice1.toString());
    });

    test("should still display omnibus price info when special price is raised (below base)", async ({
        adminPage,
        shopPage,
    }) => {
        await setSpecialPrice(adminPage, specialPrice3);

        const omnibusShop = new OmnibusShop(shopPage);

        await omnibusShop.openProductPage(productName);
        await omnibusShop.verifyOmnibusVisible();

        const text = await omnibusShop.getOmnibusText();

        expect(text).toContain(specialPrice2.toString());
    });

    test("should hide omnibus price info when feature is disabled", async ({
        adminPage,
        shopPage,
    }) => {
        const omnibusAdmin = new OmnibusAdmin(adminPage);

        await omnibusAdmin.disableOmnibus();
        await omnibusAdmin.saveConfig();

        const omnibusShop = new OmnibusShop(shopPage);

        await omnibusShop.openProductPage(productName);
        await omnibusShop.verifyOmnibusNotVisible();
    });
});
