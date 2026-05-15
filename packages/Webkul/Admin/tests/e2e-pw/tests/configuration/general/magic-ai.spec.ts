import { test, expect } from "../../../setup";
import { generateDescription } from "../../../utils/faker";
import { MagicAIConfigurationPage } from "../../../pages/admin/configuration/general/MagicAIConfigurationPage";

test.describe("magic ai configuration", () => {
    test("should update the openai credential", async ({ adminPage }) => {
        const page = new MagicAIConfigurationPage(adminPage);

        await page.openSettings();
        await page.enableOpenAISettings();
        await page.saveAndVerify();

        await page.openProviders();
        await page.fillOpenAIKey(generateDescription(20));
        await page.saveAndVerify();
    });

    test("should manage content using ai", async ({ adminPage }) => {
        const page = new MagicAIConfigurationPage(adminPage);

        await page.openAdminFeatures();
        await page.enableAdminFeature("text_generation");
        await page.saveAndVerify();

        await page.openAdminFeatures();
        await expect(await page.isAdminFeatureEnabled("text_generation")).toBe(
            true,
        );
    });

    test("should enable image generation using ai", async ({ adminPage }) => {
        const page = new MagicAIConfigurationPage(adminPage);

        await page.openAdminFeatures();
        await page.enableAdminFeature("image_generation");
        await page.saveAndVerify();

        await page.openAdminFeatures();
        await expect(await page.isAdminFeatureEnabled("image_generation")).toBe(
            true,
        );
    });

    test("should enable review translation using ai", async ({ adminPage }) => {
        const page = new MagicAIConfigurationPage(adminPage);

        await page.openStorefrontFeatures();
        await page.enableStorefrontFeature("review_translation");
        await page.selectReviewTranslationModel("gemini-2.5-flash");
        await page.saveAndVerify();

        await page.openStorefrontFeatures();
        await expect(
            await page.isStorefrontFeatureEnabled("review_translation"),
        ).toBe(true);
    });

    test("should craft a personalized checkout message for customers using AI", async ({
        adminPage,
    }) => {
        const page = new MagicAIConfigurationPage(adminPage);

        await page.openStorefrontFeatures();
        await page.enableStorefrontFeature("checkout_message");
        await page.saveAndVerify();

        await page.openStorefrontFeatures();
        await expect(
            await page.isStorefrontFeatureEnabled("checkout_message"),
        ).toBe(true);
    });
});
