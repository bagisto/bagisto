import fs from "fs";
import { expect, Page } from "@playwright/test";
import { CommonPage } from "../../utils/tinymce";
import { loginAsAdmin } from "../../utils/admin";
import { BasePage } from "../BasePage";

/**
 * Reads product data from JSON file
 */
function readProductData() {
    const product = JSON.parse(fs.readFileSync("product-data.json", "utf-8"));
    return product.name;
}

export class RMACreatePage extends BasePage {
    constructor(
        page: Page,
        private editor = new CommonPage(page),
    ) {
        super(page);
    }

    get viewOrder() {
        return this.page.locator(".row > div:nth-child(4) > a").first();
    }

    get invoiceTab() {
        return this.page.getByText("Invoice", { exact: true });
    }

    get createInvoiceButton() {
        return this.page.getByRole("button", { name: "Create Invoice" });
    }

    get successInvoiceMessage() {
        return this.page.getByText("Invoice created successfully");
    }

    get requestRMAButton() {
        return this.page.getByText("New RMA Request");
    }

    get editIcon() {
        return this.page.locator("a.icon-edit");
    }

    get checkBox() {
        return this.page.locator('input[name^="isChecked["]');
    }

    get resolutionSelect() {
        return this.page.locator('select[name^="resolution_type"]');
    }

    get reasonSelect() {
        return this.page.locator('select[name="rma_reason_id"]');
    }

    get rmaQtyInput() {
        return this.page.locator('input[name^="rma_qty"]');
    }

    get orderStatusSelect() {
        return this.page.locator('select[name="package_condition"]');
    }

    get infoInput() {
        return this.page.locator('textarea[name="information"]');
    }

    get agreementCheckbox() {
        return this.page.locator("label:has(input#agreement)");
    }

    get submitButton() {
        return this.page.locator('button:has-text("Submit request")');
    }

    get successRMAMessage() {
        return this.page
            .getByRole("paragraph")
            .filter({ hasText: "Request created successfully." });
    }

    get invalidRMAMessage() {
        return this.page.getByText("The RMA Qty field must be 1 or less");
    }

    private async visitOrderPage() {
        await this.visit("admin/sales/orders");
    }

    private async createInvoice() {
        await this.viewOrder.click();
        await this.invoiceTab.click();
        await this.createInvoiceButton.click();
        await expect(this.successInvoiceMessage).toBeVisible();
    }

    private async createRMA() {
        await this.visit("customer/account/rma");

        await this.requestRMAButton.click();
        await this.page.waitForLoadState("networkidle");

        await this.editIcon.first().click();
        await this.checkBox.check();

        await this.page.waitForLoadState("networkidle");
        await this.resolutionSelect.selectOption("return");
        await this.resolutionSelect.selectOption("return");

        await this.page.waitForLoadState("networkidle");
        await this.reasonSelect.selectOption("1");

        await this.rmaQtyInput.fill("1");
        await this.orderStatusSelect.selectOption({ value: "open" });

        await this.infoInput.fill("Changed My Mind.");
        await this.agreementCheckbox.check();

        await this.submitButton.click();
        await expect(this.successRMAMessage).toBeVisible();
    }

    private async createInvalidRMA() {
        await this.visit("customer/account/rma");

        await this.requestRMAButton.click();
        await this.editIcon.first().click();
        await this.checkBox.check();

        await this.page.waitForLoadState("networkidle");
        await this.resolutionSelect.selectOption("return");
        await this.resolutionSelect.selectOption("return");

        await this.page.waitForLoadState("networkidle");
        await this.reasonSelect.selectOption("1");

        await this.rmaQtyInput.fill("4");

        await expect(this.invalidRMAMessage).toBeVisible();
    }

    private async verfiyRMADetails() {
        const productName = readProductData();

        await expect(
            this.page.getByText(productName, { exact: true }),
        ).toBeVisible();
    }


    async rmaCreation() {
        await loginAsAdmin(this.page);
        await this.visitOrderPage();
        await this.createInvoice();
        await this.createRMA();
        await this.verfiyRMADetails();
    }

    async invalidRMARequest() {
        await loginAsAdmin(this.page);
        await this.visitOrderPage();
        await this.createInvoice();
        await this.createInvalidRMA();
    }
}