import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../configuration/ConfigurationFormPage";

const OMNIBUS_FIELD = "catalog[products][omnibus][is_enabled]";

export class OmnibusAdminPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/catalog/products";
    }

    async readEnabled(): Promise<boolean> {
        await this.open();

        return this.readBoolean(OMNIBUS_FIELD);
    }

    async setEnabled(enabled: boolean): Promise<void> {
        await this.open();
        await this.setBoolean(OMNIBUS_FIELD, enabled);
        await this.save();
    }

    async expectEnabled(enabled: boolean): Promise<void> {
        await this.open();
        await this.expectBoolean(OMNIBUS_FIELD, enabled);
    }
}
