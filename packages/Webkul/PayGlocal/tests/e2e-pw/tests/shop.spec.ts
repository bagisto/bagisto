import { expect, test } from "../setup";
import { AdminPayGlocalConfigPage } from "../locators/admin/PayGlocalConfigPage";
import { ProductCheckout } from "../pages/checkout-flow";
import { generateCredentials, TEST_PRODUCT as PRODUCT_NAME } from "../utils/keys";

test.describe("PayGlocal at checkout", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new AdminPayGlocalConfigPage(adminPage).configure(generateCredentials());
    });

    test("is offered at the payment step, with its logo", async ({ shopPage }) => {
        const checkout = new ProductCheckout(shopPage);

        await checkout.reachPaymentStep(PRODUCT_NAME);

        await expect(checkout.checkoutShopPage.payGlocalPayment).toBeAttached();
        await expect(checkout.checkoutShopPage.payGlocalLogo).toBeVisible();
    });

    test("hands the customer to the package when the order is placed", async ({ shopPage }) => {
        const checkout = new ProductCheckout(shopPage);

        await shopPage.route("**/payglocal/redirect", (route) =>
            route.fulfill({
                status: 200,
                contentType: "text/html",
                body: "<html><body><h1>PayGlocal hosted checkout</h1></body></html>",
            })
        );

        await checkout.reachPaymentStep(PRODUCT_NAME);
        await checkout.selectPayGlocal();
        await checkout.placeOrder();

        await shopPage.waitForURL(/\/payglocal\/redirect/, { timeout: 20000 });

        await expect(shopPage.getByRole("heading", { name: /PayGlocal hosted checkout/i })).toBeVisible();
    });
});


test.describe("PayGlocal when the configuration cannot be paid with", () => {
    test.afterEach(async ({ adminPage }) => {
        await new AdminPayGlocalConfigPage(adminPage).configure(generateCredentials());
    });

    test("is still offered when the keys cannot be parsed, and sends the customer back to the cart", async ({ adminPage, shopPage }) => {
        const config = new AdminPayGlocalConfigPage(adminPage);

        await config.navigate();
        await config.enable();

        await config.fillCredentials({
            ...generateCredentials(),
            payGlocalPublicKey: "not-a-real-pem",
            merchantPrivateKey: "not-a-real-pem",
        });

        await config.save();

        const checkout = new ProductCheckout(shopPage);

        await checkout.reachPaymentStep(PRODUCT_NAME);

        await expect(checkout.checkoutShopPage.payGlocalPayment).toBeAttached();

        await checkout.selectPayGlocal();
        await checkout.placeOrder();

        await shopPage.waitForURL(/checkout\/cart/, { timeout: 20000 });
    });

    test("is still offered when the cart's currency is not accepted, and sends the customer back to the cart", async ({ adminPage, shopPage }) => {

        await new AdminPayGlocalConfigPage(adminPage).configure(generateCredentials(), "KWD");

        const checkout = new ProductCheckout(shopPage);

        await checkout.reachPaymentStep(PRODUCT_NAME);

        await expect(checkout.checkoutShopPage.payGlocalPayment).toBeAttached();

        await checkout.selectPayGlocal();
        await checkout.placeOrder();

        await shopPage.waitForURL(/checkout\/cart/, { timeout: 20000 });
    });
});
