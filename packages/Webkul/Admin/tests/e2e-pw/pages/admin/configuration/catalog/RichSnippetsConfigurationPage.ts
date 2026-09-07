import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export type RichSnippetOption =
    | "enable"
    | "show_sku"
    | "show_weight"
    | "show_categories"
    | "show_images"
    | "show_reviews"
    | "show_ratings"
    | "show_offers";

export const PRODUCT_RICH_SNIPPET_OPTIONS: RichSnippetOption[] = [
    "enable",
    "show_sku",
    "show_weight",
    "show_categories",
    "show_images",
    "show_reviews",
    "show_ratings",
    "show_offers",
];

export type RichSnippetSettings = Record<RichSnippetOption, boolean>;

export class RichSnippetsConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/catalog/rich_snippets";
    }

    private field(option: RichSnippetOption): string {
        return `catalog[rich_snippets][products][${option}]`;
    }

    async readSettings(): Promise<RichSnippetSettings> {
        await this.open();

        const settings = {} as RichSnippetSettings;

        for (const option of PRODUCT_RICH_SNIPPET_OPTIONS) {
            settings[option] = await this.readBoolean(this.field(option));
        }

        return settings;
    }

    async applySettings(settings: Partial<RichSnippetSettings>): Promise<void> {
        await this.open();

        for (const option of PRODUCT_RICH_SNIPPET_OPTIONS) {
            const value = settings[option];

            if (value !== undefined) {
                await this.setBoolean(this.field(option), value);
            }
        }

        await this.save();
    }

    async expectSettings(settings: Partial<RichSnippetSettings>): Promise<void> {
        await this.open();

        for (const option of PRODUCT_RICH_SNIPPET_OPTIONS) {
            const value = settings[option];

            if (value !== undefined) {
                await this.expectBoolean(this.field(option), value);
            }
        }
    }
}
