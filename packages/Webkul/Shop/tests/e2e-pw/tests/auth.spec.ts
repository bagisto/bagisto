import { test, expect } from "../setup";
import { AuthPage, CustomerCredentials } from "../pages/shop/AuthPage";
import {
    generateEmail,
    generateFirstName,
    generateLastName,
} from "../utils/faker";

test("should be able to register", async ({ shopPage }) => {
    const authPage = new AuthPage(shopPage);

    await authPage.register({
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        password: "admin123",
    });
});

test("should be able to login", async ({ shopPage }) => {
    const authPage = new AuthPage(shopPage);
    const credentials: CustomerCredentials = {
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        password: "admin123",
    };

    await authPage.register(credentials);
    await authPage.login(credentials);
});

test("should reveal and mask each sign up password field independently", async ({
    shopPage,
}) => {
    const authPage = new AuthPage(shopPage);
    const password = "admin123";

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
    const credentials: CustomerCredentials = {
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        password: "admin123",
    };

    await authPage.register(credentials);

    await authPage.openSignInForm();
    await authPage.fillSignInForm(credentials);
    await authPage.togglePasswordVisibility("password");
    await authPage.expectPasswordRevealed("password", credentials.password);

    await authPage.submitSignInForm();
});

test("should be able to logout", async ({ shopPage }) => {
    const authPage = new AuthPage(shopPage);
    const credentials: CustomerCredentials = {
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        password: "admin123",
    };

    await authPage.register(credentials);
    await authPage.login(credentials);
    await authPage.logout();
});
