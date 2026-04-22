import { test } from "../../setup";
import { expect } from "@playwright/test";
import { AttributeFamilyCreatePage } from "../../pages/admin/catalog/attribute-families/AttributeFamilyCreatePage";
import { AttributeFamilyDeletePage } from "../../pages/admin/catalog/attribute-families/AttributeFamilyDeletePage";
import { AttributeFamilyEditPage } from "../../pages/admin/catalog/attribute-families/AttributeFamilyEditPage";

test.describe("attribute family management", () => {
    test("should be able to create attribute family", async ({ adminPage }) => {
        await new AttributeFamilyCreatePage(adminPage).createAttributeFamily();
        await expect(
            adminPage.getByText("Family created successfully.").first(),
        ).toBeVisible();
    });

    test("should be able to edit attribute family", async ({ adminPage }) => {
        await new AttributeFamilyEditPage(adminPage).editAttributeFamily();
        await expect(
            adminPage.getByText("Family updated successfully.").first(),
        ).toBeVisible();
    });

    test("should be able to delete attribute family", async ({ adminPage }) => {
        await new AttributeFamilyDeletePage(adminPage).deleteAttributeFamily();
        await expect(
            adminPage.getByText("Family deleted successfully.").first(),
        ).toBeVisible();
    });
});
