import type { Browser, BrowserContext, Page } from "@playwright/test";
import { test, withTinymce } from "../../../setup";
import { RestrictedAdminPage } from "../../../pages/admin/acl/RestrictedAdminPage";
import { CmsPage, type CmsPageData } from "../../../pages/admin/cms/CmsPage";
import {
    CustomerGroupsPage,
    type CustomerGroupData,
} from "../../../pages/admin/customers/CustomerGroupsPage";
import {
    RolesPage,
    type RoleData,
} from "../../../pages/admin/settings/RolesPage";
import {
    UsersPage,
    type AdminUserData,
} from "../../../pages/admin/settings/UsersPage";
import {
    generateDescription,
    generateFullName,
    generateName,
    generateSlug,
    uniqueStamp,
} from "../../../utils/faker";

function buildRole(permission?: string): RoleData {
    const stamp = uniqueStamp();

    return {
        name: `Role ${stamp}`,
        description: `Role ${stamp} created by the acl suite.`,
        permissionType: permission ? "custom" : "all",
        permissions: permission ? [permission] : undefined,
    };
}

function buildUser(roleName: string): AdminUserData {
    const stamp = uniqueStamp();

    return {
        name: `${generateFullName()} ${stamp}`,
        email: `acl-${stamp}@example.com`,
        password: "user12345",
        role: roleName,
        active: true,
    };
}

function buildCmsPage(): CmsPageData {
    return {
        title: `${generateName()} ${uniqueStamp()}`,
        urlKey: generateSlug(),
        content: generateDescription(),
    };
}

function buildGroup(): CustomerGroupData {
    const stamp = uniqueStamp();

    return {
        name: `Group ${stamp}`,
        code: `group_${stamp}`,
    };
}

test.describe("role action permissions", () => {
    test.describe.configure({ timeout: 180000 });

    let rolesPage: RolesPage;
    let usersPage: UsersPage;
    let role: RoleData;
    let user: AdminUserData;
    let context: BrowserContext;
    let restrictedPage: Page;

    async function signInWithPermission(
        browser: Browser,
        permission: string,
    ): Promise<void> {
        role = buildRole(permission);
        user = buildUser(role.name);

        await rolesPage.createRole(role);
        await usersPage.createUser(user);

        context = await browser.newContext();
        restrictedPage = withTinymce(await context.newPage());

        await new RestrictedAdminPage(restrictedPage).login(
            user.email,
            user.password,
        );
    }

    test.beforeEach(async ({ adminPage }) => {
        rolesPage = new RolesPage(adminPage);
        usersPage = new UsersPage(adminPage);
    });

    test.afterEach(async () => {
        try {
            await context?.close();
        } finally {
            try {
                await usersPage.deleteUsersIfPresent([user.email]);
            } finally {
                await rolesPage.deleteRolesIfPresent([role.name]);
            }
        }
    });

    test.describe("cms pages", () => {
        let adminCms: CmsPage;
        let created: string[];

        test.beforeEach(async ({ adminPage }) => {
            adminCms = new CmsPage(adminPage);
            created = [];
        });

        test.afterEach(async () => {
            await adminCms.deletePagesIfPresent(created);
        });

        test("should let a role with only the create permission add a page but not edit or delete it", async ({
            browser,
        }) => {
            const data = buildCmsPage();
            created.push(data.title);

            await signInWithPermission(browser, "cms.create");

            const restrictedCms = new CmsPage(restrictedPage);

            await restrictedCms.createPage(data);

            await adminCms.expectPageListed(data.title, data.urlKey);
            await restrictedCms.expectRowEditUnavailable(data.title);
            await restrictedCms.expectRowDeleteUnavailable(data.title);
        });

        test("should let a role with only the edit permission update a page but not create or delete one", async ({
            browser,
        }) => {
            const data = buildCmsPage();
            const changes = {
                title: `${data.title} edited`,
                urlKey: `${data.urlKey}-edited`,
            };
            created.push(data.title, changes.title);

            await adminCms.createPage(data);
            await signInWithPermission(browser, "cms.edit");

            const restrictedCms = new CmsPage(restrictedPage);

            await restrictedCms.expectCreateUnavailable();
            await restrictedCms.updatePage(data.title, changes);

            await adminCms.expectPageListed(changes.title, changes.urlKey);
            await restrictedCms.expectRowDeleteUnavailable(changes.title);
        });

        test("should let a role with only the delete permission remove a page but not create or edit one", async ({
            browser,
        }) => {
            const data = buildCmsPage();
            created.push(data.title);

            await adminCms.createPage(data);
            await signInWithPermission(browser, "cms.delete");

            const restrictedCms = new CmsPage(restrictedPage);

            await restrictedCms.expectCreateUnavailable();
            await restrictedCms.expectRowEditUnavailable(data.title);
            await restrictedCms.deletePage(data.title);

            await adminCms.expectPageAbsent(data.title);
        });
    });

    test.describe("customer groups", () => {
        let adminGroups: CustomerGroupsPage;
        let created: string[];

        test.beforeEach(async ({ adminPage }) => {
            adminGroups = new CustomerGroupsPage(adminPage);
            created = [];
        });

        test.afterEach(async () => {
            await adminGroups.deleteGroupsIfPresent(created);
        });

        test("should let a role with only the create permission add a group but not edit or delete it", async ({
            browser,
        }) => {
            const group = buildGroup();
            created.push(group.name);

            await signInWithPermission(browser, "customers.groups.create");

            const restrictedGroups = new CustomerGroupsPage(restrictedPage);

            await restrictedGroups.createGroup(group);

            await adminGroups.expectGroupListed(group);
            await restrictedGroups.expectRowEditUnavailable(group.name);
            await restrictedGroups.expectRowDeleteUnavailable(group.name);
        });

        test("should let a role with only the edit permission rename a group but not create or delete one", async ({
            browser,
        }) => {
            const group = buildGroup();
            const newName = `${group.name} edited`;
            created.push(group.name, newName);

            await adminGroups.createGroup(group);
            await signInWithPermission(browser, "customers.groups.edit");

            const restrictedGroups = new CustomerGroupsPage(restrictedPage);

            await restrictedGroups.expectCreateUnavailable();
            await restrictedGroups.renameGroup(group.name, newName);

            await adminGroups.expectGroupListed({ ...group, name: newName });
            await restrictedGroups.expectRowDeleteUnavailable(newName);
        });

        test("should let a role with only the delete permission remove a group but not create or edit one", async ({
            browser,
        }) => {
            const group = buildGroup();
            created.push(group.name);

            await adminGroups.createGroup(group);
            await signInWithPermission(browser, "customers.groups.delete");

            const restrictedGroups = new CustomerGroupsPage(restrictedPage);

            await restrictedGroups.expectCreateUnavailable();
            await restrictedGroups.expectRowEditUnavailable(group.name);
            await restrictedGroups.deleteGroup(group.name);

            await adminGroups.expectGroupAbsent(group.name);
        });
    });

    test.describe("roles", () => {
        let created: string[];

        test.beforeEach(() => {
            created = [];
        });

        test.afterEach(async () => {
            await rolesPage.deleteRolesIfPresent(created);
        });

        test("should let a role with only the create permission add a role within its own permissions but not grant any beyond them", async ({
            browser,
        }) => {
            const data = buildRole("settings.roles.create");
            created.push(data.name);

            await signInWithPermission(browser, "settings.roles.create");

            const restrictedRoles = new RolesPage(restrictedPage);

            await restrictedRoles.expectCreateFormGrantsOnly(
                "settings.roles.create",
                "catalog",
            );
            await restrictedRoles.createRole(data);

            await rolesPage.expectRoleListed(data.name, "custom");
        });

        test("should let a role with only the delete permission remove a role within its own permissions but not an all-access one", async ({
            browser,
        }) => {
            const data = buildRole("settings.roles.delete");
            const allAccessRole = buildRole();
            created.push(data.name, allAccessRole.name);

            await rolesPage.createRole(data);
            await rolesPage.createRole(allAccessRole);
            await signInWithPermission(browser, "settings.roles.delete");

            const restrictedRoles = new RolesPage(restrictedPage);

            await restrictedRoles.expectRowDeleteUnavailable(
                allAccessRole.name,
            );
            await restrictedRoles.deleteRole(data.name);

            await rolesPage.expectRoleAbsent(data.name);
        });
    });

    test.describe("users", () => {
        let createdUsers: string[];
        let createdRoles: string[];

        test.beforeEach(() => {
            createdUsers = [];
            createdRoles = [];
        });

        test.afterEach(async () => {
            try {
                await usersPage.deleteUsersIfPresent(createdUsers);
            } finally {
                await rolesPage.deleteRolesIfPresent(createdRoles);
            }
        });

        test("should let a role with only the create permission add a user with its own role but not offer an all-access role", async ({
            browser,
        }) => {
            const allAccessRole = buildRole();
            createdRoles.push(allAccessRole.name);

            await rolesPage.createRole(allAccessRole);
            await signInWithPermission(browser, "settings.users.create");

            const data = buildUser(role.name);
            createdUsers.push(data.email);

            const restrictedUsers = new UsersPage(restrictedPage);

            await restrictedUsers.expectCreateFormOffersRole(
                role.name,
                allAccessRole.name,
            );
            await restrictedUsers.createUser(data);

            await usersPage.expectUserListed(data.email, data.name);
        });

        test("should let a role with only the delete permission remove a user with its own role but not one with an all-access role", async ({
            browser,
        }) => {
            const allAccessRole = buildRole();
            const allAccessUser = buildUser(allAccessRole.name);
            createdRoles.push(allAccessRole.name);
            createdUsers.push(allAccessUser.email);

            await rolesPage.createRole(allAccessRole);
            await usersPage.createUser(allAccessUser);
            await signInWithPermission(browser, "settings.users.delete");

            const data = buildUser(role.name);
            createdUsers.push(data.email);

            await usersPage.createUser(data);

            const restrictedUsers = new UsersPage(restrictedPage);

            await restrictedUsers.expectRowDeleteUnavailable(
                allAccessUser.email,
            );
            await restrictedUsers.deleteUser(data.email);

            await usersPage.expectUserAbsent(data.email);
        });
    });
});
