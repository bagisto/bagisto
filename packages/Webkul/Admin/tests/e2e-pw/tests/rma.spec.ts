import { test } from "../setup";
import { ProductCreation } from "../pages/product";
import { ProductCheckout } from "../pages/checkout-flow";
import { loginAsCustomer, addAddress } from "../utils/customer";
import { RMACreation } from "../pages/rma";

test.describe("Should perform action for RMA by admin", () => {
    test.setTimeout(240000);
    test.beforeEach(
        "should create simple product for checkout to create rma",
        async ({ adminPage }) => {
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
        },
    );

    test("Should allow checkout and RMA creation so the admin can accept it", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.customerCheckout();

        const rmaCreation = new RMACreation(shopPage);
        await rmaCreation.rmaCreation();
    });

    test("should allow admin to accept rma", async ({ adminPage }) => {
        const rmaCreation = new RMACreation(adminPage);
        await rmaCreation.adminAcceptRMA();
    });

    test("Should allow checkout and RMA creation so the admin can decline it", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.customerCheckout();

        const rmaCreation = new RMACreation(shopPage);
        await rmaCreation.rmaCreation();
    });

    test("should allow admin to declined rma", async ({ adminPage }) => {
        const rmaCreation = new RMACreation(adminPage);
        await rmaCreation.adminDeclinedRMA();
    });
});
