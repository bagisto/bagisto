import { Locator, Page } from "@playwright/test";

export class CategoryActionPage {
  constructor(private page: Page) {}

  get createBtn() {
    return this.page.locator(".primary-button");
  }

  get iconEdit() {
    return this.page.locator(".icon-edit");
  }

  get saveCategoryBtn() {
    return this.page.getByRole("button", { name: "Save Category" });
  }

  get categorySuccess() {
    return this.page.getByText("Category updated successfully.");
  }

  get selectRowBtn() {
    return this.page.locator(".icon-uncheckbox");
  }

  get selectAction() {
    return this.page.getByRole("button", { name: "Select Action" });
  }

  get deleteBtn() {
    return this.page.getByRole("link", { name: "Delete" });
  }

  get agreeBtn() {
    return this.page.getByRole("button", { name: "Agree", exact: true });
  }

  get categoryDeleteSuccess() {
    return this.page.getByText("The category has been successfully deleted.");
  }
}
