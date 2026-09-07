import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface AddressRequirementSettings {
    country: boolean;
    state: boolean;
    postcode: boolean;
}

const FIELDS = {
    country: "customer[address][requirements][country]",
    state: "customer[address][requirements][state]",
    postcode: "customer[address][requirements][postcode]",
} as const;

export class CustomerAddressPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/customer/address";
    }

    async readSettings(): Promise<AddressRequirementSettings> {
        await this.open();

        return {
            country: await this.readBoolean(FIELDS.country),
            state: await this.readBoolean(FIELDS.state),
            postcode: await this.readBoolean(FIELDS.postcode),
        };
    }

    async applySettings(
        settings: Partial<AddressRequirementSettings>,
    ): Promise<void> {
        await this.open();

        for (const key of Object.keys(FIELDS) as (keyof typeof FIELDS)[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.setBoolean(FIELDS[key], value);
            }
        }

        await this.save();
    }

    async expectSettings(
        settings: Partial<AddressRequirementSettings>,
    ): Promise<void> {
        await this.open();

        for (const key of Object.keys(FIELDS) as (keyof typeof FIELDS)[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.expectBoolean(FIELDS[key], value);
            }
        }
    }
}
