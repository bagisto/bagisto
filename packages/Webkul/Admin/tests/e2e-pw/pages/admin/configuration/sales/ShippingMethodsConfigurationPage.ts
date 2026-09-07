import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface ShippingMethodSettings {
    freeShippingTitle: string;
    freeShippingDescription: string;
    flatRateTitle: string;
    flatRateDescription: string;
    flatRateDefaultRate: string;
    flatRateType: string;
}

const TEXTS = {
    freeShippingTitle: "sales[carriers][free][title]",
    flatRateTitle: "sales[carriers][flatrate][title]",
    flatRateDefaultRate: "sales[carriers][flatrate][default_rate]",
} as const;

const TEXT_AREAS = {
    freeShippingDescription: "sales[carriers][free][description]",
    flatRateDescription: "sales[carriers][flatrate][description]",
} as const;

const FLAT_RATE_TYPE = "sales[carriers][flatrate][type]";

export class ShippingMethodsConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/sales/carriers";
    }

    async readSettings(): Promise<ShippingMethodSettings> {
        await this.open();

        return {
            freeShippingTitle: await this.readText(TEXTS.freeShippingTitle),
            freeShippingDescription: await this.readTextArea(
                TEXT_AREAS.freeShippingDescription,
            ),
            flatRateTitle: await this.readText(TEXTS.flatRateTitle),
            flatRateDescription: await this.readTextArea(
                TEXT_AREAS.flatRateDescription,
            ),
            flatRateDefaultRate: await this.readText(TEXTS.flatRateDefaultRate),
            flatRateType: await this.readSelect(FLAT_RATE_TYPE),
        };
    }

    async applySettings(settings: Partial<ShippingMethodSettings>): Promise<void> {
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

        if (settings.flatRateType !== undefined) {
            await this.setSelect(FLAT_RATE_TYPE, settings.flatRateType);
        }

        await this.save();
    }

    async expectSettings(settings: Partial<ShippingMethodSettings>): Promise<void> {
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

        if (settings.flatRateType !== undefined) {
            await this.expectSelect(FLAT_RATE_TYPE, settings.flatRateType);
        }
    }
}
