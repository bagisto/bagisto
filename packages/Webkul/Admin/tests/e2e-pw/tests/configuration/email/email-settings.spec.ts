import { uniqueStamp } from "../../../utils/faker";
import { test } from "../../../setup";
import {
    EmailConfigurationPage,
    type EmailSettings,
} from "../../../pages/admin/configuration/email/EmailConfigurationPage";

test.describe("email settings configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: EmailConfigurationPage;
    let original: EmailSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new EmailConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist the sender, admin and contact details after reload", async () => {
        const stamp = uniqueStamp();
        const changed = {
            senderName: `Sender ${stamp}`,
            senderEmail: `sender-${stamp}@example.com`,
            adminName: `Admin ${stamp}`,
            adminEmail: `admin-${stamp}@example.com`,
            contactName: `Contact ${stamp}`,
            contactEmail: `contact-${stamp}@example.com`,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
