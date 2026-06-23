import { test, expect } from "../../../setup";
import { ConfigurationPage } from "../../../pages/admin/configuration/ConfigurationPage";


const projectID = "bagisto-test-project";
const apiKey = "AIzaSyD-EXAMPLEKEY1234567890";
const siteKey = "6LcEXAMPLEKEY1234567890";

test.describe("google captcha configuration", () => {
    test.afterEach(async ({ adminPage }) => {
        await new ConfigurationPage(adminPage).disableGoogleCaptcha();
    });

    test("should make enable the google captcha with site and secret key", async ({
        adminPage,
    }) => {
        const page = new ConfigurationPage(adminPage);

        await page.ensureGoogleCaptchaEnabled({
            projectId: projectID,
            apiKey,
            siteKey,
        });
        await page.verifyGoogleCaptchaSettings({
            projectId: projectID,
            apiKey,
            siteKey,
        });
    });
});
