import fs from "fs";
import { expect, Page } from "@playwright/test";
import { WebLocators } from "../locators/locator";
import { CommonPage } from "../utils/tinymce";

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
        await this.locators.view.first().click();
        await this.locators.status.selectOption("2");
        await this.locators.save.click();
        await this.locators.agreeButton.click();
        await expect(this.locators.rmaAcceptmsg).toBeVisible();
        await expect(this.locators.acceptStatus).toBeVisible();
        await this.page.waitForLoadState("networkidle");
        await this.locators.status.selectOption("5");
        await this.locators.save.click();
        await this.locators.agreeButton.click();
        await expect(this.locators.receivedPack).toBeVisible();
    }

    async adminDeclinedRMA() {
        await this.page.goto("admin/sales/rma/requests");
        await this.locators.view.first().click();
        await this.locators.status.selectOption("7");
        await this.locators.save.click();
        await this.locators.agreeButton.click();
        await expect(this.locators.rmaAcceptmsg).toBeVisible();
        await expect(this.locators.rmaDeclined).toBeVisible();
    }

    async adminCraeteRMAReason() {
        await this.page.goto("admin/sales/rma/reasons");
        await this.locators.createRMAReason.click();
        await this.locators.reasonTitle.fill("Broken Product")
        await this.locators.reasonStatus.check();
        await this.locators.position.fill("1");
        await this.locators.reasonType.selectOption("return")
        await this.locators.saveReason.click();
        await expect(this.locators.saveReasonSuccess).toBeVisible();
        await expect(this.locators.listVerify).toBeVisible();
    }

    async adminCraeteRMARules() {
        await this.page.goto("admin/sales/rma/rules");
        await this.locators.rmaRulesCreate.click();
        await this.page.waitForLoadState("networkidle")
        await this.locators.ruleTitle.fill("Test Rule");
        await this.locators.reasonStatus.check();
        await this.locators.ruleDescription.fill("Test Rule Description");
        await this.locators.returnPeriod.fill("15");
        await this.locators.saveRule.click();
        await expect(this.locators.ruleSuccessMSG).toBeVisible();
        await expect(this.locators.listRuleVerify).toBeVisible();
    }

    async adminCraeteRMAStatus() {
        await this.page.goto("admin/sales/rma/rma-status");
        await this.locators.createRMAStatus.click();
        await this.page.waitForLoadState("networkidle")
        await this.locators.rmaStatusTitle.fill("RMA Status");
        await this.locators.reasonStatus.click();
        await this.locators.saveStatus.click();
        await expect(this.locators.statusSuccess).toBeVisible();
        await expect(this.locators.listStatusVerify).toBeVisible();
    }
   
}
