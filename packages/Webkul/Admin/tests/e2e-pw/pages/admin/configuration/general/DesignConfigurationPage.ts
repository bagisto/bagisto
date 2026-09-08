import { expect, type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export type CategoryView = "default" | "sidebar";

export interface DesignSettings {
    categoryView: CategoryView;
}

const CATEGORY_VIEW = "general[design][categories][category_view]";

type LogoField = "logo_image" | "favicon";

export class DesignConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/general/design";
    }

    private mediaInput(field: LogoField) {
        return this.page.locator(
            `input[type="file"][name="general[design][admin_logo][${field}]"]`,
        );
    }

    private mediaTile(field: LogoField) {
        return this.mediaInput(field).locator("xpath=../div[1]");
    }

    private previewButton(view: CategoryView) {
        return this.page.getByRole("button", {
            name: view === "sidebar" ? "Preview Sidebar Menu" : "Preview Default Menu",
        });
    }

    private get previewModal() {
        return this.page.locator("div.fixed div.box-shadow:has(span.icon-close)");
    }

    private get closePreviewButton() {
        return this.previewModal.locator("span.icon-close");
    }

    async readSettings(): Promise<DesignSettings> {
        await this.open();

        return {
            categoryView: (await this.readSelect(CATEGORY_VIEW)) as CategoryView,
        };
    }

    async applySettings(settings: Partial<DesignSettings>): Promise<void> {
        await this.open();

        if (settings.categoryView !== undefined) {
            await this.setSelect(CATEGORY_VIEW, settings.categoryView);
        }

        await this.save();
    }

    async expectSettings(settings: Partial<DesignSettings>): Promise<void> {
        await this.open();

        if (settings.categoryView !== undefined) {
            await this.expectSelect(CATEGORY_VIEW, settings.categoryView);
        }
    }

    async uploadMedia(field: LogoField, filePath: string): Promise<void> {
        await this.open();

        await expect(this.mediaInput(field)).toBeAttached();

        await this.mediaInput(field).setInputFiles(filePath);

        await expect(this.mediaTile(field).locator("img")).toBeVisible();

        await this.save();
    }

    async deleteMedia(field: LogoField): Promise<void> {
        await this.open();

        const tile = this.mediaTile(field);

        await tile.hover();
        await tile.locator(".icon-delete").click();

        await expect(tile).toBeHidden();

        await this.save();
    }

    async hasMedia(field: LogoField): Promise<boolean> {
        await this.open();

        return (await this.mediaTile(field).locator("img").count()) > 0;
    }

    async expectMediaStored(field: LogoField): Promise<void> {
        await this.open();

        await expect(this.mediaTile(field).locator("img")).toHaveAttribute(
            "src",
            /storage\//,
        );
    }

    async expectMediaAbsent(field: LogoField): Promise<void> {
        await this.open();

        await expect(this.mediaTile(field).locator("img")).toHaveCount(0);
    }

    async previewCategoryView(view: CategoryView): Promise<void> {
        await this.open();
        await this.setSelect(CATEGORY_VIEW, view);
        await this.previewButton(view).click();

        await expect(this.previewModal).toBeVisible();

        await this.closePreviewButton.click();

        await expect(this.previewModal).toHaveCount(0);
    }
}
