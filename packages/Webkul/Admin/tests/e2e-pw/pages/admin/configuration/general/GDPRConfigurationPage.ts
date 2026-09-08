import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface GdprSettings {
    enabled: boolean;
    agreementEnabled: boolean;
    agreementLabel: string;
    cookieEnabled: boolean;
    cookiePosition: string;
    cookieBlockIdentifier: string;
    cookieDescription: string;
}

const BOOLEANS = {
    enabled: "general[gdpr][settings][enabled]",
    agreementEnabled: "general[gdpr][agreement][enabled]",
    cookieEnabled: "general[gdpr][cookie][enabled]",
} as const;

const TEXTS = {
    agreementLabel: "general[gdpr][agreement][agreement_label]",
    cookieBlockIdentifier: "general[gdpr][cookie][static_block_identifier]",
} as const;

const COOKIE_POSITION = "general[gdpr][cookie][position]";

const COOKIE_DESCRIPTION = "general[gdpr][cookie][description]";

export class GDPRConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/general/gdpr";
    }

    async readSettings(): Promise<GdprSettings> {
        await this.open();

        return {
            enabled: await this.readBoolean(BOOLEANS.enabled),
            agreementEnabled: await this.readBoolean(BOOLEANS.agreementEnabled),
            agreementLabel: await this.readText(TEXTS.agreementLabel),
            cookieEnabled: await this.readBoolean(BOOLEANS.cookieEnabled),
            cookiePosition: await this.readSelect(COOKIE_POSITION),
            cookieBlockIdentifier: await this.readText(TEXTS.cookieBlockIdentifier),
            cookieDescription: await this.readTextArea(COOKIE_DESCRIPTION),
        };
    }

    async applySettings(settings: Partial<GdprSettings>): Promise<void> {
        await this.open();
        await this.setBooleans(BOOLEANS, settings, true);

        for (const key of Object.keys(TEXTS) as (keyof typeof TEXTS)[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.setText(TEXTS[key], value);
            }
        }

        if (settings.cookiePosition !== undefined) {
            await this.setSelect(COOKIE_POSITION, settings.cookiePosition);
        }

        if (settings.cookieDescription !== undefined) {
            await this.setTextArea(COOKIE_DESCRIPTION, settings.cookieDescription);
        }

        await this.setBooleans(BOOLEANS, settings, false);
        await this.save();
    }

    async expectSettings(settings: Partial<GdprSettings>): Promise<void> {
        await this.open();

        for (const key of Object.keys(BOOLEANS) as (keyof typeof BOOLEANS)[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.expectBoolean(BOOLEANS[key], value);
            }
        }

        for (const key of Object.keys(TEXTS) as (keyof typeof TEXTS)[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.expectText(TEXTS[key], value);
            }
        }

        if (settings.cookiePosition !== undefined) {
            await this.expectSelect(COOKIE_POSITION, settings.cookiePosition);
        }

        if (settings.cookieDescription !== undefined) {
            await this.expectTextArea(COOKIE_DESCRIPTION, settings.cookieDescription);
        }
    }
}
