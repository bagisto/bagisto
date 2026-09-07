import { test } from "../../../setup";
import { ProductCreatePage } from "../../../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../../../pages/admin/catalog/products/ProductListPage";
import {
    CaptchaConfigurationPage,
    type CaptchaSettings,
} from "../../../pages/admin/configuration/customer/CaptchaConfigurationPage";
import { CaptchaShopPage } from "../../../pages/shop/CaptchaShopPage";
import {
    generateDescription,
    generateSKU,
    uniqueStamp,
} from "../../../utils/faker";

const CREDENTIALS = {
    projectId: "bagisto-test-project",
    apiKey: "AIzaSyD-EXAMPLEKEY1234567890",
    siteKey: "6LcEXAMPLEKEY1234567890",
};

test.describe("google captcha configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let captchaConfig: CaptchaConfigurationPage;
    let original: CaptchaSettings;

    test.beforeEach(async ({ adminPage }) => {
        captchaConfig = new CaptchaConfigurationPage(adminPage);
        original = await captchaConfig.readSettings();
    });

    test.afterEach(async () => {
        await captchaConfig.applySettings(original);
    });

    test("should save the captcha credentials and keep them after reload", async () => {
        await captchaConfig.applySettings({ enabled: true, ...CREDENTIALS });

        await captchaConfig.expectSettings({ enabled: true, ...CREDENTIALS });
    });

    test.describe("storefront captcha", () => {
        let captchaShop: CaptchaShopPage;

        test.beforeEach(async ({ adminPage }) => {
            captchaShop = new CaptchaShopPage(adminPage);
        });

        test("should not render the captcha on the sign in page while it is disabled", async () => {
            await captchaConfig.applySettings({ enabled: false });

            await captchaShop.openSignIn();

            await captchaShop.expectCaptchaAbsent();
        });

        const guardedPages = [
            { label: "sign in", open: () => captchaShop.openSignIn() },
            { label: "sign up", open: () => captchaShop.openSignUp() },
            { label: "forgot password", open: () => captchaShop.openForgotPassword() },
            { label: "contact us", open: () => captchaShop.openContactUs() },
        ];

        for (const guarded of guardedPages) {
            test(`should render the captcha on the ${guarded.label} page once it is enabled`, async () => {
                await captchaConfig.applySettings({ enabled: true, ...CREDENTIALS });

                await guarded.open();

                await captchaShop.expectCaptchaPresent();
            });
        }

        test("should render the captcha on the checkout sign in form once it is enabled", async ({
            adminPage,
        }) => {
            test.setTimeout(120000);

            const productName = `Captcha ${uniqueStamp()}`;
            const productListPage = new ProductListPage(adminPage);

            await new ProductCreatePage(adminPage).createSimpleProduct({
                name: productName,
                productNumber: generateSKU(),
                shortDescription: generateDescription(),
                description: generateDescription(),
                price: "199",
                weight: "25",
                inventory: "5000",
            });

            try {
                await captchaConfig.applySettings({ enabled: true, ...CREDENTIALS });

                await captchaShop.openCheckoutSignIn(productName);

                await captchaShop.expectCaptchaPresent();
            } finally {
                await productListPage.deleteProductsIfPresent([productName]);
            }
        });
    });
});
