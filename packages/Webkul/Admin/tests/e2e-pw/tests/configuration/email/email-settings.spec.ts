import { test } from "../../../setup";
import type { AdminPage } from "../../../setup";
import { generateName, generateEmail } from "../../../utils/faker";
import { EmailConfigurationPage } from "../../../pages/admin/configuration/email/EmailConfigurationPage";

test.describe("email settings configuration", () => {
    test("should configure the email settings", async ({
        adminPage,
    }: {
        adminPage: AdminPage;
    }) => {
        const page = new EmailConfigurationPage(adminPage);

        await page.openSettings();
        await page.fillEmailSettings({
            senderName: generateName(),
            senderEmail: generateEmail(),
            adminName: generateName(),
            adminEmail: generateEmail(),
            contactName: generateName(),
            contactEmail: generateEmail(),
        });
        await page.saveAndVerify();
    });
});
