import { test } from "../../../setup";
import { ACLManagement } from "../../../pages/admin/acl/index";

test.describe("appearance acl", () => {
    test("should create custom role with appearance permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["appearance"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["appearance"]);
    });

    test("should create custom role with appearance (themes) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["appearance"]);
        await aclManagement.editRolePermission(["appearance.sections"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["appearance->themes"]);
    });

    test("should create custom role with appearance (sections) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["appearance"]);
        await aclManagement.editRolePermission(["appearance.themes"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["appearance->sections"]);
    });
});
