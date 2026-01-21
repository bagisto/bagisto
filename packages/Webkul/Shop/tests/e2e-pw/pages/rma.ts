import fs from "fs";
import { expect, Page } from "@playwright/test";
import { WebLocators } from "../locators/locator";
import { CommonPage } from "../utils/tinymce";
import { BaseProduct } from "./types/product.types";
import {
    generateName,
    generateHostname,
    generateLocation,
} from "../utils/faker";

export class RMACreation {
    constructor(
        private page: Page,

        private locators = new WebLocators(page),

        private editor = new CommonPage(page),
    ) {}

    async gotoProductPage() {
        await this.page.goto("admin/sales/orders");
    }

    async adminLogin() {
        /**
         * Admin credentials.
         */
        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };

        /**
         * Authenticate the admin user.
         */
        await this.page.goto("admin/login");
        await this.page.locator('input[name="email"]').fill(adminCredentials.email);
        await this.page
            .locator('input[name="password"]')
            .fill(adminCredentials.password);
        await this.page.press('input[name="password"]', "Enter");

        /**
         * Wait for the dashboard to load.
         */
        await this.page.waitForURL("**/admin/dashboard");
    }
    async createInvoice() {
        await this.locators.viewOrder.click();
        await this.locators.Invoice.click();
        await this.locators.createInvoice.click();
        await expect(this.locators.successInvoice).toBeVisible();
    }

    async createRMA() {
        await this.page.goto("customer/account/rma");
        await this.locators.reqRMA.click();
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
}
