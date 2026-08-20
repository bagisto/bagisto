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

    private get publishActiveButton() {
        return this.page.getByRole("button", { name: "Publish", exact: true });
    }

    private get closeDrawerButton() {
        return this.page.locator("span.icon-cross").last();
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    /**
     * The tile carries an icon glyph beside its label, which lands in the button's
     * accessible name, so it is picked by the label itself.
     */
    private typeTile(label: string) {
        return this.createForm
            .locator("span")
            .filter({ hasText: new RegExp(`^${label}$`) });
    }

    /**
     * Scoped to the draggable list, so the open section's drawer header does not answer
     * to the name as well.
     */
    private sectionRow(name: string) {
        return this.page
            .locator("div[data-draggable]")
            .filter({ hasText: name });
    }

    /**
     * The dot a row carries while it holds changes the storefront has not been given
     * yet. Read per row rather than from the header count, which every other section
     * in the list also feeds.
     */
    private unsavedMarker(name: string) {
        return this.sectionRow(name).locator("span.icon-dot");
    }

    /**
     * Creating a section opens it for editing straight away, and the open drawer covers
     * the list underneath it.
     */
    private async closeOpenSection(): Promise<void> {
        await this.closeDrawerButton.click();

        await expect(this.publishActiveButton).toBeHidden();
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

        return name;
    }

    /**
     * A new section is staged rather than published, so it has to count as an unsaved
     * change and reach the storefront only once it is published.
     */
    async createAndPublishSection(type: string): Promise<void> {
        const name = await this.createSection(type);

        await expect(this.unsavedMarker(name)).toBeVisible();

        await this.publishActiveButton.click();

        await expect(this.unsavedMarker(name)).toHaveCount(0);

        await expect(this.sectionRow(name)).toBeVisible();
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

    /**
     * A channel shows one footer links section, so the type is withdrawn once the
     * channel already has one.
     */
    async expectFooterLinksNotOffered(): Promise<void> {
        await this.open();
        await this.createSectionButton.click();
        await this.createForm.waitFor();

        await expect(this.typeTile("Footer Links")).toHaveCount(0);
    }
}
