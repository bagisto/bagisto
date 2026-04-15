import fs from "fs";
import { expect, Page } from "@playwright/test";
import { CustomerRMAActionPage } from "../locators/shop/CustomerRMAActionPage.ts";
import { RMAActionPage } from "../locators/admin/sales/RMAActionPage";

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

        private customerRMAActionPage = new CustomerRMAActionPage(page),

        private rmaActionPage = new RMAActionPage(page),
    ) {}

    private async gotoProductPage() {
        await this.page.goto("admin/sales/orders");
    }

    async adminLogin() {
        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };
        await this.page.goto("admin/login");
        await this.page
            .locator('input[name="email"]')
            .fill(adminCredentials.email);
        await this.page
            .locator('input[name="password"]')
            .fill(adminCredentials.password);
        await this.page.press('input[name="password"]', "Enter");
        await this.page.waitForURL("**/admin/dashboard");
    }

    private async createInvoice() {
        await this.rmaActionPage.viewOrder.click();
        await this.rmaActionPage.Invoice.click();
        await this.rmaActionPage.createInvoice.click();
        await expect(this.rmaActionPage.successInvoice).toBeVisible();
    }

    private async createRMA() {
        await this.page.goto("customer/account/rma");
        await this.customerRMAActionPage.reqRMA.click();
        await this.page.waitForLoadState("networkidle");
        await this.customerRMAActionPage.editIcon.first().click();
        await this.customerRMAActionPage.checkBox.check();
        await this.page.waitForLoadState("networkidle");
        await this.customerRMAActionPage.resolution.selectOption("return");
        await this.customerRMAActionPage.resolution.selectOption("return");
        await this.page.waitForLoadState("networkidle");
        await this.customerRMAActionPage.reason.selectOption("1");
        await this.customerRMAActionPage.rmaQTY.fill("1");
        await this.customerRMAActionPage.orderStatus.selectOption({
            value: "open",
        });
        await this.customerRMAActionPage.info.fill("Changed My Mind.");
        await this.customerRMAActionPage.agreement.check();
        await this.customerRMAActionPage.submit.click();
        await expect(
            this.customerRMAActionPage.successRMA.first(),
        ).toBeVisible();
    }

    private async createInvalidRMA() {
        await this.page.goto("customer/account/rma");
        await this.customerRMAActionPage.reqRMA.click();
        await this.customerRMAActionPage.editIcon.first().click();
        await this.customerRMAActionPage.checkBox.check();
        await this.page.waitForLoadState("networkidle");
        await this.customerRMAActionPage.resolution.selectOption("return");
        await this.customerRMAActionPage.resolution.selectOption("return");
        await this.page.waitForLoadState("networkidle");
        await this.customerRMAActionPage.reason.selectOption("1");
        await this.customerRMAActionPage.rmaQTY.fill("4");
        await expect(
            this.customerRMAActionPage.invalidRMAMessage,
        ).toBeVisible();
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
        await this.adminLogin();
        await this.gotoProductPage();
        await this.createInvoice();
        await this.createRMA();
        await this.verfiyRMADetails();
    }

    async invalidRMARequest() {
        await this.adminLogin();
        await this.gotoProductPage();
        await this.createInvoice();
        await this.createInvalidRMA();
    }

    async adminAcceptRMA() {
        await this.page.goto("admin/sales/rma/requests");
        await this.rmaActionPage.view.first().click();
        await this.rmaActionPage.status.selectOption("2");
        await this.rmaActionPage.save.click();
        await this.rmaActionPage.agreeButton.click();
        await expect(this.rmaActionPage.rmaAcceptmsg).toBeVisible();
        await expect(this.rmaActionPage.acceptStatus).toBeVisible();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.status.selectOption("5");
        await this.rmaActionPage.save.click();
        await this.rmaActionPage.agreeButton.click();
        await expect(this.rmaActionPage.receivedPack).toBeVisible();
    }

    async adminDeclinedRMA() {
        await this.page.goto("admin/sales/rma/requests");
        await this.rmaActionPage.view.first().click();
        await this.rmaActionPage.status.selectOption("7");
        await this.rmaActionPage.save.click();
        await this.rmaActionPage.agreeButton.click();
        await expect(this.rmaActionPage.rmaAcceptmsg).toBeVisible();
        await expect(this.rmaActionPage.rmaDeclined).toBeVisible();
    }

    async adminCraeteRMAReason() {
        await this.page.goto("admin/sales/rma/reasons");
        await this.rmaActionPage.createRMAReason.click();
        await this.rmaActionPage.reasonTitle.fill("Broken Product");
        await this.rmaActionPage.reasonStatus.check();
        await this.rmaActionPage.position.fill("1");
        await this.rmaActionPage.reasonType.selectOption("return");
        await this.rmaActionPage.saveReason.click();
        await expect(this.rmaActionPage.saveReasonSuccess).toBeVisible();
    }

    async adminCraeteRMARules() {
        await this.page.goto("admin/sales/rma/rules");
        await this.rmaActionPage.rmaRulesCreate.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.ruleTitle.fill("Test Rule");
        await this.rmaActionPage.reasonStatus.check();
        await this.rmaActionPage.ruleDescription.fill("Test Rule Description");
        await this.rmaActionPage.returnPeriod.fill("15");
        await this.rmaActionPage.saveRule.click();
        await expect(this.rmaActionPage.ruleSuccessMSG).toBeVisible();
    }

    async adminCraeteRMAStatus() {
        await this.page.goto("admin/sales/rma/rma-status");
        await this.rmaActionPage.createRMAStatus.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.rmaStatusTitle.fill("RMA Status");
        await this.rmaActionPage.reasonStatus.click();
        await this.rmaActionPage.saveStatus.click();
        await expect(this.rmaActionPage.statusSuccess).toBeVisible();
    }
}
