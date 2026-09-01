import { test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { SimpleProductCheckout } from "../pages/shop/checkout/product-types/SimpleProductCheckout";
import { loginAsCustomer, addAddress } from "../utils/customer";
import { RmaCreatePage } from "../pages/shop/RmaCreatePage";

test.describe("should create rma for order (rma rule enable)", () => {
    test.beforeEach(
        "should create simple product for checkout to create rma",
        async ({ adminPage }) => {
            const productCreation = new ProductCreatePage(adminPage);

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
        },
    );

    test("should allow customer to complete checkout and create rma", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const simpleProductCheckout = new SimpleProductCheckout(shopPage);
        await simpleProductCheckout.checkoutWithDefaultShipping();
        const rmaCreatePage = new RmaCreatePage(shopPage);
        await rmaCreatePage.rmaCreation();
    });

    test("should display validation message for submitting invalid rma request", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const simpleProductCheckout = new SimpleProductCheckout(shopPage);
        await simpleProductCheckout.checkoutWithDefaultShipping();

        const rmaCreatePage = new RmaCreatePage(shopPage);
        await rmaCreatePage.invalidRMARequest();
    });
});

test.describe("should create rma for order (rma rule disable)", () => {
    test.beforeEach(
        "should create simple product for checkout to create rma",
        async ({ adminPage }) => {
            const productCreation = new ProductCreatePage(adminPage);

            await productCreation.createProductWithoutRMARule({
                type: "simple",
                sku: `SKU-${Date.now()}`,
                name: `Simple-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 1,
                inventory: 100,
            });
        },
    );

    test("should allow customer to create rma for simple product whose allow rma rule is disable", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const simpleProductCheckout = new SimpleProductCheckout(shopPage);
        await simpleProductCheckout.checkoutWithDefaultShipping();
        
        const rmaCreatePage = new RmaCreatePage(shopPage);
        await rmaCreatePage.rmaCreation();
    });
});
