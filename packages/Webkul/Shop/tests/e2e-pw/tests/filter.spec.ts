import { test, expect } from "../setup";

test("should be able to filter A-Z products", async ({ page }) => {
    await page.goto("mens");
    await page.getByRole("button", { name: "Expensive First " }).click();
    await page.getByRole("listitem").filter({ hasText: "From A-Z" }).click();
    const productNames = await page.$$eval("p.break-all", (elements) =>
        elements.map((el) => el.textContent?.trim() || ""),
    );
    const sortedNames = [...productNames].sort((a, b) => a.localeCompare(b));

    expect(productNames).toEqual(sortedNames);
});

test("should be able to filter Z-A products", async ({ page }) => {
    await page.goto("mens");
    await page.getByRole("button", { name: "Expensive First " }).click();
    await page.getByRole("listitem").filter({ hasText: "From Z-A" }).click();
    const productNames = await page.$$eval("p.break-all", (elements) =>
        elements.map((el) => el.textContent?.trim() || ""),
    );
    const sortedNamesDesc = [...productNames].sort((a, b) =>
        b.localeCompare(a),
    );
    
    expect(productNames).toEqual(sortedNamesDesc);
});

test("should be able to Expensive First filter on products", async ({
    page,
}) => {
    await page.goto("mens");
    await page.getByRole("button", { name: "Expensive First " }).click();
    await page
        .getByRole("listitem")
        .filter({ hasText: "Expensive First" })
        .click();
    const prices = await page.$$eval("p.final-price", (elements) =>
        elements.map((el) => {
            const text = el.textContent?.replace(/[^0-9.]/g, "") || "0";
            return parseFloat(text);
        }),
    );
    const sortedPrices = [...prices].sort((a, b) => b - a);

    expect(prices).toEqual(sortedPrices);
});

test("should be able to cheaper First filter on products", async ({ page }) => {
    await page.goto("mens");
    await page.getByRole("button", { name: "Expensive First " }).click();
    await page
        .getByRole("listitem")
        .filter({ hasText: "Cheapest First" })
        .click();
    const prices = await page.$$eval("p.final-price", (elements) =>
        elements.map((el) => {
            const text = el.textContent?.replace(/[^0-9.]/g, "") || "0";
            return parseFloat(text);
        }),
    );
    const sortedPrices = [...prices].sort((a, b) => a - b);
    
    expect(prices).toEqual(sortedPrices);
});
