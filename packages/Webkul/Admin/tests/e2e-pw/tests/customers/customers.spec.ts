import { test, expect } from "../../setup";
import type { Page } from "@playwright/test";
import {
    generateFirstName,
    generateLastName,
    generateEmail,
    randomElement,
    generatePhoneNumber,
    generateFullName,
    generateDescription,
} from "../../utils/faker";

const CUSTOMERS_URL = "admin/customers";
const PRIMARY_BUTTON = "button.primary-button:visible";
const OPEN_DETAILS_ICON = "a.cursor-pointer.icon-sort-right";
const CONFIRM_PRIMARY_BUTTON =
    'button[type="button"].transparent-button + button[type="button"].primary-button';
const MASS_CHECKBOX = ".icon-uncheckbox:visible";
const SELECT_ACTION_BUTTON = 'button:has-text("Select Action")';
const AGREE_BUTTON = 'button.primary-button:has-text("Agree")';
const CUSTOMER_EDIT_LINK =
    'div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible';

async function openCustomersList(adminPage: Page) {
    await adminPage.goto(CUSTOMERS_URL);
    await adminPage.waitForSelector(PRIMARY_BUTTON, {
        state: "visible",
    });
}

async function openFirstCustomerDetails(adminPage: Page) {
    await openCustomersList(adminPage);
    await adminPage.waitForSelector(OPEN_DETAILS_ICON, {
        state: "visible",
    });
    const detailIcons = await adminPage.$$(OPEN_DETAILS_ICON);
    expect(detailIcons.length).toBeGreaterThan(0);
    await detailIcons[0].click();
}

async function confirmAgreeDialog(adminPage: Page) {
    await adminPage.waitForSelector("text=Are you sure", {
        state: "visible",
        timeout: 1000,
    });
    const agreeButton = adminPage.locator(AGREE_BUTTON);
    await expect(agreeButton).toBeVisible();
    await agreeButton.click();
}

async function openMassActionMenu(adminPage: Page) {
    await openCustomersList(adminPage);
    await adminPage.waitForSelector(MASS_CHECKBOX, {
        state: "visible",
    });
    const checkboxes = await adminPage.$$(MASS_CHECKBOX);
    expect(checkboxes.length).toBeGreaterThan(1);
    await checkboxes[1].click();

    const selectActionButton = await adminPage.waitForSelector(
        SELECT_ACTION_BUTTON,
        { timeout: 1000 }
    );
    await selectActionButton.click();
}

async function createCustomer(adminPage: Page) {
    await openCustomersList(adminPage);

    await adminPage.click(PRIMARY_BUTTON);

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
    test("should be create customer", async ({ adminPage }) => {
        await createCustomer(adminPage);
    });

    test("should be able to edit customer", async ({ adminPage }) => {
        /**
         * Creating a customer first.
         */
        await createCustomer(adminPage);

        await openFirstCustomerDetails(adminPage);

        await adminPage.waitForSelector(CUSTOMER_EDIT_LINK, {
            state: "visible",
        });
        const createBtn = await adminPage.$$(CUSTOMER_EDIT_LINK);
        await createBtn[0].click();

        await adminPage.fill(
            'input[name="first_name"]:visible',
            generateFirstName()
        );
        await adminPage.fill(
            'input[name="last_name"]:visible',
            generateLastName()
        );
        const email = generateEmail();
        await adminPage.fill('input[name="email"]:visible', generateEmail());
        await adminPage.fill(
            'input[name="phone"]:visible',
            generatePhoneNumber()
        );
        await adminPage.selectOption('select[name="gender"]:visible', "Other");

        await adminPage.press('input[name="phone"]:visible', "Enter");

        await expect(
            adminPage.getByText("Customer Updated Successfully")
        ).toBeVisible();
    });

    test("should be add address", async ({ adminPage }) => {
        await openFirstCustomerDetails(adminPage);

        await adminPage.waitForSelector(
            'div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible'
        );
        const createBtn = await adminPage.$$(
            'div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible'
        );
        await createBtn[1].click();

        await adminPage.fill('input[name="company_name"]', generateFullName());
        await adminPage.fill('input[name="first_name"]', generateFirstName());
        await adminPage.fill('input[name="last_name"]', generateLastName());
        await adminPage.fill('input[name="email"]', generateEmail());
        await adminPage.fill('input[name="address[0]"]', generateFirstName());
        await adminPage.selectOption('select[name="country"]', "IN");
        await adminPage.selectOption('select[name="state"]', "UP");
        await adminPage.fill('input[name="city"]', generateLastName());
        await adminPage.fill('input[name="postcode"]', "201301");
        await adminPage.fill('input[name="phone"]', generatePhoneNumber());
        await adminPage.press('input[name="phone"]', "Enter");

        await expect(
            adminPage.getByText("Address Created Successfully")
        ).toBeVisible();
    });

    test("should be able to edit address", async ({ adminPage }) => {
        await openFirstCustomerDetails(adminPage);

        await adminPage.waitForSelector(
            'div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible'
        );

        const createBtn = await adminPage.$$(
            'p[class="cursor-pointer text-blue-600 transition-all hover:underline"]:visible'
        );

        // if (createBtn.length == 0) {
        //     throw new Error("No address found for edit");
        // }

        await createBtn[0].click();

        await adminPage.fill('input[name="company_name"]', generateLastName());
        await adminPage.fill('input[name="first_name"]', generateFirstName());
        await adminPage.fill('input[name="last_name"]', generateLastName());
        await adminPage.fill('input[name="email"]', generateEmail());
        await adminPage.fill('input[name="address[0]"]', generateFirstName());
        await adminPage.selectOption('select[name="country"]', "IN");
        await adminPage.selectOption('select[name="state"]', "UP");
        await adminPage.fill('input[name="city"]', generateLastName());
        await adminPage.fill('input[name="postcode"]', "201301");
        await adminPage.fill('input[name="phone"]', generatePhoneNumber());
        await adminPage.press('input[name="phone"]', "Enter");

        await expect(
            adminPage.getByText("Address Updated Successfully")
        ).toBeVisible();
    });

    test("should be set default address", async ({ adminPage }) => {
        await openFirstCustomerDetails(adminPage);

        await adminPage.waitForSelector(
            'button.flex:has-text("Set as Default"):visible'
        );

        const createBtn = await adminPage.$$(
            'button.flex:has-text("Set as Default"):visible'
        );

        // if (createBtn.length == 0) {
        //     throw new Error('No address found for edit');
        // }

        await createBtn[createBtn.length - 1].click();

        await expect(
            adminPage.getByText("Default Address Updated Successfully")
        ).toBeVisible();
    });

    test("should be able to delete address", async ({ adminPage }) => {
        await openFirstCustomerDetails(adminPage);

        await adminPage.waitForSelector(
            'p[class="cursor-pointer text-red-600 transition-all hover:underline"]:visible'
        );
        await adminPage.locator("p.text-red-600").click();

        await adminPage.click(CONFIRM_PRIMARY_BUTTON);

        await expect(
            adminPage.getByText("Address Deleted Successfully")
        ).toBeVisible();
    });

    test("should be add note in customer", async ({ adminPage }) => {
        await openCustomersList(adminPage);

        const Description = generateDescription();
        /**
         * edit customer profile
         */
        await openFirstCustomerDetails(adminPage);
        await adminPage.waitForTimeout(5000);
        await adminPage.reload();

        /**
         * add note in Customer Profile
         */
        await adminPage.waitForSelector('textarea[name="note"]', {
            state: "visible",
        });
        await adminPage.fill('textarea[name="note"]', Description);

        await adminPage.click('input[name="customer_notified"] + span');

        /**
         * submit note
         */
        const submitBtn = adminPage.locator(
            'button[type="submit"].secondary-button:visible'
        );
        await expect(submitBtn).toBeVisible({ timeout: 5000 });
        await submitBtn.click();
        await adminPage.waitForTimeout(5000);

        /**
         * check Note Created Successfully
         */
        // await expect(adminPage.getByText('Note Created Successfully Close')).toBeVisible({ timeout: 5000 });
        await expect(adminPage.getByText(Description)).toBeVisible();

    });

    test("should be able to delete account", async ({ adminPage }) => {
        await createCustomer(adminPage);

        await openFirstCustomerDetails(adminPage);
        await adminPage.waitForTimeout(3000);

        await adminPage.click(".icon-cancel:visible");
        await adminPage
            .getByRole("button", { name: "Agree", exact: true })
            .click();

        await adminPage.waitForSelector("text=Customer Deleted Successfully", {
            timeout: 3000,
        });

        await expect(
            adminPage
                .locator("#app")
                .filter({ hasText: "Customer Deleted Successfully" })
        ).toBeVisible();
    });

    test("should be able to create order", async ({ adminPage }) => {
        await openFirstCustomerDetails(adminPage);

        await adminPage.click(".icon-cart:visible");

        await adminPage.click(CONFIRM_PRIMARY_BUTTON);

        await expect(adminPage.getByText("Cart Items").first()).toBeVisible();
    });

    test("should be able to mass delete the customers.", async ({
        adminPage,
    }) => {
        /**
         * Creating a customer first.
         */
        await createCustomer(adminPage);

        await openMassActionMenu(adminPage);

        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

        await confirmAgreeDialog(adminPage);

        await expect(
            adminPage.getByText("Selected data successfully deleted")
        ).toBeVisible();
    });

    test("should be able to mass update the customers", async ({
        adminPage,
    }) => {
        /**
         * Creating a customer first.
         */
        await createCustomer(adminPage);

        await openMassActionMenu(adminPage);

        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector(
            'a:has-text("Active"), a:has-text("Inactive")',
            { state: "visible", timeout: 1000 }
        );
        await adminPage.click('a:has-text("Active")');

        await confirmAgreeDialog(adminPage);

        await expect(
            adminPage.getByText("Selected Customers successfully updated")
        ).toBeVisible();
    });
});
