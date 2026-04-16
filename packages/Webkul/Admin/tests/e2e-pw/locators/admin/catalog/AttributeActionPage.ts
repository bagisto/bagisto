import { Locator, Page } from "@playwright/test";

export class AttributeActionPage {
  constructor(private page: Page) {}

  get createBtn() {
    return this.page.locator(".primary-button");
  }

  get editBtn() {
    return this.page.locator("span.cursor-pointer.icon-sort-right").nth(1);
  }

  get fillname() {
    return this.page.locator('input[name="admin_name"]');
  }

  get fillCode() {
    return this.page.locator('input[name="code"]');
  }

  get selectTypeAttribute() {
    return this.page.locator('select[name="type"]');
  }

  get saveAttributeBtn() {
    return this.page.locator("button.primary-button");
  }

  get attributeSuccess() {
    return this.page.getByText("Attribute created successfully");
  }

  get iconEdit() {
    return this.page.locator(".icon-edit");
  }

  get attributeUpdateSuccess() {
    return this.page.getByText("Attribute Updated Successfully");
  }

  get deleteIcon() {
    return this.page.locator(".icon-delete");
  }

  get agreeBtn() {
    return this.page.getByRole("button", { name: "Agree", exact: true });
  }

  get attributeDeleteSuccess() {
    return this.page.getByText(/Attribute Deleted/);
  }
}
