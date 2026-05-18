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
