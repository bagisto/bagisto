import { test } from "../../setup";
import { ProductCreation } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";

test.describe("booking product checkout flow ", () => {

    test.describe("Default Booking", () => {
        test.describe("One Booking For Many Days", () => {
            test("should create default booking product with one booking for many days with cancellation", async ({ adminPage }) => {
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
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout("12");
                await checkout.verifyduration(customer, id, false)
            });

            test("should prevent cancellation when toggle is off", async ({ adminPage, }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "default",
                    defaultBookingType: "one",
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `default-no-cancel-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 1,
                    inventory: 100,
                });
            });

            test("should prevent cancellation when toggle is off in customer end", async ({ shopPage }) => {

                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('12', undefined, false);
                await checkout.verifyCancellationNotAllowed(id)

            });

            test.describe("Many Bookings For One Day", () => {
                test("should create default booking product with many bookings for one day with allow cancellation", async ({ adminPage }) => {
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
                    await checkout.verifyduration(customer, id, true)

                });

                test("should create default booking product with many bookings for one day with not allowed cancellation", async ({ adminPage, }) => {
                    const productCreation = new ProductCreation(adminPage);
                    await productCreation.createProduct({
                        type: "booking",
                        bookingType: "default",
                        defaultBookingType: "many",
                        allowCancellation: false,
                        sku: `SKU-${Date.now()}`,
                        name: `default-${Date.now()}`,
                        shortDescription: "Short desc",
                        description: "Full desc",
                        price: 199,
                        weight: 1,
                        inventory: 100,
                    });
                });

                test("should prevent cancellation when toggle is off in customer end", async ({ shopPage }) => {

                    const customer = await loginAsCustomer(shopPage);
                    await addAddress(shopPage);
                    const checkout = new BookingProductCheckout(shopPage);
                    const id = await checkout.checkout('10', undefined, false);
                    await checkout.verifyCancellationNotAllowed(id)

                });
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
                await checkout.verifyduration(customer, id, true)

            });

            test("should create appointment booking with available every week and same slot for all days without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "appointment",
                    sameSlotAllDays: true,
                    availableEveryWeek: true,
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `appointment-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });

            test("should allow customer to complete checkout appointment booking with all the test without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('10', undefined, false);
                await checkout.verifyCancellationNotAllowed(id)

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
                await checkout.verifyduration(customer, id, true)

            });

            test("should create appointment booking with available every week and not same slot for all days without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "appointment",
                    sameSlotAllDays: false,
                    availableEveryWeek: true,
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `appointment-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });

            test("should allow customer to complete checkout appointment booking with all the test without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('10', undefined, false);
                await checkout.verifyCancellationNotAllowed(id)

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
                await checkout.verifyduration(customer, id, true)

            });

            test("should create appointment booking with not available every week and same slot for all days without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "appointment",
                    sameSlotAllDays: true,
                    availableEveryWeek: false,
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `appointment-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });

            test("should allow customer to complete checkout appointment booking with not available every week and same slot for all days for customer checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('10', undefined, false);
                await checkout.verifyCancellationNotAllowed(id)

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
                await checkout.verifyduration(customer, id, true)

            });

            test("should create appointment booking with not available every week and not same slot for all days without cancelation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "appointment",
                    sameSlotAllDays: false,
                    availableEveryWeek: false,
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `appointment-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });

            test("should allow customer to complete checkout with not available every week and not same slot for all days for customer checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('10', undefined, false);
                await checkout.verifyCancellationNotAllowed(id)

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
                await checkout.verifyduration(customer, id, false)

            });

            test("Should create event booking product for one ticket without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "event",
                    sameSlotAllDays: true,
                    availableEveryWeek: false,
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `event-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            })

            test("should allow customer to complete checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('12', 1, false);
                await checkout.verifyCancellationNotAllowed(id)

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
                await checkout.verifyduration(customer, id, false)

            });

            test("Should create event booking product with multiple tickets without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "event",
                    sameSlotAllDays: true,
                    availableEveryWeek: false,
                    allowCancellation: false,
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

            test("should allow customer to complete checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout('12', 2, false);
                await checkout.verifyCancellationNotAllowed(id)

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

            });

            test("should create rental booking product for daily basis with available every week without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: true,
                    allowCancellation: false,
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

            test("should allow customer to complete checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalCheckoutDaily(false);
                await checkout.verifyCancellationNotAllowed(id)

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

            test("should allow customer to complete checkout", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalCheckoutDaily();

            });

            test("should create rental booking product for daily basis with not available every week without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: false,
                    allowCancellation: false,
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

            test("should allow customer to complete checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalCheckoutDaily(false);
                await checkout.verifyCancellationNotAllowed(id)

            });

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
                const id = await checkout.rentalCheckoutHourly('10');

            });

            test("should create rental booking product for hourly basis with available every week with same slot all days without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: true,
                    allowCancellation: false,
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
            test("should allow customer to complete checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalCheckoutHourly('10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
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
                const id = await checkout.rentalCheckoutHourly('10');

            });

            test("should create rental booking product for hourly basis with available every week and not same slot all days without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: true,
                    allowCancellation: false,
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

            test("should allow customer to complete checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalCheckoutHourly('10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
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
                const id = await checkout.rentalCheckoutHourly('10');

            });

            test("should create rental booking product for hourly basis without available every week and same slot all days without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: false,
                    allowCancellation: false,
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

            test("should allow customer to complete checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalCheckoutHourly('10', false);
                await checkout.verifyCancellationNotAllowed(id)

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
                const id = await checkout.rentalCheckoutHourly('10');

            });

            test("should create rental booking product for hourly basis without available every week and not same slot all days without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: false,
                    allowCancellation: false,
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

            test("should allow customer to complete checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalCheckoutHourly('10', false);
                await checkout.verifyCancellationNotAllowed(id)

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

            test("should allow customer to complete checkout for hourly", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalcheckoutHourlyDaily(true, '10');

            });
            test("should allow customer to complete checkout for daily", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                await checkout.rentalcheckoutHourlyDaily(false);

            });

            test("should create rental booking product for hourly and daily basis with available every week and same slot all days without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: true,
                    allowCancellation: false,
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

            test("should allow customer to complete checkout for hourly without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalcheckoutHourlyDaily(true, '10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
            test("should allow customer to complete checkout for daily without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalcheckoutHourlyDaily(false, undefined, false);
                await checkout.verifyCancellationNotAllowed(id)

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

            test("should allow customer to complete checkout for hourly", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalcheckoutHourlyDaily(true, '10');

            });
            test("should allow customer to complete checkout for daily", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                await checkout.rentalcheckoutHourlyDaily(false);

            });

            test("should create rental booking product for daily and hourly basis with available every week not same slot all days without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: true,
                    allowCancellation: false,
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

            test("should allow customer to complete checkout for hourly without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalcheckoutHourlyDaily(true, '10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
            test("should allow customer to complete checkout for daily without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalcheckoutHourlyDaily(false, undefined, false);
                await checkout.verifyCancellationNotAllowed(id)

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
            test("should allow customer to complete checkout for hourly", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalcheckoutHourlyDaily(true, '10');

            });
            test("should allow customer to complete checkout for daily", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                await checkout.rentalcheckoutHourlyDaily(false);

            });

            test("should create rental booking product for daily and hourly basis with not available every week and same slot all days without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: false,
                    allowCancellation: false,
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
            test("should allow customer to complete checkout for hourly without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalcheckoutHourlyDaily(true, '10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
            test("should allow customer to complete checkout for daily without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalcheckoutHourlyDaily(false, undefined, false);
                await checkout.verifyCancellationNotAllowed(id)

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
            test("should allow customer to complete checkout for hourly", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalcheckoutHourlyDaily(true, '10');

            });
            test("should allow customer to complete checkout for daily", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                await checkout.rentalcheckoutHourlyDaily(false);

            });

            test("should create rental booking product for daily and hourly basis with not available every week and not same slot all days without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "rental",
                    availableEveryWeek: false,
                    allowCancellation: false,
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
            test("should allow customer to complete checkout for hourly without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalcheckoutHourlyDaily(true, '10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
            test("should allow customer to complete checkout for daily without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.rentalcheckoutHourlyDaily(false, undefined, false);
                await checkout.verifyCancellationNotAllowed(id)

            });
        });
    });

    test.describe('Table Booking product', () => {
        test.describe("per_guest | every week | same slot all days", () => {
            test("should create table booking product", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_guest",
                    availableEveryWeek: true,
                    sameSlotAllDays: true,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-guest-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout for hourly", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(false, '10');
                await checkout.verifyduration(customer, id, true)

            });
            test("should create table booking product without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_guest",
                    availableEveryWeek: true,
                    allowCancellation: false,
                    sameSlotAllDays: true,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-guest-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout for hourly without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(false, '10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
        });

        test.describe("per_guest | every week | different slots", () => {
            test("should create table booking product", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_guest",
                    availableEveryWeek: true,
                    sameSlotAllDays: false,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-guest-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout ", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(false, '10');
                await checkout.verifyduration(customer, id, true)

            });

            test("should create table booking product without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_guest",
                    availableEveryWeek: true,
                    allowCancellation: false,
                    sameSlotAllDays: false,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-guest-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout for customer without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(false, '10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
        });

        test.describe("per_guest | date range | same slot all days", () => {
            test("should create table booking product", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_guest",
                    availableEveryWeek: false,
                    sameSlotAllDays: true,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-guest-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(false, '10');
                await checkout.verifyduration(customer, id, true)

            });

            test("should create table booking product without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_guest",
                    availableEveryWeek: false,
                    sameSlotAllDays: true,
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-guest-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(false, '10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
        });

        test.describe("per_guest | date range | different slots", () => {
            test("should create table booking product", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_guest",
                    availableEveryWeek: false,
                    sameSlotAllDays: false,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-guest-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(false, '10');
                await checkout.verifyduration(customer, id, true)

            });
            test("should create table booking product without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_guest",
                    availableEveryWeek: false,
                    sameSlotAllDays: false,
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-guest-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(false, '10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
        });
        test.describe("per_table | every week | same slot all days", () => {
            test("should create table booking product", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_table",
                    availableEveryWeek: true,
                    sameSlotAllDays: true,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-table-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(true, '10');
                await checkout.verifyduration(customer, id, true)

            });

            test("should create table booking product without cancellation ", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_table",
                    availableEveryWeek: true,
                    sameSlotAllDays: true,
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-table-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(true, '10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
        });

        test.describe("per_table | every week | different slots", () => {
            test("should create table booking product", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_table",
                    availableEveryWeek: true,
                    sameSlotAllDays: false,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-table-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout for hourly", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(true, '10');
                await checkout.verifyduration(customer, id, true)

            });

            test("should create table booking product without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_table",
                    availableEveryWeek: true,
                    sameSlotAllDays: false,
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-table-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout for hourly without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(true, '10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
        });

        test.describe("per_table | date range | same slot all days", () => {
            test("should create table booking product", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_table",
                    availableEveryWeek: false,
                    sameSlotAllDays: true,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-table-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout for hourly", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(true, '10');
                await checkout.verifyduration(customer, id, true)

            });

            test("should create table booking product without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_table",
                    availableEveryWeek: false,
                    sameSlotAllDays: true,
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-table-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout for hourly without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(true, '10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
        });

        test.describe("per_table | date range | different slots", () => {
            test("should create table booking product", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_table",
                    availableEveryWeek: false,
                    sameSlotAllDays: false,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-table-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout for hourly", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(true, '10');
                await checkout.verifyduration(customer, id, true)

            });

            test("should create table booking product without cancellation", async ({ adminPage }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "table",
                    tableType: "per_table",
                    availableEveryWeek: false,
                    sameSlotAllDays: false,
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `table-per-table-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 10,
                    inventory: 100,
                });
            });
            test("should allow customer to complete checkout for hourly without cancellation", async ({ shopPage }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.table_checkout(true, '10', false);
                await checkout.verifyCancellationNotAllowed(id)

            });
        });

    })

});
