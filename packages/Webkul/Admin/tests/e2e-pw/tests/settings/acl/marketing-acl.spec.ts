import { test } from "../../../setup";
import { ACLManagement } from "../../../pages/acl";

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
        await aclManagement.cartRuleCreateVerify();
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
        await aclManagement.cartRuleCopyVerify();
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
        await aclManagement.cartRuleEditVerify();
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
        await aclManagement.cartRuleDeleteVerify();
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
        await aclManagement.catalogRuleCreateVerify();
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
            "marketing->promotions->catalog_rules",
        ]);
        await aclManagement.catalogRuleEditVerify();
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
            "marketing->promotions->catalog_rules",
        ]);
        await aclManagement.catalogRuleDeleteVerify();
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
        await aclManagement.verfiyAssignedRole(["marketing->communications"]);
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
        await aclManagement.communicationEmailTemplateCreateVerify();
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
        await aclManagement.communicationEmailTemplateEditVerify();
    });

    test("should create custom role with marketing (communications-> email template-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.communications.email_templates.delete",
            "marketing.communications.email_templates.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->communications->email",
        ]);
        await aclManagement.communicationEmailTemplateDeleteVerify();
        await adminPage.goto("admin/marketing/communications/email-templates");
        await aclManagement.communicationEmailTemplateCreateVerify();
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
            "marketing->communications->event",
        ]);
        await adminPage.goto("admin/marketing/communications/events");
        await aclManagement.eventCreateVerify();
    });

    // test("should create custom role with marketing (communications-> events-> edit) permission", async ({
    //     adminPage,
    // }) => {
    //     const aclManagement = new ACLManagement(adminPage);
    //     await aclManagement.createRole("custom", [
    //         "marketing.communications.events.edit",
    //     ]);
    //     await aclManagement.createUser();
    //     await aclManagement.verfiyAssignedRole([
    //         "marketing->communications->event",
    //     ]);
    //     await aclManagement.eventEditVerify();
    // });

    test("should create custom role with marketing (communications-> events-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.communications.events.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->communications->event",
        ]);
        await aclManagement.eventDeleteVerify();
    });

    test("should create custom role with marketing (communications-> campaigns-> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.communications.campaigns.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->communications->campaign",
        ]);
        await aclManagement.campaignCreateVerify();
    });

    test("should create custom role with marketing (communications-> campaigns-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.communications.campaigns.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->communications->campaign",
        ]);
        await adminPage.goto("admin/marketing/communications/campaigns");
        await aclManagement.campaignEditVerify();
    });

    test("should create custom role with marketing (communications-> campaigns-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.communications.campaigns.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->communications->campaign",
        ]);
        await adminPage.goto("admin/marketing/communications/campaigns");
        await aclManagement.campaignDeleteVerify();
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

    test("should create custom role with marketing (search_seo->url_rewrites->create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.search_seo.url_rewrites.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->search_seo->url_rewrites",
        ]);
        await aclManagement.urlRewriteCreateVerify();
    });

    test("should create custom role with marketing (search_seo->url_rewrites->edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.search_seo.url_rewrites.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->search_seo->url_rewrites",
        ]);
        await aclManagement.urlRewriteEditVerify();
    });

    test("should create custom role with marketing (search_seo->url_rewrites->delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.search_seo.url_rewrites.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->search_seo->url_rewrites",
        ]);
        await aclManagement.urlRewriteDeleteVerify();
    });

    test("should create custom role with marketing (search_seo->search_terms->create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.search_seo.search_terms.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->search_seo->search_terms",
        ]);
        await aclManagement.searchTermsCreateVerify();
    });

    test("should create custom role with marketing (search_seo->search_terms->edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.search_seo.search_terms.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->search_seo->search_terms",
        ]);
        await aclManagement.searchTermsEditVerify();
    });

    test("should create custom role with marketing (search_seo->search_terms->delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.search_seo.search_terms.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->search_seo->search_terms",
        ]);
        await aclManagement.searchTermsDeleteVerify();
    });

    test("should create custom role with marketing (search_seo->search_synonyms->create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.search_seo.search_synonyms.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->search_seo->search_synonyms",
        ]);
        await aclManagement.searchSynonymsCreateVerify();
    });

    test("should create custom role with marketing (search_seo->search_synonyms->edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.search_seo.search_synonyms.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->search_seo->search_synonyms",
        ]);
        await aclManagement.searchSynonymsEditVerify();
    });

    test("should create custom role with marketing (search_seo->search_synonyms->delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.search_seo.search_synonyms.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->search_seo->search_synonyms",
        ]);
        await aclManagement.searchSynonymsDeleteVerify();
    });

    test("should create custom role with marketing (search_seo->sitemaps->create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.search_seo.sitemaps.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->search_seo->sitemaps",
        ]);
        await aclManagement.sitemapCreateVerify();
    });

    test("should create custom role with marketing (search_seo->sitemaps->edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.search_seo.sitemaps.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->search_seo->sitemaps",
        ]);
        await aclManagement.sitemapEditVerify();
    });

    test("should create custom role with marketing (search_seo->sitemaps->delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.search_seo.sitemaps.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->search_seo->sitemaps",
        ]);
        await aclManagement.sitemapDeleteVerify();
    });
});
