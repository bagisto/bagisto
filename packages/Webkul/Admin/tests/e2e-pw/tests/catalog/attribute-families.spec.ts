import { test } from "../../setup";
import { AttributeFamilyCreatePage } from "../../pages/admin/catalog/attribute-families/AttributeFamilyCreatePage";
import { AttributeFamilyDeletePage } from "../../pages/admin/catalog/attribute-families/AttributeFamilyDeletePage";
import { AttributeFamilyEditPage } from "../../pages/admin/catalog/attribute-families/AttributeFamilyEditPage";

test.describe("attribute family management", () => {
    test("should be able to create attribute family", async ({ adminPage }) => {
        await new AttributeFamilyCreatePage(adminPage).createAttributeFamily();
    });

    test("should be able to edit attribute family", async ({ adminPage }) => {
        await new AttributeFamilyEditPage(adminPage).editAttributeFamily();
    });

    test("should be able to delete attribute family", async ({ adminPage }) => {
        await new AttributeFamilyDeletePage(adminPage).deleteAttributeFamily();
    });
});
