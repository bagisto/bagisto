import { Locator, Page } from "@playwright/test";

export class OrderActionPage {
  constructor(private page: Page) {}

  get createBtn() {
    return this.page.locator(".primary-button");
  }

  get saveBtn() {
    return this.page.locator("button.secondary-button");
  }

  get copyBtn() {
    return this.page.locator("span.icon-copy");
  }
}
