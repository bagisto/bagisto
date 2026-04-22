import { test } from "../../setup";
import { CategoryCreatePage } from "../../pages/admin/catalog/categories/CategoryCreatePage";
import { CategoryDeletePage } from "../../pages/admin/catalog/categories/CategoryDeletePage";
import { CategoryEditPage } from "../../pages/admin/catalog/categories/CategoryEditPage";

test.describe("category management", () => {
    test("should create a category", async ({ adminPage }) => {
        await new CategoryCreatePage(adminPage).createCategory();
    });

    test("should edit a category", async ({ adminPage }) => {
        await new CategoryEditPage(adminPage).editCategory();
    });

    test("should delete a category", async ({ adminPage }) => {
        await new CategoryCreatePage(adminPage).createCategory();
        await new CategoryDeletePage(adminPage).deleteCategory();
    });

    test("should mass update a categories", async ({ adminPage }) => {
        await new CategoryEditPage(adminPage).massUpdateCategories();
    });

    test("should mass delete a categories", async ({ adminPage }) => {
        await new CategoryCreatePage(adminPage).createCategory();
        await new CategoryDeletePage(adminPage).massDeleteCategories();
    });
});
