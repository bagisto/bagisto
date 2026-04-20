import { expect, Page } from "@playwright/test";
import { generateEmail } from "../../../utils/faker";
import { BasePage } from "../../BasePage";
import { ACL_ROUTES } from "./routes";

export class ACLSharedPage extends BasePage {
    readonly roleName: string;
    readonly userName: string;
    readonly userEmail: string;

    constructor(page: Page) {
        super(page);

        this.roleName = `role-${Date.now()}`;
        this.userName = `user-${Date.now()}`;
        this.userEmail = `user_${Date.now()}_${Math.floor(Math.random() * 10000)}${generateEmail()}`;
    }

    protected get adminSessionPage() {
        return {
            userEmail: this.page.locator('input[name="email"]'),
            userPassword: this.page.locator('input[name="password"]'),
            profile: this.page.locator("div.flex.select-none >> button"),
            logout: this.page.getByRole("link", { name: "Logout" }),
        };
    }

    protected get roleActionPage() {
        return {
            createRole: this.page.locator("a.primary-button:visible"),
            name: this.page.locator('input[name="name"]'),
            selectRoleType: this.page.locator('select[name="permission_type"]'),
            roleDescription: this.page.locator('textarea[name="description"]'),
            iconEdit: this.page.locator(".icon-edit"),
            successEditRole: this.page.getByText(
                "Roles is updated successfully",
            ),
            saveRole: this.page.locator(
                'button.primary-button:has-text("Save Role")',
            ),
            successRole: this.page.getByText("Roles Created Successfully"),
            successUpdateRole: this.page.getByText(
                "Roles is updated successfully",
            ),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            successDeleteRole: this.page.getByText(
                "Roles is deleted successfully",
            ),
        };
    }

    protected get userActionPage() {
        return {
            createUser: this.page.getByRole("button", { name: "Create User" }),
            name: this.page.locator('input[name="name"]'),
            selectRole: this.page.locator('select[name="role_id"]'),
            userEmail: this.page.locator('input[name="email"]'),
            userPassword: this.page.locator('input[name="password"]'),
            confirmPassword: this.page.locator(
                'input[name="password_confirmation"]',
            ),
            statusToggle: this.page.locator('label[for="status"]'),
            saveUser: this.page.getByRole("button", { name: "Save User" }),
            successUser: this.page.getByText("User created successfully."),
            unauthorized: this.page.getByText("401").first(),
            iconEdit: this.page.locator(".icon-edit"),
            successUserUpdate: this.page.getByText(
                "User updated successfully.",
            ),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            successUserDelete: this.page.getByText(
                "User deleted successfully.",
            ),
        };
    }

    private async togglePermissionCheckbox(
        permissionValue: string,
        shouldCheck: boolean,
    ): Promise<void> {
        const input = this.page.locator(
            `input[name="permissions[]"][value="${permissionValue}"]`,
        );

        await expect(input).toBeAttached({ timeout: 10000 });

        const isChecked = await input.isChecked();

        if (shouldCheck && !isChecked) {
            await input.locator("..").locator("span").click();
        } else if (!shouldCheck && isChecked) {
            await input.locator("..").locator("span").click();
        }
    }

    async rolePermission(permissionValues: string[]): Promise<void> {
        await this.page.waitForLoadState("networkidle");

        for (const value of permissionValues) {
            await this.togglePermissionCheckbox(value, true);
        }
    }

    async editRolePermission(permissionValues: string[]): Promise<void> {
        await this.visit("admin/settings/roles");
        await this.roleActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");

        for (const value of permissionValues) {
            await this.togglePermissionCheckbox(value, false);
        }

        await this.roleActionPage.saveRole.click();
        await expect(this.roleActionPage.successEditRole).toBeVisible();
    }

    async createRole(
        permissionType: string,
        permissions?: string[],
    ): Promise<void> {
        await this.visit("admin/settings/roles");
        await this.roleActionPage.createRole.click();
        await this.roleActionPage.name.fill(this.roleName);
        await this.roleActionPage.selectRoleType.selectOption(permissionType);

        if (
            permissionType === "custom" &&
            permissions &&
            permissions.length > 0
        ) {
            await this.rolePermission(permissions);
        }

        await this.roleActionPage.roleDescription.fill("test description");
        await this.roleActionPage.saveRole.click();
        await expect(this.roleActionPage.successRole.first()).toBeVisible();
    }

    async createUser(): Promise<void> {
        await this.visit("admin/settings/users");
        await this.userActionPage.createUser.click();
        await this.userActionPage.name.fill(this.userName);
        await this.userActionPage.selectRole.selectOption({
            label: this.roleName,
        });
        await this.userActionPage.userEmail.fill(this.userEmail);
        await this.userActionPage.userPassword.fill("user123");
        await this.userActionPage.confirmPassword.fill("user123");
        await this.userActionPage.statusToggle.click();
        await expect(this.userActionPage.statusToggle).toBeChecked();
        await this.userActionPage.saveUser.click();
        await expect(this.userActionPage.successUser.first()).toBeVisible();
    }

    async expectUnauthorizedFor(routes: string[]) {
        for (const route of routes) {
            await this.visit(route);
            await expect(this.userActionPage.unauthorized).toBeVisible();
        }
    }

    async expectAuthorizedFor(route: string) {
        await this.visit(route);
        await expect(this.userActionPage.unauthorized).not.toBeVisible();
    }

    async verfiyAssignedRole(permissions: string[] = []) {
        await this.visit("admin/dashboard");
        await this.page.waitForTimeout(3000);
        await this.adminSessionPage.profile.first().click();
        await this.adminSessionPage.logout.click();
        await this.visit("admin/login");
        await this.page.waitForURL("**/admin/login");
        await this.page.waitForLoadState("networkidle");
        await this.adminSessionPage.userEmail.fill(this.userEmail);
        await this.adminSessionPage.userPassword.fill("user123");
        await this.adminSessionPage.userPassword.press("Enter");
        await this.page.waitForLoadState("networkidle");

        const sidebar = this.page.locator("div.fixed.top-14 nav");
        let sidebarLinks: string[] = [];

        if (permissions.includes("all")) {
            sidebarLinks = [
                "/admin/dashboard",
                "/admin/sales/orders",
                "/admin/catalog/products",
                "/admin/customers",
                "/admin/cms",
                "/admin/marketing/promotions/catalog-rules",
                "/admin/reporting/sales",
                "/admin/settings/locales",
                "/admin/configuration",
            ];
        } else {
            for (const permission of permissions) {
                const config = ACL_ROUTES[permission];

                if (!config) {
                    continue;
                }

                if (config.notAllowed?.length) {
                    await this.expectUnauthorizedFor(config.notAllowed);
                }

                await this.expectAuthorizedFor(config.allowed);
                sidebarLinks.push(config.sidebar);
            }
        }

        for (const href of sidebarLinks) {
            await expect(
                sidebar.locator(`a[href*="${href}"]`).first(),
            ).toBeVisible();
        }
    }
}
