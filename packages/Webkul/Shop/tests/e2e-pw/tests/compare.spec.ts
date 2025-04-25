import { test, expect } from "../setup";

test("should add product to compare page", async ({ page }) => {
    await page.goto("");

    await page
        .locator("div:nth-child(2) > .-mt-9 > .action-items > .icon-compare")
        .first()
        .click();
    await page.locator(".action-items > .icon-compare").first().click();
    await page
        .locator("div:nth-child(3) > .-mt-9 > .action-items > .icon-compare")
        .first()
        .click();

    await expect(
        page.getByText("Item added successfully to compare list").first()
    ).toBeVisible();
});

test("should remove product from the compare page", async ({ page }) => {
    await page.goto("");

    await page
        .locator("div:nth-child(2) > .-mt-9 > .action-items > .icon-compare")
        .first()
        .click();
    await page.locator(".action-items > .icon-compare").first().click();
    await page.locator("div:nth-child(3) > .-mt-9 > div").first().click();

    await page.getByRole("link", { name: "Compare" }).click();
    await page.locator(".relative > .icon-cancel").first().click();
    await page.getByRole("button", { name: "Agree", exact: true }).click();
});

test("should remove all products from the compare page", async ({ page }) => {
    await page.goto("");
    
    await page
        .locator("div:nth-child(2) > .-mt-9 > .action-items > .icon-compare")
        .first()
        .click();
    await page.locator(".action-items > .icon-compare").first().click();
    await page.locator("div:nth-child(3) > .-mt-9 > div").first().click();

    await page.getByRole("link", { name: "Compare" }).click();
    await page.getByText("Delete All", { exact: true }).click();
    await page.getByRole("button", { name: "Agree", exact: true }).click();

    await expect(
        page.getByText("All items removed successfully.").first()
    ).toBeVisible();
});
