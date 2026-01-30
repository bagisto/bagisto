import { test, expect } from "../../../setup";
import { ACLManagement } from "../../../pages/acl";
import {
    generateFirstName,
    generateLastName,
    generateEmail,
    randomElement,
    generateName,
    generateSlug,
    generateDescription,
    generateRandomDate,
} from "../../../utils/faker";

test.describe("acl management", () => {
    test("should create role for all permission", async ({ adminPage }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("all");
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["all"]);
    });

    test("should create custom role with dashboard permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["dashboard"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["dashboard"]);
    });

    test.describe("sales acl", () => {
        test("should create custom role with sales permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales"]);
        });

        test("should create custom role with sales (order) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.editRolePermission([
                "sales.transactions",
                "sales.refunds",
                "sales.shipments",
                "sales.invoices",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->order"]);
        });

        test("should create custom role with sales (order-> create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales.orders.create"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->order"]);
            await expect(
                adminPage.locator("button.primary-button"),
            ).toBeVisible();
            await adminPage.locator("button.primary-button").click();
            await expect(
                adminPage.locator("button.secondary-button"),
            ).toBeVisible();
        });

        test("should create custom role with sales (transaction) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.editRolePermission([
                "sales.orders",
                "sales.refunds",
                "sales.shipments",
                "sales.invoices",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->transactions"]);
        });

        test("should create custom role with sales (transaction->view) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "sales.transactions.view",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->transactions"]);
            await expect(
                adminPage.locator("button.primary-button"),
            ).toBeVisible();
        });

        test("should create custom role with sales (shipments) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.editRolePermission([
                "sales.orders",
                "sales.refunds",
                "sales.transactions",
                "sales.invoices",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->shipments"]);
        });

        test("should create custom role with sales (shipments->view & create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "sales.shipments.create",
                "sales.shipments.view",
            ]);

            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->shipments"]);
            await expect(
                adminPage.locator(".table-responsive").first(),
            ).toBeVisible();
        });

        test("should create custom role with sales (invoices) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.editRolePermission([
                "sales.orders",
                "sales.refunds",
                "sales.transactions",
                "sales.shipments",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->invoices"]);
        });

        test("should create custom role with sales (refunds) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.editRolePermission([
                "sales.orders",
                "sales.invoices",
                "sales.transactions",
                "sales.shipments",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->refunds"]);
        });
    });

    test("should create custom role with sales (refunds->view) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales.refunds.view"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->refund"]);
        await expect(
            adminPage.locator(".table-responsive").first(),
        ).toBeVisible();
    });

    test("should create custom role with sales (refunds->create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales.refunds.create"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->refund"]);
        await expect(
            adminPage.locator(".table-responsive").first(),
        ).toBeVisible();
    });

    test.describe("catalog acl", () => {
        test("should create custom role with catalog permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["catalog"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog"]);
        });

        test("should create custom role with catalog (products) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["catalog"]);
            await aclManagement.editRolePermission([
                "catalog.families",
                "catalog.attributes",
                "catalog.categories",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->products"]);
        });

        test("should create custom role with catalog (products-> edit) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["catalog.products.edit"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->products"]);
            await expect(
                adminPage.locator("button.primary-button"),
            ).not.toBeVisible();
            await adminPage
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1)
                .click();
            await adminPage.waitForLoadState("networkidle");
            await adminPage.locator("button.primary-button").click();
            await expect(adminPage.locator("#app")).toContainText(
                "Product updated successfully",
            );
        });

        test("should create custom role with catalog (products -> copy) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["catalog.products.copy"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->products"]);
            await adminPage.waitForLoadState("networkidle");
            await expect(
                adminPage.locator("button.primary-button"),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("span.cursor-pointer.icon-sort-right").nth(1),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("button.primary-button"),
            ).not.toBeVisible();
            await adminPage.locator("span.icon-copy").nth(1).click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(
                adminPage.getByText("Product copied successfully").first(),
            ).toBeVisible();
        });

        test("should create custom role with catalog (products-> delete) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "catalog.products.delete",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->products"]);
            await expect(
                adminPage.locator("button.primary-button"),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("span.cursor-pointer.icon-sort-right").nth(1),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("button.primary-button"),
            ).not.toBeVisible();
            await adminPage.locator(".icon-uncheckbox").nth(2).click();
            await adminPage
                .getByRole("button", { name: "Select Action" })
                .click();
            await adminPage.getByRole("link", { name: "Delete" }).click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(
                adminPage
                    .getByText("Selected Products Deleted Successfully")
                    .first(),
            ).toBeVisible();
        });

        test("should create custom role with catalog (categories) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["catalog"]);
            await aclManagement.editRolePermission([
                "catalog.families",
                "catalog.attributes",
                "catalog.products",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->categories"]);
        });

        test("should create custom role with catalog (categories -> create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "catalog.categories.create",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->categories"]);
            await expect(
                adminPage.locator(".primary-button").first(),
            ).toBeVisible();
        });

        test("should create custom role with catalog (categories -> edit) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "catalog.categories.edit",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->categories"]);
            await expect(
                adminPage.locator("button.primary-button"),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("span.icon-edit").first(),
            ).toBeVisible();
            await adminPage.locator("span.icon-edit").first().click();
            await adminPage.waitForLoadState("networkidle");
            await adminPage
                .getByRole("button", { name: "Save Category" })
                .click();
            await expect(
                adminPage.getByText("Category updated successfully.").first(),
            ).toBeVisible();
        });

        test("should create custom role with catalog (categories -> delete) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "catalog.categories.delete",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->categories"]);
            await expect(
                adminPage.locator("button.primary-button"),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("span.icon-edit").first(),
            ).not.toBeVisible();
            await adminPage.locator(".icon-uncheckbox").nth(1).click();
            await adminPage
                .getByRole("button", { name: "Select Action" })
                .click();
            await adminPage.getByRole("link", { name: "Delete" }).click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(
                adminPage
                    .getByText("The category has been successfully deleted.")
                    .first(),
            ).toBeVisible();
        });

        test("should create custom role with catalog (attributes) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["catalog"]);
            await aclManagement.editRolePermission([
                "catalog.families",
                "catalog.categories",
                "catalog.products",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->attributes"]);
        });

        test("should create custom role with catalog (attributes-> create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "catalog.attributes.create",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->attributes"]);
            await expect(
                adminPage.getByRole("link", { name: "Create Attributes" }),
            ).toBeVisible();
            await adminPage
                .getByRole("link", { name: "Create Attributes" })
                .click();
            await adminPage.waitForLoadState("networkidle");
            await adminPage
                .locator('input[name="admin_name"]')
                .fill("attribute");
            await adminPage.locator('input[name="code"]').fill("admin123");
            await adminPage.locator('select[name="type"]').selectOption("text");
            await adminPage.locator("button.primary-button").click();
            await expect(
                adminPage.getByText("Attribute Created Successfully"),
            ).toBeVisible();
        });

        test("should create custom role with catalog (attributes-> edit) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "catalog.attributes.edit",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->attributes"]);
            await expect(
                adminPage.getByRole("link", { name: "Create Attributes" }),
            ).not.toBeVisible();
            await expect(
                adminPage.getByRole("link", { name: "Create Attributes" }),
            ).not.toBeVisible();
            await adminPage;
            await adminPage
                .locator('input[name="admin_name"]')
                .fill("test attribute");
            await adminPage.locator('input[name="code"]').fill("admin123");
            await adminPage.locator('select[name="type"]').selectOption("text");
            await adminPage.locator("button.primary-button").click();
            await expect(
                adminPage.getByText("Attribute Created Successfully"),
            ).toBeVisible();
        });

        test("should create custom role with catalog (attributes-> delete) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "catalog.attributes.delete",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->attributes"]);
            await expect(
                adminPage.getByRole("link", { name: "Create Attributes" }),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("span.icon-edit").first(),
            ).not.toBeVisible();
            await adminPage.locator(".icon-delete").first().click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(
                adminPage.getByText(
                    /Attribute Deleted Successfully|Attribute Deleted Failed/,
                ),
            ).toBeVisible();
        });

        test("should create custom role with catalog (families) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["catalog"]);
            await aclManagement.editRolePermission([
                "catalog.attributes",
                "catalog.categories",
                "catalog.products",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->families"]);
        });

        test("should create custom role with catalog (families->create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "catalog.families.create",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->families"]);
            await adminPage
                .getByRole("link", { name: "Create Attribute Family" })
                .click();
            await adminPage.waitForLoadState("networkidle");
            await adminPage.locator('input[name="code"]').fill("testcode");
            await adminPage.locator('input[name="name"]').fill("test name");
            await adminPage.locator("button.primary-button").click();
            await expect(
                adminPage.getByText("Family created successfully.").first(),
            ).toBeVisible();
        });

        test("should create custom role with catalog (families->edit) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["catalog.families.edit"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->families"]);
            await expect(
                adminPage.getByRole("link", {
                    name: "Create Attribute Family",
                }),
            ).not.toBeVisible();
            await adminPage.locator("span.icon-edit").first().click();
            await adminPage.waitForLoadState("networkidle");
            await adminPage
                .getByRole("button", { name: " Save Attribute Family" })
                .click();
            await expect(
                adminPage.getByText("Family updated successfully.").first(),
            ).toBeVisible();
        });

        test("should create custom role with catalog (families->delete) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "catalog.families.delete",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog->families"]);
            await expect(
                adminPage.getByRole("link", {
                    name: "Create Attribute Family",
                }),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("span.icon-edit").first(),
            ).not.toBeVisible();
            await adminPage.locator(".icon-delete").first().click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(
                adminPage.getByText(/Family deleted successfully./),
            ).toBeVisible();
        });
    });

    test.describe("customers acl", () => {
        test("should create custom role with customers permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["customers"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers"]);
        });

        test("should create custom role with customers (customers) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["customers"]);
            await aclManagement.editRolePermission([
                "customers.gdpr_requests",
                "customers.groups",
                "customers.reviews",
                "customers.note",
                "customers.addresses",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers->customers"]);
        });

        test("should create custom role with customers (customers->create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "customers.customers.create",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers->customers"]);
            await adminPage.goto("admin/customers");
            await adminPage.waitForSelector("button.primary-button:visible", {
                state: "visible",
            });

            await adminPage.click("button.primary-button:visible");

            await adminPage.fill(
                'input[name="first_name"]:visible',
                generateFirstName(),
            );
            await adminPage.fill(
                'input[name="last_name"]:visible',
                generateLastName(),
            );
            await adminPage.fill(
                'input[name="email"]:visible',
                generateEmail(),
            );
            await adminPage.selectOption(
                'select[name="gender"]:visible',
                randomElement(["Male", "Female", "Other"]),
            );

            await adminPage.press('input[name="phone"]:visible', "Enter");

            await expect(
                adminPage.getByText("Customer created successfully"),
            ).toBeVisible();
        });

        test("should create custom role with customers (customers->edit) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "customers.customers.edit",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers->customers"]);
            await expect(
                adminPage.locator("button.primary-button"),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("a.icon-sort-right.cursor-pointer").first(),
            ).toBeVisible();
        });

        test("should create custom role with customers (customers->delete) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "customers.customers.delete",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers->customers"]);
            await expect(
                adminPage.locator("button.primary-button"),
            ).not.toBeVisible();
            await adminPage.locator(".icon-uncheckbox").nth(1).click();
            await adminPage
                .getByRole("button", { name: "Select Action" })
                .click();
            await adminPage.getByRole("link", { name: "Delete" }).click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(
                adminPage
                    .getByText("Selected data successfully deleted")
                    .first(),
            ).toBeVisible();
        });

        test("should create custom role with customers (groups) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["customers"]);
            await aclManagement.editRolePermission([
                "customers.gdpr_requests",
                "customers.customers",
                "customers.reviews",
                "customers.note",
                "customers.addresses",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers->groups"]);
        });

        test("should create custom role with customers (groups-> create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "customers.groups.create",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers->groups"]);
            await adminPage.click("button.primary-button:visible");
            await adminPage.locator('input[name="name"]').fill("group_name");
            await adminPage.locator('input[name="code"]').fill("admin123");
            await adminPage.locator("button.primary-button").nth(1).click();
            await expect(
                adminPage.getByText("Group created successfully").first(),
            ).toBeVisible();
        });

        test("should create custom role with customers (groups-> edit) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["customers.groups.edit"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers->groups"]);
            await expect(
                adminPage.locator("button.primary-button:visible"),
            ).not.toBeVisible();
            await adminPage.locator("span.icon-edit").first().click();
            await adminPage.waitForLoadState("networkidle");
            await adminPage.getByRole("button", { name: "Save Group" }).click();
            await expect(
                adminPage.getByText("Group Updated Successfully").first(),
            ).toBeVisible();
        });

        test("should create custom role with customers (groups-> delete) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "customers.groups.delete",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers->groups"]);
            await expect(
                adminPage.locator("button.primary-button:visible"),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("span.icon-edit").first(),
            ).not.toBeVisible();
            await adminPage.locator(".icon-delete").first().click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(
                adminPage.getByText(/Group Deleted Successfully/).first(),
            ).toBeVisible();
        });

        test("should create custom role with customers (reviews) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["customers"]);
            await aclManagement.editRolePermission([
                "customers.gdpr_requests",
                "customers.customers",
                "customers.groups",
                "customers.note",
                "customers.addresses",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers->reviews"]);
        });

        test("should create custom role with customers (gdpr) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["customers"]);
            await aclManagement.editRolePermission([
                "customers.reviews",
                "customers.customers",
                "customers.groups",
                "customers.note",
                "customers.addresses",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers->gdpr"]);
        });
    });

    test.describe("cms acl", () => {
        test("should create custom role with cms permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["cms"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["cms"]);
        });

        test("should create custom role with cms (create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["cms.create"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["cms"]);
            const cms = {
                name: generateName(),
                slug: generateSlug(),
                shortDescription: generateDescription(),
            };
            await adminPage.waitForLoadState("networkidle");
            await adminPage.fillInTinymce("#content_ifr", cms.shortDescription);
            await adminPage.fill("#page_title", cms.name);
            await adminPage.click('label[for="channels_1"]');
            await expect(adminPage.locator("input#channels_1")).toBeChecked();
            await adminPage.fill("#meta_title", cms.name);
            await adminPage.fill("#url_key", cms.slug);
            await adminPage.fill("#meta_keywords", cms.name);
            await adminPage.fill("#meta_description", cms.shortDescription);
            await adminPage.click(
                'button.primary-button:has-text("Save Page")',
            );
            await expect(
                adminPage.locator("#app p", {
                    hasText: "CMS created successfully.",
                }),
            ).toBeVisible();
        });

        test("should create custom role with cms (edit) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["cms.edit"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["cms"]);
            await adminPage.goto("admin/cms");
            await expect(
                adminPage.locator('a.primary-button:has-text("Create Page")'),
            ).not.toBeVisible();
            await adminPage.locator("span.icon-edit").first().click();
            await adminPage.waitForLoadState("networkidle");
            await adminPage.click(
                'button.primary-button:has-text("Save Page")',
            );
            await expect(
                adminPage.locator("#app p", {
                    hasText: "CMS updated successfully.",
                }),
            ).toBeVisible();
        });

        test("should create custom role with cms (delete) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["cms.delete"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["cms"]);
            await adminPage.goto("admin/cms");
            await expect(
                adminPage.locator('a.primary-button:has-text("Create Page")'),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("span.icon-edit").first(),
            ).not.toBeVisible();
            await adminPage.waitForLoadState("networkidle");
            await adminPage.click(
                'button.primary-button:has-text("Save Page")',
            );
            await expect(
                adminPage.locator("#app p", {
                    hasText: "CMS updated successfully.",
                }),
            ).toBeVisible();
        });
    });

    test.describe("marketing acl", () => {
        test("should create custom role with marketing permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["marketing"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["marketing"]);
        });

        test("should create custom role with marketing (promotions) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["marketing"]);
            await aclManagement.editRolePermission([
                "marketing.search_seo",
                "marketing.communications",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["marketing->promotions"]);
        });

        test("should create custom role with marketing (promotions-> cart rule-> create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.promotions.cart_rules.create",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->promotions->cart_rule",
            ]);
            await adminPage.goto("admin/marketing/promotions/cart-rules");
            await adminPage.click(
                'a.primary-button:has-text("Create Cart Rule")',
            );
            await adminPage.waitForSelector(
                'form[action*="/promotions/cart-rules/create"]',
            );
            await adminPage.fill("#name", generateName());
            await adminPage.fill("#description", generateDescription());
            await adminPage.click(
                'div.secondary-button:has-text("Add Condition")',
            );
            await adminPage.waitForSelector(
                'select[id="conditions[0][attribute]"]',
            );
            await adminPage.selectOption(
                'select[id="conditions[0][attribute]"]',
                "product|name",
            );
            await adminPage.waitForSelector(
                'input[name="conditions[0][value]"]',
            );
            await adminPage.fill(
                'input[name="conditions[0][value]"]',
                generateName(),
            );
            await adminPage.fill('input[name="discount_amount"]', "10");
            await adminPage.fill('input[name="sort_order"]', "1");
            await adminPage.click('label[for="channel__1"]');
            await expect(adminPage.locator("input#channel__1")).toBeChecked();
            await adminPage.click('label[for="customer_group__2"]');
            await expect(
                adminPage.locator("input#customer_group__2"),
            ).toBeChecked();
            await adminPage.click('label[for="status"]');
            const toggleInput = await adminPage.locator('input[name="status"]');
            await expect(toggleInput).toBeChecked();
            await adminPage.click(
                'button.primary-button:has-text("Save Cart Rule")',
            );
            await expect(
                adminPage.getByText("Cart rule created successfully"),
            ).toBeVisible();
        });

        test("should create custom role with marketing (promotions-> cart rule-> copy) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.promotions.cart_rules.copy",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->promotions->cart_rule",
            ]);
            await expect(
                adminPage.locator(
                    'a.primary-button:has-text("Create Cart Rule")',
                ),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("span.icon-edit").first(),
            ).not.toBeVisible();
            await adminPage.locator("span.icon-copy").nth(1).click();
            await expect(
                adminPage.getByText("cart rule copied successfully").first(),
            ).toBeVisible();
        });

        test("should create custom role with marketing (promotions-> cart rule-> edit) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.promotions.cart_rules.edit",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->promotions->cart_rule",
            ]);
            await expect(
                adminPage.locator(
                    'a.primary-button:has-text("Create Cart Rule")',
                ),
            ).not.toBeVisible();
            await adminPage.locator("span.icon-edit").first().click();
            await adminPage.waitForLoadState("networkidle");
            await adminPage.click(
                'button.primary-button:has-text(" Save Cart Rule ")',
            );
            await expect(
                adminPage.getByText("Cart rule updated successfully"),
            ).toBeVisible();
        });

        test("should create custom role with marketing (promotions-> cart rule-> delete) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.promotions.cart_rules.delete",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->promotions->cart_rule",
            ]);
            await expect(
                adminPage.locator(
                    'a.primary-button:has-text("Create Cart Rule")',
                ),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("span.icon-edit").first(),
            ).not.toBeVisible();
            await adminPage.locator(".icon-delete").first().click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(
                adminPage.getByText(/Cart Rule Deleted Successfully/i).first(),
            ).toBeVisible();
        });

        test("should create custom role with marketing (promotions-> catalog rule-> create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.promotions.catalog_rules.create",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->promotions->catalog_rules",
            ]);
            await adminPage.goto("admin/marketing/promotions/catalog-rules");
            await adminPage.click(
                'a.primary-button:has-text("Create Catalog Rule")',
            );
            await adminPage.waitForSelector(
                'form[action*="/promotions/catalog-rules/create"]',
            );
            await adminPage.fill("#name", generateName());
            await adminPage.fill("#description", generateDescription());
            await adminPage.click(
                'div.secondary-button:has-text("Add Condition")',
            );
            await adminPage.waitForSelector(
                'select[id="conditions[0][attribute]"]',
            );
            await adminPage.selectOption(
                'select[id="conditions[0][attribute]"]',
                "product|name",
            );
            await adminPage.waitForSelector(
                'input[name="conditions[0][value]"]',
            );
            await adminPage.fill(
                'input[name="conditions[0][value]"]',
                generateName(),
            );
            await adminPage.fill('input[name="discount_amount"]', "10");
            await adminPage.fill('input[name="sort_order"]', "1");
            await adminPage.click('label[for="channel__1"]');
            await expect(adminPage.locator("input#channel__1")).toBeChecked();
            await adminPage.click('label[for="customer_group__2"]');
            await expect(
                adminPage.locator("input#customer_group__2"),
            ).toBeChecked();
            await adminPage.click('label[for="status"]');
            const toggleInput = await adminPage.locator('input[name="status"]');
            await expect(toggleInput).toBeChecked();
            await adminPage.click(
                'button.primary-button:has-text("Save Catalog Rule")',
            );

            await expect(
                adminPage.getByText("Catalog rule created successfully"),
            ).toBeVisible();
        });

        test("should create custom role with marketing (promotions-> catalog rule-> edit) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.promotions.catalog_rules.edit",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->promotions->catalog_rule",
            ]);
            await expect(
                adminPage.locator(
                    'a.primary-button:has-text("Create Catalog Rule")',
                ),
            ).not.toBeVisible();
            await adminPage.locator("span.icon-edit").first().click();
            await adminPage.waitForLoadState("networkidle");
            await adminPage.click(
                'button.primary-button:has-text(" Save Cataog Rule ")',
            );
            await expect(
                adminPage.getByText("Catalog rule updated successfully"),
            ).toBeVisible();
        });

        test("should create custom role with marketing (promotions-> catalog rule-> delete) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.promotions.catalog_rules.delete",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->promotions->catalog_rule",
            ]);
            await expect(
                adminPage.locator(
                    'a.primary-button:has-text("Create Catalog Rule")',
                ),
            ).not.toBeVisible();
            await expect(
                adminPage.locator("span.icon-edit").first(),
            ).not.toBeVisible();
            await adminPage.locator(".icon-delete").first().click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(
                adminPage
                    .getByText(/Catalog Rule Deleted Successfully/i)
                    .first(),
            ).toBeVisible();
        });

        test("should create custom role with marketing (communications) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["marketing"]);
            await aclManagement.editRolePermission([
                "marketing.search_seo",
                "marketing.promotions",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->communications",
            ]);
        });

        test("should create custom role with marketing (communications-> email template-> create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.communications.email_templates.create",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->communications->email",
            ]);
            await adminPage.goto(
                `admin/marketing/communications/email-templates`,
            );
            await adminPage.click(
                'div.primary-button:visible:has-text("Create Template")',
            );

            const name = generateName();
            const description = generateDescription();
            await adminPage.fill('input[name="name"]', name);
            await adminPage.selectOption('select[name="status"]', "active");
            await adminPage.fillInTinymce("#content_ifr", description);
            await adminPage.click(
                'button[type="submit"][class="primary-button"]:visible:has-text("Save Template")',
            );
            await expect(adminPage.locator("#app")).toContainText(
                "Email template created successfully.",
            );
        });

        test("should create custom role with marketing (communications-> email template-> edit) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.communications.email_templates.edit",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->communications->email",
            ]);
            await adminPage
                .locator("span.cursor-pointer.icon-edit")
                .first()
                .click();
            await adminPage.fill('input[name="name"]', generateName());
            await adminPage.click(
                'button[type="submit"][class="primary-button"]:visible',
            );
            await expect(adminPage.locator("#app")).toContainText(
                "Updated successfully",
            );
        });

        test("should create custom role with marketing (communications-> email template-> delete) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.communications.email_templates.delete",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->communications->email",
            ]);
            await expect(
                adminPage.locator("span.cursor-pointer.icon-edit"),
            ).not.toBeVisible();
            await adminPage.waitForSelector("span.cursor-pointer.icon-delete", {
                state: "visible",
            });
            const iconDelete = await adminPage.$$(
                "span.cursor-pointer.icon-delete",
            );
            await iconDelete[0].click();

            await adminPage.click(
                "button.transparent-button + button.primary-button:visible",
            );

            await expect(
                adminPage.getByText("Template Deleted successfully"),
            ).toBeVisible();
        });

        test("should create custom role with marketing (communications-> events-> create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.communications.events.create",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->communications->events",
            ]);
            await adminPage.click("div.primary-button:visible");

            adminPage.hover('input[name="name"]');

            const inputs = await adminPage.$$(
                'textarea.rounded-md:visible, input[type="text"].rounded-md:visible',
            );

            for (let input of inputs) {
                await input.fill(generateName());
            }

            const time = generateRandomDate();

            await adminPage.fill('input[name="date"]', time);

            await adminPage.click('button[class="primary-button"]:visible');

            await expect(
                adminPage.getByText("Events Created Successfully"),
            ).toBeVisible();
        });

        test("should create custom role with marketing (communications-> events-> edit) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.communications.events.edit",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->communications->events",
            ]);
            await expect(
                adminPage.locator("div.primary-button:visible"),
            ).not.toBeVisible();
            await adminPage
                .locator("span.cursor-pointer.icon-edit")
                .first()
                .click();
            await adminPage.waitForTimeout(1000);
            await adminPage.getByRole("button", { name: "Save Event" }).click();
            await expect(
                adminPage.getByText("Events Updated Successfully").first(),
            ).toBeVisible();
        });

        test("should create custom role with marketing (communications-> events-> delete) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "marketing.communications.events.delete",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "marketing->communications->events",
            ]);
            await expect(
                adminPage.locator("div.primary-button"),
            ).not.toBeVisible();
            await adminPage.waitForLoadState("networkidle");
            await expect(
                adminPage.locator(".icon-edit").first(),
            ).not.toBeVisible();
            await adminPage.locator(".icon-delete").first().click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(
                adminPage.getByText("Events Deleted Successfully"),
            ).toBeVisible();
        });

        test("should create custom role with marketing (search_seo) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["marketing"]);
            await aclManagement.editRolePermission([
                "marketing.communications",
                "marketing.promotions",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["marketing->search_seo"]);
        });
    });

    test.describe("reporting acl", () => {
        test("should create custom role with reporting permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["reporting"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["reporting"]);
        });
    });

    test.describe("settings acl", () => {
        test("should create custom role with settings permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["settings"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["settings"]);
        });

        test("should create custom role with settings (locale) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["settings"]);
            await aclManagement.editRolePermission([
                "settings.data_transfer",
                "settings.taxes",
                "settings.themes",
                "settings.roles",
                "settings.users",
                "settings.channels",
                "settings.inventory_sources",
                "settings.exchange_rates",
                "settings.currencies",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["settings->locales"]);
        });

        test("should create custom role with settings (currencies) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["settings"]);
            await aclManagement.editRolePermission([
                "settings.data_transfer",
                "settings.taxes",
                "settings.themes",
                "settings.roles",
                "settings.users",
                "settings.channels",
                "settings.inventory_sources",
                "settings.exchange_rates",
                "settings.locales",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["settings->currencies"]);
        });

        test("should create custom role with settings (exchange_rates) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["settings"]);
            await aclManagement.editRolePermission([
                "settings.data_transfer",
                "settings.taxes",
                "settings.themes",
                "settings.roles",
                "settings.users",
                "settings.channels",
                "settings.inventory_sources",
                "settings.currencies",
                "settings.locales",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "settings->exchange_rates",
            ]);
        });

        test("should create custom role with settings (inventory_sources) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["settings"]);
            await aclManagement.editRolePermission([
                "settings.data_transfer",
                "settings.taxes",
                "settings.themes",
                "settings.roles",
                "settings.users",
                "settings.channels",
                "settings.exchange_rates",
                "settings.currencies",
                "settings.locales",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole([
                "settings->inventory_sources",
            ]);
        });

        test("should create custom role with settings (channels) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["settings"]);
            await aclManagement.editRolePermission([
                "settings.data_transfer",
                "settings.taxes",
                "settings.themes",
                "settings.roles",
                "settings.users",
                "settings.inventory_sources",
                "settings.exchange_rates",
                "settings.currencies",
                "settings.locales",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["settings->channels"]);
        });

        test("should create custom role with settings (users) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["settings"]);
            await aclManagement.editRolePermission([
                "settings.data_transfer",
                "settings.taxes",
                "settings.themes",
                "settings.roles",
                "settings.channels",
                "settings.inventory_sources",
                "settings.exchange_rates",
                "settings.currencies",
                "settings.locales",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["settings->users"]);
        });

        test("should create custom role with settings (roles) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["settings"]);
            await aclManagement.editRolePermission([
                "settings.data_transfer",
                "settings.taxes",
                "settings.themes",
                "settings.users",
                "settings.channels",
                "settings.inventory_sources",
                "settings.exchange_rates",
                "settings.currencies",
                "settings.locales",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["settings->roles"]);
        });

        test("should create custom role with settings (themes) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["settings"]);
            await aclManagement.editRolePermission([
                "settings.data_transfer",
                "settings.taxes",
                "settings.roles",
                "settings.users",
                "settings.channels",
                "settings.inventory_sources",
                "settings.exchange_rates",
                "settings.currencies",
                "settings.locales",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["settings->themes"]);
        });

        test("should create custom role with settings (taxes) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["settings"]);
            await aclManagement.editRolePermission([
                "settings.data_transfer",
                "settings.themes",
                "settings.roles",
                "settings.users",
                "settings.channels",
                "settings.inventory_sources",
                "settings.exchange_rates",
                "settings.currencies",
                "settings.locales",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["settings->taxes"]);
        });

        test("should create custom role with settings (data_transfer) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["settings"]);
            await aclManagement.editRolePermission([
                "settings.taxes",
                "settings.themes",
                "settings.roles",
                "settings.users",
                "settings.channels",
                "settings.inventory_sources",
                "settings.exchange_rates",
                "settings.currencies",
                "settings.locales",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["settings->data_transfer"]);
        });
    });

    test.describe("configuration acl", () => {
        test("should create custom role with configuration permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["configuration"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["configuration"]);
        });
    });
});
