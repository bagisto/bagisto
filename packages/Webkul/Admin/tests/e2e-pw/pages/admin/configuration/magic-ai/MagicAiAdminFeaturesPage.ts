import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface MagicAiAdminFeatureSettings {
    textGeneration: boolean;
    imageGeneration: boolean;
}

const FIELDS = {
    textGeneration: "magic_ai[admin_features][text_generation][enabled]",
    imageGeneration: "magic_ai[admin_features][image_generation][enabled]",
} as const;

type FeatureKey = keyof typeof FIELDS;

export class MagicAiAdminFeaturesPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/magic_ai/admin_features";
    }

    async readSettings(): Promise<MagicAiAdminFeatureSettings> {
        await this.open();

        return {
            textGeneration: await this.readBoolean(FIELDS.textGeneration),
            imageGeneration: await this.readBoolean(FIELDS.imageGeneration),
        };
    }

    async applySettings(
        settings: Partial<MagicAiAdminFeatureSettings>,
    ): Promise<void> {
        await this.open();

        for (const key of Object.keys(FIELDS) as FeatureKey[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.setBoolean(FIELDS[key], value);
            }
        }

        await this.save();
    }

    async expectSettings(
        settings: Partial<MagicAiAdminFeatureSettings>,
    ): Promise<void> {
        await this.open();

        for (const key of Object.keys(FIELDS) as FeatureKey[]) {
            const value = settings[key];

            if (value !== undefined) {
                await this.expectBoolean(FIELDS[key], value);
            }
        }
    }
}
