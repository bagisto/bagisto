import { test } from "../../../setup";
import {
    PRODUCT_RICH_SNIPPET_OPTIONS,
    RichSnippetsConfigurationPage,
    type RichSnippetSettings,
} from "../../../pages/admin/configuration/catalog/RichSnippetsConfigurationPage";

test.describe("rich snippets configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: RichSnippetsConfigurationPage;
    let original: RichSnippetSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new RichSnippetsConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist every product rich snippet option after reload", async () => {
        const changed = {} as RichSnippetSettings;

        for (const option of PRODUCT_RICH_SNIPPET_OPTIONS) {
            changed[option] = !original[option];
        }

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
