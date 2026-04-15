import { Page } from "playwright/test";

export class LocaleActionPage {
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

  get direction() {
    return this.page.locator('select[name="direction"]');
  }

  get successLocaleCreate() {
    return this.page.getByText("Locale created successfully");
  }

  get successLocaleDelete() {
    return this.page.getByText("Locale deleted successfully");
  }

  get successLocaleUpdate() {
    return this.page.getByText("Locale updated successfully");
  }
}
