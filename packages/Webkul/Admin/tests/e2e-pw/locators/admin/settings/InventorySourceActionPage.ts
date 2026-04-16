import { Page } from "playwright/test";

export class InventorySourceActionPage {
    constructor(private page: Page) {}

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    get fillCode() {
        return this.page.locator('input[name="code"]');
    }

    get name() {
        return this.page.locator('input[name="name"]');
    }

    get description() {
        return this.page.getByRole("textbox", { name: "Description" });
    }

    get contactName() {
        return this.page.locator("#contact_name");
    }

    get contactNumber() {
        return this.page.getByRole("textbox", { name: "Contact Number" });
    }

    get enterEmail() {
        return this.page.getByRole("textbox", { name: "Email" });
    }

    get fax() {
        return this.page.getByRole("textbox", { name: "Fax" });
    }

    get country() {
        return this.page.locator("#country");
    }

    get state() {
        return this.page.locator("#state");
    }

    get city() {
        return this.page.getByRole("textbox", { name: "City" });
    }

    get street() {
        return this.page.getByRole("textbox", { name: "Street" });
    }

    get postcode() {
        return this.page.getByRole("textbox", { name: "Postcode" });
    }

    get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    get successInventorySourceCreate() {
        return this.page.getByText("Inventory Source Created Successfully");
    }

    get successInventorySourceUpdate() {
        return this.page.getByText("Inventory Sources Updated Successfully");
    }

    get successInventorySourceDelete() {
        return this.page.getByText("Inventory Sources Deleted Successfully");
    }
}
