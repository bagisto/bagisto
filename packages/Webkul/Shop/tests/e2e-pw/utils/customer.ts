import { expect, config } from "../setup";
import {
    generateName,
    generateFirstName,
    generateLastName,
    generateEmail,
} from "./faker";

export async function register(page) {
    const credentials = {
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        password: "admin123",
    };

    await page.goto(`${config.baseUrl}`);
    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Sign Up" }).click();
    await page.getByPlaceholder("First Name").click();
    await page.getByPlaceholder("First Name").fill(credentials.firstName);
    await page.getByPlaceholder("Last Name").click();
    await page.getByPlaceholder("Last Name").fill(credentials.lastName);
    await page.getByPlaceholder("email@example.com").click();
    await page.getByPlaceholder("email@example.com").fill(credentials.email);
    await page.getByPlaceholder("Password", { exact: true }).click();
    await page
        .getByPlaceholder("Password", { exact: true })
        .fill(credentials.password);
    await page.getByPlaceholder("Confirm Password").click();
    await page.getByPlaceholder("Confirm Password").fill(credentials.password);
    await page
        .locator("#main form div")
        .filter({ hasText: "Subscribe to newsletter" })
        .locator("label")
        .first()
        .click();
    await page.getByRole("button", { name: "Register" }).click();

    await expect(
        page
            .getByText(
                "Account created successfully, an e-mail has been sent for verification."
            )
            .first()
    ).toBeVisible();

    return credentials;
}

export async function loginAsCustomer(page) {
    const credentials = await register(page);

    await page.goto(`${config.baseUrl}`);
    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Sign In" }).click();
    await page.getByPlaceholder("email@example.com").click();
    await page.getByPlaceholder("email@example.com").fill(credentials.email);
    await page.getByPlaceholder("email@example.com").press("Tab");
    await page.getByPlaceholder("Password").fill(credentials.password);
    await page.getByRole("button", { name: "Sign In" }).click();

    return credentials;
}

export async function addAddress(page) {
    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Profile" }).click();
    await page.getByRole("link", { name: " Address " }).click();
    await page.getByRole("link", { name: "Add Address" }).click();
    await page.getByPlaceholder("Company Name").click();
    await page.getByPlaceholder("Company Name").fill(generateName());
    await page.getByPlaceholder("Company Name").press("Tab");
    await page.getByPlaceholder("First Name").fill(generateFirstName());
    await page.getByPlaceholder("First Name").press("Tab");
    await page.getByPlaceholder("Last Name").fill(generateLastName());
    await page.getByPlaceholder("Last Name").press("Tab");
    await page
        .getByPlaceholder("Email", { exact: true })
        .fill("test@example.com");
    await page.getByPlaceholder("Email", { exact: true }).press("Tab");
    await page.getByPlaceholder("Vat ID").press("Tab");
    await page.getByPlaceholder("Street Address").fill("Demo");
    await page.getByPlaceholder("Street Address").press("Tab");
    await page.getByLabel("Country").selectOption("DZ");
    await page.getByPlaceholder("State").click();
    await page.getByPlaceholder("State").fill("any");
    await page.getByPlaceholder("City").click();
    await page.getByPlaceholder("City").fill("any");
    await page.getByPlaceholder("Post Code").click();
    await page.getByPlaceholder("Post Code").fill("123456");
    await page.getByPlaceholder("Phone").click();
    await page.getByPlaceholder("Phone").fill("9876543210");
    await page
        .locator("#main form div")
        .filter({ hasText: "Set as Default" })
        .locator("label")
        .first()
        .click();
    await page
        .locator("#main form div")
        .filter({ hasText: "Set as Default" })
        .locator("label")
        .first()
        .click();
    await page.getByRole("button", { name: "Save" }).click();

    await expect(
        page.getByText("Address have been successfully added.").first()
    ).toBeVisible();
}

export async function addWishlist(page) {
    await page.locator(".action-items > span").first().click();
    await page
        .locator(
            "div:nth-child(9) > div:nth-child(2) > div > .-mt-9 > .action-items > span"
        )
        .first()
        .click();

    await expect(
        page.getByText("Item Successfully Added To Wishlist").first()
    ).toBeVisible();
}
