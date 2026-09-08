import { test } from "../../setup";
import {
    CustomerGroupsPage,
    type CustomerGroupData,
} from "../../pages/admin/customers/CustomerGroupsPage";
import { generateName, uniqueStamp } from "../../utils/faker";

function buildGroup(): CustomerGroupData {
    const name = `${generateName()} ${uniqueStamp()}`;

    return {
        name,
        code: name.toLowerCase().replace(/[^a-z0-9]+/g, "_"),
    };
}

test.describe("group management", () => {
    let groupsPage: CustomerGroupsPage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        groupsPage = new CustomerGroupsPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await groupsPage.deleteGroupsIfPresent(created);
    });

    test("should create a group and list it with its code", async () => {
        const group = buildGroup();
        created.push(group.name);

        await groupsPage.createGroup(group);

        await groupsPage.expectGroupListed(group);
    });

    test("should reject a group without a name and code", async () => {
        await groupsPage.submitEmptyCreateForm();

        await groupsPage.expectValidationError("The Name field is required");
        await groupsPage.expectValidationError("The Code field is required");
    });

    test("should reject a group whose code is already used", async () => {
        const existing = buildGroup();
        const duplicate = { ...buildGroup(), code: existing.code };
        created.push(existing.name, duplicate.name);

        await groupsPage.createGroup(existing);
        await groupsPage.attemptCreateGroup(duplicate);

        await groupsPage.expectValidationError(
            "The code has already been taken.",
        );
        await groupsPage.expectGroupAbsent(duplicate.name);
        await groupsPage.expectGroupCodeListedOnce(existing.code);
    });

    test("should rename a group and keep the new name after reload", async () => {
        const group = buildGroup();
        const newName = `${generateName()} ${uniqueStamp()}`;
        created.push(group.name, newName);

        await groupsPage.createGroup(group);
        await groupsPage.renameGroup(group.name, newName);

        await groupsPage.expectGroupListed({ name: newName, code: group.code });
        await groupsPage.expectGroupAbsent(group.name);
        await groupsPage.expectNameInEditForm(newName);
    });

    test("should delete a group and remove it from the grid", async () => {
        const group = buildGroup();
        const untouched = buildGroup();
        created.push(group.name, untouched.name);

        await groupsPage.createGroup(group);
        await groupsPage.createGroup(untouched);
        await groupsPage.deleteGroup(group.name);

        await groupsPage.expectGroupAbsent(group.name);
        await groupsPage.expectGroupListed(untouched);
    });

    test("should refuse to delete a default group", async () => {
        await groupsPage.attemptDeleteGroup("General");

        await groupsPage.expectErrorMessage("Default Group Can not be Deleted");
        await groupsPage.expectDefaultGroupListed("General");
    });
});
