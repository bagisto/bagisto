import { test } from "../setup";
import { AuthPage } from "../pages/shop/AuthPage";
import { buildCustomerCredentials } from "../utils/customer";
import { generateEmail } from "../utils/faker";

test.describe("customer authentication", () => {
    test("should refuse to register an email that is already registered", async ({
        shopPage,
    }) => {
        const authPage = new AuthPage(shopPage);
        const credentials = buildCustomerCredentials();

        await authPage.register(credentials);
        await authPage.attemptRegister({ ...buildCustomerCredentials(), email: credentials.email });

        await authPage.expectRegistrationRefused("The email has already been taken.");
    });

    test("should refuse to register when the passwords do not match", async ({
        shopPage,
    }) => {
        const authPage = new AuthPage(shopPage);
        const credentials = buildCustomerCredentials();

        await authPage.attemptRegister(credentials, `${credentials.password}-other`);

        await authPage.expectRegistrationRefused(
            "The Password field confirmation does not match",
        );

        await authPage.attemptLogin(credentials.email, credentials.password);

        await authPage.expectLoginRefused();
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

    test("should send a password reset link to a registered email", async ({
        shopPage,
    }) => {
        const authPage = new AuthPage(shopPage);
        const credentials = buildCustomerCredentials();

        await authPage.register(credentials);
        await authPage.requestPasswordReset(credentials.email);

        await authPage.expectResetLinkSent();
    });

    test("should answer a password reset for an unknown email exactly as for a registered one", async ({
        shopPage,
    }) => {
        const authPage = new AuthPage(shopPage);

        await authPage.requestPasswordReset(generateEmail());

        await authPage.expectResetLinkSent();
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
