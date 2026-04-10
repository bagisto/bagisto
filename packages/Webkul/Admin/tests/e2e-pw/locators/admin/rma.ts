import { Locator, Page } from "@playwright/test";

export class RMAAdminPage {
    constructor(private page: Page) {}

    get view() {
        return this.page.locator("span.icon-view");
    }

    get status() {
        return this.page.locator('select[name="rma_status_id"]');
    }

    get save() {
        return this.page.locator('button:has-text("Save")');
    }

    get acceptStatus() {
        return this.page.getByText("Accept", { exact: true });
    }

    get receivedPack() {
        return this.page.getByText("Received Package", { exact: true });
    }

    get rmaDeclined() {
        return this.page.getByText("Declined", { exact: true });
    }

    get createRMAReason() {
        return this.page.getByRole("button", { name: " Create RMA Reason " });
    }

    get reasonTitle() {
        return this.page.locator('input[name="title"]');
    }

    get reasonStatus() {
        return this.page.locator('label[for="status"]');
    }

    get position() {
        return this.page.locator('input[name="position"]');
    }

    get reasonType() {
        return this.page.locator('select[name="resolution_type[]"]');
    }

    get saveReason() {
        return this.page.getByRole("button", { name: "Save Reason" });
    }

    get saveReasonSuccess() {
        return this.page.getByText("Reason created successfully.");
    }

    get rmaRulesCreate() {
        return this.page.getByRole("button", { name: "Create RMA Rules" });
    }

    get ruleTitle() {
        return this.page.getByRole("textbox", { name: "Rules Title" });
    }

    get ruleDescription() {
        return this.page.getByRole("textbox", { name: "Rules Description" });
    }

    get returnPeriod() {
        return this.page.getByPlaceholder("Return Period (Days)");
    }

    get saveRule() {
        return this.page.getByRole("button", { name: "Save RMA Rules" });
    }

    get ruleSuccessMSG() {
        return this.page.getByText("RMA Rules created");
    }

    get createRMAStatus() {
        return this.page.getByRole("button", { name: "Create RMA Status" });
    }

    get rmaStatusTitle() {
        return this.page.getByRole("textbox", { name: "Title" });
    }

    get saveStatus() {
        return this.page.getByRole("button", { name: "Save RMA Status" });
    }

    get statusSuccess() {
        return this.page.getByText("RMA Status created");
    }

    get viewOrder() {
        return this.page.locator(".row > div:nth-child(4) > a").first();
    }

    get Invoice() {
        return this.page.getByText("Invoice", { exact: true });
    }

    get createInvoice() {
        return this.page.getByRole("button", { name: "Create Invoice" });
    }

    get successInvoice() {
        return this.page
            .locator("#app")
            .getByText("Invoice created successfully");
    }

    get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    get rmaAcceptmsg() {
        return this.page.getByText("RMA Status updated").first();
    }
}
