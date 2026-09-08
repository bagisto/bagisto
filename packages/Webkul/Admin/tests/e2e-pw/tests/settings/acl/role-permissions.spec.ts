import type { Browser, BrowserContext } from "@playwright/test";
import { test } from "../../../setup";
import { RestrictedAdminPage } from "../../../pages/admin/acl/RestrictedAdminPage";
import {
    ACL_MODULE_ROUTES,
    MODULE_PROBES,
} from "../../../pages/admin/acl/routes";
import { RolesPage, type RoleData } from "../../../pages/admin/settings/RolesPage";
import {
    UsersPage,
    type AdminUserData,
} from "../../../pages/admin/settings/UsersPage";
import { generateFullName, uniqueStamp } from "../../../utils/faker";

function buildRole(permissions?: string[]): RoleData {
    const stamp = uniqueStamp();

    return {
        name: `Role ${stamp}`,
        description: `Role ${stamp} created by the acl suite.`,
        permissionType: permissions ? "custom" : "all",
        permissions,
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

async function openRestrictedSession(
    browser: Browser,
    user: AdminUserData,
): Promise<{ context: BrowserContext; restricted: RestrictedAdminPage }> {
    const context = await browser.newContext();
    const restricted = new RestrictedAdminPage(await context.newPage());

    await restricted.login(user.email, user.password);

    return { context, restricted };
}

test.describe("role permissions", () => {
    test.describe.configure({ timeout: 180000 });

    let rolesPage: RolesPage;
    let usersPage: UsersPage;
    let role: RoleData;
    let user: AdminUserData;

    test.beforeEach(async ({ adminPage }) => {
        rolesPage = new RolesPage(adminPage);
        usersPage = new UsersPage(adminPage);
    });

    test.afterEach(async () => {
        try {
            await usersPage.deleteUsersIfPresent([user.email]);
        } finally {
            await rolesPage.deleteRolesIfPresent([role.name]);
        }
    });

    test("should open every module for a user whose role has all permissions", async ({
        browser,
    }) => {
        role = buildRole();
        user = buildUser(role.name);

        await rolesPage.createRole(role);
        await usersPage.createUser(user);

        const { context, restricted } = await openRestrictedSession(browser, user);

        try {
            for (const path of Object.values(MODULE_PROBES)) {
                await restricted.expectRouteAllowed(path);
            }
        } finally {
            await context.close();
        }
    });

    for (const route of ACL_MODULE_ROUTES) {
        test(`should limit a custom role with the ${route.permission} permission to its own routes`, async ({
            browser,
        }) => {
            role = buildRole([route.permission]);
            user = buildUser(role.name);

            await rolesPage.createRole(role);
            await usersPage.createUser(user);

            const { context, restricted } = await openRestrictedSession(
                browser,
                user,
            );

            try {
                await restricted.expectRouteAllowed(route.allowed);

                if (route.sidebar) {
                    await restricted.expectSidebarLinkVisible(route.sidebar);
                }

                for (const path of route.denied) {
                    await restricted.expectRouteDenied(path);
                }
            } finally {
                await context.close();
            }
        });
    }
});
