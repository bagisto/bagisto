import type { Page } from "@playwright/test";
import { expect } from "../setup";
import {
    generateName,
    generateFirstName,
    generateLastName,
    generateEmail,
} from "./faker";

export interface CustomerCredentials {
    firstName: string;
    lastName: string;
    email: string;
    password: string;
}

function textLabel(page: Page, forId: string) {
    return page.locator(`label[for="${forId}"]`).filter({ hasText: /\S/ });
}

export async function register(page: Page): Promise<CustomerCredentials> {
    const credentials = {
        firstName: generateFirstName(),
        lastName: generateLastName(),
        email: generateEmail(),
        password: "admin123",
    };

    await page.goto("customer/register");
    await page.getByPlaceholder("First Name").fill(credentials.firstName);
    await page.getByPlaceholder("Last Name").fill(credentials.lastName);
    await page.getByPlaceholder("email@example.com").fill(credentials.email);
    await page
        .getByPlaceholder("Password", { exact: true })
        .fill(credentials.password);
    await page.getByPlaceholder("Confirm Password").fill(credentials.password);

    const agreement = textLabel(page, "agreement");

    if (await agreement.count()) {
        await agreement.click();

        await expect(page.locator("input#agreement")).toBeChecked();
    }

    await page.getByRole("button", { name: "Register" }).click();

    await expect(page.getByText("Account created successfully.").first()).toBeVisible();

    return credentials;
}

export async function loginAsCustomer(page: Page): Promise<CustomerCredentials> {
    const credentials = await register(page);

    await page.goto("customer/login");
    await page.getByPlaceholder("email@example.com").fill(credentials.email);
    await page.getByPlaceholder("Password").fill(credentials.password);
    await page.getByRole("button", { name: "Sign In" }).click();

    await page.goto("customer/account/profile");
    await expect(page).toHaveURL(/customer\/account\/profile/);

    return credentials;
}

export async function addAddress(page: Page): Promise<void> {
    await page.goto("customer/account/addresses/create");
    await page.getByPlaceholder("Company Name").fill(generateName());
    await page.getByPlaceholder("First Name").fill(generateFirstName());
    await page.getByPlaceholder("Last Name").fill(generateLastName());
    await page.getByPlaceholder("Email", { exact: true }).fill(generateEmail());
    await page.getByPlaceholder("Street Address").fill("Demo");
    await page.getByLabel("Country").selectOption("DZ");
    await page.getByPlaceholder("State").fill("any");
    await page.getByPlaceholder("City").fill("any");
    await page.getByPlaceholder("Post Code").fill("123456");
    await page.getByPlaceholder("Phone").fill("9876543210");
    await textLabel(page, "default_address").click();

    await expect(page.locator("input#default_address")).toBeChecked();

    await page.getByRole("button", { name: "Save" }).click();

    await expect(
        page.getByText("Address have been successfully added.").first(),
    ).toBeVisible();
}
