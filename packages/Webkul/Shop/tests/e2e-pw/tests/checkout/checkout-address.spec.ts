import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { SimpleProductCheckout } from "../../pages/shop/checkout/product-types/SimpleProductCheckout";
import { uniqueStamp } from "../../utils/faker";

test.describe("checkout address", () => {
    let productName: string;
    let productListPage: ProductListPage;

    test.beforeEach(async ({ adminPage }) => {
        productListPage = new ProductListPage(adminPage);
        productName = `simple-${uniqueStamp()}`;

        await new ProductCreatePage(adminPage).createProduct({
            type: "simple",
            sku: `SKU-${uniqueStamp()}`,
            name: productName,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent([productName]);
    });

    test("should require the state to be chosen again when the country changes", async ({
        shopPage,
    }) => {
        const checkout = new SimpleProductCheckout(shopPage);

        await checkout.addSimpleProductToCart(productName);
        await checkout.openCheckout();

        const address = await checkout.fillGuestAddress();

        await checkout.expectGuestState(address.state);

        await checkout.changeGuestCountry("US");
        await checkout.proceedWithEditedAddress();

        await checkout.expectStateRequiredError();
        await checkout.expectShippingMethodsNotOffered();
    });

    test("should ask the customer to proceed again after editing the address", async ({
        shopPage,
    }) => {
        const checkout = new SimpleProductCheckout(shopPage);

        await checkout.addSimpleProductToCart(productName);
        await checkout.proceedAsGuest();

        await checkout.expectShippingMethodsOffered();

        await checkout.editGuestStreet(`${uniqueStamp()} South Street`);

        await checkout.expectAddressUpdatedNotice();

        await checkout.proceedWithEditedAddress();

        await checkout.expectShippingMethodsOffered();
        await checkout.expectAddressUpdatedNoticeCleared();
    });
});
