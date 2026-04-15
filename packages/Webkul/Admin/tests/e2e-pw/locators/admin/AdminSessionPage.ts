import { Locator, Page } from "@playwright/test";

export class AdminSessionPage {
  constructor(private page: Page) {}

  /**
   *  Login
   */

  get userEmail() {
    return this.page.locator('input[name="email"]');
  }

  get userPassword() {
    return this.page.locator('input[name="password"]');
  }

  /**
   *  LogOut
   */

  get profile() {
    return this.page.locator("div.flex.select-none >> button");
  }

  get logout() {
    return this.page.getByRole("link", { name: "Logout" });
  }
}
