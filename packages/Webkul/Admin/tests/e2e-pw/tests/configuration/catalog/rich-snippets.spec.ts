import { test, expect } from "../../../setup";

test.describe("rich snippets configuration", () => {
    test("should update products settings including with sku, weight, categories, images, reviews, ratings, offers and etc.", async ({
        adminPage,
    }) => {
        /**
         * Navigate to the configuration page.
         */
        await adminPage.goto("admin/configuration/catalog/rich_snippets");

        await adminPage.click(
            'label[for="catalog[rich_snippets][products][enable]"]'
        );
        await adminPage.click(
            'label[for="catalog[rich_snippets][products][show_sku]"]'
        );
        await adminPage.click(
            'label[for="catalog[rich_snippets][products][show_weight]"]'
        );
        await adminPage.click(
            'label[for="catalog[rich_snippets][products][show_categories]"]'
        );
        await adminPage.click(
            'label[for="catalog[rich_snippets][products][show_images]"]'
        );
        await adminPage.click(
            'label[for="catalog[rich_snippets][products][show_reviews]"]'
        );
        await adminPage.click(
            'label[for="catalog[rich_snippets][products][show_ratings]"]'
        );
        await adminPage.click(
            'label[for="catalog[rich_snippets][products][show_offers]"]'
        );
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();
    });
});
