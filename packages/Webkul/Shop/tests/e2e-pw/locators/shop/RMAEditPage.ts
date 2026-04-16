import { Locator, Page } from "@playwright/test";

export class RMAEditPage {
    constructor(private page: Page) {}

    get viewOrder(): Locator {
      return this.page.locator(".row > div:nth-child(4) > a").first();
    }

    get Invoice(): Locator {
      return this.page.getByText("Invoice", { exact: true });
    }

    get createInvoice(): Locator {
      return this.page.getByRole("button", {
        name: "Create Invoice",
      });
    }

    get successInvoice(): Locator {
      return this.page.getByText("Invoice created successfully");
    }

    get reqRMA(): Locator {
      return this.page.getByText("New RMA Request");
    }

    get editIcon(): Locator {
      return this.page.locator("a.icon-edit");
    }

    get checkBox(): Locator {
      return this.page.locator('input[name^="isChecked["]');
    }

    get resolution(): Locator {
      return this.page.locator('select[name^="resolution_type"]');
    }

    get reason(): Locator {
      return this.page.locator('select[name="rma_reason_id"]');
    }

    get rmaQTY(): Locator {
      return this.page.locator('input[name^="rma_qty"]');
    }

    get orderStatus(): Locator {
      return this.page.locator('select[name="package_condition"]');
    }

    get info(): Locator {
      return this.page.locator('textarea[name="information"]');
    }

    get agreement(): Locator {
      return this.page.locator("label:has(input#agreement)");
    }

    get submit(): Locator {
      return this.page.locator('button:has-text("Submit request")');
    }

    get successRMA(): Locator {
      return this.page
        .getByRole("paragraph")
        .filter({ hasText: "Request created successfully." });
    }

    get invalidRMAMessage(): Locator {
      return this.page.getByText(
        "The RMA Qty field must be 1 or less"
      );
    }
}
