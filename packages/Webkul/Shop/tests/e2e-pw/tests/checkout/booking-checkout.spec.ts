import { test } from "../../setup";
import { ProductCreation } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";

test.describe("booking product checkout flow @smoke", () => {

    test.describe("Default Booking", () => {
        test.describe("One Booking For Many Days", () => {
            test("should create default booking product with one booking for many days", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "default",
                    defaultBookingType: "one",
                    sku: `SKU-${Date.now()}`,
                    name: `default-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 1,
                    inventory: 100,
                });
            });

            test("should allow customer to complete checkout for one booking for many days", async ({ shopPage }) => {
                await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                await checkout.checkout("12");
            });
        });

        test.describe("Many Bookings For One Day", () => {
            test("should create default booking product with many bookings for one day", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "default",
                    defaultBookingType: "many",
                    sku: `SKU-${Date.now()}`,
                    name: `default-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 1,
                    inventory: 100,
                });
            });

            test("should allow customer to complete checkout for many bookings for one day", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('10');

            });
        });
    });

    test.describe("Appointment Booking", () => {

        test.describe('available every week and same slot for all days', () => {
            test("should create appointment booking with available every week and same slot for all days", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "appointment",
                    sameSlotAllDays: true,
                    availableEveryWeek: true,
                    sku: `SKU-${Date.now()}`,
                    name: `appointment-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });

            test("should allow customer to complete checkout appointment booking with all the test ", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('10');

            });
        })

        test.describe('available every week but not same slot for all days', () => {
            test("should create appointment booking with available every week and not same slot for all days", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "appointment",
                    sameSlotAllDays: false,
                    availableEveryWeek: true,
                    sku: `SKU-${Date.now()}`,
                    name: `appointment-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });

            test("should allow customer to complete checkout appointment booking with all the test ", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('10');

            });
        })

        test.describe('not available every week but same slot for all days', () => {
            test("should create appointment booking with not available every week and same slot for all days", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "appointment",
                    sameSlotAllDays: true,
                    availableEveryWeek: false,
                    sku: `SKU-${Date.now()}`,
                    name: `appointment-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });

            test("should allow customer to complete checkout appointment booking with not available every week and same slot for all days for customer checkout", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('10');

            });
        })

        test.describe('not available every week and not same slot for all days', () => {
            test("should create appointment booking with not available every week and not same slot for all days", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "appointment",
                    sameSlotAllDays: false,
                    availableEveryWeek: false,
                    sku: `SKU-${Date.now()}`,
                    name: `appointment-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });

            test("should allow customer to complete checkout with not available every week and not same slot for all days for customer checkout", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('10');

            });
        })
    });

    test.describe("Event Booking product", () => {

        test.describe("Event Booking product for one ticket ", () => {

            test("Should create event booking product for one ticket", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "event",
                    sameSlotAllDays: true,
                    availableEveryWeek: false,
                    sku: `SKU-${Date.now()}`,
                    name: `event-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            })

            test("should allow customer to complete checkout", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('12', 1)

            });

        });

        test.describe("Event Booking product for multiple tickets ", () => {

            test("Should create event booking product with multiple tickets", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "event",
                    sameSlotAllDays: true,
                    availableEveryWeek: false,
                    sku: `SKU-${Date.now()}`,
                    name: `event-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                    numberOfTickets: 2,
                });
            })

            test("should allow customer to complete checkout", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('12', 2);

            });

        });
    });

    test.describe("Rental booking product", () => {
        test.describe("Rental booking product for daily basis with available every week ", () => {

            test("should create rental booking product for daily basis with available every week", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: true,
                    sku: `SKU-${Date.now()}`,
                    name: `rental-daily-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                    rentalType: "daily",

                });

            })
            test("should allow customer to complete checkout", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalCheckoutDaily();
                console.log("order id:", id)

            });
        })
        test.describe("Rental booking product for daily basis not available every week ", () => {

            test("should create rental booking product for daily basis with not available every week", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: false,
                    sku: `SKU-${Date.now()}`,
                    name: `rental-daily-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                    rentalType: "daily",

                });


            })

        })

        test.describe("Rental booking product for hourly basis with available every week with same slot all days ", () => {
            test("should create rental booking product for hourly basis with available every week with same slot all days", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: true,
                    sku: `SKU-${Date.now()}`,
                    name: `rental-hourly-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                    rentalType: "hourly",
                    sameSlotAllDays: true
                });

            })
            test("should allow customer to complete checkout ", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                await checkout.rentalCheckoutHourly('10');

            });
        })
    })


    test.describe("Rental booking product for hourly basis with available every week and not same slot all days", () => {
        test("should create rental booking product for hourly basis with available every week and not same slot all days", async ({ adminPage }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                sku: `SKU-${Date.now()}`,
                name: `rental-hourly-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "hourly",
                sameSlotAllDays: false
            });
        });

        test("should allow customer to complete checkout ", async ({ shopPage }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalCheckoutHourly('10');

        });
    })

})

test.describe("Rental booking product for hourly basis without available every week and same slot all days", () => {
    test("should create rental booking product for hourly basis without available every week and same slot all days", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);
        await productCreation.createProduct({
            type: "booking",
            bookingType: "rental",
            availableEveryWeek: false,
            sku: `SKU-${Date.now()}`,
            name: `rental-hourly-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 10,
            inventory: 100,
            rentalType: "hourly",
            sameSlotAllDays: true
        });
    });

    test("should allow customer to complete checkout ", async ({ shopPage }) => {
        const customer = await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new BookingProductCheckout(shopPage);
        await checkout.rentalCheckoutHourly('10');

    });

})

test.describe("Rental booking product for hourly basis without available every week and not same slot all days", () => {
    test("should create rental booking product for hourly basis without available every week and not same slot all days", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);
        await productCreation.createProduct({
            type: "booking",
            bookingType: "rental",
            availableEveryWeek: false,
            sku: `SKU-${Date.now()}`,
            name: `rental-hourly-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 10,
            inventory: 100,
            rentalType: "hourly",
            sameSlotAllDays: false
        });
    });

    test("should allow customer to complete checkout ", async ({ shopPage }) => {
        const customer = await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new BookingProductCheckout(shopPage);
        await checkout.rentalCheckoutHourly('10');

    });

})

test.describe("Rental booking product for hourly and daily basis with available every week and same slot all days", () => {
    test("should create rental booking product for hourly and daily basis with available every week and same slot all days", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);
        await productCreation.createProduct({
            type: "booking",
            bookingType: "rental",
            availableEveryWeek: true,
            sku: `SKU-${Date.now()}`,
            name: `rental-both-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 10,
            inventory: 100,
            rentalType: "both",
            sameSlotAllDays: true
        });
    });
});
test.describe("Rental booking product for hourly and daily basis with available every week and not same slot all days", () => {
    test("should create rental booking product for daily and hourly basis with available every week not same slot all days", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);
        await productCreation.createProduct({
            type: "booking",
            bookingType: "rental",
            availableEveryWeek: true,
            sku: `SKU-${Date.now()}`,
            name: `rental-both-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 10,
            inventory: 100,
            rentalType: "both",
            sameSlotAllDays: false
        });
    });
});

test.describe("Rental booking product for hourly and daily basis with not available every week and same slot all days", () => {
    test("should create rental booking product for daily and hourly basis with not available every week and same slot all days", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);
        await productCreation.createProduct({
            type: "booking",
            bookingType: "rental",
            availableEveryWeek: false,
            sku: `SKU-${Date.now()}`,
            name: `rental-both-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 10,
            inventory: 100,
            rentalType: "both",
            sameSlotAllDays: true
        });
    });
});

test.describe("Rental booking product for hourly and daily basis with not available every week and not same slot all days", () => {
    test("should create rental booking product for daily and hourly basis with not available every week and not same slot all days", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);
        await productCreation.createProduct({
            type: "booking",
            bookingType: "rental",
            availableEveryWeek: false,
            sku: `SKU-${Date.now()}`,
            name: `rental-both-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 10,
            inventory: 100,
            rentalType: "both",
            sameSlotAllDays: false
        });
    });
});
