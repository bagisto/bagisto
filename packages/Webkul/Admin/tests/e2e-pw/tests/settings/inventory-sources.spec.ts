import { test, expect } from "../../setup";
import { InventorySourcesPage } from "../../pages/admin/settings/InventorySourcesPage";

test.describe("inventory source management", () => {
    test("should create a inventory source", async ({ adminPage }) => {
        const inventorySourcesPage = new InventorySourcesPage(adminPage);
        await inventorySourcesPage.createInventorySource();
    });

    test("should edit a inventory source", async ({ adminPage }) => {
        const inventorySourcesPage = new InventorySourcesPage(adminPage);
        await inventorySourcesPage.editFirstInventorySource();
    });

    test("should delete a inventory source", async ({ adminPage }) => {
        const inventorySourcesPage = new InventorySourcesPage(adminPage);
        await inventorySourcesPage.deleteFirstInventorySource();
    });
});
