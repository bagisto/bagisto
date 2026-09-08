import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface CaptchaSettings {
    enabled: boolean;
    projectId: string;
    apiKey: string;
    siteKey: string;
}

const FIELDS = {
    enabled: "customer[captcha][credentials][status]",
    projectId: "customer[captcha][credentials][project_id]",
    apiKey: "customer[captcha][credentials][api_key]",
    siteKey: "customer[captcha][credentials][site_key]",
} as const;

export class CaptchaConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/customer/captcha";
    }

    async readSettings(): Promise<CaptchaSettings> {
        await this.open();

        return {
            enabled: await this.readBoolean(FIELDS.enabled),
            projectId: await this.readText(FIELDS.projectId),
            apiKey: await this.readText(FIELDS.apiKey),
            siteKey: await this.readText(FIELDS.siteKey),
        };
    }

    async applySettings(settings: Partial<CaptchaSettings>): Promise<void> {
        await this.open();

        if (settings.enabled === true) {
            await this.setBoolean(FIELDS.enabled, true);
        }

        if (settings.projectId !== undefined) {
            await this.setText(FIELDS.projectId, settings.projectId);
        }

        if (settings.apiKey !== undefined) {
            await this.setText(FIELDS.apiKey, settings.apiKey);
        }

        if (settings.siteKey !== undefined) {
            await this.setText(FIELDS.siteKey, settings.siteKey);
        }

        if (settings.enabled === false) {
            await this.setBoolean(FIELDS.enabled, false);
        }

        await this.save();
    }

    async expectSettings(settings: Partial<CaptchaSettings>): Promise<void> {
        await this.open();

        if (settings.enabled !== undefined) {
            await this.expectBoolean(FIELDS.enabled, settings.enabled);
        }

        if (settings.projectId !== undefined) {
            await this.expectText(FIELDS.projectId, settings.projectId);
        }

        if (settings.apiKey !== undefined) {
            await this.expectText(FIELDS.apiKey, settings.apiKey);
        }

        if (settings.siteKey !== undefined) {
            await this.expectText(FIELDS.siteKey, settings.siteKey);
        }
    }
}
