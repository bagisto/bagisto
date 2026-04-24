import { test, expect } from "../../setup";
import { generateFullName, generateEmail } from "../../utils/faker";

test.describe("user management", () => {
    test("should create a user", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/users");
        await adminPage.getByRole("button", { name: "Create User" }).click();
        await adminPage.locator('input[name="name"]').fill(generateFullName());
        await adminPage.locator('input[name="email"]').fill(generateEmail());
        await adminPage.locator('input[name="password"]').fill("admin123");
        await adminPage.locator('input[name="password_confirmation"]').fill("admin123");
        await adminPage.locator('select[name="role_id"]').selectOption("1");
        await adminPage.click('label[for="status"]');
        const toggleInput = await adminPage.locator('input[name="status"]');
        await expect(toggleInput).toBeChecked();
        await adminPage.getByRole("button", { name: "Save User" }).click();

        await expect(
            adminPage.getByText("User created successfully.")
        ).toBeVisible();
    });

    test("should edit a users", async ({ adminPage }) => {
        const updatedName = generateFullName();
        const updatedEmail = generateEmail();
        await adminPage.goto("admin/settings/users");
        await adminPage.waitForSelector("span.cursor-pointer.icon-edit", {
            state: "visible",
        });
        const iconEdit = await adminPage.$$("span.cursor-pointer.icon-edit");
        await iconEdit[0].click();
        await adminPage.locator('input[name="name"]').fill(updatedName);
        await adminPage.locator('input[name="email"]').fill(updatedEmail);
        await adminPage.getByRole("button", { name: "Save User" }).click();

        await expect(
            adminPage.getByText("User updated successfully.")
        ).toBeVisible();

        await expect(adminPage.getByText(updatedName)).toBeVisible();

        await expect(adminPage.getByText(updatedEmail)).toBeVisible();
    });

    test("should delete a user", async ({ adminPage }) => {
        await adminPage.goto("admin/settings/users");
        await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
        const iconDelete = await adminPage.$$("span.cursor-pointer.icon-delete");
        await iconDelete[0].click();

        await adminPage.waitForSelector("text=Are you sure");
        const agreeButton = await adminPage.locator('button.primary-button:has-text("Agree")');

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            adminPage.getByText("User deleted successfully.")
        ).toBeVisible();
    });
});
