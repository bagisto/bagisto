import { test, expect } from "../../../setup";

const RICH_SNIPPETS_URL = "admin/configuration/catalog/rich_snippets";
const SAVE_BUTTON = 'button[type="submit"].primary-button:visible';
const PRODUCT_TOGGLES = [
    'label[for="catalog[rich_snippets][products][enable]"]',
    'label[for="catalog[rich_snippets][products][show_sku]"]',
    'label[for="catalog[rich_snippets][products][show_weight]"]',
    'label[for="catalog[rich_snippets][products][show_categories]"]',
    'label[for="catalog[rich_snippets][products][show_images]"]',
    'label[for="catalog[rich_snippets][products][show_reviews]"]',
    'label[for="catalog[rich_snippets][products][show_ratings]"]',
    'label[for="catalog[rich_snippets][products][show_offers]"]',
];

test.describe("rich snippets configuration", () => {
    test("should update products settings including with sku, weight, categories, images, reviews, ratings, offers and etc.", async ({
        adminPage,
    }) => {
        await adminPage.goto(RICH_SNIPPETS_URL);

        for (const toggle of PRODUCT_TOGGLES) {
            await adminPage.click(toggle);
        }

        await adminPage.click(SAVE_BUTTON);

        await expect(
            adminPage.getByText("Configuration saved successfully").first(),
        ).toBeVisible();
    });
});
