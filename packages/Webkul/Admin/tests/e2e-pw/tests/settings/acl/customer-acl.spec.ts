import { test } from "../../../setup";
import { ACLManagement } from "../../../pages/acl";

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
        await aclManagement.customerCreateVerify();
    });

    test("should create custom role with customers (customers->edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["customers.customers.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["customers->customers"]);
        await aclManagement.customerEditVerify();
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
        await adminPage.goto("admin/customers")
        await aclManagement.customerDeleteVerify();
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
        await aclManagement.createRole("custom", ["customers.groups.create"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["customers->groups"]);;
        await aclManagement.groupCreateVerify();
    });

    test("should create custom role with customers (groups-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["customers.groups.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["customers->groups"]);
        await aclManagement.groupEditVerify();
    });

    test("should create custom role with customers (groups-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["customers.groups.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["customers->groups"]);
        await aclManagement.groupDeleteVerify();
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
