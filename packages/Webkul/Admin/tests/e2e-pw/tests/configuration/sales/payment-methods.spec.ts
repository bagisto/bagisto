import { test, expect } from "../../../setup";
import { generateDescription, getImageFile } from "../../../utils/faker";
import { PaymentMethodsConfigurationPage } from "../../../pages/admin/configuration/sales/PaymentMethodsConfigurationPage";

test.describe("payment methods configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new PaymentMethodsConfigurationPage(adminPage).open();
    });

    test("should configure the cash on delivery payment method", async ({
        adminPage,
    }) => {
        const page = new PaymentMethodsConfigurationPage(adminPage);

        await page.configureCashOnDelivery(
            generateDescription(200),
            generateDescription(200),
            "pending",
            "pending",
            "2",
            getImageFile(),
        );
        await page.saveAndVerify();
    });

    test("should configure the money transfer payment method", async ({
        adminPage,
    }) => {
        const page = new PaymentMethodsConfigurationPage(adminPage);

        await page.configureMoneyTransfer(
            generateDescription(200),
            generateDescription(200),
            "pending",
            "pending",
            "2",
            getImageFile(),
        );
        await page.saveAndVerify();
    });

    test("should configure the paypal standard payment method", async ({
        adminPage,
    }) => {
        const page = new PaymentMethodsConfigurationPage(adminPage);

        await page.configurePaypalStandard(
            generateDescription(200),
            true,
            "2",
            getImageFile(),
        );
        await page.saveAndVerify();
    });

    test("should configure the paypal smart button payment method", async ({
        adminPage,
    }) => {
        const page = new PaymentMethodsConfigurationPage(adminPage);

        await page.configurePaypalSmartButton(
            generateDescription(200),
            true,
            "2",
            getImageFile(),
        );
        await page.saveAndVerify();
    });
});
