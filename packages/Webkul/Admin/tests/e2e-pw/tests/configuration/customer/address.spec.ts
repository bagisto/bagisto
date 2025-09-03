import { test, expect } from "../../../setup";

test.describe("customer address configuration", () => {
    test("should make country, state and zip as a required field", async ({
        adminPage,
    }) => {
        /**
         * Navigate to the configuration page.
         */
        await adminPage.goto("admin/configuration/customer/address");

        await adminPage.click(
            'label[for="customer[address][requirements][country]"]'
        );
        await adminPage.click(
            'label[for="customer[address][requirements][state]"]'
        );
        await adminPage.click(
            'label[for="customer[address][requirements][postcode]"]'
        );
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();
    });
});
