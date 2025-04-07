import { test, expect, config } from "../setup";

test("should search by query", async ({ page }) => {
    await page.goto(`${config.baseUrl}`);

    await page.getByLabel("Search products here").click();
    await page.getByLabel("Search products here").fill("arct");
    await page.getByLabel("Search products here").press("Enter");

    await expect(
        page.getByText("Search results for : arct").first()
    ).toBeVisible();
});
