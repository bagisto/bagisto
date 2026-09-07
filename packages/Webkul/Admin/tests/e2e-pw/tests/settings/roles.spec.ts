import { test } from "../../setup";
import { RolesPage, type RoleData } from "../../pages/admin/settings/RolesPage";
import { UsersPage } from "../../pages/admin/settings/UsersPage";
import {
    generateDescription,
    generateName,
    uniqueStamp,
} from "../../utils/faker";

function buildRole(): RoleData {
    return {
        name: `${generateName()} ${uniqueStamp()}`,
        description: generateDescription(),
    };
}

test.describe("role management", () => {
    let rolesPage: RolesPage;
    let usersPage: UsersPage;
    let createdRoles: string[];
    let createdUsers: string[];

    test.beforeEach(async ({ adminPage }) => {
        rolesPage = new RolesPage(adminPage);
        usersPage = new UsersPage(adminPage);
        createdRoles = [];
        createdUsers = [];
    });

    test.afterEach(async () => {
        try {
            await usersPage.deleteUsersIfPresent(createdUsers);
        } finally {
            await rolesPage.deleteRolesIfPresent(createdRoles);
        }
    });

    test("should create a role and list it with its permission type", async () => {
        const role = buildRole();
        createdRoles.push(role.name);

        await rolesPage.createRole(role);

        await rolesPage.expectRoleListed(role.name, "all");
    });

    test("should reject a role without a name and description", async () => {
        await rolesPage.submitEmptyCreateForm();

        await rolesPage.expectValidationError("The Name field is required");
        await rolesPage.expectValidationError(
            "The Description field is required",
        );
        await rolesPage.expectStillOnCreateForm();
    });

    test("should rename a role and keep the new name after reload", async () => {
        const role = buildRole();
        const newName = `${generateName()} ${uniqueStamp()}`;
        createdRoles.push(role.name, newName);

        await rolesPage.createRole(role);
        await rolesPage.renameRole(role.name, newName);

        await rolesPage.expectRoleListed(newName, "all");
        await rolesPage.expectRoleAbsent(role.name);
        await rolesPage.expectNameInEditForm(newName);
    });

    test("should delete a role and remove it from the grid", async () => {
        const role = buildRole();
        const untouched = buildRole();
        createdRoles.push(role.name, untouched.name);

        await rolesPage.createRole(role);
        await rolesPage.createRole(untouched);
        await rolesPage.deleteRole(role.name);

        await rolesPage.expectRoleAbsent(role.name);
        await rolesPage.expectRoleListed(untouched.name, "all");
    });

    test("should refuse to delete a role that is assigned to a user", async () => {
        const role = buildRole();
        const stamp = uniqueStamp();
        const userEmail = `role-user-${stamp}@example.com`;
        createdRoles.push(role.name);
        createdUsers.push(userEmail);

        await rolesPage.createRole(role);
        await usersPage.createUser({
            name: `Role User ${stamp}`,
            email: userEmail,
            password: "user12345",
            role: role.name,
            active: true,
        });
        await rolesPage.attemptDeleteRole(role.name);

        await rolesPage.expectErrorMessage(
            "Role is already used in Admin User",
        );
        await rolesPage.expectRoleListed(role.name, "all");
    });
});
