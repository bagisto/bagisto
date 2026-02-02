import { Page, expect } from "@playwright/test";

export async function fillInTinymce(
    page: Page,
    iframeSelector: string,
    content: string,
) {
    const iframe = page.frameLocator(iframeSelector);
    const body = iframe.locator("body");

    await body.click();
    await body.press("Control+a");
    await body.press("Backspace");
    await body.pressSequentially(content);
    await expect(body).toHaveText(content);
}
