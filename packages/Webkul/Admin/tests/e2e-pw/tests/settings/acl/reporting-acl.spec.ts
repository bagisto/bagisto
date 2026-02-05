import { test } from "../../../setup";
import { ACLManagement } from "../../../pages/acl";

test.describe("reporting acl", () => {
    test("should create custom role with reporting permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["reporting"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["reporting"]);
    });

    test("should create custom role with reporting (sales) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["reporting.sales"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["reporting->sales"]);
    });

    test("should create custom role with reporting (customers) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["reporting.customers"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["reporting->customers"]);
    });

    test("should create custom role with reporting (products) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["reporting.products"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["reporting->products"]);
    });
});
