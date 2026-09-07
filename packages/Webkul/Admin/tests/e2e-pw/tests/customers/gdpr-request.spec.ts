import { test } from "../../setup";
import { loginAsCustomer } from "../../utils/customer";
import {
    GDPRConfigurationPage,
    type GdprSettings,
} from "../../pages/admin/configuration/general/GDPRConfigurationPage";
import { CustomerGDPRPage } from "../../pages/admin/customers/CustomerGDPRPage";
import { uniqueStamp } from "../../utils/faker";

function getGdprRequestMessage(prefix: string): string {
    return `${prefix} ${uniqueStamp()}`;
}

test.describe("gdpr request management", () => {
    let gdprConfig: GDPRConfigurationPage;
    let original: GdprSettings;

    test.beforeEach(async ({ adminPage }) => {
        gdprConfig = new GDPRConfigurationPage(adminPage);
        original = await gdprConfig.readSettings();

        await gdprConfig.applySettings({ enabled: true });
    });

    test.afterEach(async () => {
        await gdprConfig.applySettings({ enabled: original.enabled });
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
        const requestMessage = getGdprRequestMessage("Delete request:");
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
        const requestMessage = getGdprRequestMessage("Delete request:");
        await gdprPage.createRequest("update", requestMessage);
        await gdprPage.expectRequestState(requestMessage, "Pending", "Update");
        await gdprPage.deleteRequest(requestMessage);
        await gdprPage.expectRequestAbsent(requestMessage);
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
