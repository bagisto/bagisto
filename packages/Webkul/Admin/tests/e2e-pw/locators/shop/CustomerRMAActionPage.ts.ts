import { Locator, Page } from "@playwright/test";

export class CustomerRMAActionPage {
    constructor(private page: Page) {}

    get successRMA() {
        return this.page.getByText("Request created successfully.");
    }

    get successAdminRMA() {
        return this.page.getByText("RMA created successfully.");
    }

    get invalidRMAMessage() {
        return this.page.getByText("The RMA Qty field must be 1 or less");
    }

    get rmaAcceptmsg() {
        return this.page.getByText("RMA Status updated").first();
    }

    get reqRMA() {
        return this.page.locator('text=" New RMA Request "');
    }

    get editIcon() {
        return this.page.locator("a.icon-edit");
    }

    get checkBox() {
        return this.page.locator('input[name^="isChecked["]');
    }

    get resolution() {
        return this.page.locator('select[name^="resolution_type"]');
    }

    get reason() {
        return this.page.locator('select[name="rma_reason_id"]');
    }

    get rmaQTY() {
        return this.page.locator('input[name^="rma_qty"]');
    }

    get orderStatus() {
        return this.page.locator('select[name="package_condition"]');
    }

    get info() {
        return this.page.locator('textarea[name="information"]');
    }

    get agreement() {
        return this.page.locator("label:has(input#agreement)");
    }

    get submit() {
        return this.page.locator('button:has-text("Submit request")');
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
}
