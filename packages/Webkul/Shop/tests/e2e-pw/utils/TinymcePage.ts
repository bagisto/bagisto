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

    await expectEditorHolds(page, iframeSelector, editorId, content);
}

async function expectEditorHolds(
    page: Page,
    iframeSelector: string,
    editorId: string,
    content: string,
): Promise<void> {
    if (!/<[a-z][^>]*>/i.test(content)) {
        await expect(
            page.frameLocator(iframeSelector).locator("body"),
        ).toHaveText(content);

        return;
    }

    const expectedText = await page.evaluate((html) => {
        const holder = document.createElement("div");

        holder.innerHTML = html;

        return holder.textContent ?? "";
    }, content);

    await expect
        .poll(async () =>
            withoutWhitespace(
                await page.evaluate(
                    (id) =>
                        (window as any).tinymce.get(id).getBody().textContent,
                    editorId,
                ),
            ),
        )
        .toBe(withoutWhitespace(expectedText));
}

function withoutWhitespace(text: string): string {
    return text.replace(/\s+/g, "");
}

export class TinymcePage {
    constructor(private readonly page: Page) {}

    async fillInTinymce(
        iframeSelector: string,
        content: string,
    ): Promise<void> {
        await fillTinymce(this.page, iframeSelector, content);
    }
}
