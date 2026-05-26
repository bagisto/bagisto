import { test, expect } from "../setup";
import { SearchPage } from "../pages/shop/SearchPage";

test("should search by query", async ({ shopPage }) => {
    const searchPage = new SearchPage(shopPage);

    await searchPage.gotoHome();
    await searchPage.search("arct");
    await searchPage.expectResults("arct");
});
