import fs from "fs";
import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import { addAddress, loginAsCustomer } from "../../../utils/customer";
import { loginAsAdmin } from "../../../utils/admin";

function getGeneratedProductName(): string {
    const data = JSON.parse(
        fs.readFileSync("generatedProductName.json", "utf-8"),
    );

    return data.productName;
}

export class RmaManagePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private async adminLogin() {
        await this.visit("admin/login");
        await this.page
            .locator('input[name="email"]')
            .fill("admin@example.com");
        await this.page.locator('input[name="password"]').fill("admin123");
        await this.page.press('input[name="password"]', "Enter");
        await this.page.waitForURL("**/admin/dashboard");
    }

    private async customerCheckout() {
        const acceptButton = this.page.getByRole("button", { name: "Accept" });

        if (await acceptButton.isVisible().catch(() => false)) {
            await acceptButton.click();
        }

        const productName = getGeneratedProductName();

        await this.visit("");
        await this.page
            .getByRole("textbox", { name: "Search products here" })
            .fill(productName);
        await this.page
            .getByRole("textbox", { name: "Search products here" })
            .press("Enter");
        await this.page
            .locator("(//button[contains(@class,'secondary-button')])[2]")
            .click();
        await expect(
            this.page.getByText("Item Added Successfully").first(),
        ).toBeVisible();
        await this.page
            .locator("(//span[contains(@class,'icon-cart')])[1]")
            .click();
        await this.page
            .locator('(//a[contains(.," Continue to Checkout ")])[1]')
            .click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.page.getByRole("button", { name: "Proceed" }).click();
        await this.page.getByText("Free Shipping").first().click();
        await this.page.getByAltText("Money Transfer").click();
        await this.page.waitForTimeout(2000);
        await this.page.getByRole("button", { name: "Place Order" }).click();
        await this.page.waitForTimeout(8000);
    }

    private async createInvoiceFromLatestOrder() {
        await this.visit("admin/sales/orders");
        await this.page.locator(".row > div:nth-child(4) > a").first().click();
        await this.page.getByText("Invoice", { exact: true }).click();
        await this.page.getByRole("button", { name: "Create Invoice" }).click();
        await expect(
            this.page.locator("#app").getByText("Invoice created successfully"),
        ).toBeVisible();
    }

    private async createCustomerRma() {
        await loginAsAdmin(this.page);
        await this.visit("admin/customers");
        await this.page.locator(".icon-login").first().click();
        await this.page.waitForLoadState("networkidle");
        await this.visit("customer/account/rma");
        await this.page.locator('text=" New RMA Request "').click();
        await this.page.waitForLoadState("networkidle");
        await this.page.locator("a.icon-edit").first().click();
        await this.page.locator('input[name^="isChecked["]').click();
        await this.page.waitForTimeout(1000);
        await this.page
            .locator('select[name^="resolution_type"]')
            .selectOption("return");
        await this.page
            .locator('select[name^="resolution_type"]')
            .selectOption("return");
        await this.page.waitForTimeout(1000);
        await this.page
            .locator('select[name="rma_reason_id"]')
            .selectOption("1");
        await this.page.locator('input[name^="rma_qty"]').fill("1");
        await this.page
            .locator('select[name="package_condition"]')
            .selectOption({
                value: "open",
            });
        await this.page
            .locator('textarea[name="information"]')
            .fill("Changed My Mind.");
        await this.page.locator("label:has(input#agreement)").click();
        await this.page.locator('button:has-text("Submit request")').click();
        await expect(
            this.page.getByText("Request created successfully.").first(),
        ).toBeVisible();
    }

    async customerCheckoutForRMA() {
        await loginAsCustomer(this.page);
        await addAddress(this.page);
        await this.customerCheckout();
    }

    async adminInvoiceCreateRMA() {
        await this.adminLogin();
        await this.createInvoiceFromLatestOrder();
    }

    async customerCreateRMA() {
        await this.createCustomerRma();
    }

    async adminAcceptRma() {
        await this.visit("admin/sales/rma/requests");
        await this.page.locator("span.icon-view").first().click();
        await this.page
            .locator('select[name="rma_status_id"]')
            .selectOption("2");
        await this.page.locator('button:has-text("Save")').click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await expect(
            this.page.getByText("RMA Status updated").first(),
        ).toBeVisible();
        await expect(
            this.page.getByText("Accept", { exact: true }),
        ).toBeVisible();
        await this.page
            .locator('select[name="rma_status_id"]')
            .selectOption("5");
        await this.page.locator('button:has-text("Save")').click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await expect(
            this.page.getByText("Received Package", { exact: true }),
        ).toBeVisible();
    }

    async adminDeclineRma() {
        await this.visit("admin/sales/rma/requests");
        await this.page.locator("span.icon-view").first().click();
        await this.page
            .locator('select[name="rma_status_id"]')
            .selectOption("7");
        await this.page.locator('button:has-text("Save")').click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await expect(
            this.page.getByText("RMA Status updated").first(),
        ).toBeVisible();
        await expect(
            this.page.getByText("Declined", { exact: true }),
        ).toBeVisible();
    }

    async adminCreateRmaReason() {
        await this.visit("admin/sales/rma/reasons");
        await this.page
            .getByRole("button", { name: " Create RMA Reason " })
            .click();
        await this.page.locator('input[name="title"]').fill("Broken Product");
        await this.page.locator('label[for="status"]').click();
        await this.page.locator('input[name="position"]').fill("1");
        await this.page
            .locator('select[name="resolution_type[]"]')
            .selectOption("return");
        await this.page.getByRole("button", { name: "Save Reason" }).click();
        await expect(
            this.page.getByText("Reason created successfully."),
        ).toBeVisible();
    }

    async adminCreateRmaRule() {
        await this.visit("admin/sales/rma/rules");
        await this.page
            .getByRole("button", { name: "Create RMA Rules" })
            .click();
        await this.page.waitForLoadState("networkidle");
        await this.page
            .getByRole("textbox", { name: "Rules Title" })
            .fill("Test Rule");
        await this.page.locator('label[for="status"]').click();
        await this.page
            .getByRole("textbox", { name: "Rules Description" })
            .fill("Test Rule Description");
        await this.page.getByPlaceholder("Return Period (Days)").fill("15");
        await this.page.getByRole("button", { name: "Save RMA Rules" }).click();
        await expect(this.page.getByText("RMA Rules created")).toBeVisible();
    }

    async adminCreateRmaStatus() {
        await this.visit("admin/sales/rma/rma-status");
        await this.page
            .getByRole("button", { name: "Create RMA Status" })
            .click();
        await this.page.waitForLoadState("networkidle");
        await this.page
            .getByRole("textbox", { name: "Title" })
            .fill("RMA Status");
        await this.page.locator('label[for="status"]').click();
        await this.page
            .getByRole("button", { name: "Save RMA Status" })
            .click();
        await expect(this.page.getByText("RMA Status created")).toBeVisible();
    }
}
