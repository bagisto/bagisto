import { expect, type Locator, type Page } from "@playwright/test";
import type { MediaFile } from "@shared/media-files";

type MediaKind = "image" | "video";

export class MediaGalleryField {
    constructor(
        private readonly root: Locator,
        private readonly kind: MediaKind = "image",
    ) {}

    private get addTile() {
        return this.root
            .locator(`label[for$="_${this.kind}Input"]`)
            .filter({ visible: true });
    }

    private get items() {
        return this.root
            .locator("div.group")
            .filter({
                has: this.root
                    .page()
                    .locator(this.kind === "image" ? "img" : "video"),
            })
            .filter({ visible: true });
    }

    private get sources() {
        return this.items.locator(
            this.kind === "image" ? "img" : "video source",
        );
    }

    private message(text: string) {
        return this.root.getByText(text).filter({ visible: true });
    }

    static underHeading(
        page: Page,
        heading: string,
        kind: MediaKind = "image",
    ): MediaGalleryField {
        return new MediaGalleryField(
            page
                .getByText(heading, { exact: true })
                .locator(
                    `xpath=ancestor::div[.//input[@type="file" and contains(@id, "_${kind}Input")]][1]`,
                ),
            kind,
        );
    }

    async attach(files: MediaFile | MediaFile[]): Promise<void> {
        const attached = await this.items.count();

        await this.addTile.setInputFiles(files);

        await expect(this.items).toHaveCount(
            attached + (Array.isArray(files) ? files.length : 1),
        );
    }

    async attemptAttach(file: MediaFile): Promise<void> {
        await this.addTile.setInputFiles(file);
    }

    async removeAt(position: number): Promise<void> {
        const remaining = (await this.items.count()) - 1;
        const item = this.items.nth(position);

        await item.hover();
        await item.locator(".icon-delete").click();

        await expect(this.items).toHaveCount(remaining);
    }

    async expectError(text: string): Promise<void> {
        await expect(this.message(text)).toBeVisible();
    }

    async expectNoError(text: string): Promise<void> {
        await expect(this.message(text)).toHaveCount(0);
    }

    async expectUploadTileFlagged(): Promise<void> {
        await expect(this.addTile).toHaveClass(/border-red-500/);
    }

    async expectUploadTileNotFlagged(): Promise<void> {
        await expect(this.addTile).not.toHaveClass(/border-red-500/);
    }

    async expectNotice(text: string): Promise<void> {
        await expect(
            this.root.locator("[data-field-error]").filter({ hasText: text }),
        ).toBeVisible();
    }

    async expectErrorInView(): Promise<void> {
        await expect(this.root.locator("[data-field-error]")).toBeInViewport();
    }

    async expectEmpty(): Promise<void> {
        await expect(this.addTile).toBeVisible();
        await expect(this.items).toHaveCount(0);
    }

    async expectStored(count: number = 1): Promise<void> {
        await expect(this.items).toHaveCount(count);

        for (const source of await this.sources.all()) {
            await expect(source).toHaveAttribute("src", /^(?!data:)/);

            if (this.kind === "image") {
                await expect
                    .poll(() =>
                        source.evaluate(
                            (image: HTMLImageElement) => image.naturalWidth,
                        ),
                    )
                    .toBeGreaterThan(0);
            }
        }
    }
}
