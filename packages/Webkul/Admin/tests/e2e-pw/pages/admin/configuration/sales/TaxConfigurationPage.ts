import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface TaxSettings {
    shippingTaxCategory: string;
    productTaxCategory: string;
    basedOn: string;
    productPrices: string;
    shippingPrices: string;
    applyTaxOn: string;
    defaultCountry: string;
    defaultState: string;
    defaultPostcode: string;
    cartDisplayPrices: string;
    cartDisplaySubtotal: string;
    cartDisplayShipping: string;
    salesDisplayPrices: string;
    salesDisplaySubtotal: string;
    salesDisplayShipping: string;
}

const SELECTS = {
    shippingTaxCategory: "sales[taxes][categories][shipping]",
    productTaxCategory: "sales[taxes][categories][product]",
    basedOn: "sales[taxes][calculation][based_on]",
    productPrices: "sales[taxes][calculation][product_prices]",
    shippingPrices: "sales[taxes][calculation][shipping_prices]",
    applyTaxOn: "sales[taxes][calculation][apply_tax_on]",
    defaultCountry: "sales[taxes][default_destination_calculation][country]",
    defaultState: "sales[taxes][default_destination_calculation][state]",
    cartDisplayPrices: "sales[taxes][shopping_cart][display_prices]",
    cartDisplaySubtotal: "sales[taxes][shopping_cart][display_subtotal]",
    cartDisplayShipping: "sales[taxes][shopping_cart][display_shipping_amount]",
    salesDisplayPrices: "sales[taxes][sales][display_prices]",
    salesDisplaySubtotal: "sales[taxes][sales][display_subtotal]",
    salesDisplayShipping: "sales[taxes][sales][display_shipping_amount]",
} as const;

const DEFAULT_POSTCODE =
    "sales[taxes][default_destination_calculation][post_code]";

type SelectKey = keyof typeof SELECTS;

export class TaxConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/sales/taxes";
    }

    async readSettings(): Promise<TaxSettings> {
        await this.open();

        const settings = {} as TaxSettings;

        for (const key of Object.keys(SELECTS) as SelectKey[]) {
            settings[key] = await this.readSelect(SELECTS[key]);
        }

        settings.defaultPostcode = await this.readText(DEFAULT_POSTCODE);

        return settings;
    }

    async applySettings(settings: Partial<TaxSettings>): Promise<void> {
        await this.open();

        for (const key of Object.keys(SELECTS) as SelectKey[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.setSelect(SELECTS[key], value);
            }
        }

        if (settings.defaultPostcode !== undefined) {
            await this.setText(DEFAULT_POSTCODE, settings.defaultPostcode);
        }

        await this.save();
    }

    async expectSettings(settings: Partial<TaxSettings>): Promise<void> {
        await this.open();

        for (const key of Object.keys(SELECTS) as SelectKey[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.expectSelect(SELECTS[key], value);
            }
        }

        if (settings.defaultPostcode !== undefined) {
            await this.expectText(DEFAULT_POSTCODE, settings.defaultPostcode);
        }
    }
}
