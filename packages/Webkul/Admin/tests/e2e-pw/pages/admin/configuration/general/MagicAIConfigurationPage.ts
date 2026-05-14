import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

export class MagicAIConfigurationPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get saveButton() {
        return this.page.locator(
            'button[type="submit"].primary-button:visible',
        );
    }

    private get successNotification() {
        return this.page.getByText("Configuration saved successfully");
    }

    private get enabledToggle() {
        return this.page.locator(
            'label[for="magic_ai[general][settings][enabled]"]',
        );
    }

    private get openaiApiKeyInput() {
        return this.page.locator(
            'input[name="magic_ai[providers][openai][api_key]"]',
        );
    }

    private featureToggle(feature: string) {
        return this.page.locator(
            `label[for="magic_ai[admin_features][${feature}][enabled]"]`,
        );
    }

    private featureToggleInput(feature: string) {
        return this.page.locator(
            `input[type="checkbox"][name="magic_ai[admin_features][${feature}][enabled]"]`,
        );
    }

    private storefrontFeatureToggle(feature: string) {
        return this.page.locator(
            `label[for="magic_ai[storefront_features][${feature}][enabled]"]`,
        );
    }

    private storefrontFeatureInput(feature: string) {
        return this.page.locator(
            `input[type="checkbox"][name="magic_ai[storefront_features][${feature}][enabled]"]`,
        );
    }

    private reviewTranslationModelSelect() {
        return this.page.locator(
            'select[name="magic_ai[storefront_features][review_translation][model]"]',
        );
    }

    async openSettings(): Promise<void> {
        await this.visit("admin/configuration/magic_ai/general");
    }

    async openProviders(): Promise<void> {
        await this.visit("admin/configuration/magic_ai/providers");
    }

    async openAdminFeatures(): Promise<void> {
        await this.visit("admin/configuration/magic_ai/admin_features");
    }

    async openStorefrontFeatures(): Promise<void> {
        await this.visit("admin/configuration/magic_ai/storefront_features");
    }

    async enableOpenAISettings(): Promise<void> {
        const isChecked = await this.page
            .locator(
                'input[type="checkbox"][name="magic_ai[general][settings][enabled]"]',
            )
            .isChecked();

        if (!isChecked) {
            await this.enabledToggle.click();
        }
    }

    async fillOpenAIKey(key: string): Promise<void> {
        await this.openaiApiKeyInput.fill(key);
    }

    async enableAdminFeature(feature: string): Promise<void> {
        const toggleInput = this.featureToggleInput(feature);
        if (!(await toggleInput.isChecked())) {
            await this.featureToggle(feature).click();
        }
    }

    async isAdminFeatureEnabled(feature: string): Promise<boolean> {
        return this.featureToggleInput(feature).isChecked();
    }

    async enableStorefrontFeature(feature: string): Promise<void> {
        const toggleInput = this.storefrontFeatureInput(feature);
        if (!(await toggleInput.isChecked())) {
            await this.storefrontFeatureToggle(feature).click();
        }
    }

    async isStorefrontFeatureEnabled(feature: string): Promise<boolean> {
        return this.storefrontFeatureInput(feature).isChecked();
    }

    async selectReviewTranslationModel(model: string): Promise<void> {
        await this.reviewTranslationModelSelect().selectOption(model);
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }
}
