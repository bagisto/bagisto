import { expect, type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface ContentSettings {
    headerOfferTitle: string;
    redirectionTitle: string;
    redirectionLink: string;
    customCss: string;
    customJs: string;
}

const TEXTS = {
    headerOfferTitle: "general[content][header_offer][title]",
    redirectionTitle: "general[content][header_offer][redirection_title]",
    redirectionLink: "general[content][header_offer][redirection_link]",
} as const;

const TEXT_AREAS = {
    customCss: "general[content][custom_scripts][custom_css]",
    customJs: "general[content][custom_scripts][custom_javascript]",
} as const;

export class ContentConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/general/content";
    }

    async readSettings(): Promise<ContentSettings> {
        await this.open();

        return {
            headerOfferTitle: await this.readText(TEXTS.headerOfferTitle),
            redirectionTitle: await this.readText(TEXTS.redirectionTitle),
            redirectionLink: await this.readText(TEXTS.redirectionLink),
            customCss: await this.readTextArea(TEXT_AREAS.customCss),
            customJs: await this.readTextArea(TEXT_AREAS.customJs),
        };
    }

    async applySettings(settings: Partial<ContentSettings>): Promise<void> {
        await this.open();

        for (const key of Object.keys(TEXTS) as (keyof typeof TEXTS)[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.setText(TEXTS[key], value);
            }
        }

        for (const key of Object.keys(TEXT_AREAS) as (keyof typeof TEXT_AREAS)[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.setTextArea(TEXT_AREAS[key], value);
            }
        }

        await this.save();
    }

    async expectSettings(settings: Partial<ContentSettings>): Promise<void> {
        await this.open();

        for (const key of Object.keys(TEXTS) as (keyof typeof TEXTS)[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.expectText(TEXTS[key], value);
            }
        }

        for (const key of Object.keys(TEXT_AREAS) as (keyof typeof TEXT_AREAS)[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.expectTextArea(TEXT_AREAS[key], value);
            }
        }
    }

    async expectHeaderOfferOnStorefront(
        title: string,
        redirectionTitle: string,
    ): Promise<void> {
        await this.visit("");

        await expect(this.page.getByText(title)).toBeVisible();
        await expect(
            this.page.getByRole("link", { name: redirectionTitle }),
        ).toBeVisible();
    }
}
