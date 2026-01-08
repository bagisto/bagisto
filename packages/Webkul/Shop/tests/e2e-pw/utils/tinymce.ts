import { Page, expect } from "@playwright/test";

export class CommonPage {
  readonly page: Page;

  constructor(page: Page) {
    this.page = page;
  }

  /**
   * Fill TinyMCE editor inside an iframe
   * @param iframeSelector - iframe CSS or XPath selector
   * @param content - text content to insert into TinyMCE
   */
  async fillInTinymce(iframeSelector: string, content: string) {
    await this.page.waitForSelector(iframeSelector);
    const iframe = this.page.frameLocator(iframeSelector);
    const editorBody = iframe.locator("body");

    await expect(editorBody).toBeVisible();

    await editorBody.click();
    await editorBody.press("Control+A"); // Select all existing content
    await editorBody.press("Backspace"); // Clear existing content
    await editorBody.pressSequentially(content);

    await expect(editorBody).toHaveText(content);
  }
}