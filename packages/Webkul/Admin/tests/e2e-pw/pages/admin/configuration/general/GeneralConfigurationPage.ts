import { expect, type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface GeneralSettings {
    weightUnit: string;
    breadcrumbs: boolean;
}

const FIELDS = {
    weightUnit: "general[general][locale_options][weight_unit]",
    breadcrumbs: "general[general][breadcrumbs][shop]",
} as const;

export class GeneralConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/general/general";
    }

    private get storefrontBreadcrumbs() {
        return this.page.locator("nav ol li", { hasText: /compare/i });
    }

    async readSettings(): Promise<GeneralSettings> {
        await this.open();

        return {
            weightUnit: await this.readSelect(FIELDS.weightUnit),
            breadcrumbs: await this.readBoolean(FIELDS.breadcrumbs),
        };
    }

    async applySettings(settings: Partial<GeneralSettings>): Promise<void> {
        await this.open();

        if (settings.weightUnit !== undefined) {
            await this.setSelect(FIELDS.weightUnit, settings.weightUnit);
        }

        if (settings.breadcrumbs !== undefined) {
            await this.setBoolean(FIELDS.breadcrumbs, settings.breadcrumbs);
        }

        await this.save();
    }

    async expectSettings(settings: Partial<GeneralSettings>): Promise<void> {
        await this.open();

        if (settings.weightUnit !== undefined) {
            await this.expectSelect(FIELDS.weightUnit, settings.weightUnit);
        }

        if (settings.breadcrumbs !== undefined) {
            await this.expectBoolean(FIELDS.breadcrumbs, settings.breadcrumbs);
        }
    }

    async expectBreadcrumbsOnStorefront(shown: boolean): Promise<void> {
        await this.visit("compare");

        await expect(
            this.page.getByRole("heading", { name: "Product Compare" }),
        ).toBeVisible();
        await expect(this.storefrontBreadcrumbs).toHaveCount(shown ? 1 : 0);
    }
}
