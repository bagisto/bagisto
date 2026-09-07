import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface OrderSettings {
    orderNumberPrefix: string;
    orderNumberLength: string;
    orderNumberSuffix: string;
    minimumOrderEnabled: boolean;
    minimumOrderAmount: string;
    reorderInAdmin: boolean;
    reorderInShop: boolean;
}

const FIELDS = {
    orderNumberPrefix: "sales[order_settings][order_number][order_number_prefix]",
    orderNumberLength: "sales[order_settings][order_number][order_number_length]",
    orderNumberSuffix: "sales[order_settings][order_number][order_number_suffix]",
    minimumOrderEnabled: "sales[order_settings][minimum_order][enable]",
    minimumOrderAmount:
        "sales[order_settings][minimum_order][minimum_order_amount]",
    reorderInAdmin: "sales[order_settings][reorder][admin]",
    reorderInShop: "sales[order_settings][reorder][shop]",
} as const;

export class OrderSettingsConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/sales/order_settings";
    }

    async readSettings(): Promise<OrderSettings> {
        await this.open();

        return {
            orderNumberPrefix: await this.readText(FIELDS.orderNumberPrefix),
            orderNumberLength: await this.readText(FIELDS.orderNumberLength),
            orderNumberSuffix: await this.readText(FIELDS.orderNumberSuffix),
            minimumOrderEnabled: await this.readBoolean(FIELDS.minimumOrderEnabled),
            minimumOrderAmount: await this.readText(FIELDS.minimumOrderAmount),
            reorderInAdmin: await this.readBoolean(FIELDS.reorderInAdmin),
            reorderInShop: await this.readBoolean(FIELDS.reorderInShop),
        };
    }

    async applySettings(settings: Partial<OrderSettings>): Promise<void> {
        await this.open();

        for (const key of [
            "minimumOrderEnabled",
            "reorderInAdmin",
            "reorderInShop",
        ] as const) {
            if (settings[key] !== undefined) {
                await this.setBoolean(FIELDS[key], settings[key]);
            }
        }

        for (const key of [
            "orderNumberPrefix",
            "orderNumberLength",
            "orderNumberSuffix",
            "minimumOrderAmount",
        ] as const) {
            if (settings[key] !== undefined) {
                await this.setText(FIELDS[key], settings[key]);
            }
        }

        await this.save();
    }

    async expectSettings(settings: Partial<OrderSettings>): Promise<void> {
        await this.open();

        for (const key of [
            "orderNumberPrefix",
            "orderNumberLength",
            "orderNumberSuffix",
            "minimumOrderAmount",
        ] as const) {
            if (settings[key] !== undefined) {
                await this.expectText(FIELDS[key], settings[key]);
            }
        }

        for (const key of [
            "minimumOrderEnabled",
            "reorderInAdmin",
            "reorderInShop",
        ] as const) {
            if (settings[key] !== undefined) {
                await this.expectBoolean(FIELDS[key], settings[key]);
            }
        }
    }
}
