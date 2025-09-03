import { test, expect } from "../setup";
import * as forms from "../utils/form";
import address from "../utils/address";
import {
    generateFirstName,
    generateLastName,
    generateEmail,
    generatePhoneNumber,
} from "../utils/faker";

export async function generateOrder(adminPage) {
    await adminPage.goto("admin/sales/orders");
    await adminPage.click("button.primary-button:visible");
    await adminPage.click(
        "div.flex.flex-col.items-center > button.secondary-button:visible"
    );

    /**
     * Fill customer details
     */
    await adminPage.fill(
        'input[name="first_name"]:visible',
        generateFirstName()
    );
    await adminPage.fill('input[name="last_name"]:visible', generateLastName());
    await adminPage.fill('input[name="email"]:visible', generateEmail());
    await adminPage.fill('input[name="phone"]:visible', generatePhoneNumber());
    await adminPage.selectOption('select[name="gender"]:visible', "Other");
    await adminPage.press('input[name="phone"]:visible', "Enter");

    /**
     * selecting product
     */
    const productSelector =
        ".grid > div.mt-2.flex > .cursor-pointer.text-emerald-600.transition-all";
    const itemExists = await adminPage
        .waitForSelector(productSelector, { timeout: 5000 })
        .catch(() => null);

    if (itemExists) {
        const items = await adminPage.$$(productSelector);
        const randomItem = items[Math.floor(Math.random() * items.length)];
        await randomItem.click();
        await adminPage.click("button.primary-button:visible");
    } else {
        await adminPage.click(
            "p.flex.flex-col.gap-1.text-base.font-semibold + button.secondary-button"
        );
        await adminPage
            .getByRole("textbox", { name: "Search by name" })
            .fill("arct");

        const searchResult = await adminPage
            .waitForSelector(
                "button.cursor-pointer.text-sm.text-blue-600.transition-all",
                { timeout: 5000 }
            )
            .catch(() => null);

        if (searchResult) {
            const cartBtns = await adminPage.$$(
                ".grid.place-content-start.gap-2.text-right > button.text-blue-600"
            );
            const inputQty = await adminPage.$$('input[name="qty"]:visible');

            for (let i = 0; i < cartBtns.length; i++) {
                const shouldClick = Math.random() < 0.5 || cartBtns.length < 2;
                if (shouldClick) {
                    const qty = Math.floor(Math.random() * 9) + 2;
                    await inputQty[i].scrollIntoViewIfNeeded();
                    await inputQty[i].fill(qty.toString());
                    await cartBtns[i].click();
                    break;
                }
            }
        }
    }

    const toastSelector =
        ".flex.items-center.break-all.text-sm > .icon-toast-done";
    const iconExists = await adminPage
        .waitForSelector(toastSelector, { timeout: 5000 })
        .catch(() => null);

    if (iconExists) {
        const icons = await adminPage.$$(
            ".flex.items-center.break-all.text-sm + .cursor-pointer.underline"
        );
        await icons[0].click();
    } else {
        const uncheckedOptions = await adminPage.$$(
            'input[type="checkbox"]:not(:checked) + label, input[type="radio"]:not(:checked) + label'
        );
        for (let checkbox of uncheckedOptions) {
            await checkbox.click();
        }

        await adminPage.click(
            ".flex.items-center.justify-between > button.primary-button:visible"
        );

        const iconAfterRetry = await adminPage
            .waitForSelector(toastSelector, { timeout: 5000 })
            .catch(() => null);
        if (iconAfterRetry) {
            const icons = await adminPage.$$(
                ".flex.items-center.break-all.text-sm + .cursor-pointer.underline"
            );
            await icons[0].click();
        }
    }

    /**
     * Billing address selection or creation
     */
    const billingRadios = await adminPage.$$('input[name="billing.id"]');
    if (billingRadios.length > 0) {
        const addressLabels = await adminPage.$$(
            `input[name="billing.id"] + label`
        );
        const randomIndex = Math.floor(Math.random() * billingRadios.length);
        await addressLabels[randomIndex].click();
    } else {
        await adminPage.click(
            "p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all"
        );
        if ((await address(adminPage)) !== "done") return;
    }

    const useForShipping = await adminPage.$(
        'input[name="billing.use_for_shipping"]'
    );
    const shouldUseBilling = Math.floor(Math.random() * 20) % 3 !== 1;
    const isShippingChecked = await useForShipping?.isChecked();

    if (shouldUseBilling !== isShippingChecked) {
        await adminPage.click('input[name="billing.use_for_shipping"] + label');
    }

    /**
     * Shipping address logic (if different from billing)
     */
    if (!shouldUseBilling) {
        const shippingRadios = await adminPage.$$('input[name="shipping.id"]');
        if (shippingRadios.length > 0) {
            const shippingLabels = await adminPage.$$(
                `input[name="shipping.id"] + label`
            );
            const randomIndex = Math.floor(
                Math.random() * shippingRadios.length
            );
            await shippingLabels[randomIndex].click();
        } else {
            await adminPage.click(
                "p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all:visible"
            );

            await adminPage.fill(
                'input[name="shipping.company_name"]',
                generateLastName()
            );
            await adminPage.fill(
                'input[name="shipping.first_name"]',
                generateFirstName()
            );
            await adminPage.fill(
                'input[name="shipping.last_name"]',
                generateLastName()
            );
            await adminPage.fill(
                'input[name="shipping.email"]',
                generateEmail()
            );
            await adminPage.fill(
                'input[name="shipping.address.[0]"]',
                generateFirstName()
            );
            await adminPage.selectOption(
                'select[name="shipping.country"]',
                "IN"
            );
            await adminPage.selectOption('select[name="shipping.state"]', "UP");
            await adminPage.fill(
                'input[name="shipping.city"]',
                generateLastName()
            );
            await adminPage.fill('input[name="shipping.postcode"]', "201301");
            await adminPage.fill(
                'input[name="shipping.phone"]',
                generatePhoneNumber()
            );
            await adminPage.press('input[name="shipping.phone"]', "Enter");
        }
    }

    /**
     * shipping method
     */
    await adminPage.click(
        ".mt-4.flex.justify-end > button.primary-button:visible"
    );

    const shippingMethods = await adminPage
        .waitForSelector('input[name="shipping_method"] + label', {
            timeout: 10000,
        })
        .catch(() => null);

    if (shippingMethods) {
        const options = await adminPage.$$(
            'input[name="shipping_method"] + label'
        );
        await options[Math.floor(Math.random() * options.length)].click();
    }

    const paymentMethods = await adminPage
        .waitForSelector('input[name="payment_method"] + label', {
            timeout: 10000,
        })
        .catch(() => null);

    if (paymentMethods) {
        const radios = await adminPage.$$(
            'input[name="payment_method"] + label'
        );
        await radios[1].click();

        const nextBtn = await adminPage.$$(
            "button.primary-button.w-max.px-11.py-3"
        );
        await nextBtn[nextBtn.length - 1].click();
    }

    await expect(adminPage.getByText("Order Items")).toBeVisible();
}

test.describe("sales management", () => {
    test("should be able to create orders", async ({ adminPage }) => {
        await generateOrder(adminPage);
    });

    test("should be comment on order", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/orders");

        await adminPage.locator(".row > div:nth-child(4) > a").first().click();

        const lorem100 = forms.generateRandomStringWithSpaces(500);
        adminPage.fill('textarea[name="comment"]', lorem100);
        await adminPage
            .locator('span.icon-uncheckbox.cursor-pointer[role="button"]')
            .click();

        await adminPage.getByRole("button", { name: "Submit Comment" }).click();
        await expect(adminPage.locator("#app")).toContainText(
            "Comment added successfully."
        );
    });

    test("should be able to reorder", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/orders");
        await adminPage.waitForTimeout(3000);
        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        await adminPage.getByRole("link", { name: " Reorder" }).click();

        await expect(adminPage.getByText("Cart Items")).toBeVisible();
        await adminPage.locator("label.icon-radio-normal").first().click();
        await adminPage.getByRole("button", { name: "Proceed" }).click();
        await adminPage.getByText("Free Shipping$0.00Free").click();
        await adminPage
            .locator("label")
            .filter({ hasText: "Cash On Delivery" })
            .click();
        await adminPage.getByRole("button", { name: "Place Order" }).click();
        await expect(adminPage.locator("#app")).toContainText("Pending");
    });

    test("should be able to create invoice", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/orders");

        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        await adminPage
            .waitForSelector(
                "div.transparent-button.px-1 > .icon-sales.text-2xl:visible"
            )
            .catch(() => null);

        await adminPage.click(
            "div.transparent-button.px-1 > .icon-sales.text-2xl:visible"
        );

        await adminPage.click('button[type="submit"].primary-button:visible');
        await expect(adminPage.locator("#app")).toContainText(
            "Invoice created successfully"
        );
    });

    test("should be create shipment", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/orders");

        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        const exists = await adminPage
            .waitForSelector(
                "div.transparent-button.px-1 > .icon-ship.text-2xl:visible",
                { timeout: 1000 }
            )
            .catch(() => null);

        await adminPage.click(
            "div.transparent-button.px-1 > .icon-ship.text-2xl:visible"
        );

        await adminPage.fill(
            'input[name="shipment[carrier_title]"]',
            forms.generateRandomStringWithSpaces(20)
        );
        await adminPage.fill(
            'input[name="shipment[track_number]"]',
            forms.generateRandomStringWithSpaces(20)
        );

        await adminPage
            .locator('[id="shipment\\[source\\]"]')
            .selectOption("1");

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.locator("#app")).toContainText(
            "Shipment created successfully"
        );
    });

    test("should be able to create refund", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/orders");
        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        await adminPage
            .waitForSelector(
                "div.transparent-button.px-1 > .icon-cancel.text-2xl:visible",
                { timeout: 1000 }
            )
            .catch(() => null);

        await adminPage
            .locator("div.transparent-button.px-1 > .icon-cancel.text-2xl")
            .click();
        await adminPage
            .waitForSelector(
                'input[type="text"].w-full.rounded-md.border.px-3.text-sm.text-gray-600.transition-all:visible',
                { timeout: 1000 }
            )
            .catch(() => null);

        const itemQty = await adminPage.$$(
            'input[type="text"].w-full.rounded-md.border.px-3.text-sm.text-gray-600.transition-all:visible'
        );
        let i = 1;
        for (let element of itemQty) {
            await element.scrollIntoViewIfNeeded();

            if (i > itemQty.length - 2) {
                let rand = Math.floor(Math.random() * 2000);
                await element.fill(rand.toString());
            }

            if (i > itemQty.length - 3) {
                continue;
            }

            const currentValue = await element.inputValue();

            const maxQty = parseInt(currentValue, 10);
            const qty = Math.floor(Math.random() * (maxQty - 1)) + 1;

            await element.fill(qty.toString());

            i++;
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(
            adminPage.locator("p", { hasText: "Refund created successfully" })
        ).toBeVisible();
    });

    test("should be create mail invoice", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/invoices");

        await adminPage.waitForSelector(
            ".cursor-pointer.rounded-md.text-2xl.transition-all.icon-view"
        );
        await adminPage
            .locator(
                ".cursor-pointer.rounded-md.text-2xl.transition-all.icon-view"
            )
            .first()
            .click();

        await adminPage
            .getByRole("button", { name: " Send Duplicate Invoice" })
            .click();
        await adminPage
            .getByRole("button", { name: "Send", exact: true })
            .click();
        await expect(adminPage.locator("#app")).toContainText(
            "Invoice sent successfully"
        );
    });

    test("should be able to print invoice", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/invoices");

        await adminPage.waitForSelector(
            ".cursor-pointer.rounded-md.text-2xl.transition-all.icon-view"
        );
        await adminPage
            .locator(
                ".cursor-pointer.rounded-md.text-2xl.transition-all.icon-view"
            )
            .first()
            .click();

        const downloadPromise = adminPage.waitForEvent("download");
        await adminPage.getByRole("link", { name: " Print" }).click();
        const download = await downloadPromise;
    });

    test("should be able to cancel order", async ({ adminPage }) => {
        /**
         * create order
         */
        await generateOrder(adminPage);
        await adminPage.waitForTimeout(3000);

        /**
         * Should Cancel a Order
         */
        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        await adminPage.locator(".icon-cancel").click();
        await adminPage
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await expect(adminPage.locator("#app")).toContainText(
            "Order cancelled successfully"
        );
    });

    test("should be able to create transaction", async ({ adminPage }) => {
        /**
         * create order
         */
        await generateOrder(adminPage);
        await adminPage.waitForTimeout(3000);

        /**
         * Create Transaction
         */
        await adminPage.goto("admin/sales/orders");
        await adminPage.waitForTimeout(3000);
        await adminPage.reload();
        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        await adminPage.locator(".transparent-button > .icon-sales").click();
        await adminPage.locator("#can_create_transaction").nth(1).click();
        await adminPage.getByRole("button", { name: "Create Invoice" }).click();

        /**
         * Go to transaction page
         */
        await adminPage.goto("admin/sales/transactions");
        await expect(adminPage.getByText("Paid").first()).toBeVisible();
    });

    test("support mass status Change  to Paid for Invoices", async ({
        adminPage,
    }) => {
        await generateOrder(adminPage);
        await adminPage.waitForTimeout(5000);

        /**
         * create invoice
         */
        await adminPage.goto("admin/sales/orders");
        await adminPage.reload();
        await adminPage.waitForTimeout(5000);
        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        await adminPage
            .waitForSelector(
                "div.transparent-button.px-1 > .icon-sales.text-2xl:visible"
            )
            .catch(() => null);

        await adminPage.click(
            "div.transparent-button.px-1 > .icon-sales.text-2xl:visible"
        );

        await adminPage.click('button[type="submit"].primary-button:visible');
        await expect(adminPage.locator("#app")).toContainText(
            "Invoice created successfully"
        );

        /**
         * Go to invoice page
         */
        await adminPage.goto("admin/sales/invoices");

        const checkboxes = await adminPage.locator('.icon-uncheckbox')
        await checkboxes.first().click();
        await adminPage.getByRole("button", { name: "Select Action " }).click();
        await adminPage.getByRole('link', { name: 'Update Status ' }).hover();
        await adminPage.getByRole("link", { name: "Paid" }).click();
        await adminPage.getByRole("button", { name: "Agree", exact: true }).click();

        await expect(adminPage.locator("#app")).toContainText("Paid");
        await expect(adminPage.getByText('Selected invoice updated successfully')).toBeVisible();
    });

 test("support mass status Change to overdue for Invoices", async ({
        adminPage,
    }) => {

        /**
         * Go to invoice page
         */
        await adminPage.goto("admin/sales/invoices");

        const checkboxes = await adminPage.locator('.icon-uncheckbox')
        await checkboxes.first().click();
        await adminPage.getByRole("button", { name: "Select Action " }).click();
        await adminPage.getByRole('link', { name: 'Update Status ' }).hover();
        await adminPage.getByRole("link", { name: "Overdue" }).click();
        await adminPage.getByRole("button", { name: "Agree", exact: true }).click();

        await expect(adminPage.locator("#app")).toContainText("Overdue");
        await expect(adminPage.getByText('Selected invoice updated successfully')).toBeVisible();
    });
});
