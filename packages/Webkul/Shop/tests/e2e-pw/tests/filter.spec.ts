import { test, expect } from "../setup";
import { FilterPage } from "../pages/shop/FilterPage";

test("should be able to filter A-Z products", async ({ shopPage }) => {
    const filterPage = new FilterPage(shopPage);

    await filterPage.gotoCategory("mens");
    await filterPage.openSortDropdown();
    await filterPage.selectSortOption("From A-Z");

    const productNames = await filterPage.getProductNames();
    const sortedNames = [...productNames].sort((a, b) => a.localeCompare(b));

    expect(productNames).toEqual(sortedNames);
});

test("should be able to filter Z-A products", async ({ shopPage }) => {
    const filterPage = new FilterPage(shopPage);

    await filterPage.gotoCategory("mens");
    await filterPage.openSortDropdown();
    await filterPage.selectSortOption("From Z-A");

    const productNames = await filterPage.getProductNames();
    const sortedNamesDesc = [...productNames].sort((a, b) =>
        b.localeCompare(a),
    );

    expect(productNames).toEqual(sortedNamesDesc);
});

test("should be able to Expensive First filter on products", async ({
    shopPage,
}) => {
    const filterPage = new FilterPage(shopPage);

    await filterPage.gotoCategory("mens");
    await filterPage.openSortDropdown();
    await filterPage.selectSortOption("Expensive First");

    const prices = await filterPage.getProductPrices();
    const sortedPrices = [...prices].sort((a, b) => b - a);

    expect(prices).toEqual(sortedPrices);
});

test("should be able to cheaper First filter on products", async ({
    shopPage,
}) => {
    const filterPage = new FilterPage(shopPage);

    await filterPage.gotoCategory("mens");
    await filterPage.openSortDropdown();
    await filterPage.selectSortOption("Cheapest First");

    const prices = await filterPage.getProductPrices();
    const sortedPrices = [...prices].sort((a, b) => a - b);

    expect(prices).toEqual(sortedPrices);
});
