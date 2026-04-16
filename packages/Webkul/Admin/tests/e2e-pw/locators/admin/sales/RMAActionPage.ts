import { Page } from "@playwright/test";

export class RMAActionPage {
    constructor(private page: Page) {}

    // ================= COMMON =================

    get createBtn() {
        return this.page.locator(".primary-button").first();
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get saveBtn() {
        return this.page.locator(".primary-button").nth(1);
    }

    get editFirstRow() {
        return this.page.locator(".icon-edit").first();
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    get checkBox() {
        return this.page.locator('input[name^="isChecked["]');
    }

    get reason() {
        return this.page.locator('select[name="rma_reason_id"]');
    }

    // ================= RMA CREATE =================

    get resolution() {
        return this.page.locator('select[name^="resolution_type"]');
    }

    get reasonDropdown() {
        return this.page.locator('select[name="rma_reason_id"]');
    }

    get rmaQty() {
        return this.page.locator('input[name^="rma_qty"]');
    }

    get info() {
        return this.page.locator('textarea[name="information"]');
    }

    get successRMA() {
        return this.page.getByText("RMA created successfully.").first();
    }

    // ================= RMA REASON =================

    get createReasonBtn() {
        return this.page.getByRole("button", { name: "Create RMA Reason" });
    }

    get reasonTitle() {
        return this.page.locator('input[name="title"]');
    }

    get position() {
        return this.page.locator('input[name="position"]');
    }

    get reasonType() {
        return this.page.locator('select[name="resolution_type[]"]');
    }

    get saveReasonBtn() {
        return this.page.getByRole("button", { name: "Save Reason" });
    }

    get successReasonCreate() {
        return this.page.getByText("Reason created successfully.").first();
    }

    get successReasonUpdate() {
        return this.page.getByText("Reason updated successfully.").first();
    }

    get successReasonDelete() {
        return this.page.getByText("Reason deleted successfully.").first();
    }

    // ================= RMA RULES =================

    get createRuleBtn() {
        return this.page.getByRole("button", { name: "Create RMA Rules" });
    }

    get saveRuleBtn() {
        return this.page.getByRole("button", { name: "Save RMA Rules" });
    }

    get successRuleCreate() {
        return this.page.getByText("RMA Rules created").first();
    }

    get successRuleUpdate() {
        return this.page.getByText("RMA Rules updated successfully.").first();
    }

    get successRuleDelete() {
        return this.page.getByText("RMA Rules deleted successfully.").first();
    }

    // ================= RMA STATUS =================

    get createStatusBtn() {
        return this.page.getByRole("button", { name: "Create RMA Status" });
    }

    get statusTitle() {
        return this.page.getByRole("textbox", { name: "Title" });
    }

    get saveStatusBtn() {
        return this.page.getByRole("button", { name: "Save RMA Status" });
    }

    get successStatusCreate() {
        return this.page.getByText("RMA Status created").first();
    }

    get successStatusUpdate() {
        return this.page.getByText("RMA Status updated successfully.").first();
    }

    get successStatusDelete() {
        return this.page
            .getByText("Selected rma status deleted successfully.")
            .first();
    }

    get selectRow() {
        return this.page.locator(".icon-uncheckbox");
    }

    get selectAction() {
        return this.page.getByRole("button", { name: "Select Action" });
    }

    get deleteAction() {
        return this.page.getByRole("link", { name: "Delete" });
    }

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

    get reasonStatus() {
        return this.page.locator('label[for="status"]');
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
