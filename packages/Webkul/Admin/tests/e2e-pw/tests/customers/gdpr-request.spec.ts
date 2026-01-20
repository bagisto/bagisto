import { test, expect } from "../../setup";
import { loginAsCustomer } from "../../utils/customer";
import { generateDescription } from "../../utils/faker";
import {
    enableGDPR,
    enableGDPRAgreement,
    enableCookiesNotice,
} from "../../utils/gdpr";

test.describe("customer agreement configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await enableGDPR(adminPage);

        await enableGDPRAgreement(adminPage);

        await enableCookiesNotice(adminPage);
    });

    test("should edit gdpr request state pending to processing", async ({
        adminPage,
    }) => {
        /**
         * Customer login.
         */
        await loginAsCustomer(adminPage);

        await adminPage.goto("customer/account/gdpr");

        /**
         * Create GDPR request.
         */
        await adminPage.getByRole("button", { name: "Create Request" }).click();
        await adminPage.locator('select[name="type"]').selectOption("update");
        await adminPage.locator('textarea[name="message"]').click();
        await adminPage
            .locator('textarea[name="message"]')
            .fill(generateDescription());
        await adminPage.getByRole("button", { name: "Save" }).click();
        await expect(adminPage.getByRole('paragraph').filter({ hasText: 'Request created successfully' })).toBeVisible({ timeout: 5000 });
        await adminPage.locator(".icon-cancel").first().click();

        /**
         * GDPR request is pending.
         */
        await expect(adminPage.locator("#main")).toContainText("Pending");

        /**
         * Change state of GDPR request to processing.
         */
        await adminPage.goto("admin/customers/gdpr");
        await adminPage.locator(".row > .flex > a").first().click();
        await adminPage.waitForSelector("#status", { state: "visible" });
        await adminPage.selectOption("#status", "processing");
        await adminPage.getByRole("button", { name: "Save" }).click();

        /**
         * Update the GDPR request (pending --> processing).
         */
        await adminPage.goto("customer/account/gdpr");
        await expect(adminPage.locator("#main")).toContainText("Processing");
        await expect(adminPage.locator("#main")).toContainText("Update");
    });

    test("should edit gdpr request state processing to completed", async ({
        adminPage,
    }) => {
        /**
         * Customer login.
         */
        await loginAsCustomer(adminPage);

        await adminPage.goto("customer/account/profile");

        /**
         * Create GDPR request.
         */
        await adminPage
            .getByRole("link", { name: " GDPR Requests " })
            .click();
        await adminPage.getByRole("button", { name: "Create Request" }).click();
        await adminPage.locator('select[name="type"]').selectOption("delete");
        await adminPage.locator('textarea[name="message"]').click();
        await adminPage
            .locator('textarea[name="message"]')
            .fill("Delete my last name - Verma");
        await adminPage.getByRole("button", { name: "Save" }).click();
        await expect(adminPage.getByRole('paragraph').filter({ hasText: 'Request created successfully' })).toBeVisible();
        await adminPage.locator(".icon-cancel").first().click();

        /**
         * Check request is pending.
         */
        await expect(adminPage.locator("#main")).toContainText("Pending");

        /**
         * Change state of GDPR request to processing.
         */
        await adminPage.goto("admin/customers/gdpr");
        await adminPage.locator(".row > .flex > a").first().click();
        await adminPage.waitForSelector("#status", { state: "visible" });
        await adminPage.selectOption("#status", "processing");
        await adminPage.getByRole("button", { name: "Save" }).click();

        /**
         * Update the GDPR request (processing --> completed).
         */
        await adminPage.goto("customer/account/gdpr");
        await expect(adminPage.locator("#main")).toContainText("Processing");
        await expect(adminPage.locator("#main")).toContainText("Delete");

        /**
         * Change state of GDPR request to completed.
         */
        await adminPage.goto("admin/customers/gdpr");
        await adminPage.locator(".row > .flex > a").first().click();
        await adminPage.waitForSelector("#status", { state: "visible" });
        await adminPage.selectOption("#status", "completed");
        await adminPage.getByRole("button", { name: "Save" }).click();

        /**
         * Update the GDPR request (processing --> completed).
         */
        await adminPage.goto("customer/account/gdpr");
        await expect(adminPage.locator("#main")).toContainText("Completed");
        await expect(adminPage.locator("#main")).toContainText("Delete");
    });

    test("should delete gdpr request", async ({ adminPage }) => {
        /**
         * Customer login.
         */
        await loginAsCustomer(adminPage);

        await adminPage.goto("customer/account/profile");

        /**
         * Create GDPR request.
         */
        await adminPage
            .getByRole("link", { name: " GDPR Requests " })
            .click();
        await adminPage.getByRole("button", { name: "Create Request" }).click();
        await adminPage.locator('select[name="type"]').selectOption("update");
        await adminPage.locator('textarea[name="message"]').click();
        await adminPage
            .locator('textarea[name="message"]')
            .fill("Update Phone No.: 123456");
        await adminPage.getByRole("button", { name: "Save" }).click();
        await expect(adminPage.getByRole('paragraph').filter({ hasText: 'Request created successfully' })).toBeVisible();
        await adminPage.locator(".icon-cancel").first().click();

        /**
         * Check request is pending.
         */
        await expect(adminPage.locator("#main")).toContainText("Pending");

        /**
         * Verify the GDPR request.
         */
        await adminPage.goto("admin/customers/gdpr");
        await adminPage
            .locator(".row > .flex > a:nth-child(2)")
            .first()
            .click();
        await adminPage
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await adminPage.getByText("Data Request deleted").click();
        await adminPage.goto("customer/account/gdpr");
        await expect(adminPage.locator("#main")).not.toHaveText(
            "Update Phone No.: 123456"
        );
    });

    test("should decline gdpr request ", async ({ adminPage }) => {
        /**
         * Customer login.
         */
        await loginAsCustomer(adminPage);
        await adminPage.goto("customer/account/gdpr");

        /**
         * Create GDPR request.
         */
        await adminPage.getByRole("button", { name: "Create Request" }).click();
        await adminPage.locator('select[name="type"]').selectOption("update");
        await adminPage.locator('textarea[name="message"]').click();
        await adminPage
            .locator('textarea[name="message"]')
            .fill(generateDescription());
        await adminPage.getByRole("button", { name: "Save" }).click();
        await expect(adminPage.locator('#app')).toContainText('Request created successfully', { timeout: 5000 });
        await adminPage.locator(".icon-cancel").first().click();

        /**
         * Check request is pending.
         */
        await expect(adminPage.locator("#main")).toContainText("Pending");

        /**
         * Change state of GDPR request to declined.
         */
        await adminPage.goto("admin/customers/gdpr");
        await adminPage.locator(".row > .flex > a").first().click();
        await adminPage.waitForSelector("#status", { state: "visible" });
        await adminPage.selectOption("#status", "declined");
        await adminPage.getByRole("button", { name: "Save" }).click();

        /**
         * Update the GDPR request (pending --> declined).
         */
        await adminPage.goto("customer/account/gdpr");
        await expect(adminPage.locator("#main")).toContainText("Declined");
        await expect(adminPage.locator("#main")).toContainText("Update");
    });
});