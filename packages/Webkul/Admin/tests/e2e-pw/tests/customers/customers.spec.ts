import { test, expect, config } from "../../setup";
import {
    generateFirstName,
    generateLastName,
    generateEmail,
    randomElement,
} from "../../utils/faker";
import * as forms from "../../utils/form";

async function createCustomer(adminPage) {
    await adminPage.goto(`${config.baseUrl}/admin/customers/customers`);
    await adminPage.waitForSelector("button.primary-button:visible", {
        state: "visible",
    });

    await adminPage.click("button.primary-button:visible");

    await adminPage.fill(
        'input[name="first_name"]:visible',
        generateFirstName()
    );
    await adminPage.fill('input[name="last_name"]:visible', generateLastName());
    await adminPage.fill('input[name="email"]:visible', generateEmail());
    await adminPage.selectOption(
        'select[name="gender"]:visible',
        randomElement(["Male", "Female", "Other"])
    );

    await adminPage.press('input[name="phone"]:visible', "Enter");

    await expect(
        adminPage.getByText("Customer created successfully")
    ).toBeVisible();
}

test.describe("customer management", () => {
    test("create customer", async ({ adminPage }) => {
        await createCustomer(adminPage);
    });

    test("edit customer", async ({ adminPage }) => {
        /**
         * Creating a customer first.
         */
        await createCustomer(adminPage);

        await adminPage.goto(`${config.baseUrl}/admin/customers/customers`);
        await adminPage.waitForSelector("button.primary-button:visible", {
            state: "visible",
        });

        await adminPage.waitForSelector("a.cursor-pointer.icon-sort-right", {
            state: "visible",
        });
        const iconRight = await adminPage.$$(
            "a.cursor-pointer.icon-sort-right"
        );
        await iconRight[0].click();

        await adminPage.waitForSelector(
            'div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible',
            { state: "visible" }
        );
        const createBtn = await adminPage.$$(
            'div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible'
        );
        await createBtn[0].click();

        await adminPage.fill(
            'input[name="first_name"]:visible',
            forms.form.firstName
        );
        await adminPage.fill(
            'input[name="last_name"]:visible',
            forms.form.lastName
        );
        const email = forms.form.email;
        await adminPage.fill('input[name="email"]:visible', email);
        await adminPage.fill('input[name="phone"]:visible', forms.form.phone);
        await adminPage.selectOption('select[name="gender"]:visible', "Other");

        const checkboxs = await adminPage.$$('input[type="checkbox"] + label');

        for (let checkbox of checkboxs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 == 1) {
                await checkbox.click();
            }
        }

        await adminPage.press('input[name="phone"]:visible', "Enter");

        await expect(
            adminPage.getByText("Customer Updated Successfully")
        ).toBeVisible();
    });

    test("Add address", async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/customers/customers`);
        await adminPage.waitForSelector("button.primary-button:visible", {
            state: "visible",
        });

        await adminPage.waitForSelector("a.cursor-pointer.icon-sort-right", {
            state: "visible",
        });
        const iconRight = await adminPage.$$(
            "a.cursor-pointer.icon-sort-right"
        );
        await iconRight[0].click();

        await adminPage.waitForSelector(
            'div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible'
        );
        const createBtn = await adminPage.$$(
            'div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible'
        );
        await createBtn[1].click();

        await adminPage.fill('input[name="company_name"]', forms.form.lastName);
        await adminPage.fill('input[name="first_name"]', forms.form.firstName);
        await adminPage.fill('input[name="last_name"]', forms.form.lastName);
        await adminPage.fill('input[name="email"]', forms.form.email);
        await adminPage.fill('input[name="address[0]"]', forms.form.firstName);
        await adminPage.selectOption('select[name="country"]', "IN");
        await adminPage.selectOption('select[name="state"]', "UP");
        await adminPage.fill('input[name="city"]', forms.form.lastName);
        await adminPage.fill('input[name="postcode"]', "201301");
        await adminPage.fill('input[name="phone"]', forms.form.phone);

        await adminPage.click('input[name="default_address"] + label:visible');
        await adminPage.press('input[name="phone"]', "Enter");

        await expect(
            adminPage.getByText("Address Created Successfully")
        ).toBeVisible();
    });

    test("edit address", async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/customers/customers`);
        await adminPage.waitForSelector("button.primary-button:visible", {
            state: "visible",
        });

        await adminPage.waitForSelector("a.cursor-pointer.icon-sort-right", {
            state: "visible",
        });
        const iconRight = await adminPage.$$(
            "a.cursor-pointer.icon-sort-right"
        );
        await iconRight[0].click();

        await adminPage.waitForSelector(
            'div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible'
        );

        const createBtn = await adminPage.$$(
            'p[class="cursor-pointer text-blue-600 transition-all hover:underline"]:visible'
        );

        if (createBtn.length == 0) {
            throw new Error("No address found for edit");
        }

        await createBtn[0].click();

        await adminPage.fill('input[name="company_name"]', forms.form.lastName);
        await adminPage.fill('input[name="first_name"]', forms.form.firstName);
        await adminPage.fill('input[name="last_name"]', forms.form.lastName);
        await adminPage.fill('input[name="email"]', forms.form.email);
        await adminPage.fill('input[name="address[0]"]', forms.form.firstName);
        await adminPage.selectOption('select[name="country"]', "IN");
        await adminPage.selectOption('select[name="state"]', "UP");
        await adminPage.fill('input[name="city"]', forms.form.lastName);
        await adminPage.fill('input[name="postcode"]', "201301");
        await adminPage.fill('input[name="phone"]', forms.form.phone);

        await adminPage.click('input[name="default_address"] + label:visible');
        await adminPage.press('input[name="phone"]', "Enter");

        await expect(
            adminPage.getByText("Address Updated Successfully")
        ).toBeVisible();
    });

    // test('set default address', async ({ adminPage }) => {
    //     await adminPage.goto(`${config.baseUrl}/admin/customers/customers`);
    //     await adminPage.waitForSelector('button.primary-button:visible', { state: 'visible' });

    //     await adminPage.waitForSelector('a.cursor-pointer.icon-sort-right', { state: 'visible' });
    //     const iconRight = await adminPage.$$('a.cursor-pointer.icon-sort-right');
    //     await iconRight[0].click();

    //     await adminPage.waitForSelector('div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible');

    //     const createBtn = await adminPage.$$('button[class="flex cursor-pointer justify-center text-sm text-blue-600 transition-all hover:underline"]:visible');

    //     if (createBtn.length == 0) {
    //         throw new Error('No address found for edit');
    //     }

    //     await createBtn[createBtn.length - 1].click();

    //     await expect(adminPage.getByText('Default Address Updated Successfully')).toBeVisible();
    // });

    test("delete address", async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/customers/customers`);
        await adminPage.waitForSelector("button.primary-button:visible", {
            state: "visible",
        });

        await adminPage.waitForSelector("a.cursor-pointer.icon-sort-right", {
            state: "visible",
        });
        const iconRight = await adminPage.$$(
            "a.cursor-pointer.icon-sort-right"
        );
        await iconRight[0].click();

        await adminPage.waitForSelector(
            'p[class="cursor-pointer text-red-600 transition-all hover:underline"]:visible'
        );
        await adminPage.locator("p.text-red-600").click();

        await adminPage.click(
            'button[type="button"].transparent-button + button[type="button"].primary-button'
        );

        await expect(
            adminPage.getByText("Address Deleted Successfully")
        ).toBeVisible();
    });

    // test('add note', async ({ adminPage }) => {
    //     await adminPage.goto(`${config.baseUrl}/admin/customers/customers`);
    //     await adminPage.waitForSelector('button.primary-button:visible', { state: 'visible' });

    //     await adminPage.waitForSelector('a.cursor-pointer.icon-sort-right', { state: 'visible' });
    //     const iconRight = await adminPage.$$('a.cursor-pointer.icon-sort-right');
    //     await iconRight[0].click();

    //     const lorem100 = forms.generateRandomStringWithSpaces(200);
    //     adminPage.fill('textarea[name="note"]', lorem100);

    //     await adminPage.click('input[name="customer_notified"] + span');

    //     await adminPage.click('button[type="submit"].secondary-button:visible');

    //     await adminPage.waitForTimeout(2000);

    //     await expect(adminPage.getByText('Note Created Successfully')).toBeVisible();
    // });

    // test('delete account', async ({ adminPage }) => {
    //     await createCustomer(adminPage);

    //     await adminPage.goto(`${config.baseUrl}/admin/customers/customers`);
    //     await adminPage.waitForSelector('button.primary-button:visible', { state: 'visible' });

    //     await adminPage.waitForSelector('a.cursor-pointer.icon-sort-right', { state: 'visible' });
    //     const iconRight = await adminPage.$$('a.cursor-pointer.icon-sort-right');
    //     await iconRight[0].click();

    //     await adminPage.click('.icon-cancel:visible');
    //     await adminPage.click('button[type="button"].transparent-button + button[type="button"].primary-button');

    //     await expect(adminPage.getByText('Customer Deleted Successfully')).toBeVisible();
    // });

    test("create order", async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/customers/customers`);
        await adminPage.waitForSelector("button.primary-button:visible", {
            state: "visible",
        });

        await adminPage.waitForSelector("a.cursor-pointer.icon-sort-right", {
            state: "visible",
        });
        const iconRight = await adminPage.$$(
            "a.cursor-pointer.icon-sort-right"
        );
        await iconRight[0].click();

        await adminPage.click(".icon-cart:visible");

        await adminPage.click(
            'button[type="button"].transparent-button + button[type="button"].primary-button'
        );

        await expect(adminPage.getByText("Cart Items").first()).toBeVisible();
    });

    test("mass delete customers", async ({ adminPage }) => {
        /**
         * Creating a customer first.
         */
        await createCustomer(adminPage);

        await adminPage.goto(`${config.baseUrl}/admin/customers/customers`);
        await adminPage.waitForSelector("button.primary-button:visible", {
            state: "visible",
        });

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
            adminPage.getByText("Selected data successfully deleted")
        ).toBeVisible();
    });

    test("mass update customers", async ({ adminPage }) => {
        /**
         * Creating a customer first.
         */
        await createCustomer(adminPage);

        await adminPage.goto(`${config.baseUrl}/admin/customers/customers`);
        await adminPage.waitForSelector("button.primary-button:visible", {
            state: "visible",
        });

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
            'a:has-text("Active"), a:has-text("Inactive")',
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
            adminPage.getByText("Selected Customers successfully updated")
        ).toBeVisible();
    });
});
