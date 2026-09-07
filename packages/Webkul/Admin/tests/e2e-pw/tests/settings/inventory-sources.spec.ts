import { test } from "../../setup";
import {
    InventorySourcesPage,
    type InventorySourceData,
} from "../../pages/admin/settings/InventorySourcesPage";
import {
    generateEmail,
    generateFullName,
    generateName,
    generatePhoneNumber,
    generateSlug,
    uniqueStamp,
} from "../../utils/faker";

function buildInventorySource(
    overrides: Partial<InventorySourceData> = {},
): InventorySourceData {
    return {
        code: generateSlug("_"),
        name: `${generateName()} ${uniqueStamp()}`,
        contactName: generateFullName(),
        contactEmail: generateEmail(),
        contactNumber: generatePhoneNumber(),
        street: "Sector 10 Dwarka",
        city: "New Delhi",
        postcode: "110045",
        ...overrides,
    };
}

test.describe("inventory source management", () => {
    let inventorySourcesPage: InventorySourcesPage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        inventorySourcesPage = new InventorySourcesPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await inventorySourcesPage.deleteInventorySourcesIfPresent(created);
    });

    test("should create an inventory source and list it as active", async () => {
        const source = buildInventorySource();
        created.push(source.name);

        await inventorySourcesPage.createInventorySource(source);

        await inventorySourcesPage.expectInventorySourceListed(source);
    });

    test("should reject an inventory source without a code and name", async () => {
        await inventorySourcesPage.submitEmptyCreateForm();

        await inventorySourcesPage.expectValidationError(
            "The Code field is required",
        );
        await inventorySourcesPage.expectValidationError(
            "The Name field is required",
        );
        await inventorySourcesPage.expectStillOnCreateForm();
    });

    test("should reject an inventory source whose code is already used", async () => {
        const existing = buildInventorySource();
        const duplicate = buildInventorySource({ code: existing.code });
        created.push(existing.name, duplicate.name);

        await inventorySourcesPage.createInventorySource(existing);
        await inventorySourcesPage.attemptCreateInventorySource(duplicate);

        await inventorySourcesPage.expectValidationError(
            "The code has already been taken.",
        );
        await inventorySourcesPage.expectInventorySourceAbsent(duplicate.name);
        await inventorySourcesPage.expectInventorySourceCodeListedOnce(
            existing.code,
        );
    });

    test("should rename an inventory source and keep the new name after reload", async () => {
        const source = buildInventorySource();
        const newName = `${generateName()} ${uniqueStamp()}`;
        created.push(source.name, newName);

        await inventorySourcesPage.createInventorySource(source);
        await inventorySourcesPage.renameInventorySource(source.name, newName);

        await inventorySourcesPage.expectInventorySourceListed({
            name: newName,
            code: source.code,
        });
        await inventorySourcesPage.expectInventorySourceAbsent(source.name);
        await inventorySourcesPage.expectNameInEditForm(newName);
    });

    test("should delete an inventory source and remove it from the grid", async () => {
        const source = buildInventorySource();
        const untouched = buildInventorySource();
        created.push(source.name, untouched.name);

        await inventorySourcesPage.createInventorySource(source);
        await inventorySourcesPage.createInventorySource(untouched);
        await inventorySourcesPage.deleteInventorySource(source.name);

        await inventorySourcesPage.expectInventorySourceAbsent(source.name);
        await inventorySourcesPage.expectInventorySourceListed(untouched);
    });
});
