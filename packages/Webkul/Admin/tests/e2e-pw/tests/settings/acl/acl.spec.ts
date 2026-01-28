import { test } from "../../../setup";
import { ACLManagement } from "../../../pages/acl";

test.describe("acl management", () => {
    test("should create role for all permission", async ({ adminPage }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("all");
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["all"]);
    });

    test("should create custome role with dashboard permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["dashboard"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["dashboard"]);
    });

    test.describe("sales acl", () => {
        test("should create custome role with sales permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales"]);
        });

        test("should create custome role with sales (order) permission", async ({
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

        test("should create custome role with sales (transaction) permission", async ({
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

        test("should create custome role with sales (shipments) permission", async ({
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

        test("should create custome role with sales (invoices) permission", async ({
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

        test("should create custome role with sales (refunds) permission", async ({
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

    test.describe("catalog acl", () => {
        test("should create custome role with catalog permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["catalog"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["catalog"]);
        });

        test("should create custome role with catalog (products) permission", async ({
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

        test("should create custome role with catalog (categories) permission", async ({
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

        test("should create custome role with catalog (attributes) permission", async ({
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

        test("should create custome role with catalog (families) permission", async ({
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
    });

    test.describe("customers acl", () => {
        test("should create custome role with customers permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["customers"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers"]);
        });

        test("should create custome role with customers (customers) permission", async ({
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
        test("should create custome role with customers (groups) permission", async ({
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
        test("should create custome role with customers (reviews) permission", async ({
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
        test("should create custome role with customers (gdpr) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["customers"]);
            await aclManagement.editRolePermission([
                "customers.reviews",
                "customers.customers",
                "customers.group",
                "customers.note",
                "customers.addresses",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["customers->gdpr"]);
        });
    });

    test.describe("cms acl", () => {
        test("should create custome role with cms permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["cms"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["cms"]);
        });
    });

    test.describe("marketing acl", () => {
        test("should create custome role with marketing permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["marketing"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["marketing"]);
        });
    });

    test.describe("reporting acl", () => {
        test("should create custome role with reporting permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["reporting"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["reporting"]);
        });
    });

    test.describe("configuration acl", () => {
        test("should create custome role with configuration permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["configuration"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["configuration"]);
        });
    });
});
