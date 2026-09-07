import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface EmailSettings {
    senderName: string;
    senderEmail: string;
    adminName: string;
    adminEmail: string;
    contactName: string;
    contactEmail: string;
}

const FIELDS = {
    senderName: "emails[configure][email_settings][sender_name]",
    senderEmail: "emails[configure][email_settings][sender_email]",
    adminName: "emails[configure][email_settings][admin_name]",
    adminEmail: "emails[configure][email_settings][admin_email]",
    contactName: "emails[configure][email_settings][contact_name]",
    contactEmail: "emails[configure][email_settings][contact_email]",
} as const;

export class EmailConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/emails/configure";
    }

    async readSettings(): Promise<EmailSettings> {
        await this.open();

        return {
            senderName: await this.readText(FIELDS.senderName),
            senderEmail: await this.readText(FIELDS.senderEmail),
            adminName: await this.readText(FIELDS.adminName),
            adminEmail: await this.readText(FIELDS.adminEmail),
            contactName: await this.readText(FIELDS.contactName),
            contactEmail: await this.readText(FIELDS.contactEmail),
        };
    }

    async applySettings(settings: Partial<EmailSettings>): Promise<void> {
        await this.open();

        for (const key of Object.keys(FIELDS) as (keyof EmailSettings)[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.setText(FIELDS[key], value);
            }
        }

        await this.save();
    }

    async expectSettings(settings: Partial<EmailSettings>): Promise<void> {
        await this.open();

        for (const key of Object.keys(FIELDS) as (keyof EmailSettings)[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.expectText(FIELDS[key], value);
            }
        }
    }
}
