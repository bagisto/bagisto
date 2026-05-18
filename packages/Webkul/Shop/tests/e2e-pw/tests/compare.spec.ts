import { test, expect } from "../setup";
import { ComparePage } from "../pages/shop/ComparePage";

test("should add product to compare page", async ({ shopPage }) => {
    const comparePage = new ComparePage(shopPage);

    await comparePage.gotoHome();
    await comparePage.addProductToCompare(0);
    await comparePage.addProductToCompare(1);
    await comparePage.addProductToCompare(2);

    await comparePage.expectAddedSuccessfully();
});

test("should remove product from the compare page", async ({ shopPage }) => {
    const comparePage = new ComparePage(shopPage);

    await comparePage.gotoHome();
    await comparePage.addProductToCompare(0);
    await comparePage.addProductToCompare(1);
    await comparePage.addProductToCompare(2);
    await comparePage.openCompare();
    await comparePage.removeFirstProductFromCompare();
});

test("should remove all products from the compare page", async ({
    shopPage,
}) => {
    const comparePage = new ComparePage(shopPage);

    await comparePage.gotoHome();
    await comparePage.addProductToCompare(0);
    await comparePage.addProductToCompare(1);
    await comparePage.addProductToCompare(2);
    await comparePage.openCompare();
    await comparePage.deleteAllProductsFromCompare();

    await comparePage.expectAllItemsRemoved();
});
