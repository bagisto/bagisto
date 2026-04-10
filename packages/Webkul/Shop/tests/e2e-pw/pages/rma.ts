import fs from "fs";
import { expect, Page } from "@playwright/test";
import { CommonPage } from "../utils/tinymce";
import { loginAsAdmin } from "../utils/admin";
import { RMAEditPage } from "../locators/shop/RMAEditPage";

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

        private rmaEditPage = new RMAEditPage(page),

        private editor = new CommonPage(page),
    ) {}

    private async gotoProductPage() {
        await this.page.goto("admin/sales/orders");
    }

    private async createInvoice() {
        await this.rmaEditPage.viewOrder.click();
        await this.rmaEditPage.Invoice.click();
        await this.rmaEditPage.createInvoice.click();
        await expect(this.rmaEditPage.successInvoice).toBeVisible();
    }

    private async createRMA() {
        await this.page.goto("customer/account/rma");
        await this.rmaEditPage.reqRMA.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaEditPage.editIcon.first().click();
        await this.rmaEditPage.checkBox.check();
        await this.page.waitForLoadState("networkidle");
        await this.rmaEditPage.resolution.selectOption("return");
        await this.rmaEditPage.resolution.selectOption("return");
        await this.page.waitForLoadState("networkidle");
        await this.rmaEditPage.reason.selectOption("1");
        await this.rmaEditPage.rmaQTY.fill("1");
        await this.rmaEditPage.orderStatus.selectOption({ value: "open" });
        await this.rmaEditPage.info.fill("Changed My Mind.");
        await this.rmaEditPage.agreement.check();
        await this.rmaEditPage.submit.click();
        await expect(this.rmaEditPage.successRMA).toBeVisible();
    }

    private async createInvalidRMA() {
        await this.page.goto("customer/account/rma");
        await this.rmaEditPage.reqRMA.click();
        await this.rmaEditPage.editIcon.first().click();
        await this.rmaEditPage.checkBox.check();

        await this.page.waitForLoadState("networkidle");
        await this.rmaEditPage.resolution.selectOption("return");
        await this.rmaEditPage.resolution.selectOption("return");
        await this.page.waitForLoadState("networkidle");
        await this.rmaEditPage.reason.selectOption("1");
        await this.rmaEditPage.rmaQTY.fill("4");

        await expect(this.rmaEditPage.invalidRMAMessage).toBeVisible();
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
