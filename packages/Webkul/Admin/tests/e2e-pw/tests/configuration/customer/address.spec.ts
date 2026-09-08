import { test } from "../../../setup";
import {
    CustomerAddressPage,
    type AddressRequirementSettings,
} from "../../../pages/admin/configuration/customer/CustomerAddressPage";

test.describe("customer address configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: CustomerAddressPage;
    let original: AddressRequirementSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new CustomerAddressPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist which address fields are required after reload", async () => {
        const changed = {
            country: !original.country,
            state: !original.state,
            postcode: !original.postcode,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
