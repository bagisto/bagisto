import { test } from "../../../setup";
import type { AdminPage } from "../../../setup";
import { EmailConfigurationPage } from "../../../pages/admin/configuration/email/EmailConfigurationPage";

test.describe("email notification configuration", () => {
    test("should configure the email settings", async ({
        adminPage,
    }: {
        adminPage: AdminPage;
    }) => {
        const page = new EmailConfigurationPage(adminPage);

        await page.openNotifications();
        await page.saveAndVerify();
    });
});
