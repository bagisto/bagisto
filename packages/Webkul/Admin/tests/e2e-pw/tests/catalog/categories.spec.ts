import { test } from "../../setup";
import { expect } from "@playwright/test";
import { CategoryCreatePage } from "../../pages/admin/catalog/categories/CategoryCreatePage";
import { CategoryDeletePage } from "../../pages/admin/catalog/categories/CategoryDeletePage";
import { CategoryEditPage } from "../../pages/admin/catalog/categories/CategoryEditPage";

test.describe("category management", () => {
    test("should create a category", async ({ adminPage }) => {
        await new CategoryCreatePage(adminPage).createCategory();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Category created successfully.",
            }),
        ).toBeVisible();
    });

    test("should edit a category", async ({ adminPage }) => {
        await new CategoryEditPage(adminPage).editCategory();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Category updated successfully.",
            }),
        ).toBeVisible();
    });

    test("should delete a category", async ({ adminPage }) => {
        await new CategoryCreatePage(adminPage).createCategory();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Category created successfully.",
            }),
        ).toBeVisible();
        
        await new CategoryDeletePage(adminPage).deleteCategory();
        await expect(
            adminPage.locator("#app p", {
                hasText: "The category has been successfully deleted.",
            }),
        ).toBeVisible();
    });

    test("should mass update a categories", async ({ adminPage }) => {
        await new CategoryEditPage(adminPage).massUpdateCategories();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Category updated successfully.",
            }),
        ).toBeVisible();
    });

    test("should mass delete a categories", async ({ adminPage }) => {
        await new CategoryCreatePage(adminPage).createCategory();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Category created successfully.",
            }),
        ).toBeVisible();

        await new CategoryDeletePage(adminPage).massDeleteCategories();
        await expect(
            adminPage.locator("#app p", {
                hasText: "The category has been successfully deleted.",
            }),
        ).toBeVisible();
    });
});
