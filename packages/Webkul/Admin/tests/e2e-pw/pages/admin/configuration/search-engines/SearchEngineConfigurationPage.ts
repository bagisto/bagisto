import { expect, type Locator, type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

const MIN_QUERY_LENGTH = "search_engines[elastic][settings][min_query_length]";
const MAX_QUERY_LENGTH = "search_engines[elastic][settings][max_query_length]";

export interface QueryLengthSettings {
    min: string;
    max: string;
}

export class SearchEngineConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/search_engines/elastic";
    }

    private get validationErrors(): Locator {
        return this.page.locator("p.text-red-600");
    }

    async readQueryLength(): Promise<QueryLengthSettings> {
        await this.open();

        return {
            min: await this.readText(MIN_QUERY_LENGTH),
            max: await this.readText(MAX_QUERY_LENGTH),
        };
    }

    async applyQueryLength(settings: QueryLengthSettings): Promise<void> {
        await this.open();
        await this.setText(MIN_QUERY_LENGTH, settings.min);
        await this.setText(MAX_QUERY_LENGTH, settings.max);
        await this.save();
    }

    async attemptQueryLength(settings: QueryLengthSettings): Promise<void> {
        await this.open();
        await this.setText(MIN_QUERY_LENGTH, settings.min);
        await this.setText(MAX_QUERY_LENGTH, settings.max);
        await this.saveButton.click();
    }

    async expectQueryLengthRefused(message: string): Promise<void> {
        await expect(this.validationErrors.filter({ hasText: message }).first()).toBeVisible();

        await expect(this.savedMessage).toHaveCount(0);
    }

    async expectQueryLength(settings: QueryLengthSettings): Promise<void> {
        await this.open();
        await this.expectText(MIN_QUERY_LENGTH, settings.min);
        await this.expectText(MAX_QUERY_LENGTH, settings.max);
    }
}
