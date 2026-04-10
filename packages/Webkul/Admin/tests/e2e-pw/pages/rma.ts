import fs from "fs";
import { expect, Page } from "@playwright/test";
import { RMAShopPage } from "../locators/shop/rma";
import { RMAAdminPage } from "../locators/admin/rma";
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

        private rmaShopPage = new RMAShopPage(page),

        private rmaAdminPage = new RMAAdminPage(page),

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
        await this.rmaAdminPage.viewOrder.click();
        await this.rmaAdminPage.Invoice.click();
        await this.rmaAdminPage.createInvoice.click();
        await expect(this.rmaAdminPage.successInvoice).toBeVisible();
    }

    private async createRMA() {
        await this.page.goto("customer/account/rma");
        await this.rmaShopPage.reqRMA.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaShopPage.editIcon.first().click();
        await this.rmaShopPage.checkBox.check();
        await this.page.waitForLoadState("networkidle");
        await this.rmaShopPage.resolution.selectOption("return");
        await this.rmaShopPage.resolution.selectOption("return");
        await this.page.waitForLoadState("networkidle");
        await this.rmaShopPage.reason.selectOption("1");
        await this.rmaShopPage.rmaQTY.fill("1");
        await this.rmaShopPage.orderStatus.selectOption({ value: "open" });
        await this.rmaShopPage.info.fill("Changed My Mind.");
        await this.rmaShopPage.agreement.check();
        await this.rmaShopPage.submit.click();
        await expect(this.rmaShopPage.successRMA.first()).toBeVisible();
    }

    private async createInvalidRMA() {
        await this.page.goto("customer/account/rma");
        await this.rmaShopPage.reqRMA.click();
        await this.rmaShopPage.editIcon.first().click();
        await this.rmaShopPage.checkBox.check();
        await this.page.waitForLoadState("networkidle");
        await this.rmaShopPage.resolution.selectOption("return");
        await this.rmaShopPage.resolution.selectOption("return");
        await this.page.waitForLoadState("networkidle");
        await this.rmaShopPage.reason.selectOption("1");
        await this.rmaShopPage.rmaQTY.fill("4");
        await expect(this.rmaShopPage.invalidRMAMessage).toBeVisible();
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
        await this.rmaAdminPage.view.first().click();
        await this.rmaAdminPage.status.selectOption("2");
        await this.rmaAdminPage.save.click();
        await this.rmaAdminPage.agreeButton.click();
        await expect(this.rmaAdminPage.rmaAcceptmsg).toBeVisible();
        await expect(this.rmaAdminPage.acceptStatus).toBeVisible();
        await this.page.waitForLoadState("networkidle");
        await this.rmaAdminPage.status.selectOption("5");
        await this.rmaAdminPage.save.click();
        await this.rmaAdminPage.agreeButton.click();
        await expect(this.rmaAdminPage.receivedPack).toBeVisible();
    }

    async adminDeclinedRMA() {
        await this.page.goto("admin/sales/rma/requests");
        await this.rmaAdminPage.view.first().click();
        await this.rmaAdminPage.status.selectOption("7");
        await this.rmaAdminPage.save.click();
        await this.rmaAdminPage.agreeButton.click();
        await expect(this.rmaAdminPage.rmaAcceptmsg).toBeVisible();
        await expect(this.rmaAdminPage.rmaDeclined).toBeVisible();
    }

    async adminCraeteRMAReason() {
        await this.page.goto("admin/sales/rma/reasons");
        await this.rmaAdminPage.createRMAReason.click();
        await this.rmaAdminPage.reasonTitle.fill("Broken Product");
        await this.rmaAdminPage.reasonStatus.check();
        await this.rmaAdminPage.position.fill("1");
        await this.rmaAdminPage.reasonType.selectOption("return");
        await this.rmaAdminPage.saveReason.click();
        await expect(this.rmaAdminPage.saveReasonSuccess).toBeVisible();
    }

    async adminCraeteRMARules() {
        await this.page.goto("admin/sales/rma/rules");
        await this.rmaAdminPage.rmaRulesCreate.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaAdminPage.ruleTitle.fill("Test Rule");
        await this.rmaAdminPage.reasonStatus.check();
        await this.rmaAdminPage.ruleDescription.fill(
            "Test Rule Description",
        );
        await this.rmaAdminPage.returnPeriod.fill("15");
        await this.rmaAdminPage.saveRule.click();
        await expect(this.rmaAdminPage.ruleSuccessMSG).toBeVisible();
    }

    async adminCraeteRMAStatus() {
        await this.page.goto("admin/sales/rma/rma-status");
        await this.rmaAdminPage.createRMAStatus.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaAdminPage.rmaStatusTitle.fill("RMA Status");
        await this.rmaAdminPage.reasonStatus.click();
        await this.rmaAdminPage.saveStatus.click();
        await expect(this.rmaAdminPage.statusSuccess).toBeVisible();
    }
}
