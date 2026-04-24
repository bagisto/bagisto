import { expect, test } from "../../setup";
import { AdminDataTransfer } from "../../utils/data-transfer";
import * as fs from "fs";

test.describe("check the data transfer flow", () => {
    test("create/update products using csv file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "products",
            "append",
            "skip-errors",
            "10",
            ",",
            "products.csv",
            // 'product_images.zip',
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );

        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("delete products using csv file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "products",
            "delete",
            "skip-errors",
            "10",
            ",",
            "products.csv",
            // 'product_images.zip',
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("create/update products using xls file  ", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "products",
            "append",
            "skip-errors",
            "10",
            ",",
            "products.xls",
            // 'product_images.zip',
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("delete products using xls file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "products",
            "delete",
            "skip-errors",
            "10",
            ",",
            "products.xls",
            // 'product_images.zip',
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("create/update products using xlsx file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "products",
            "append",
            "skip-errors",
            "10",
            ",",
            "products.xlsx",
            // 'product_images.zip',
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("delete products using xlsx file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "products",
            "delete",
            "skip-errors",
            "10",
            ",",
            "products.xlsx",
            // 'product_images.zip',
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("create/update products using xml file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "products",
            "append",
            "skip-errors",
            "10",
            ",",
            "products.xml",
            // 'product_images.zip',
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("delete products using xml file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "products",
            "delete",
            "skip-errors",
            "10",
            ",",
            "products.xml",
            // 'product_images.zip',
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("create/update customers using csv file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "customers",
            "append",
            "skip-errors",
            "10",
            ",",
            "customers.csv",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("delete customers using csv file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "customers",
            "delete",
            "skip-errors",
            "10",
            ",",
            "customers.csv",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("create/update customers using xls file  ", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "customers",
            "append",
            "skip-errors",
            "10",
            ",",
            "customers.xls",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("delete customers using xls file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "customers",
            "delete",
            "skip-errors",
            "10",
            ",",
            "customers.xls",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("create/update customers using xlsx file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "customers",
            "append",
            "skip-errors",
            "10",
            ",",
            "customers.xlsx",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("delete customers using xlsx file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "customers",
            "delete",
            "skip-errors",
            "10",
            ",",
            "customers.xlsx",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("create/update customers using xml file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "customers",
            "append",
            "skip-errors",
            "10",
            ",",
            "customers.xml",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("delete customers using xml file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "customers",
            "delete",
            "skip-errors",
            "10",
            ",",
            "customers.xml",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("create/update tax-rates using csv file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "tax_rates",
            "append",
            "skip-errors",
            "10",
            ",",
            "tax-rates.csv",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("delete tax-rates using csv file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "tax_rates",
            "delete",
            "skip-errors",
            "10",
            ",",
            "tax-rates.csv",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("create/update tax-rates using xls file  ", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "tax_rates",
            "append",
            "skip-errors",
            "10",
            ",",
            "tax-rates.xls",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("delete tax-rates using xls file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "tax_rates",
            "delete",
            "skip-errors",
            "10",
            ",",
            "tax-rates.xls",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("create/update tax-rates using xlsx file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "tax_rates",
            "append",
            "skip-errors",
            "10",
            ",",
            "tax-rates.xlsx",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("delete tax-rates using xlsx file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "tax_rates",
            "delete",
            "skip-errors",
            "10",
            ",",
            "tax-rates.xlsx",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("create/update tax-rates using xml file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "tax_rates",
            "append",
            "skip-errors",
            "10",
            ",",
            "tax-rates.xml",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });

    test("delete tax-rates using xml file", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            "tax_rates",
            "delete",
            "skip-errors",
            "10",
            ",",
            "tax-rates.xml",
        );

        await adminPage.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );
        
        await expect(
            adminPage.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    });
});
