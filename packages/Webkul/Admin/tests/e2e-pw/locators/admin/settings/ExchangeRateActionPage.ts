import { Page } from "playwright/test";

export class ExchangeRateActionPage {
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

  get targetCurrency() {
    return this.page.locator('select[name="target_currency"]');
  }

  get rate() {
    return this.page.locator('input[name="rate"]');
  }

  get successExchangeRateCreate() {
    return this.page.getByText("Exchange Rate created successfully");
  }

  get successExchangeRateDelete() {
    return this.page.getByText("Exchange Rate deleted successfully");
  }

  get successExchangeRateUpdate() {
    return this.page.getByText("Exchange Rate updated successfully");
  }
}
