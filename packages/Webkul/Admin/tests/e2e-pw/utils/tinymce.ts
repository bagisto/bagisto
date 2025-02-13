import { expect } from "../setup";

export async function fillInTinymce(page, iframeSelector, content) {
    await page.waitForSelector(iframeSelector);
    const iframe = await page.frameLocator(iframeSelector);
    const editorBody = iframe.locator("body");
    await editorBody.click();
    await editorBody.pressSequentially(content);
    await expect(editorBody).toHaveText(content);
}
