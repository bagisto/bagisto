import { test } from "../setup";
import { AuthPage } from "../pages/shop/AuthPage";
import { buildCustomerCredentials } from "../utils/customer";

test.describe("customer authentication", () => {
    test("should register a new customer", async ({ shopPage }) => {
        const authPage = new AuthPage(shopPage);
        const credentials = buildCustomerCredentials();

        await authPage.register(credentials);
        await authPage.login(credentials);

        await authPage.expectSignedIn(`${credentials.firstName} ${credentials.lastName}`);
    });

    test("should refuse to register an email that is already registered", async ({
        shopPage,
    }) => {
        const authPage = new AuthPage(shopPage);
        const credentials = buildCustomerCredentials();

        await authPage.register(credentials);
        await authPage.attemptRegister({ ...buildCustomerCredentials(), email: credentials.email });

        await authPage.expectRegistrationRefused("The email has already been taken.");
    });

    test("should sign in a registered customer", async ({ shopPage }) => {
        const authPage = new AuthPage(shopPage);
        const credentials = buildCustomerCredentials();

        await authPage.register(credentials);
        await authPage.login(credentials);

        await authPage.expectSignedIn(`${credentials.firstName} ${credentials.lastName}`);
    });

    test("should refuse a wrong password", async ({ shopPage }) => {
        const authPage = new AuthPage(shopPage);
        const credentials = buildCustomerCredentials();

        await authPage.register(credentials);
        await authPage.attemptLogin(credentials.email, "wrong-password");

        await authPage.expectLoginRefused();
        await authPage.expectSignedOut();
    });

    test("should sign a customer out", async ({ shopPage }) => {
        const authPage = new AuthPage(shopPage);
        const credentials = buildCustomerCredentials();

        await authPage.register(credentials);
        await authPage.login(credentials);
        await authPage.logout();

        await authPage.expectGuestMenu();
        await authPage.expectSignedOut();
    });
});
