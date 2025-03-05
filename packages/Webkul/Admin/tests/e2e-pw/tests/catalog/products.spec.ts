import { test, expect } from "../../setup";;
import {
    generateSKU,
    generateName,
    generateDescription,
    getImageFile,
    generateLocation,
    generateRandomDateTime,
} from "../../utils/faker";

async function createSimpleProduct(adminPage) {
    /**
     * Main product data which we will use to create the product.
     */
    const product = {
        name: generateName(),
        sku: generateSKU(),
        productNumber: generateSKU(),
        shortDescription: generateDescription(),
        description: generateDescription(),
        price: "199",
        weight: "25",
    };

    /**
     * Reaching to the create product page.
     */
    await adminPage.goto("admin/catalog/products");
    await adminPage.waitForSelector(
        'button.primary-button:has-text("Create Product")'
    );
    await adminPage.getByRole("button", { name: "Create Product" }).click();

    /**
     * Opening create product form in modal.
     */
    await adminPage.locator('select[name="type"]').selectOption("simple");
    await adminPage
        .locator('select[name="attribute_family_id"]')
        .selectOption("1");
    await adminPage.locator('input[name="sku"]').fill(generateSKU());
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    /**
     * After creating the product, the page is redirected to the edit product page, where
     * all the details need to be filled in.
     */
    await adminPage.waitForSelector(
        'button.primary-button:has-text("Save Product")'
    );

    /**
     * Waiting for the main form to be visible.
     */
    await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

    /**
     * General Section.
     */
    await adminPage.locator("#product_number").fill(product.productNumber);
    await adminPage.locator("#name").fill(product.name);

    /**
     * Description Section.
     */
    await adminPage.fillInTinymce(
        "#short_description_ifr",
        product.shortDescription
    );
    await adminPage.fillInTinymce("#description_ifr", product.description);

    /**
     * Meta Description Section.
     */
    await adminPage.locator("#meta_title").fill(product.name);
    await adminPage.locator("#meta_keywords").fill(product.name);
    await adminPage.locator("#meta_description").fill(product.shortDescription);

    /**
     * Image Section.
     */
    // Will add images later.

    /**
     * Price Section.
     */
    await adminPage.locator("#price").fill(product.price);

    /**
     * Shipping Section.
     */
    await adminPage.locator("#weight").fill(product.weight);

    /**
     * Settings Section.
     */
    await adminPage
        .locator(".mt-3\\.5 > div:nth-child(2) > div:nth-child(3) > div")
        .first()
        .click();
    await adminPage.locator(".relative > label").first().click();
    await adminPage.locator("div:nth-child(3) > .relative > label").click();
    await adminPage.locator("div:nth-child(4) > .relative > label").click();
    await adminPage.locator("div:nth-child(5) > .relative > label").click();
    await adminPage.locator("div:nth-child(6) > .relative > label").click();

    /**
     * Inventories Section.
     */
    await adminPage.locator('input[name="inventories\\[1\\]"]').click();
    await adminPage.locator('input[name="inventories\\[1\\]"]').fill("5000");

    /**
     * Categories Section.
     */
    await adminPage
        .locator("label")
        .filter({ hasText: "Men" })
        .locator("span")
        .click();

    /**
     * Saving the product.
     */
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    await expect(
        adminPage.getByText("Product updated successfully")
    ).toBeVisible();
}

test.describe("simple product management", () => {
    test("should create a simple product", async ({ adminPage }) => {
        await createSimpleProduct(adminPage);
    });

    test("should edit a simple product", async ({ adminPage }) => {
        /**
         * Reaching to the edit product page.
         */
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")'
        );
        await adminPage.waitForSelector("span.cursor-pointer.icon-sort-right", {
            state: "visible",
        });
        const iconRight = await adminPage.$$(
            "span.cursor-pointer.icon-sort-right"
        );
        await iconRight[0].click();

        /**
         * Waiting for the main form to be visible.
         */
        await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

        // Content will be added here. Currently just checking the general save button.

        /**
         * Saving the product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        await expect(
            adminPage.getByText("Product updated successfully")
        ).toBeVisible();
    });

    test("should mass update the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")'
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 }
        );
        await selectActionButton.click();

        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector(
            'a:has-text("Active"), a:has-text("Disable")',
            { state: "visible", timeout: 1000 }
        );
        await adminPage.click('a:has-text("Active")');

        await adminPage.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")'
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            adminPage.getByText("Selected Products Updated Successfully")
        ).toBeVisible();
    });

    test("should mass delete the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
            { state: "visible" }
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 }
        );
        await selectActionButton.click();

        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

        await adminPage.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });

        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")'
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            adminPage.getByText("Selected Products Deleted Successfully")
        ).toBeVisible();
    });
});

async function createBookingProduct(adminPage) {
    /**
     * Main product data which we will use to create the product.
     */
    const product = {
        name: generateName(),
        sku: generateSKU(),
        productNumber: generateSKU(),
        shortDescription: generateDescription(),
        description: generateDescription(),
        price: "199",
        weight: "25",
        date: generateRandomDateTime(),
        location: generateLocation(),
    };

    const availableToDate = new Date(product.date);
    const availableFromDate = new Date(availableToDate.getTime() + 60 * 60000);
    const formattedAvailableFromDate = availableFromDate.toISOString().slice(0, 19).replace('T', ' ');

    /**
     * Reaching to the create product page.
     */
    await adminPage.goto("admin/catalog/products");
    await adminPage.waitForSelector('button.primary-button:has-text("Create Product")');
    await adminPage.getByRole("button", { name: "Create Product" }).click();

    /**
     * Opening create product form in modal.
     */
    await adminPage.locator('select[name="type"]').selectOption("booking");
    await adminPage
        .locator('select[name="attribute_family_id"]')
        .selectOption("1");
    await adminPage.locator('input[name="sku"]').fill(generateSKU());
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    /**
     * After creating the product, the page is redirected to the edit product page, where
     * all the details need to be filled in.
     */
    await adminPage.waitForSelector('button.primary-button:has-text("Save Product")');

    /**
     * Waiting for the main form to be visible.
     */
    await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

    /**
     * General Section.
     */
    await adminPage.locator("#product_number").fill(product.productNumber);
    await adminPage.locator("#name").fill(product.name);

    /**
     * Description Section.
     */
    await adminPage.fillInTinymce("#short_description_ifr", product.shortDescription);
    await adminPage.fillInTinymce("#description_ifr", product.description);

    /**
     * Meta Description Section.
     */
    await adminPage.locator("#meta_title").fill(product.name);
    await adminPage.locator("#meta_keywords").fill(product.name);
    await adminPage.locator("#meta_description").fill(product.shortDescription);

    /**
     * Image Section.
     */
    // Will add images later.

    /**
     * Price Section.
     */
    await adminPage.locator("#price").fill(product.price);

    /**
     * Settings Section.
     */
    await adminPage.locator('.relative > label').first().click();
    await adminPage.locator('div:nth-child(3) > .relative > label').click();
    await adminPage.locator('div:nth-child(4) > .relative > label').click();
    await adminPage.locator('div:nth-child(5) > .relative > label').click();

    /**
     * Default Booking Section.
     */
    //await adminPage.locator('select[name="booking[type]"]').selectOption("Default Booking");
    await adminPage.locator('input[name="booking[location]"]').fill(product.location);

    //await adminPage.locator('input[name="booking[qty]"]').fill(product.weight);

    /**
     * Selecting Availablity Time frame.
     */
    await adminPage.locator('input[name="booking[available_from]"]').fill(formattedAvailableFromDate);
    await adminPage.locator('input[name="booking[available_to]"]').fill(product.date);

    return product;
};

test.describe("Booking product for default product", () => {
    test("should create default product with One booking for many Days", async ({ adminPage }) => {
        const product = await createBookingProduct(adminPage);

        await adminPage.locator('input[name="booking[qty]"]').fill(product.weight);

        for (let i = 0; i < 3; i++) {
            await adminPage.getByText('Add Slots').first().click();

            await adminPage.locator('select[name="from_day"]').selectOption(i.toString());

            await adminPage.getByRole('textbox', { name: 'From Time' }).click();
            await adminPage.waitForTimeout(500);
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).click();
            await adminPage.waitForTimeout(500); // Adding a delay of 0.5 second

            await adminPage.locator('select[name="to_day"]').selectOption((i + 1).toString());
            await adminPage.getByRole('textbox', { name: 'To Time' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).click();
            await adminPage.waitForTimeout(500); // Adding a delay of 0.5 second

            await adminPage.getByRole('button', { name: 'Save', exact: true }).click();
            await adminPage.locator(`input[name="booking[slots][${i}][id]`);
            await expect(adminPage.locator(`input[name="booking[slots][${i}][id]"]`)).toHaveValue(/.+/);
        }

        await adminPage.getByRole("button", { name: "Save Product" }).click();
        //await expect(adminPage.getByText("Selected Products Deleted Successfully")).toBeVisible();
        await expect(adminPage.getByText(product.name)).toBeVisible();
    });

    test("should create default product with Many booking for One Day", async ({ adminPage }) => {
        /**
        * create the product.
        */
        const product = await createBookingProduct(adminPage);

        /**
        * Selecting Default Booking Product with Many booking for One Day.
        */
        await adminPage.locator('select[name="booking\\[booking_type\\]"]').selectOption('many');

        /**
        * Adding Product Quantity.
        */
        await adminPage.locator('input[name="booking[qty]"]').fill(product.weight);

        let weeks = [
            {
                name: 'Sunday',
                status: 1
            },
            {
                name: 'Monday',
                status: 2
            },
            {
                name: 'Tuesday',
                status: 3
            },
        ];
        /**
        * Loop for slots for each day.
        */
        for (const day of weeks) {
            /**
            * Clicking add button for adding Slots.
            */
            await adminPage.locator(`.overflow-x-auto > div:nth-child(${day.status}) > div:nth-child(2) > .cursor-pointer`).first().click();

            /**
            * Available From.
            */
            const fromInput = adminPage.getByRole('textbox', { name: 'From', exact: true });
            await fromInput.click();
            await adminPage.waitForSelector('.flatpickr-calendar.hasTime.noCalendar.open', { state: 'visible' });
            await adminPage.getByRole('spinbutton', { name: 'Hour' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Hour' }).fill('10');
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).fill('35');

            /**
            * Available To.
            */
            const toInput = adminPage.getByRole('textbox', { name: 'To', exact: true });
            await toInput.click();
            await adminPage.waitForSelector('.flatpickr-calendar.hasTime.noCalendar.open', { state: 'visible' });
            await adminPage.getByRole('spinbutton', { name: 'Hour' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Hour' }).fill('20');
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).fill('35');

            /**
            * Timeout of 0.5sec.
            */
            await adminPage.waitForTimeout(500);

            /**
            * Condition for choosing close on Sunday.
            */
            if (day.name === 'Sunday') {
                await adminPage.locator('select[name="status"]').selectOption('0');
            }
            else {
                await adminPage.locator('select[name="status"]').selectOption('1');
            }

            /**
            * Saving the slot.
            */
            await adminPage.getByRole('button', { name: 'Save', exact: true }).click();

            /**
            * Expecting that the slot is successfully saved and should be visible under slot time duration
            */
            await expect(adminPage.locator(`input[name="booking[slots][${day.status - 1}][0][id]"]`)).toHaveValue(/.+/);
        }

        /**
        * Saving the Booking Product.
        */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
        * Expecting the Alert Message as Product updated successfully.
        */
        await expect(adminPage.getByText("Product updated successfully")).toBeVisible();

        /**
        * Expecting the Product Name to be visible.
        */
        await expect(adminPage.getByText(product.name)).toBeVisible();
    });
});

test.describe("Booking Product for Appointment Booking Product", () => {
    test("should create Appointment Booking Product that are Not Available Every Week with same slot for all days", async ({ adminPage }) => {
        /**
         * create the product.
         */
        const product = await createBookingProduct(adminPage);

        /**
         * Selecting Appointment Product.
         */
        await adminPage.locator('select[name="booking[type]"]').selectOption('appointment');

        /**
         * Adding Product Quantity.
         */
        await adminPage.locator('input[name="booking[qty]"]').fill(product.weight);

        /**
         * Selecting No in Available Every Week.
         */
        await adminPage.locator('select[name="booking[available_every_week]"]').selectOption('0');

        /**
         * Selecting Yes in Same Slot For All days.
         */
        await adminPage.locator('select[name="booking[same_slot_all_days]"]').selectOption('1');

        /**
         * Now Adding Slots with Time Duration.
         */
        await adminPage.getByText('Add Slots').first().click();

        /**
         * Slot 1 Time available From.
         */
        await adminPage.getByRole('textbox', { name: 'From', exact: true }).click();
        await adminPage.waitForSelector('.flatpickr-calendar.hasTime.noCalendar.open', { state: 'visible' });
        await adminPage.getByRole('spinbutton', { name: 'Hour' }).click();
        await adminPage.getByRole('spinbutton', { name: 'Hour' }).fill('10');
        await adminPage.getByRole('spinbutton', { name: 'Minute' }).click();
        await adminPage.getByRole('spinbutton', { name: 'Minute' }).fill('35');

        /**
         * Slot 1 Time available To.
         */
        await adminPage.getByRole('textbox', { name: 'To', exact: true }).click();
        await adminPage.waitForSelector('.flatpickr-calendar.hasTime.noCalendar.open', { state: 'visible' });
        await adminPage.getByRole('spinbutton', { name: 'Hour' }).click();
        await adminPage.getByRole('spinbutton', { name: 'Hour' }).fill('10');
        await adminPage.getByRole('spinbutton', { name: 'Minute' }).click();
        await adminPage.getByRole('spinbutton', { name: 'Minute' }).fill('55');

        /**
         * Adding Slot 2 and waiting for time slot to be visible.
         */
        await adminPage.getByText('Add Slots').nth(2).click();
        await adminPage.waitForSelector('div.flex.gap-2\\.5[index="1"]', { state: 'visible' });

        /**
         * Slot 2 Time available From.
         */
        await adminPage.getByRole('textbox', { name: 'From' }).nth(2).click();
        await adminPage.waitForSelector('.flatpickr-calendar.hasTime.noCalendar.open', { state: 'visible' });
        await adminPage.getByRole('spinbutton', { name: 'Hour' }).click();
        await adminPage.getByRole('spinbutton', { name: 'Hour' }).fill('11');
        await adminPage.getByRole('spinbutton', { name: 'Minute' }).click();
        await adminPage.getByRole('spinbutton', { name: 'Minute' }).fill('10');

        /**
         * Slot 2 Time available To.
         */
        await adminPage.getByRole('textbox', { name: 'To' }).nth(2).click();
        await adminPage.waitForSelector('.flatpickr-calendar.hasTime.noCalendar.open', { state: 'visible' });
        await adminPage.getByRole('spinbutton', { name: 'Hour' }).click();
        await adminPage.getByRole('spinbutton', { name: 'Hour' }).fill('11');
        await adminPage.getByRole('spinbutton', { name: 'Minute' }).click();
        await adminPage.getByRole('spinbutton', { name: 'Minute' }).fill('35');

        /**
         * Saving the Slots.
         */
        await adminPage.getByRole('button', { name: 'Save', exact: true }).click();

        /**
         * Expecting that the slot is successfully saved and should be visible under slot time duration.
         */
        await expect(adminPage.locator(`input[name="booking[slots][0][id]"]`)).toHaveValue(/.+/);

        /**
         * Saving the Booking Product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
         * Expecting the Alert Message as Product updated successfully.
         */
        await expect(adminPage.getByText("Product updated successfully")).toBeVisible();

        /**
         * Expecting the Product Name to be visible.
         */
        await expect(adminPage.getByText(product.name)).toBeVisible();
    });

    test("should create Appointment Booking Product that are Not Available Every Week with No same slot for all days", async ({ adminPage }) => {
        /**
         * create the product.
         */
        const product = await createBookingProduct(adminPage);

        /**
         * Selecting Appointment Product.
         */
        await adminPage.locator('select[name="booking[type]"]').selectOption('appointment');

        /**
         * Adding Product Quantity.
         */
        await adminPage.locator('input[name="booking[qty]"]').fill(product.weight);

        /**
         * Selecting No in Available Every Week.
         */
        await adminPage.locator('select[name="booking[available_every_week]"]').selectOption('0');

        /**
         * Selecting No in Same Slot For All days.
         */
        await adminPage.locator('select[name="booking[same_slot_all_days]"]').selectOption('0');

        let weeks = [
            {
                name: 'Sunday',
                status: 0
            },
            {
                name: 'Monday',
                status: 1
            },
            {
                name: 'Tuesday',
                status: 2
            },
        ];

        /**
         * Loop for slots for each day.
         */
        for (const day of weeks) {
            /**
             * Clicking add button for adding Slots.
             */
            await adminPage.locator(`.overflow-x-auto > div:nth-child(${day.status}) > div:nth-child(2) > .cursor-pointer`).first().click();

            /**
            * Slot 1 Time available From.
            */
            await adminPage.getByRole('textbox', { name: 'From', exact: true }).click();
            await adminPage.waitForSelector('.flatpickr-calendar.hasTime.noCalendar.open', { state: 'visible' });
            await adminPage.getByRole('spinbutton', { name: 'Hour' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Hour' }).fill('10');
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).fill('35');

            /**
             * Slot 1 Time available To.
             */
            await adminPage.getByRole('textbox', { name: 'To', exact: true }).click();
            await adminPage.waitForSelector('.flatpickr-calendar.hasTime.noCalendar.open', { state: 'visible' });
            await adminPage.getByRole('spinbutton', { name: 'Hour' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Hour' }).fill('10');
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).fill('55');

            /**
             * Adding Slot 2 and waiting for time slot to be visible.
             */

            await adminPage.getByText('Add Slots').click();
            //await page.getByRole('textbox', { name: 'From' }).nth(2).click();
            //await adminPage.getByText('Add Slots').nth(2).click();
            await adminPage.waitForSelector(`div.flex.gap-2\\.5[index="${day.status}_1}"]`, { state: 'visible' });

            /**
             * Slot 2 Time available From.
             */
            await adminPage.getByRole('textbox', { name: 'From' }).nth(2).click();
            await adminPage.waitForSelector('.flatpickr-calendar.hasTime.noCalendar.open', { state: 'visible' });
            await adminPage.getByRole('spinbutton', { name: 'Hour' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Hour' }).fill('11');
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).fill('10');

            /**
             * Slot 2 Time available To.
             */
            await adminPage.getByRole('textbox', { name: 'To' }).nth(2).click();
            await adminPage.waitForSelector('.flatpickr-calendar.hasTime.noCalendar.open', { state: 'visible' });
            await adminPage.getByRole('spinbutton', { name: 'Hour' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Hour' }).fill('11');
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).click();
            await adminPage.getByRole('spinbutton', { name: 'Minute' }).fill('35');

            /**
             * Saving the Slots.
             */
            await adminPage.getByRole('button', { name: 'Save', exact: true }).click();
        }


        await adminPage.getByRole('button', { name: 'Save', exact: true }).click();

        //await expect(adminPage.locator(`input[name="booking[slots][0][id]"]`)).toHaveValue(/.+/);
        //await adminPage.getByRole("button", { name: "Save Product" }).click();
        await expect(adminPage.getByText(product.name)).toBeVisible();
    });
});