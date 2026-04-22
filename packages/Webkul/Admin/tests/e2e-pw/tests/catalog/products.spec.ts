import { test } from "../../setup";
import { expect } from "@playwright/test";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductDeletePage } from "../../pages/admin/catalog/products/ProductDeletePage";
import { ProductEditPage } from "../../pages/admin/catalog/products/ProductEditPage";
import {
    generateDescription,
    generateHostname,
    generateLocation,
    generateName,
    generateRandomDateTime,
    generateSKU,
} from "../../utils/faker";

test.describe("simple product management", () => {
    test("should update the product group price after delete", async ({
        adminPage,
    }) => {
        await new ProductEditPage(
            adminPage,
        ).updateProductGroupPriceAfterDelete();

        await expect(
            adminPage.getByText("For 022 Qty at fixed price of"),
        ).toBeVisible();
        await expect(
            adminPage.getByText("For 020 Qty at discount of"),
        ).not.toBeVisible();
        await expect(
            adminPage.getByText("For 015 Qty at fixed price of"),
        ).toBeVisible();
    });

    test("should create a simple product", async ({ adminPage }) => {
        const product = {
            name: `simple-${generateName()}`,
            sku: generateSKU(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
            weight: "25",
            inventory: "5000",
        };

        await new ProductCreatePage(adminPage).createSimpleProduct(product);

        await expect(
            adminPage
                .locator("p.break-all.text-base")
                .filter({ hasText: new RegExp(`^${product.name}$`) }),
        ).toBeVisible();
    });

    test("should edit a simple product", async ({ adminPage }) => {
        await new ProductEditPage(adminPage).editSimpleProduct();

        await expect(adminPage.locator("#app")).toContainText(
            /Product updated successfully/i,
        );
    });

    test("should mass update the products", async ({ adminPage }) => {
        await new ProductEditPage(adminPage).massUpdateProducts();

        await expect(
            adminPage.getByText("Selected Products Updated Successfully"),
        ).toBeVisible();
    });

    test("should mass delete the products", async ({ adminPage }) => {
        await new ProductDeletePage(adminPage).massDeleteProducts();

        await expect(
            adminPage.getByText(/deleted successfully/i).first(),
        ).toBeVisible();
    });
});

test.describe("configurable product management", () => {
    test("should create a configurable product", async ({ adminPage }) => {
        const product = {
            name: generateName(),
            sku: generateSKU(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
        };

        await new ProductCreatePage(adminPage).createConfigurableProduct(
            product,
        );

        await expect(
            adminPage
                .getByRole("paragraph")
                .filter({ hasText: new RegExp(`^${product.name}$`) }),
        ).toBeVisible();
    });

    test("should edit a configurable product", async ({ adminPage }) => {
        await new ProductEditPage(adminPage).editConfigurableProduct();

        await expect(adminPage.locator("#app")).toContainText(
            /Product updated successfully/i,
        );
    });

    test("should mass update the products", async ({ adminPage }) => {
        await new ProductEditPage(adminPage).massUpdateProducts();

        await expect(adminPage.locator("#app")).toContainText(
            /Product updated successfully/i,
        );
    });

    test("should mass delete the products", async ({ adminPage }) => {
        await new ProductDeletePage(adminPage).massDeleteProducts();

        await expect(adminPage.locator("#app")).toContainText(
            /Product deleted successfully/i,
        );
    });
});

test.describe("grouped product management", () => {
    test("should create a grouped product", async ({ adminPage }) => {
        const product = {
            name: generateName(),
            productNumber: generateSKU(),
            shortDescription: "test",
            description: "test",
        };

        await new ProductCreatePage(adminPage).createGroupedProduct(product);

        await expect(
            adminPage
                .getByRole("paragraph")
                .filter({ hasText: new RegExp(`^${product.name}$`) }),
        ).toBeVisible();
    });

    test("should edit a grouped product", async ({ adminPage }) => {
        await new ProductEditPage(adminPage).editGroupedProduct();

        await expect(adminPage.locator("#app")).toContainText(
            /Product updated successfully/i,
        );
    });

    test("should mass update the products", async ({ adminPage }) => {
        await new ProductEditPage(adminPage).massUpdateProducts();

        await expect(adminPage.locator("#app")).toContainText(
            /Product updated successfully/i,
        );
    });

    test("should mass delete the products", async ({ adminPage }) => {
        await new ProductDeletePage(adminPage).massDeleteProducts();

        await expect(adminPage.locator("#app")).toContainText(
            /Product deleted successfully/i,
        );
    });
});

test.describe("virtual product management", () => {
    test("should create a virtual product", async ({ adminPage }) => {
        const product = {
            name: generateName(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
        };

        await new ProductCreatePage(adminPage).createVirtualProduct(product);

        await expect(
            adminPage
                .getByRole("paragraph")
                .filter({ hasText: new RegExp(`^${product.name}$`) }),
        ).toBeVisible();
    });

    test("should edit a virtual product", async ({ adminPage }) => {
        await new ProductEditPage(adminPage).editVirtualProduct();

        await expect(adminPage.locator("#app")).toContainText(
            /Product updated successfully/i,
        );
    });

    test("should mass update the products", async ({ adminPage }) => {
        await new ProductEditPage(adminPage).massUpdateProducts();

        await expect(adminPage.locator("#app")).toContainText(
            /Product updated successfully/i,
        );
    });

    test("should mass delete the products", async ({ adminPage }) => {
        await new ProductDeletePage(adminPage).massDeleteProducts();

        await expect(adminPage.locator("#app")).toContainText(
            /Product deleted successfully/i,
        );
    });
});

test.describe("downloadable product management", () => {
    test("should create a downloadable product", async ({ adminPage }) => {
        const product = {
            name: generateName(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
        };

        await new ProductCreatePage(adminPage).createDownloadableProduct(
            product,
        );

        await expect(
            adminPage
                .getByRole("paragraph")
                .filter({ hasText: new RegExp(`^${product.name}$`) }),
        ).toBeVisible();
    });

    test("should edit a downloadable product", async ({ adminPage }) => {
        await new ProductEditPage(adminPage).editDownloadableProduct();

        await expect(adminPage.locator("#app")).toContainText(
            /Product updated successfully/i,
        );
    });

    test("should mass update the products", async ({ adminPage }) => {
        await new ProductEditPage(adminPage).massUpdateProducts();

        await expect(adminPage.locator("#app")).toContainText(
            /Product updated successfully/i,
        );
    });

    test("should mass delete the products", async ({ adminPage }) => {
        await new ProductDeletePage(adminPage).massDeleteProducts();

        await expect(adminPage.locator("#app")).toContainText(
            /Product deleted successfully/i,
        );
    });
});

test.describe("booking product management", () => {
    test.describe("booking product for default booking type", () => {
        test("should create default product with one booking for many days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createDefaultBookingProductWithOneBookingForManyDays();
        });

        test("should create default product with many booking for one day", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createDefaultBookingProductWithManyBookingForOneDay();
        });
    });

    test.describe("booking product for appointment booking type", () => {
        test("should create appointment booking product that are not available every week with same slot for all days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createAppointmentBookingProductNotAvailableEveryWeekWithSameSlotForAllDays();
        });

        test("should create appointment booking product that are not available every week with no same slot for all days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createAppointmentBookingProductNotAvailableEveryWeekWithNoSameSlotForAllDays();
        });

        test("should create appointment booking product that are available every week with no same slot for all days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createAppointmentBookingProductAvailableEveryWeekWithNoSameSlotForAllDays();
        });

        test("should create appointment booking product that are available every week with same slot for all days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createAppointmentBookingProductAvailableEveryWeekWithSameSlotForAllDays();
        });
    });

    test.describe("booking product for event booking type", () => {
        test("should create event booking product ", async ({ adminPage }) => {
            await new ProductCreatePage(adminPage).createEventBookingProduct();
        });
    });

    test.describe("booking product for rental booking type", () => {
        test("should create rental booking product for daily basis with not available for every week", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createRentalBookingProductDailyBasisNotAvailableEveryWeek();
        });

        test("should create rental booking product for daily basis with available for every week", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createRentalBookingProductDailyBasisAvailableEveryWeek();
        });

        test("should create rental booking product for hourly basis available for same slot for All days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createRentalBookingProductHourlyBasisSameSlotAllDays();
        });

        test("should create rental booking product for hourly basis available for not same slot for All days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createRentalBookingProductHourlyBasisNotSameSlotAllDays();
        });

        test("should create rental booking product for Both(hourly or day) basis available for same slot for All days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createRentalBookingProductBothBasisSameSlotAllDays();
        });

        test("should create rental booking product for Both(hourly or day) basis not available for same slot for All days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createRentalBookingProductBothBasisNotSameSlotAllDays();
        });
    });

    test.describe("booking product for table booking type", () => {
        test("should create Table booking product and charge per guest with same slot for all days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createTableBookingProductChargePerGuestSameSlotAllDays();
        });

        test("should create Table booking product and charge per guest with not same slot for all days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createTableBookingProductChargePerGuestNotSameSlotAllDays();
        });

        test("should create Table booking product and charge per table with same slot for all days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createTableBookingProductChargePerTableSameSlotAllDays();
        });

        test("should create Table booking product and charge per table with not same slot for all days", async ({
            adminPage,
        }) => {
            await new ProductCreatePage(
                adminPage,
            ).createTableBookingProductChargePerTableNotSameSlotAllDays();
        });
    });
});
