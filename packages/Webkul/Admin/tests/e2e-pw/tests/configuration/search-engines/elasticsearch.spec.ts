import { test } from "../../../setup";
import {
    SearchEngineConfigurationPage,
    type QueryLengthSettings,
} from "../../../pages/admin/configuration/search-engines/SearchEngineConfigurationPage";

test.describe("search engine query length", () => {
    let configurationPage: SearchEngineConfigurationPage;
    let original: QueryLengthSettings;

    test.beforeEach(async ({ adminPage }) => {
        configurationPage = new SearchEngineConfigurationPage(adminPage);
        original = await configurationPage.readQueryLength();
    });

    test.afterEach(async () => {
        await configurationPage.applyQueryLength(original);
    });

    test("should refuse a maximum query length below the minimum", async () => {
        await configurationPage.attemptQueryLength({ min: "10", max: "5" });

        await configurationPage.expectQueryLengthRefused("Maximum Query Length");
    });

    test("should refuse a maximum query length that lets no character through", async () => {
        await configurationPage.attemptQueryLength({ min: "0", max: "0" });

        await configurationPage.expectQueryLengthRefused("Maximum Query Length");
    });

    test("should keep a query length the storefront can search with", async () => {
        await configurationPage.applyQueryLength({ min: "3", max: "128" });

        await configurationPage.expectQueryLength({ min: "3", max: "128" });
    });
});
