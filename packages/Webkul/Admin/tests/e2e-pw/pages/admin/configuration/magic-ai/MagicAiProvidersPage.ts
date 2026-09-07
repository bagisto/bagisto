import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface MagicAiProviderSettings {
    openAiApiKey: string;
}

const OPENAI_API_KEY = "magic_ai[providers][openai][api_key]";

export class MagicAiProvidersPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/magic_ai/providers";
    }

    async readSettings(): Promise<MagicAiProviderSettings> {
        await this.open();

        return { openAiApiKey: await this.readText(OPENAI_API_KEY) };
    }

    async applySettings(settings: Partial<MagicAiProviderSettings>): Promise<void> {
        await this.open();

        if (settings.openAiApiKey !== undefined) {
            await this.setText(OPENAI_API_KEY, settings.openAiApiKey);
        }

        await this.save();
    }

    async expectSettings(settings: Partial<MagicAiProviderSettings>): Promise<void> {
        await this.open();

        if (settings.openAiApiKey !== undefined) {
            await this.expectText(OPENAI_API_KEY, settings.openAiApiKey);
        }
    }
}
