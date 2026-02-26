import { test, expect } from "../setup";

test("should be able to filter A-Z products", async ({ page }) => {
    await page.goto("");
    await page.getByText("MenWinter Wear").hover();
    await page.getByRole("link", { name: "Winter Wear" }).click();
    await page.getByRole("button", { name: "Expensive First " }).click();
    await page.getByRole("listitem").filter({ hasText: "From A-Z" }).click();

    /**
     * Select all product name elements
     */
    const productNames = await page.$$eval("p.break-all", (elements) =>
        elements.map((el) => el.textContent?.trim() || "")
    );

    /**
     * Create a sorted copy of the product names
     */
    const sortedNames = [...productNames].sort((a, b) => a.localeCompare(b));

    /**
     * Compare original with sorted
     */
    expect(productNames).toEqual(sortedNames);
});

test("should be able to filter Z-A products", async ({ page }) => {
    await page.goto("");
    await page.getByText("MenWinter Wear").hover();
    await page.getByRole("link", { name: "Winter Wear" }).click();
    await page.getByRole("button", { name: "Expensive First " }).click();
    await page.getByRole("listitem").filter({ hasText: "From Z-A" }).click();

    /**
     * Select all product name elements
     */
    const productNames = await page.$$eval("p.break-all", (elements) =>
        elements.map((el) => el.textContent?.trim() || "")
    );

    /**
     * Create a sorted copy of the product names
     */
    const sortedNamesDesc = [...productNames].sort((a, b) =>
        b.localeCompare(a)
    );

    // Compare actual list with sorted list
    expect(productNames).toEqual(sortedNamesDesc);
});

test("should be able to Expensive First filter on products", async ({ page }) => {
    await page.goto("");
    await page.getByText("MenWinter Wear").hover();
    await page.getByRole("link", { name: "Winter Wear" }).click();
    await page.getByRole("button", { name: "Expensive First " }).click();
    await page
        .getByRole("listitem")
        .filter({ hasText: "Expensive First" })
        .click();

    /**
     * Select all product price
     */    
    const prices = await page.$$eval("p.final-price", (elements) =>
        elements.map((el) => {
            const text = el.textContent?.replace(/[^0-9.]/g, "") || "0"; // remove $ or other chars
            return parseFloat(text);
        })
    );

    /**
     * Create a sorted (descending) copy of the prices
     */
    const sortedPrices = [...prices].sort((a, b) => b - a);
    expect(prices).toEqual(sortedPrices);
});

test("should be able to cheaper First filter on products", async ({ page }) => {
    await page.goto("");
    await page.getByText("MenWinter Wear").hover();
    await page.getByRole("link", { name: "Winter Wear" }).click();
    await page.getByRole("button", { name: "Expensive First " }).click();
    await page.getByRole('listitem').filter({ hasText: 'Cheapest First' }).click();

    /**
     * Select all product price
     */    
    const prices = await page.$$eval('p.final-price', elements =>
        elements.map(el => {
          const text = el.textContent?.replace(/[^0-9.]/g, '') || '0';
          return parseFloat(text);
        })
      );
    
      // Sort the prices in ascending order (low to high)
      const sortedPrices = [...prices].sort((a, b) => a - b);
    
      // Assert that prices are sorted correctly
      expect(prices).toEqual(sortedPrices);
});
