import { Page } from "playwright/test";

export class CurrencyActionPage {
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

  get successCurrencyCreate() {
    return this.page.getByText("Currency created successfully");
  }

  get successCurrencyDelete() {
    return this.page.getByText("Currency deleted successfully");
  }

  get successCurrencyUpdate() {
    return this.page.getByText("Currency updated successfully");
  }
}
