import { test, expect } from "../../setup";
import { loginAsCustomer } from "../../utils/customer";
import {
    enableGDPR,
    enableGDPRAgreement,
    enableCookiesNotice,
} from "../../utils/gdpr";

function getGdprRequestMessage(prefix: string): string {
    return `${prefix} ${Date.now()}`;
}

async function createGdprRequest(
    adminPage,
    type: "update" | "delete",
    message: string,
): Promise<void> {
    await adminPage.goto("customer/account/gdpr");
    await adminPage.getByRole("button", { name: "Create Request" }).click();
    await adminPage.locator('select[name="type"]').selectOption(type);
    await adminPage.locator('textarea[name="message"]').fill(message);
    await adminPage.getByRole("button", { name: "Save" }).click();

    const requestRow = adminPage
        .locator("#main .row")
        .filter({ hasText: message })
        .first();

    await expect(requestRow).toContainText("Pending");
}

async function updateRequestStatusFromAdmin(
    adminPage,
    message: string,
    status: "processing" | "completed" | "declined",
): Promise<void> {
    await adminPage.goto("admin/customers/gdpr");

    const requestRow = adminPage
        .locator(".row")
        .filter({ hasText: message })
        .first();
    await expect(requestRow).toBeVisible({ timeout: 20000 });

    await requestRow.locator(".flex > a").first().click();
    await adminPage.waitForSelector("#status", { state: "visible" });
    await adminPage.selectOption("#status", status);
    await adminPage.getByRole("button", { name: "Save" }).click();
}

async function expectCustomerRequestState(
    adminPage,
    message: string,
    expectedStatus: string,
    expectedType: string,
): Promise<void> {
    await adminPage.goto("customer/account/gdpr");
    const requestRow = adminPage
        .locator("#main .row")
        .filter({ hasText: message })
        .first();

    await expect(requestRow).toContainText(expectedStatus);
    await expect(requestRow).toContainText(expectedType);
}

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
        const requestMessage = getGdprRequestMessage("Update request:");

        /**
         * Create GDPR request.
         */
        await createGdprRequest(adminPage, "update", requestMessage);

        /**
         * GDPR request is pending.
         */
        await expectCustomerRequestState(
            adminPage,
            requestMessage,
            "Pending",
            "Update",
        );

        /**
         * Change state of GDPR request to processing.
         */
        await updateRequestStatusFromAdmin(
            adminPage,
            requestMessage,
            "processing",
        );

        /**
         * Update the GDPR request (pending --> processing).
         */
        await expectCustomerRequestState(
            adminPage,
            requestMessage,
            "Processing",
            "Update",
        );
    });

    test("should edit gdpr request state processing to completed", async ({
        adminPage,
    }) => {
        /**
         * Customer login.
         */
        await loginAsCustomer(adminPage);

        await adminPage.goto("customer/account/profile");
        const acceptButton = adminPage.getByRole("button", { name: "Accept" });

        if (await acceptButton.isVisible()) {
            await acceptButton.click();
        }
        const requestMessage = getGdprRequestMessage("Delete request:");

        /**
         * Create GDPR request.
         */
        await adminPage.getByRole("link", { name: "GDPR Requests" }).click();
        await createGdprRequest(adminPage, "delete", requestMessage);

        /**
         * Check request is pending.
         */
        await expectCustomerRequestState(
            adminPage,
            requestMessage,
            "Pending",
            "Delete",
        );

        /**
         * Change state of GDPR request to processing.
         */
        await updateRequestStatusFromAdmin(
            adminPage,
            requestMessage,
            "processing",
        );

        /**
         * Update the GDPR request (processing --> completed).
         */
        await expectCustomerRequestState(
            adminPage,
            requestMessage,
            "Processing",
            "Delete",
        );

        /**
         * Change state of GDPR request to completed.
         */
        await updateRequestStatusFromAdmin(
            adminPage,
            requestMessage,
            "completed",
        );

        /**
         * Update the GDPR request (processing --> completed)
         */
        await expectCustomerRequestState(
            adminPage,
            requestMessage,
            "Completed",
            "Delete",
        );
    });

    test("should delete gdpr request", async ({ adminPage }) => {
        /**
         * Customer login.
         */
        await loginAsCustomer(adminPage);

        await adminPage.goto("customer/account/profile");

        const acceptButton = adminPage.getByRole("button", { name: "Accept" });

        if (await acceptButton.isVisible()) {
            await acceptButton.click();
        }
        const requestMessage = getGdprRequestMessage("Delete request:");

        /**
         * Create GDPR request.
         */
        await adminPage.getByRole("link", { name: "GDPR Requests" }).click();
        await createGdprRequest(adminPage, "update", requestMessage);

        /**
         * Check request is pending.
         */
        await expectCustomerRequestState(
            adminPage,
            requestMessage,
            "Pending",
            "Update",
        );

        /**
         * Verify the GDPR request.
         */
        await adminPage.goto("admin/customers/gdpr");
        const requestRow = adminPage
            .locator(".row")
            .filter({ hasText: requestMessage })
            .first();
        await expect(requestRow).toBeVisible({ timeout: 20000 });
        await requestRow.locator(".flex > a").nth(1).click();

        await adminPage
            .getByRole("button", { name: "Agree", exact: true })
            .click();

        await expect(adminPage.locator("#app")).toContainText(
            "Data Request deleted",
        );

        await adminPage.goto("customer/account/gdpr");
        await expect(
            adminPage.locator("#main .row").filter({ hasText: requestMessage }),
        ).toHaveCount(0);
    });

    test("should decline gdpr request ", async ({ adminPage }) => {
        /**
         * Customer login.
         */
        await loginAsCustomer(adminPage);
        const requestMessage = getGdprRequestMessage("Decline request:");

        /**
         * Create GDPR request.
         */
        await createGdprRequest(adminPage, "update", requestMessage);

        /**
         * Check request is pending.
         */
        await expectCustomerRequestState(
            adminPage,
            requestMessage,
            "Pending",
            "Update",
        );

        /**
         * Change state of GDPR request to declined.
         */
        await updateRequestStatusFromAdmin(
            adminPage,
            requestMessage,
            "declined",
        );

        /**
         * Update the GDPR request (pending --> declined).
         */
        await expectCustomerRequestState(
            adminPage,
            requestMessage,
            "Declined",
            "Update",
        );
    });
});
