import { expect, type Locator, type Page } from "@playwright/test";
import type { MediaFile } from "@shared/media-files";
import { BasePage } from "../../BasePage";

export class SectionsPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createSectionButton() {
        return this.page.getByTitle("Create Section");
    }

    private get createForm() {
        return this.page.locator("#section-create-form");
    }

    private get saveSectionButton() {
        return this.page.getByRole("button", { name: "Save Section" });
    }

    private get closeDrawerButton() {
        return this.page.locator("div.fixed div.absolute > span.icon-cross");
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get publishAllButton() {
        return this.page.getByRole("button", { name: /^Publish \(\d+\)$/ });
    }

    private get discardAllButton() {
        return this.page.getByRole("button", { name: "Discard", exact: true });
    }

    private get sectionRows() {
        return this.page.locator("div[data-draggable]");
    }

    private get sectionNames() {
        return this.sectionRows.locator("span.truncate.font-medium");
    }

    private typeTile(label: string) {
        return this.createForm
            .locator("span")
            .filter({ hasText: new RegExp(`^${label}$`) });
    }

    private sectionRow(name: string): Locator {
        return this.sectionRows.filter({
            has: this.page.locator("span.truncate.font-medium", {
                hasText: new RegExp(`^\\s*${name}\\s*$`),
            }),
        });
    }

    private sectionName(name: string) {
        return this.sectionRow(name).locator("span.truncate.font-medium");
    }

    private unsavedMarker(name: string) {
        return this.sectionRow(name).locator("span.icon-dot");
    }

    private statusToggle(name: string) {
        return this.sectionRow(name).locator("button.relative");
    }

    private dragHandle(name: string) {
        return this.sectionRow(name).locator("span.section-handle");
    }

    private get addSlideButton() {
        return this.page.getByRole("button", { name: "Add Slider" });
    }

    private get slideImageInput() {
        return this.page.locator('input[type="file"][accept="image/*"]');
    }

    private get slideImagePreview() {
        return this.slideImageInput.locator(
            "xpath=ancestor::label/preceding-sibling::img",
        );
    }

    private get htmlMediaInput() {
        return this.page.locator('input[type="file"][accept="image/*,video/*"]');
    }

    private get htmlEditor() {
        return this.page
            .locator("p")
            .filter({ hasText: /^HTML$/ })
            .locator("xpath=following-sibling::*[1]");
    }

    private field(label: string) {
        return this.page
            .locator("p")
            .filter({ hasText: new RegExp(`^${label}$`) })
            .locator(
                "xpath=following-sibling::*[self::input or self::textarea][1]",
            );
    }

    private readHtml(): Promise<string> {
        return this.htmlEditor.evaluate((editor) => {
            const codeMirror = editor.querySelector(".CodeMirror") as
                | (Element & { CodeMirror: { getValue: () => string } })
                | null;

            return (
                codeMirror?.CodeMirror.getValue() ??
                (editor.querySelector("textarea") as HTMLTextAreaElement).value
            );
        });
    }

    private waitForMediaResponse() {
        return this.page.waitForResponse(
            (response) =>
                /\/admin\/appearance\/sections\/\d+\/media(\?|$)/.test(response.url())
                && response.request().method() === "POST",
        );
    }

    private async uploadAndWaitForDraft(
        input: Locator,
        file: MediaFile,
    ): Promise<void> {
        let storedName = "";

        const draftSaved = this.page.waitForResponse(
            (response) =>
                /\/admin\/appearance\/sections\/\d+\/draft(\?|$)/.test(response.url())
                && response.request().method() === "POST"
                && storedName !== ""
                && (response.request().postData() ?? "").includes(storedName),
        );

        const [upload] = await Promise.all([
            this.waitForMediaResponse(),
            input.setInputFiles(file),
        ]);

        storedName = String((await upload.json()).path).split("/").pop() ?? "";

        await draftSaved;
    }

    async open(): Promise<void> {
        await this.visit("admin/appearance/themes/default/sections");

        await expect(this.createSectionButton).toBeVisible();
    }

    async createSection(type: string, name: string): Promise<void> {
        await this.open();
        await this.createSectionButton.click();
        await this.createForm.waitFor();
        await this.typeTile(type).click();
        await this.createForm.locator('input[name="name"]').fill(name);
        await this.saveSectionButton.click();

        await expect(
            this.page.getByText("Section created successfully"),
        ).toBeVisible();
        await expect(this.createForm).toBeHidden();
        await expect(this.sectionRow(name)).toHaveCount(1);
        await expect(this.closeDrawerButton).toHaveCount(1);
    }

    async openSection(name: string): Promise<void> {
        await this.open();
        await this.sectionName(name).click();

        await expect(this.closeDrawerButton).toHaveCount(1);
    }

    async closeSection(): Promise<void> {
        await this.closeDrawerButton.click();

        await expect(this.closeDrawerButton).toHaveCount(0);
    }

    async addSlide(): Promise<void> {
        await this.addSlideButton.click();

        await expect(this.slideImageInput).toBeAttached();
    }

    async uploadSlideImage(file: MediaFile): Promise<void> {
        await this.uploadAndWaitForDraft(this.slideImageInput, file);
    }

    async attemptSlideImage(file: MediaFile): Promise<void> {
        await Promise.all([
            this.waitForMediaResponse(),
            this.slideImageInput.setInputFiles(file),
        ]);
    }

    async insertHtmlMedia(file: MediaFile): Promise<void> {
        await this.uploadAndWaitForDraft(this.htmlMediaInput, file);
    }

    async attemptHtmlMedia(file: MediaFile): Promise<void> {
        await Promise.all([
            this.waitForMediaResponse(),
            this.htmlMediaInput.setInputFiles(file),
        ]);
    }

    async fillField(label: string, value: string): Promise<void> {
        const draftSaved = this.page.waitForResponse(
            (response) =>
                /\/admin\/appearance\/sections\/\d+\/draft(\?|$)/.test(response.url())
                && response.request().method() === "POST",
        );

        await this.field(label).fill(value);

        await draftSaved;
    }

    async publishAll(): Promise<void> {
        const [response] = await Promise.all([
            this.page.waitForResponse((response) =>
                response.url().includes("/sections/publish"),
            ),
            this.publishAllButton.click(),
        ]);

        expect(response.ok()).toBeTruthy();

        await expect(this.publishAllButton).toHaveCount(0);
    }

    async discardAll(): Promise<void> {
        await this.discardAllButton.click();

        await expect(this.publishAllButton).toHaveCount(0);
    }

    async toggleStatus(name: string): Promise<void> {
        await this.statusToggle(name).click();
    }

    async dragSectionOnto(name: string, targetName: string): Promise<void> {
        const target = this.sectionRow(targetName);
        const box = await target.boundingBox();

        if (!box) {
            throw new Error(`Section "${targetName}" has no bounding box to drop onto`);
        }

        await this.dragHandle(name).dragTo(target, {
            targetPosition: { x: box.width / 2, y: 4 },
        });

        await expect
            .poll(
                async () => {
                    const order = await this.readOrder();

                    return order.indexOf(name) < order.indexOf(targetName);
                },
                { message: `"${name}" never moved before "${targetName}"` },
            )
            .toBe(true);
    }

    async deleteSection(name: string): Promise<void> {
        await this.open();

        const row = this.sectionRow(name);

        await row.locator("button.icon-dots").click();
        await row.getByText("Delete", { exact: true }).click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Section deleted successfully"),
        ).toBeVisible();
        await expect(row).toHaveCount(0);
    }

    async deleteSectionsIfPresent(names: string[]): Promise<void> {
        const failures: string[] = [];

        for (const name of names) {
            try {
                await this.open();

                if (await this.sectionRow(name).count()) {
                    await this.deleteSection(name);
                }
            } catch (error) {
                failures.push(`${name}: ${error}`);
            }
        }

        if (failures.length) {
            throw new Error(`Cleanup failed for:\n${failures.join("\n")}`);
        }
    }

    async readOrder(): Promise<string[]> {
        return (await this.sectionNames.allInnerTexts()).map((text) =>
            text.trim(),
        );
    }

    async expectSectionListed(name: string, type: string): Promise<void> {
        await expect(this.sectionRow(name)).toHaveCount(1);
        await expect(this.sectionRow(name)).toContainText(type);
    }

    async expectUploadRefused(message: string): Promise<void> {
        await expect(this.page.getByText(message)).toBeVisible();
    }

    async expectSlideImageShown(): Promise<void> {
        await expect(this.slideImagePreview).toBeVisible();

        await expect
            .poll(() =>
                this.slideImagePreview.evaluate(
                    (image: HTMLImageElement) => image.naturalWidth,
                ),
            )
            .toBeGreaterThan(0);
    }

    async expectNoSlideImage(): Promise<void> {
        await expect(this.slideImagePreview).toHaveCount(0);
    }

    async expectHtmlContains(fragment: string): Promise<void> {
        await expect.poll(() => this.readHtml()).toContain(fragment);
    }

    async expectHtmlEmpty(): Promise<void> {
        await expect.poll(() => this.readHtml()).toBe("");
    }

    async expectEditorFields(labels: string[]): Promise<void> {
        for (const label of labels) {
            await expect(
                this.page.locator("p").filter({ hasText: new RegExp(`^${label}$`) }),
            ).toBeVisible();
        }
    }

    async expectFieldValue(label: string, value: string): Promise<void> {
        await expect(this.field(label)).toHaveValue(value);
    }

    async expectUnpublishedChanges(name: string): Promise<void> {
        await expect(this.unsavedMarker(name)).toBeVisible();
    }

    async expectPublished(name: string): Promise<void> {
        await expect(this.unsavedMarker(name)).toHaveCount(0);
    }

    async expectSwitchedOff(name: string): Promise<void> {
        await expect(this.sectionName(name)).toHaveClass(/line-through/);
    }

    async expectSwitchedOn(name: string): Promise<void> {
        await expect(this.sectionName(name)).not.toHaveClass(/line-through/);
    }

    async expectOrderedBefore(first: string, second: string): Promise<void> {
        const order = await this.readOrder();

        expect(order.indexOf(first)).toBeGreaterThanOrEqual(0);
        expect(order.indexOf(second)).toBeGreaterThan(order.indexOf(first));
    }

    async expectTypeNotOffered(type: string): Promise<void> {
        await this.open();
        await this.createSectionButton.click();
        await this.createForm.waitFor();

        await expect(this.typeTile(type)).toHaveCount(0);
    }
}
