import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface MagicAiStorefrontFeatureSettings {
    reviewTranslation: boolean;
    reviewTranslationModel: string;
    checkoutMessage: boolean;
}

const FIELDS = {
    reviewTranslation: "magic_ai[storefront_features][review_translation][enabled]",
    reviewTranslationModel: "magic_ai[storefront_features][review_translation][model]",
    checkoutMessage: "magic_ai[storefront_features][checkout_message][enabled]",
} as const;

export class MagicAiStorefrontFeaturesPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/magic_ai/storefront_features";
    }

    async readSettings(): Promise<MagicAiStorefrontFeatureSettings> {
        await this.open();

        return {
            reviewTranslation: await this.readBoolean(FIELDS.reviewTranslation),
            reviewTranslationModel: await this.readSelect(FIELDS.reviewTranslationModel),
            checkoutMessage: await this.readBoolean(FIELDS.checkoutMessage),
        };
    }

    async applySettings(
        settings: Partial<MagicAiStorefrontFeatureSettings>,
    ): Promise<void> {
        await this.open();

        if (settings.reviewTranslation !== undefined) {
            await this.setBoolean(FIELDS.reviewTranslation, settings.reviewTranslation);
        }

        if (settings.reviewTranslationModel !== undefined) {
            await this.setSelect(
                FIELDS.reviewTranslationModel,
                settings.reviewTranslationModel,
            );
        }

        if (settings.checkoutMessage !== undefined) {
            await this.setBoolean(FIELDS.checkoutMessage, settings.checkoutMessage);
        }

        await this.save();
    }

    async expectSettings(
        settings: Partial<MagicAiStorefrontFeatureSettings>,
    ): Promise<void> {
        await this.open();

        if (settings.reviewTranslation !== undefined) {
            await this.expectBoolean(
                FIELDS.reviewTranslation,
                settings.reviewTranslation,
            );
        }

        if (settings.reviewTranslationModel !== undefined) {
            await this.expectSelect(
                FIELDS.reviewTranslationModel,
                settings.reviewTranslationModel,
            );
        }

        if (settings.checkoutMessage !== undefined) {
            await this.expectBoolean(FIELDS.checkoutMessage, settings.checkoutMessage);
        }
    }
}
