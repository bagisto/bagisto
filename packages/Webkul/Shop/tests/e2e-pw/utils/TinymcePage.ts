import { Page, expect } from "@playwright/test";

export class TinymcePage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page;
    }

    async fillInTinymce(iframeSelector: string, content: string) {
        await this.page.waitForSelector(iframeSelector);
        const iframe = this.page.frameLocator(iframeSelector);
        const editorBody = iframe.locator("body");

        await expect(editorBody).toBeVisible();

        await editorBody.click();
        await editorBody.press("Control+A");
        await editorBody.press("Backspace");
        await editorBody.pressSequentially(content);

        await expect(editorBody).toHaveText(content);
    }
}
