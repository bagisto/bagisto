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

    test("should reveal and mask each sign up password field independently", async ({
        shopPage,
    }) => {
        const authPage = new AuthPage(shopPage);
        const { password } = buildCustomerCredentials();

        await authPage.openSignUpForm();
        await authPage.expectPasswordMasked("password");
        await authPage.expectPasswordMasked("confirmPassword");

        await authPage.typePassword("password", password);
        await authPage.typePassword("confirmPassword", password);

        await authPage.togglePasswordVisibility("password");
        await authPage.expectPasswordRevealed("password", password);
        await authPage.expectPasswordMasked("confirmPassword");

        await authPage.togglePasswordVisibility("confirmPassword");
        await authPage.expectPasswordRevealed("confirmPassword", password);

        await authPage.togglePasswordVisibility("password");
        await authPage.expectPasswordMasked("password");
        await authPage.expectPasswordRevealed("confirmPassword", password);
    });

    test("should sign in with the password revealed", async ({ shopPage }) => {
        const authPage = new AuthPage(shopPage);
        const credentials = buildCustomerCredentials();

        await authPage.register(credentials);

        await authPage.openSignInForm();
        await authPage.fillSignInForm(credentials.email, credentials.password);
        await authPage.togglePasswordVisibility("password");
        await authPage.expectPasswordRevealed("password", credentials.password);
        await authPage.submitSignInForm();

        await authPage.expectLoginAccepted();
        await authPage.expectSignedIn(`${credentials.firstName} ${credentials.lastName}`);
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
