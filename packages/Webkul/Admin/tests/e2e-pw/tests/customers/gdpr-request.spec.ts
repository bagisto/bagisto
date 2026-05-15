import { test, expect } from "../../setup";
import { loginAsCustomer } from "../../utils/customer";
import {
    enableGDPR,
    enableGDPRAgreement,
    enableCookiesNotice,
} from "../../utils/gdpr";
import { CustomerGDPRPage } from "../../pages/admin/customers/CustomerGDPRPage";

function getGdprRequestMessage(prefix: string): string {
    return `${prefix} ${Date.now()}`;
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
        const gdprPage = new CustomerGDPRPage(adminPage);

        await loginAsCustomer(adminPage);
        const requestMessage = getGdprRequestMessage("Update request:");
        await gdprPage.createRequest("update", requestMessage);
        await gdprPage.expectRequestState(requestMessage, "Pending", "Update");
        await gdprPage.updateRequestStatus(requestMessage, "processing");
        await gdprPage.expectRequestState(
            requestMessage,
            "Processing",
            "Update",
        );
    });

    test("should edit gdpr request state processing to completed", async ({
        adminPage,
    }) => {
        const gdprPage = new CustomerGDPRPage(adminPage);

        await loginAsCustomer(adminPage);
        await adminPage.goto("customer/account/profile");
        const acceptButton = adminPage.getByRole("button", { name: "Accept" });
        if (await acceptButton.isVisible()) {
            await acceptButton.click();
        }

        const requestMessage = getGdprRequestMessage("Delete request:");
        await adminPage.getByRole("link", { name: "GDPR Requests" }).click();
        await gdprPage.createRequest("delete", requestMessage);
        await gdprPage.expectRequestState(requestMessage, "Pending", "Delete");
        await gdprPage.updateRequestStatus(requestMessage, "processing");
        await gdprPage.expectRequestState(
            requestMessage,
            "Processing",
            "Delete",
        );
        await gdprPage.updateRequestStatus(requestMessage, "completed");
        await gdprPage.expectRequestState(
            requestMessage,
            "Completed",
            "Delete",
        );
    });

    test("should delete gdpr request", async ({ adminPage }) => {
        const gdprPage = new CustomerGDPRPage(adminPage);

        await loginAsCustomer(adminPage);
        await adminPage.goto("customer/account/profile");
        const acceptButton = adminPage.getByRole("button", { name: "Accept" });
        if (await acceptButton.isVisible()) {
            await acceptButton.click();
        }

        const requestMessage = getGdprRequestMessage("Delete request:");
        await adminPage.getByRole("link", { name: "GDPR Requests" }).click();
        await gdprPage.createRequest("update", requestMessage);
        await gdprPage.expectRequestState(requestMessage, "Pending", "Update");
        await gdprPage.deleteRequest(requestMessage);
        await expect(await gdprPage.getRequestCount(requestMessage)).toBe(0);
    });

    test("should decline gdpr request", async ({ adminPage }) => {
        const gdprPage = new CustomerGDPRPage(adminPage);

        await loginAsCustomer(adminPage);
        const requestMessage = getGdprRequestMessage("Decline request:");
        await gdprPage.createRequest("update", requestMessage);
        await gdprPage.expectRequestState(requestMessage, "Pending", "Update");
        await gdprPage.updateRequestStatus(requestMessage, "declined");
        await gdprPage.expectRequestState(requestMessage, "Declined", "Update");
    });
});
