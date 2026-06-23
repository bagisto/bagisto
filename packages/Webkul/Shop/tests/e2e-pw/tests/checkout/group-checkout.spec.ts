import { test } from "../../setup";
import { ProductCreation } from "../../pages/admin/catalog/products/ProductCreatePage";
import { GroupProductCheckout } from "../../pages/shop/checkout/product-types/GroupProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";

test.describe("group product checkout flow", () => {
    test("should create simple product to add in group", async ({
        adminPage,
    }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "simple",
            sku: `SKU-${Date.now()}`,
            name: `Simple-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    test("should create another simple product to add in group", async ({
        adminPage,
    }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "simple",
            sku: `SKU-${Date.now()}`,
            name: `Simple-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    test("should create group product", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "grouped",
            sku: `SKU-${Date.now()}`,
            name: `group-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    test("should allow customer to complete checkout for group product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new GroupProductCheckout(shopPage);
        await checkout.checkoutWithDefaultShipping();
    });

    test("should allow guest to complete checkout for group product successfully", async ({
        shopPage,
    }) => {
        const checkout = new GroupProductCheckout(shopPage);
        await checkout.guestCheckout();
    });

    test("should allow customer to complete checkout for group product via flat rate shipping successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new GroupProductCheckout(shopPage);
        await checkout.checkoutWithFlatRateShipping();
    });

    test("should allow customer to complete checkout for group product via cash on delivery successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new GroupProductCheckout(shopPage);
        await checkout.checkoutWithCOD();
    });
});
