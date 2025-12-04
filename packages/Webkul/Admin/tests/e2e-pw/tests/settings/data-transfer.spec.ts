import { test } from "../../setup";
import { AdminDataTransfer } from "../../utils/data-transfer";
import * as fs from "fs";

test.describe("check the data transfer flow", () => {
    test("create/update products using data transfer", async ({ adminPage }) => {

        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            'products',
            'append',
            'skip-erros',
            '10',
            ',',
            'products.csv',
            // 'product_images.zip',
        );

    });

    test("delete products using data transfer", async ({ adminPage }) => {

        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            'products',
            'delete',
            'skip-erros',
            '10',
            ',',
            'products.csv',
            // 'product_images.zip',
        );
    });

    test("create/udpate customers using data transfer", async ({ adminPage }) => {
        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            'customers',
            'append',
            'skip-erros',
            '10',
            ',',
            'customers.csv',
        );
    });

    test("delete customers using data transfer", async ({ adminPage }) => {

        // Load seller credentials
        const sellerLoginCredentials = JSON.parse(
            fs.readFileSync("seller-credentials.json", "utf-8")
        );

        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            'customers',
            'delete',
            'skip-erros',
            '10',
            ',',
            'customers.csv',
        );
    });

    test("create/update tax-rates using data transfer", async ({ adminPage }) => {

        // Load seller credentials
        const sellerLoginCredentials = JSON.parse(
            fs.readFileSync("seller-credentials.json", "utf-8")
        );

        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            'tax_rates',
            'append',
            'skip-erros',
            '10',
            ',',
            'tax-rates.csv',
        );
    });

    test("create/update tax-rates using data transfer", async ({ adminPage }) => {

        // Load seller credentials
        const sellerLoginCredentials = JSON.parse(
            fs.readFileSync("seller-credentials.json", "utf-8")
        );

        await adminPage.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(adminPage);
        await dataTransfer.DataTransfer(
            'tax_rates',
            'delete',
            'skip-erros',
            '10',
            ',',
            'tax-rates.csv',
        );
    });
});
