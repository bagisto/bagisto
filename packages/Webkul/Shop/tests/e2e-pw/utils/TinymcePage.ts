import { expect, type Page } from "@playwright/test";

export async function fillTinymce(
    page: Page,
    iframeSelector: string,
    content: string,
): Promise<void> {
    const editorId = iframeSelector.replace(/^#/, "").replace(/_ifr$/, "");

    await page.waitForFunction(
        (id) => {
            const editor = (window as any).tinymce?.get(id);

            return !!editor && editor.initialized;
        },
        editorId,
        { timeout: 60 * 1000 },
    );

    await page.evaluate(
        ({ id, value }) => {
            const editor = (window as any).tinymce.get(id);

            editor.setContent(value);
            editor.fire("keyup");
            editor.save();
        },
        { id: editorId, value: content },
    );

    await expect(page.frameLocator(iframeSelector).locator("body")).toHaveText(
        content,
    );
}

export class TinymcePage {
    constructor(private readonly page: Page) {}

    async fillInTinymce(iframeSelector: string, content: string): Promise<void> {
        await fillTinymce(this.page, iframeSelector, content);
    }
}
