import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import { generateName } from "../../../utils/faker";

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
        return this.page.locator("span.icon-cross").last();
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

    private typeTile(label: string) {
        return this.createForm
            .locator("span")
            .filter({ hasText: new RegExp(`^${label}$`) });
    }

    private sectionRow(name: string) {
        return this.page
            .locator("div[data-draggable]")
            .filter({ hasText: name });
    }

    private unsavedMarker(name: string) {
        return this.sectionRow(name).locator("span.icon-dot");
    }

    private field(label: string) {
        return this.page
            .locator("p")
            .filter({ hasText: new RegExp(`^${label}$`) })
            .locator(
                "xpath=following-sibling::*[self::input or self::textarea][1]",
            );
    }

    private rowName(index: number) {
        return this.sectionRows.nth(index).locator("span.truncate").first();
    }

    private statusToggle(name: string) {
        return this.sectionRow(name).locator("button.relative");
    }

    private switchedOffName(name: string) {
        return this.sectionRow(name).locator("span.line-through");
    }

    private async dragRowOnto(from: number, to: number): Promise<void> {
        const handle = this.sectionRows.nth(from).locator("span.section-handle");

        const target = await this.sectionRows.nth(to).boundingBox();

        await handle.hover();
        await this.page.mouse.down();
        await this.page.mouse.move(
            target.x + target.width / 2,
            target.y + target.height / 2,
            { steps: 12 },
        );
        await this.page.mouse.up();
    }

    private async closeOpenSection(): Promise<void> {
        await this.closeDrawerButton.click();

        await expect(this.page.locator("span.icon-cross")).toHaveCount(0);
    }

    async open(): Promise<void> {
        await this.visit("admin/appearance/themes/default/sections");
        await this.page.waitForLoadState("networkidle");
        await expect(this.createSectionButton).toBeVisible();
    }

    async createSection(type: string): Promise<string> {
        const name = generateName();

        await this.open();
        await this.createSectionButton.click();
        await this.createForm.waitFor();
        await this.typeTile(type).click();
        await this.createForm.locator('input[name="name"]').fill(name);
        await this.saveSectionButton.click();

        await expect(
            this.page.getByText("Section created successfully"),
        ).toBeVisible();

        await expect(this.sectionRow(name)).toBeVisible();

        await expect(this.sectionRow(name)).toContainText(type);

        return name;
    }

    async createSectionOfType(type: string, fields: string[]): Promise<void> {
        const name = await this.createSection(type);

        for (const label of fields) {
            await expect(
                this.page.getByText(label, { exact: true }).first(),
            ).toBeVisible();
        }

        await expect(this.unsavedMarker(name)).toBeVisible();

        await this.closeOpenSection();

        await this.publishAllButton.click();

        await expect(this.unsavedMarker(name)).toHaveCount(0);
    }

    async deleteSection(type: string): Promise<void> {
        const name = await this.createSection(type);

        await this.closeOpenSection();

        const row = this.sectionRow(name);

        await row.locator("button.icon-dots").click();
        await row.getByText("Delete", { exact: true }).click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Section deleted successfully"),
        ).toBeVisible();

        await expect(row).toHaveCount(0);
    }

    async editContentAndPublish(): Promise<void> {
        const name = await this.createSection("Product Carousel");

        const title = generateName();

        await this.field("Title").fill(title);

        await expect(this.unsavedMarker(name)).toBeVisible();

        await this.closeOpenSection();

        await this.publishAllButton.click();

        await expect(this.unsavedMarker(name)).toHaveCount(0);

        await this.open();

        await this.sectionRow(name).getByText(name).click();

        await expect(this.field("Title")).toHaveValue(title);
    }

    async reorderIsStagedUntilPublished(): Promise<void> {
        await this.open();

        const first = await this.rowName(0).innerText();

        const second = await this.rowName(1).innerText();

        const third = await this.rowName(2).innerText();

        await this.dragRowOnto(1, 0);

        await expect(this.rowName(0)).toHaveText(second);

        await expect(
            this.sectionRows.nth(0).locator("span.icon-dot"),
        ).toBeVisible();

        await expect(
            this.sectionRows.nth(1).locator("span.icon-dot"),
        ).toBeVisible();

        await expect(
            this.sectionRows.nth(2).locator("span.icon-dot"),
        ).toHaveCount(0);

        await expect(this.rowName(2)).toHaveText(third);

        await this.publishAllButton.click();

        await this.open();

        await expect(this.rowName(0)).toHaveText(second);

        await expect(this.rowName(1)).toHaveText(first);
    }

    async statusChangeIsStaged(): Promise<void> {
        const name = await this.createSection("Static Content");

        await this.closeOpenSection();

        await this.publishAllButton.click();

        await expect(this.unsavedMarker(name)).toHaveCount(0);

        await this.statusToggle(name).click();

        await expect(this.unsavedMarker(name)).toBeVisible();

        await this.publishAllButton.click();

        await expect(this.unsavedMarker(name)).toHaveCount(0);
    }

    async discardRevertsStagedStatus(): Promise<void> {
        const name = await this.createSection("Static Content");

        await this.closeOpenSection();

        await this.publishAllButton.click();

        await expect(this.unsavedMarker(name)).toHaveCount(0);

        await expect(this.switchedOffName(name)).toHaveCount(0);

        await this.statusToggle(name).click();

        await expect(this.unsavedMarker(name)).toBeVisible();

        await expect(this.switchedOffName(name)).toBeVisible();

        await this.discardAllButton.click();

        await expect(this.unsavedMarker(name)).toHaveCount(0);

        await expect(this.switchedOffName(name)).toHaveCount(0);
    }

    async expectFooterLinksNotOffered(): Promise<void> {
        await this.open();
        await this.createSectionButton.click();
        await this.createForm.waitFor();

        await expect(this.typeTile("Footer Links")).toHaveCount(0);
    }
}
