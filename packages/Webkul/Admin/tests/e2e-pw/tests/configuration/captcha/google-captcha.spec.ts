import { test, expect } from "../../../setup";
import { generateDescription, generateSKU } from "../../../utils/faker";
import { ConfigurationPage } from "../../../pages/admin/configuration/ConfigurationPage";
import { CustomerCaptchaPage } from "../../../pages/admin/configuration/CustomerCaptchaPage";
import { ProductCreatePage } from "../../../pages/admin/catalog/products/ProductCreatePage";

test.describe("should verify google captcha verification", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new ConfigurationPage(adminPage).ensureGoogleCaptchaEnabled({
            projectId: "123456",
            apiKey: "test",
            siteKey: "test",
        });
    });

    test.afterEach(async ({ adminPage }) => {
        await new ConfigurationPage(adminPage).disableGoogleCaptcha();
    });

    test("should display google captcha on customer sign in page", async ({
        adminPage,
    }) => {
        const customerCaptchaPage = new CustomerCaptchaPage(adminPage);

        await customerCaptchaPage.openSignIn();
        await customerCaptchaPage.expectCaptchaVisible();
    });

    test("should display google captcha on customer sign up page", async ({
        adminPage,
    }) => {
        const customerCaptchaPage = new CustomerCaptchaPage(adminPage);

        await customerCaptchaPage.openSignUp();
        await customerCaptchaPage.expectCaptchaVisible();
    });

    test("should display google captcha on forgot password page", async ({
        adminPage,
    }) => {
        const customerCaptchaPage = new CustomerCaptchaPage(adminPage);

        await customerCaptchaPage.openForgotPassword();
        await customerCaptchaPage.expectCaptchaVisible();
    });

    test("should display google captcha on contact us page", async ({
        adminPage,
    }) => {
        const customerCaptchaPage = new CustomerCaptchaPage(adminPage);

        await customerCaptchaPage.openContactUs();
        await customerCaptchaPage.expectCaptchaVisible();
    });

    test("should display google captcha on product checkout sign in page", async ({
        adminPage,
    }) => {
        const productCreatePage = new ProductCreatePage(adminPage);
        const customerCaptchaPage = new CustomerCaptchaPage(adminPage);

        await productCreatePage.createSimpleProduct({
            name: `simple-${Date.now()}`,
            sku: generateSKU(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
            weight: "25",
            inventory: "5000",
        });

        await customerCaptchaPage.openCheckoutSignIn();
        await customerCaptchaPage.expectCaptchaVisible();
    });
});
