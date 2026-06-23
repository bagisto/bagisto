import { test } from "../../../setup";
import { generateHostname, generateName } from "../../../utils/faker";
import { ContentConfigurationPage } from "../../../pages/admin/configuration/general/ContentConfigurationPage";

test.describe("content configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new ContentConfigurationPage(adminPage).open();
    });

    test("should update header offer title with redirection title and redirection link", async ({
        adminPage,
    }) => {
        const page = new ContentConfigurationPage(adminPage);

        await page.fillHeaderOffer(
            generateName(),
            generateName(),
            generateHostname(),
        );
        await page.saveAndVerify();
    });

    test("should add css and javascript", async ({ adminPage }) => {
        const page = new ContentConfigurationPage(adminPage);
        const cssCode = `.test {\n  display: flex;\n  justify-content: center;\n}`;
        const jsCode = `document.addEventListener('DOMContentLoaded', () => {\n  console.log('JavaScript added successfully');\n});`;

        await page.fillCustomScripts(cssCode, jsCode);
        await page.saveAndVerify();
    });
});
