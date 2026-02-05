import { test } from "../../../setup";
import { ACLManagement } from "../../../pages/acl";
import {
    generateName,
    generateSlug,
    generateDescription,
} from "../../../utils/faker";

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
        await adminPage.goto("admin/cms");
        await adminPage.click('a.primary-button:has-text("Create Page")');
        await adminPage.waitForLoadState("networkidle");
        await adminPage.fillInTinymce("#content_ifr", cms.shortDescription);
        await aclManagement.cmsCreateVerify();
    });

    test("should create custom role with cms (edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["cms.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["cms"]);
        await adminPage.goto("admin/cms");
        await aclManagement.cmsEditVerify();
    });

    test("should create custom role with cms (delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["cms.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["cms"]);
        await adminPage.goto("admin/cms");
        await aclManagement.cmsDeleteVerify();
    });
});
