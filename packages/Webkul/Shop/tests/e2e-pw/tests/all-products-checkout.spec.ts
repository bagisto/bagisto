import { test } from "../setup";
import { ProductCreation } from "../pages/product";
import { ProductCheckout } from "../pages/product-checkout";
import { ConfigProductCheckout } from "../pages/config-checkout";
import { DownloadableProductCheckout } from "../pages/downloadable-checkout";
import { VirtualProductCheckout } from "../pages/virtual-checkout";
import { GroupProductCheckout } from "../pages/group-checkout";
import { BookingProductCheckout } from "../pages/booking-checkout";
import { loginAsCustomer, addAddress } from "../utils/customer";
import { MultipleCheckout } from "../pages/multiple-checkout";

/**
 * ===============================
 * SIMPLE PRODUCT CHECKOUT FLOW
 * ===============================
 * This test suite covers:
 * 1. Creating a simple product from the admin panel
 * 2. Completing checkout for the simple product as a customer
 */
test.describe("Simple Product Checkout Flow", () => {
    /**
     * Admin creates a Simple Product with basic details
     */
    test("Create Simple Product", async ({ adminPage }) => {
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

    /**
     * Customer logs in and successfully completes checkout
     * for the previously created Simple Product
     */
    test("Customer Can Complete Checkout Simple Product Successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.customerCheckout();
    });
});

/**
 * ===============================
 * GROUP PRODUCT CHECKOUT FLOW
 * ===============================
 * This test suite covers:
 * 1. Creating a simple product to be associated with a group product
 * 2. Creating a grouped product
 * 3. Completing checkout for the grouped product as a customer
 */
test.describe("Group Product Checkout Flow", () => {
    /**
     * Admin creates a Simple Product
     * (used as a child product in the grouped product)
     */
    test("Create Simple for group Product", async ({ adminPage }) => {
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

    /**
     * Admin creates a Grouped Product
     */
    test("Create Group Product", async ({ adminPage }) => {
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

    /**
     * Customer logs in and completes checkout
     * for the Grouped Product
     */
    test("Customer Can Complete Checkout Group Product Successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const productCheckout = new GroupProductCheckout(shopPage);
        await productCheckout.customerCheckout();
    });
});

/**
 * ===============================
 * CONFIGURABLE PRODUCT CHECKOUT FLOW
 * ===============================
 * This test suite covers:
 * 1. Creating a configurable product with variations
 * 2. Completing checkout for the configurable product as a customer
 */
test.describe("Config Product Checkout Flow", () => {
    /**
     * Admin creates a Configurable Product
     */
    test("Create Configurable Product", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createConfigProduct({
            type: "configurable",
            sku: `SKU-${Date.now()}`,
            name: `Config-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    /**
     * Customer selects configuration options
     * and completes checkout successfully
     */
    test("Customer Can Complete Checkout Config Product Successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const configProductCheckout = new ConfigProductCheckout(shopPage);
        await configProductCheckout.customerCheckout();
    });
});

/**
 * ===============================
 * DOWNLOADABLE PRODUCT CHECKOUT FLOW
 * ===============================
 * This test suite covers:
 * 1. Creating a downloadable product
 * 2. Completing checkout for the downloadable product
 */
test.describe("Downloadable Product Checkout Flow", () => {
    /**
     * Admin creates a Downloadable Product
     */
    test("Create Downloadable Product", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "downloadable",
            sku: `SKU-${Date.now()}`,
            name: `downloadable-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    /**
     * Customer completes checkout
     * and gains access to downloadable content
     */
    test("Customer Can Complete Checkout For Downloadbale Product Successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const downloadableProductCheckout = new DownloadableProductCheckout(
            shopPage
        );
        await downloadableProductCheckout.customerCheckout();
    });
});

/**
 * ===============================
 * VIRTUAL PRODUCT CHECKOUT FLOW
 * ===============================
 * This test suite covers:
 * 1. Creating a virtual product (no shipping required)
 * 2. Completing checkout for the virtual product
 */
test.describe("Virtual Product Checkout Flow", () => {
    /**
     * Admin creates a Virtual Product
     */
    test("Create virtual Product", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "virtual",
            sku: `SKU-${Date.now()}`,
            name: `virtual-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    /**
     * Customer completes checkout
     * without shipping information
     */
    test("Customer Can Complete Checkout For Virtual Product Successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const virtualProductCheckout = new VirtualProductCheckout(shopPage);
        await virtualProductCheckout.customerCheckout();
    });
});

/**
 * ===============================
 * BOOKING PRODUCT CHECKOUT FLOW
 * ===============================
 * This test suite covers:
 * 1. Creating a booking product
 * 2. Selecting booking details and completing checkout
 */
test.describe("Booking Product Checkout Flow", () => {
    /**
     * Admin creates a Booking Product
     */
    test("Create Booking Product", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "booking",
            sku: `SKU-${Date.now()}`,
            name: `booking-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    /**
     * Customer selects booking slot
     * and completes checkout successfully
     */
    test("Customer Can Complete Checkout For Booking Product Successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const bookingProductCheckout = new BookingProductCheckout(shopPage);
        await bookingProductCheckout.customerCheckout();
    });
});

/**
 * ===============================
 * Multiple PRODUCT CHECKOUT FLOW
 * ===============================
 * This test suite covers:
 * 1. Checkout of multiple products
 */
test.describe("Multiple Product Checkout Flow", () => {
    /**
     * completes checkout of multiple products successfully
     * Simple and Configure
     */
    test("Customer Can Complete Checkout For Simple and Configure Product Successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutSimpleAndConfig();
    });
    /**
     * completes checkout of multiple products successfully
     * Virtual and Configure
     */
    test("Customer Can Complete Checkout For Virtual and Configure Product Successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutVirtualAndGroup();
    });
    /**
     * completes checkout of multiple products successfully
     * Virtual and Group
     */
    test("Customer Can Complete Checkout For Virtual and Group Product Successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutVirtualAndGroup();
    });
    /**
     * completes checkout of multiple products successfully
     * Simple, Config Virtual and Group
     */
    test("Customer Can Complete Checkout For Simple, Config Virtual and Group Product Successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutSimpleConfigVirtulGroup();
    });
    /**
     * completes checkout of multiple products successfully
     * Simple and Downloadable
     */
    test("Customer Can Complete Checkout For Simple and Downloadable Product Successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutSimpleAndDownloadable();
    });
});
