import { test } from "../../../setup";
import { EmailNotificationsPage } from "../../../pages/admin/configuration/email/EmailNotificationsPage";

test.describe("email notification configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: EmailNotificationsPage;
    let original: boolean;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new EmailNotificationsPage(adminPage);
        original = await configPage.readNotification("new_order");
    });

    test.afterEach(async () => {
        await configPage.applyNotification("new_order", original);
    });

    test("should persist the new order notification setting after reload", async () => {
        await configPage.applyNotification("new_order", !original);

        await configPage.expectNotification("new_order", !original);
    });
});
