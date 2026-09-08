import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface ShippingOriginSettings {
    country: string;
    state: string;
    city: string;
    address: string;
    zipcode: string;
    storeName: string;
    vatNumber: string;
    contact: string;
    bankDetails: string;
}

const SELECTS = {
    country: "sales[shipping][origin][country]",
    state: "sales[shipping][origin][state]",
} as const;

const TEXTS = {
    city: "sales[shipping][origin][city]",
    address: "sales[shipping][origin][address]",
    zipcode: "sales[shipping][origin][zipcode]",
    storeName: "sales[shipping][origin][store_name]",
    vatNumber: "sales[shipping][origin][vat_number]",
    contact: "sales[shipping][origin][contact]",
} as const;

const BANK_DETAILS = "sales[shipping][origin][bank_details]";

const REQUIRED_TEXTS = new Set<string>(["city", "address", "zipcode"]);

export class ShippingSettingsConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/sales/shipping";
    }

    async readSettings(): Promise<ShippingOriginSettings> {
        await this.open();

        return {
            country: await this.readSelect(SELECTS.country),
            state: await this.readSelect(SELECTS.state),
            city: await this.readText(TEXTS.city),
            address: await this.readText(TEXTS.address),
            zipcode: await this.readText(TEXTS.zipcode),
            storeName: await this.readText(TEXTS.storeName),
            vatNumber: await this.readText(TEXTS.vatNumber),
            contact: await this.readText(TEXTS.contact),
            bankDetails: await this.readTextArea(BANK_DETAILS),
        };
    }

    async applySettings(settings: Partial<ShippingOriginSettings>): Promise<void> {
        await this.open();

        if (settings.country !== undefined) {
            await this.setSelect(SELECTS.country, settings.country);
        }

        if (settings.state !== undefined) {
            await this.setSelect(SELECTS.state, settings.state);
        }

        for (const key of Object.keys(TEXTS) as (keyof typeof TEXTS)[]) {
            const value = settings[key];

            if (value === undefined) {
                continue;
            }

            if (value === "" && REQUIRED_TEXTS.has(key)) {
                continue;
            }

            await this.setText(TEXTS[key], value);
        }

        if (settings.bankDetails !== undefined) {
            await this.setTextArea(BANK_DETAILS, settings.bankDetails);
        }

        await this.save();
    }

    async expectSettings(settings: Partial<ShippingOriginSettings>): Promise<void> {
        await this.open();

        if (settings.country !== undefined) {
            await this.expectSelect(SELECTS.country, settings.country);
        }

        if (settings.state !== undefined) {
            await this.expectSelect(SELECTS.state, settings.state);
        }

        for (const key of Object.keys(TEXTS) as (keyof typeof TEXTS)[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.expectText(TEXTS[key], value);
            }
        }

        if (settings.bankDetails !== undefined) {
            await this.expectTextArea(BANK_DETAILS, settings.bankDetails);
        }
    }
}
