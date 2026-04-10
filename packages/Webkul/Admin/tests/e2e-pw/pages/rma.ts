import fs from "fs";
import { expect, Page } from "@playwright/test";
import { RMAShopLocators } from "../locators/shop/rma-shop";
import { RMAAdminLocators } from "../locators/admin/rma-admin";
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

        private rmaShopLocators = new RMAShopLocators(page),

        private rmaAdminLocators = new RMAAdminLocators(page),

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
        await this.rmaAdminLocators.viewOrder.click();
        await this.rmaAdminLocators.Invoice.click();
        await this.rmaAdminLocators.createInvoice.click();
        await expect(this.rmaAdminLocators.successInvoice).toBeVisible();
    }

    private async createRMA() {
        await this.page.goto("customer/account/rma");
        await this.rmaShopLocators.reqRMA.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaShopLocators.editIcon.first().click();
        await this.rmaShopLocators.checkBox.check();
        await this.page.waitForLoadState("networkidle");
        await this.rmaShopLocators.resolution.selectOption("return");
        await this.rmaShopLocators.resolution.selectOption("return");
        await this.page.waitForLoadState("networkidle");
        await this.rmaShopLocators.reason.selectOption("1");
        await this.rmaShopLocators.rmaQTY.fill("1");
        await this.rmaShopLocators.orderStatus.selectOption({ value: "open" });
        await this.rmaShopLocators.info.fill("Changed My Mind.");
        await this.rmaShopLocators.agreement.check();
        await this.rmaShopLocators.submit.click();
        await expect(this.rmaShopLocators.successRMA).toBeVisible();
    }

    private async createInvalidRMA() {
        await this.page.goto("customer/account/rma");
        await this.rmaShopLocators.reqRMA.click();
        await this.rmaShopLocators.editIcon.first().click();
        await this.rmaShopLocators.checkBox.check();
        await this.page.waitForLoadState("networkidle");
        await this.rmaShopLocators.resolution.selectOption("return");
        await this.rmaShopLocators.resolution.selectOption("return");
        await this.page.waitForLoadState("networkidle");
        await this.rmaShopLocators.reason.selectOption("1");
        await this.rmaShopLocators.rmaQTY.fill("4");
        await expect(this.rmaShopLocators.invalidRMAMessage).toBeVisible();
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
        await this.rmaAdminLocators.view.first().click();
        await this.rmaAdminLocators.status.selectOption("2");
        await this.rmaAdminLocators.save.click();
        await this.rmaAdminLocators.agreeButton.click();
        await expect(this.rmaAdminLocators.rmaAcceptmsg).toBeVisible();
        await expect(this.rmaAdminLocators.acceptStatus).toBeVisible();
        await this.page.waitForLoadState("networkidle");
        await this.rmaAdminLocators.status.selectOption("5");
        await this.rmaAdminLocators.save.click();
        await this.rmaAdminLocators.agreeButton.click();
        await expect(this.rmaAdminLocators.receivedPack).toBeVisible();
    }

    async adminDeclinedRMA() {
        await this.page.goto("admin/sales/rma/requests");
        await this.rmaAdminLocators.view.first().click();
        await this.rmaAdminLocators.status.selectOption("7");
        await this.rmaAdminLocators.save.click();
        await this.rmaAdminLocators.agreeButton.click();
        await expect(this.rmaAdminLocators.rmaAcceptmsg).toBeVisible();
        await expect(this.rmaAdminLocators.rmaDeclined).toBeVisible();
    }

    async adminCraeteRMAReason() {
        await this.page.goto("admin/sales/rma/reasons");
        await this.rmaAdminLocators.createRMAReason.click();
        await this.rmaAdminLocators.reasonTitle.fill("Broken Product");
        await this.rmaAdminLocators.reasonStatus.check();
        await this.rmaAdminLocators.position.fill("1");
        await this.rmaAdminLocators.reasonType.selectOption("return");
        await this.rmaAdminLocators.saveReason.click();
        await expect(this.rmaAdminLocators.saveReasonSuccess).toBeVisible();
    }

    async adminCraeteRMARules() {
        await this.page.goto("admin/sales/rma/rules");
        await this.rmaAdminLocators.rmaRulesCreate.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaAdminLocators.ruleTitle.fill("Test Rule");
        await this.rmaAdminLocators.reasonStatus.check();
        await this.rmaAdminLocators.ruleDescription.fill(
            "Test Rule Description",
        );
        await this.rmaAdminLocators.returnPeriod.fill("15");
        await this.rmaAdminLocators.saveRule.click();
        await expect(this.rmaAdminLocators.ruleSuccessMSG).toBeVisible();
    }

    async adminCraeteRMAStatus() {
        await this.page.goto("admin/sales/rma/rma-status");
        await this.rmaAdminLocators.createRMAStatus.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaAdminLocators.rmaStatusTitle.fill("RMA Status");
        await this.rmaAdminLocators.reasonStatus.click();
        await this.rmaAdminLocators.saveStatus.click();
        await expect(this.rmaAdminLocators.statusSuccess).toBeVisible();
    }
}
