import { test } from "../../setup";
import {
    AttributeFamilyPage,
    type AttributeFamilyData,
} from "../../pages/admin/catalog/attribute-families/AttributeFamilyPage";
import { generateName, generateSlug, uniqueStamp } from "../../utils/faker";

function buildFamily(
    overrides: Partial<AttributeFamilyData> = {},
): AttributeFamilyData {
    return {
        code: generateSlug("_"),
        name: `${generateName()} ${uniqueStamp()}`,
        ...overrides,
    };
}

test.describe("attribute family management", () => {
    let familyPage: AttributeFamilyPage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        familyPage = new AttributeFamilyPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await familyPage.deleteFamiliesIfPresent(created);
    });

    test("should create an attribute family and list it with its code", async () => {
        const family = buildFamily();
        created.push(family.name);

        await familyPage.createFamily(family);

        await familyPage.expectFamilyListed(family);
    });

    test("should reject an attribute family without a code and name", async () => {
        await familyPage.submitEmptyCreateForm();

        await familyPage.expectValidationError("The Code field is required");
        await familyPage.expectValidationError("The Name field is required");
        await familyPage.expectStillOnCreateForm();
    });

    test("should reject an attribute family whose code is already used", async () => {
        const existing = buildFamily();
        const duplicate = buildFamily({ code: existing.code });
        created.push(existing.name, duplicate.name);

        await familyPage.createFamily(existing);
        await familyPage.attemptCreateFamily(duplicate);

        await familyPage.expectValidationError(
            "The code has already been taken.",
        );
        await familyPage.expectFamilyAbsent(duplicate.name);
        await familyPage.expectFamilyCodeListedOnce(existing.code);
    });

    test("should rename an attribute family and keep the new name after reload", async () => {
        const family = buildFamily();
        const newName = `${generateName()} ${uniqueStamp()}`;
        created.push(family.name, newName);

        await familyPage.createFamily(family);
        await familyPage.renameFamily(family.name, newName);

        await familyPage.expectFamilyListed({ ...family, name: newName });
        await familyPage.expectFamilyAbsent(family.name);
        await familyPage.expectNameInEditForm(newName);
    });

    test("should delete an attribute family and remove it from the grid", async () => {
        const family = buildFamily();
        const untouched = buildFamily();
        created.push(family.name, untouched.name);

        await familyPage.createFamily(family);
        await familyPage.createFamily(untouched);
        await familyPage.deleteFamily(family.name);

        await familyPage.expectFamilyAbsent(family.name);
        await familyPage.expectFamilyListed(untouched);
    });

    test("should refuse to delete the default attribute family", async () => {
        await familyPage.attemptDeleteFamily("default");

        await familyPage.expectErrorMessage(
            "The default attribute family can not be deleted.",
        );
        await familyPage.expectDefaultFamilyListed();
    });
});
