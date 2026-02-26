import { test, expect } from "../../../setup";
import { ACLManagement } from "../../../pages/acl";

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
        await aclManagement.productEditVerify();
    });

    test("should create custom role with catalog (products -> copy) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["catalog.products.copy"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["catalog->products"]);
        await aclManagement.productCopyVerify();
    });

    test("should create custom role with catalog (products-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["catalog.products.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["catalog->products"]);
        await aclManagement.productDeleteVerify();
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
        await aclManagement.createRole("custom", ["catalog.categories.create"]);
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
        await aclManagement.createRole("custom", ["catalog.categories.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["catalog->categories"]);
        await aclManagement.categoryEditVerify();
    });

    test("should create custom role with catalog (categories -> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["catalog.categories.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["catalog->categories"]);
        await aclManagement.categoryDeleteVerify();
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
        await aclManagement.createRole("custom", ["catalog.attributes.create"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["catalog->attributes"]);
        await aclManagement.attributeCreateVerify();
    });

    test("should create custom role with catalog (attributes-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["catalog.attributes.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["catalog->attributes"]);
        await aclManagement.attributeEditVerify();
    });

    test("should create custom role with catalog (attributes-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["catalog.attributes.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["catalog->attributes"]);
        await aclManagement.attributeDeleteVerify();
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
        await aclManagement.createRole("custom", ["catalog.families.create"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["catalog->families"]);
        await aclManagement.familyCreateVerify();
    });

    test("should create custom role with catalog (families->edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["catalog.families.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["catalog->families"]);
        await aclManagement.familyEditVerify();
    });

    test("should create custom role with catalog (families->delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["catalog.families.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["catalog->families"]);
        await aclManagement.familyDeleteVerify();
    });
});
