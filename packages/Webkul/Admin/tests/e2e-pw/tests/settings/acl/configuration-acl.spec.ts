import { test } from "../../../setup";
import { ACLManagement } from "../../../pages/admin/acl/index";

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
