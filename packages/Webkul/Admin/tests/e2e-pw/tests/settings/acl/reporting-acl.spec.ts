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
});
