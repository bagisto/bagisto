import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface InventorySettings {
    backOrders: boolean;
    outOfStockThreshold: string;
}

const FIELDS = {
    backOrders: "catalog[inventory][stock_options][back_orders]",
    outOfStockThreshold:
        "catalog[inventory][stock_options][out_of_stock_threshold]",
} as const;

export class InventoryConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/catalog/inventory";
    }

    async readSettings(): Promise<InventorySettings> {
        await this.open();

        return {
            backOrders: await this.readBoolean(FIELDS.backOrders),
            outOfStockThreshold: await this.readText(FIELDS.outOfStockThreshold),
        };
    }

    async applySettings(settings: Partial<InventorySettings>): Promise<void> {
        await this.open();

        if (settings.backOrders !== undefined) {
            await this.setBoolean(FIELDS.backOrders, settings.backOrders);
        }

        if (settings.outOfStockThreshold !== undefined) {
            await this.setText(
                FIELDS.outOfStockThreshold,
                settings.outOfStockThreshold,
            );
        }

        await this.save();
    }

    async expectSettings(settings: Partial<InventorySettings>): Promise<void> {
        await this.open();

        if (settings.backOrders !== undefined) {
            await this.expectBoolean(FIELDS.backOrders, settings.backOrders);
        }

        if (settings.outOfStockThreshold !== undefined) {
            await this.expectText(
                FIELDS.outOfStockThreshold,
                settings.outOfStockThreshold,
            );
        }
    }
}
