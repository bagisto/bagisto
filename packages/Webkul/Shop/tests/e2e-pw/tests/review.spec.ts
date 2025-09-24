import { test, expect } from "../setup";
import { loginAsCustomer } from "../utils/customer";
import { generateName, generateDescription } from "../utils/faker";

test("should review a product", async ({ page }) => {
    await loginAsCustomer(page);

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .getByLabel("Arctic Touchscreen Winter")
        .click();
    await page.getByRole("button", { name: "Reviews" }).click();
    await page.waitForSelector("#review-tab");
    await page.locator("#review-tab").getByText("Write a Review").click();
    await page.locator("#review-tab span").nth(3).click();
    await page.locator("#review-tab span").nth(4).click();
    await page.getByPlaceholder("Title").click();
    await page.getByPlaceholder("Title").fill(generateName());
    await page.getByPlaceholder("Comment").click();
    await page.getByPlaceholder("Comment").fill(generateDescription());
    await page.getByRole("button", { name: "Submit Review" }).click();

    await expect(
        page.getByText("Review submitted successfully.").first()
    ).toBeVisible();
});
