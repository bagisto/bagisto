import { test, type AdminPage } from "../../../setup";
import { CustomerAddressPage } from "../../../pages/admin/configuration/customer/CustomerAddressPage";

test.describe("customer address configuration", () => {
    test("should make country, state and zip as a required field", async ({
        adminPage,
    }: {
        adminPage: AdminPage;
    }) => {
        const page = new CustomerAddressPage(adminPage);

        await page.open();
        await page.requireCountryStateZip();
    });
});
