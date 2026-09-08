import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface SitemapSettings {
    enabled: boolean;
    maximumUrls: string;
}

const FIELDS = {
    enabled: "general[sitemap][settings][enabled]",
    maximumUrls: "general[sitemap][file_limits][max_url_per_file]",
} as const;

export class SitemapConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/general/sitemap";
    }

    async readSettings(): Promise<SitemapSettings> {
        await this.open();

        return {
            enabled: await this.readBoolean(FIELDS.enabled),
            maximumUrls: await this.readText(FIELDS.maximumUrls),
        };
    }

    async applySettings(settings: Partial<SitemapSettings>): Promise<void> {
        await this.open();

        if (settings.enabled !== undefined) {
            await this.setBoolean(FIELDS.enabled, settings.enabled);
        }

        if (settings.maximumUrls !== undefined) {
            await this.setText(FIELDS.maximumUrls, settings.maximumUrls);
        }

        await this.save();
    }

    async expectSettings(settings: Partial<SitemapSettings>): Promise<void> {
        await this.open();

        if (settings.enabled !== undefined) {
            await this.expectBoolean(FIELDS.enabled, settings.enabled);
        }

        if (settings.maximumUrls !== undefined) {
            await this.expectText(FIELDS.maximumUrls, settings.maximumUrls);
        }
    }
}
