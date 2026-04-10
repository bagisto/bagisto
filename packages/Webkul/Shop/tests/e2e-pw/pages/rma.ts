import fs from "fs";
import { expect, Page } from "@playwright/test";
import { WebLocators } from "../locators/locator";
import { CommonPage } from "../utils/tinymce";
import { loginAsAdmin } from "../utils/admin";

/**
 * Reads product data from JSON file
 */
function readProductData() {
    const product = JSON.parse(fs.readFileSync("product-data.json", "utf-8"));

    const productName = product.name;

    return productName;
}

export class RMACreation {
    constructor(
        private page: Page,

        private locators = new WebLocators(page),

        private editor = new CommonPage(page),
    ) {}

    private async gotoProductPage() {
        await this.page.goto("admin/sales/orders");
    }

    private async createInvoice() {
        await this.locators.viewOrder.click();
        await this.locators.Invoice.click();
        await this.locators.createInvoice.click();
        await expect(this.locators.successInvoice).toBeVisible();
    }

    private async createRMA() {
        await this.page.goto("customer/account/rma");
        await this.locators.reqRMA.click();
        await this.page.waitForLoadState("networkidle");
        await this.locators.editIcon.first().click();
        await this.locators.checkBox.check();
        await this.page.waitForLoadState("networkidle");
        await this.locators.resolution.selectOption("return");
        await this.locators.resolution.selectOption("return");
        await this.page.waitForLoadState("networkidle");
        await this.locators.reason.selectOption("1");
        await this.locators.rmaQTY.fill("1");
        await this.locators.orderStatus.selectOption({ value: "open" });
        await this.locators.info.fill("Changed My Mind.");
        await this.locators.agreement.check();
        await this.locators.submit.click();
        await expect(this.locators.successRMA).toBeVisible();
    }

    private async createInvalidRMA() {
        await this.page.goto("customer/account/rma");
        await this.locators.reqRMA.click();
        await this.locators.editIcon.first().click();
        await this.locators.checkBox.check();

        await this.page.waitForLoadState("networkidle");
        await this.locators.resolution.selectOption("return");
        await this.locators.resolution.selectOption("return");
        await this.page.waitForLoadState("networkidle");
        await this.locators.reason.selectOption("1");
        await this.locators.rmaQTY.fill("4");

        await expect(this.locators.invalidRMAMessage).toBeVisible();
    }

    private async verfiyRMADetails() {
        const productName = readProductData();

        await expect(
            this.page.getByText(productName, { exact: true }),
        ).toBeVisible();
    }

    /**
     * Public functions
     */
    async rmaCreation() {
        await loginAsAdmin(this.page);
        await this.gotoProductPage();
        await this.createInvoice();
        await this.createRMA();
        await this.verfiyRMADetails();
    }

    async invalidRMARequest() {
        await loginAsAdmin(this.page);
        await this.gotoProductPage();
        await this.createInvoice();
        await this.createInvalidRMA();
    }
}
