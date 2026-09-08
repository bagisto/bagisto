import { test } from "../../setup";
import {
    CategoryPage,
    type CategoryData,
} from "../../pages/admin/catalog/categories/CategoryPage";
import { generateName, uniqueStamp } from "../../utils/faker";

function buildCategory(): CategoryData {
    const name = `${generateName()} ${uniqueStamp()}`;

    return {
        name,
        slug: name.toLowerCase().replace(/[^a-z0-9]+/g, "-"),
    };
}

test.describe("category management", () => {
    let categoryPage: CategoryPage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        categoryPage = new CategoryPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await categoryPage.deleteCategoriesIfPresent(created);
    });

    test("should create a category and list it as active in the grid", async () => {
        const category = buildCategory();
        created.push(category.name);

        await categoryPage.createCategory(category);

        await categoryPage.expectCategoryListed(category.name, "Active");
    });

    test("should reject a category without a name and slug", async () => {
        await categoryPage.submitEmptyCreateForm();

        await categoryPage.expectValidationError("The Name field is required");
        await categoryPage.expectValidationError("The Slug field is required");
        await categoryPage.expectStillOnCreateForm();
    });

    test("should reject a category whose slug is already used", async () => {
        const existing = buildCategory();
        const duplicate = { ...buildCategory(), slug: existing.slug };
        created.push(existing.name, duplicate.name);

        await categoryPage.createCategory(existing);
        await categoryPage.attemptCreateCategory(duplicate);

        await categoryPage.expectErrorMessage(
            "This slug is getting used in either categories or products.",
        );
        await categoryPage.expectCategoryAbsent(duplicate.name);
        await categoryPage.expectCategoryListed(existing.name, "Active");
    });

    test("should rename a category and keep the new name after reload", async () => {
        const category = buildCategory();
        const newName = `${generateName()} ${uniqueStamp()}`;
        created.push(category.name, newName);

        await categoryPage.createCategory(category);
        await categoryPage.renameCategory(category.name, newName);

        await categoryPage.expectCategoryListed(newName, "Active");
        await categoryPage.expectCategoryAbsent(category.name);
        await categoryPage.expectNameInEditForm(newName);
    });

    test("should delete a category and remove it from the grid", async () => {
        const category = buildCategory();
        const untouched = buildCategory();
        created.push(category.name, untouched.name);

        await categoryPage.createCategory(category);
        await categoryPage.createCategory(untouched);
        await categoryPage.deleteCategory(category.name);

        await categoryPage.expectCategoryAbsent(category.name);
        await categoryPage.expectCategoryListed(untouched.name, "Active");
    });

    test("should refuse to delete the root category", async () => {
        await categoryPage.attemptDeleteCategory("Root");

        await categoryPage.expectErrorMessage(
            "The Root category can not be deleted.",
        );
        await categoryPage.expectRootCategoryListed();
    });

    test("should deactivate selected categories through the mass action", async () => {
        const first = buildCategory();
        const second = buildCategory();
        const untouched = buildCategory();
        created.push(first.name, second.name, untouched.name);

        await categoryPage.createCategory(first);
        await categoryPage.createCategory(second);
        await categoryPage.createCategory(untouched);
        await categoryPage.massUpdateStatus(
            [first.name, second.name],
            "Inactive",
        );

        await categoryPage.expectCategoryListed(first.name, "Inactive");
        await categoryPage.expectCategoryListed(second.name, "Inactive");
        await categoryPage.expectCategoryListed(untouched.name, "Active");
    });

    test("should mass delete only the selected categories", async () => {
        const first = buildCategory();
        const second = buildCategory();
        const untouched = buildCategory();
        created.push(first.name, second.name, untouched.name);

        await categoryPage.createCategory(first);
        await categoryPage.createCategory(second);
        await categoryPage.createCategory(untouched);
        await categoryPage.massDeleteCategories([first.name, second.name]);

        await categoryPage.expectCategoryAbsent(first.name);
        await categoryPage.expectCategoryAbsent(second.name);
        await categoryPage.expectCategoryListed(untouched.name, "Active");
    });
});
