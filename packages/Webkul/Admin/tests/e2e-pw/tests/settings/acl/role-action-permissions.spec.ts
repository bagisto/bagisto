import type { Browser, BrowserContext, Page } from "@playwright/test";
import { test, withTinymce } from "../../../setup";
import { RestrictedAdminPage } from "../../../pages/admin/acl/RestrictedAdminPage";
import { CmsPage, type CmsPageData } from "../../../pages/admin/cms/CmsPage";
import {
    CustomerGroupsPage,
    type CustomerGroupData,
} from "../../../pages/admin/customers/CustomerGroupsPage";
import { RolesPage, type RoleData } from "../../../pages/admin/settings/RolesPage";
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

function buildRole(permission: string): RoleData {
    const stamp = uniqueStamp();

    return {
        name: `Role ${stamp}`,
        description: `Role ${stamp} created by the acl suite.`,
        permissionType: "custom",
        permissions: [permission],
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
            const changes = { title: `${data.title} edited`, urlKey: `${data.urlKey}-edited` };
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
});
