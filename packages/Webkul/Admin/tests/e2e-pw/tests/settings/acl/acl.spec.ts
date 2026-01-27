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
