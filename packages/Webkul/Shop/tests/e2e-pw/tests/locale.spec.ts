import { test } from "../setup";
import { uniqueStamp } from "../utils/faker";
import { HeaderPage } from "../pages/shop/HeaderPage";
import {
    createCategoryNamedInTwoLocales,
    createLocale,
    deleteCategory,
    deleteLocale,
    offerLocaleOnDefaultChannel,
} from "../utils/locales";

test.describe("locale switching", () => {
    let localeId: string;
    let localeName: string;
    let categoryId: string;
    let defaultCategoryName: string;
    let translatedCategoryName: string;

    test.beforeEach(async ({ adminPage }) => {
        const stamp = uniqueStamp();
        const localeCode = `pw${stamp}`;

        localeId = "";
        categoryId = "";
        localeName = `Locale ${stamp}`;
        defaultCategoryName = `Category ${stamp}`;
        translatedCategoryName = `Kategorie ${stamp}`;

        localeId = await createLocale(adminPage, localeCode, localeName);

        await offerLocaleOnDefaultChannel(adminPage, localeId);

        categoryId = await createCategoryNamedInTwoLocales(
            adminPage,
            { name: defaultCategoryName, slug: `category-${stamp}` },
            localeCode,
            { name: translatedCategoryName, slug: `kategorie-${stamp}` },
        );
    });

    test.afterEach(async ({ adminPage }) => {
        try {
            if (categoryId) {
                await deleteCategory(adminPage, categoryId);
            }
        } finally {
            if (localeId) {
                await deleteLocale(adminPage, localeId);
            }
        }
    });

    test("should show the header categories in the locale switched to on a page other than the home page", async ({
        shopPage,
    }) => {
        const header = new HeaderPage(shopPage);

        await header.open("contact-us");
        await header.expectCategoryInMenu(defaultCategoryName);

        await header.switchLocale(localeName);

        await header.expectCategoryInMenu(translatedCategoryName);
        await header.expectCategoryNotInMenu(defaultCategoryName);
    });
});
