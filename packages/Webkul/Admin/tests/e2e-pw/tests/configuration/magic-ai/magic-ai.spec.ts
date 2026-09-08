import { test } from "../../../setup";
import {
    MagicAiAdminFeaturesPage,
    type MagicAiAdminFeatureSettings,
} from "../../../pages/admin/configuration/magic-ai/MagicAiAdminFeaturesPage";
import {
    MagicAiProvidersPage,
    type MagicAiProviderSettings,
} from "../../../pages/admin/configuration/magic-ai/MagicAiProvidersPage";
import {
    MagicAiSettingsPage,
    type MagicAiSettings,
} from "../../../pages/admin/configuration/magic-ai/MagicAiSettingsPage";
import {
    MagicAiStorefrontFeaturesPage,
    type MagicAiStorefrontFeatureSettings,
} from "../../../pages/admin/configuration/magic-ai/MagicAiStorefrontFeaturesPage";
import { uniqueStamp } from "../../../utils/faker";

test.describe("magic ai configuration", () => {
    test.describe.configure({ timeout: 120000 });

    test.describe("general settings", () => {
        let settingsPage: MagicAiSettingsPage;
        let original: MagicAiSettings;

        test.beforeEach(async ({ adminPage }) => {
            settingsPage = new MagicAiSettingsPage(adminPage);
            original = await settingsPage.readSettings();
        });

        test.afterEach(async () => {
            await settingsPage.applySettings(original);
        });

        test("should toggle magic ai and keep the new state after reload", async () => {
            await settingsPage.applySettings({ enabled: !original.enabled });

            await settingsPage.expectSettings({ enabled: !original.enabled });
        });
    });

    test.describe("providers", () => {
        let providersPage: MagicAiProvidersPage;
        let original: MagicAiProviderSettings;

        test.beforeEach(async ({ adminPage }) => {
            providersPage = new MagicAiProvidersPage(adminPage);
            original = await providersPage.readSettings();
        });

        test.afterEach(async () => {
            await providersPage.applySettings(original);
        });

        test("should store the openai api key and keep it after reload", async () => {
            const openAiApiKey = `sk-e2e-${uniqueStamp()}`;

            await providersPage.applySettings({ openAiApiKey });

            await providersPage.expectSettings({ openAiApiKey });
        });
    });

    test.describe("admin features", () => {
        let featuresPage: MagicAiAdminFeaturesPage;
        let original: MagicAiAdminFeatureSettings;

        test.beforeEach(async ({ adminPage }) => {
            featuresPage = new MagicAiAdminFeaturesPage(adminPage);
            original = await featuresPage.readSettings();
        });

        test.afterEach(async () => {
            await featuresPage.applySettings(original);
        });

        test("should toggle text generation and keep the new state after reload", async () => {
            await featuresPage.applySettings({
                textGeneration: !original.textGeneration,
            });

            await featuresPage.expectSettings({
                textGeneration: !original.textGeneration,
                imageGeneration: original.imageGeneration,
            });
        });

        test("should toggle image generation and keep the new state after reload", async () => {
            await featuresPage.applySettings({
                imageGeneration: !original.imageGeneration,
            });

            await featuresPage.expectSettings({
                imageGeneration: !original.imageGeneration,
                textGeneration: original.textGeneration,
            });
        });
    });

    test.describe("storefront features", () => {
        let featuresPage: MagicAiStorefrontFeaturesPage;
        let original: MagicAiStorefrontFeatureSettings;

        test.beforeEach(async ({ adminPage }) => {
            featuresPage = new MagicAiStorefrontFeaturesPage(adminPage);
            original = await featuresPage.readSettings();
        });

        test.afterEach(async () => {
            await featuresPage.applySettings(original);
        });

        test("should enable review translation with a model and keep it after reload", async () => {
            await featuresPage.applySettings({
                reviewTranslation: true,
                reviewTranslationModel: "gemini-2.5-flash",
            });

            await featuresPage.expectSettings({
                reviewTranslation: true,
                reviewTranslationModel: "gemini-2.5-flash",
            });
        });

        test("should toggle the checkout message and keep the new state after reload", async () => {
            await featuresPage.applySettings({
                checkoutMessage: !original.checkoutMessage,
            });

            await featuresPage.expectSettings({
                checkoutMessage: !original.checkoutMessage,
                reviewTranslation: original.reviewTranslation,
            });
        });
    });
});
