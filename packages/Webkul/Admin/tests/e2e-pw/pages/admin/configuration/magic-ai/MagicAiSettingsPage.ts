import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface MagicAiSettings {
    enabled: boolean;
}

const ENABLED = "magic_ai[general][settings][enabled]";

export class MagicAiSettingsPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/magic_ai/general";
    }

    async readSettings(): Promise<MagicAiSettings> {
        await this.open();

        return { enabled: await this.readBoolean(ENABLED) };
    }

    async applySettings(settings: Partial<MagicAiSettings>): Promise<void> {
        await this.open();

        if (settings.enabled !== undefined) {
            await this.setBoolean(ENABLED, settings.enabled);
        }

        await this.save();
    }

    async expectSettings(settings: Partial<MagicAiSettings>): Promise<void> {
        await this.open();

        if (settings.enabled !== undefined) {
            await this.expectBoolean(ENABLED, settings.enabled);
        }
    }
}
