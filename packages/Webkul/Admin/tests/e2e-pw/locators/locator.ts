import { Locator, Page } from "@playwright/test";

export class WebLocators {
    /**
     * create role
     */
    readonly page: Page;
    readonly createRole: Locator;
    readonly selectType: Locator;
    readonly roleDescription: Locator;
    readonly saveRole: Locator;
    readonly successRole: Locator;
    readonly name: Locator;
    readonly iconEdit: Locator;
    readonly successEditRole: Locator;

    /**
     * user create
     */
    readonly createUser: Locator;
    readonly confirmPassword: Locator;
    readonly selectRole: Locator;
    readonly statusToggle: Locator;
    readonly saveUser: Locator;
    readonly successUser: Locator;

    /**
     * user login
     */
    readonly profile: Locator;
    readonly logout: Locator;
    readonly userEmail: Locator;
    readonly userPassword: Locator;

    readonly unauthorized: Locator;
    readonly createBtn: Locator;
    readonly saveBtn: Locator;
    readonly editBtn: Locator;
    readonly successMSG: Locator;
    readonly viewBtn: Locator;
    readonly copyBtn: Locator;
    readonly agreeBtn: Locator;
    readonly copySuccess: Locator;
    readonly selectRowBtn: Locator;
    readonly selectAction: Locator;
    readonly selectDelete: Locator;
    readonly productDeleteSuccess: Locator;
    readonly saveCategoryBtn: Locator;
    readonly categorySuccess: Locator;
    readonly deleteBtn: Locator;
    readonly categoryDeleteSuccess: Locator;
    readonly createAttributeBtn: Locator;
    readonly fillname: Locator;
    readonly fillCode: Locator;
    readonly selectTypeAttribute: Locator;
    readonly saveAttributeBtn: Locator;
    readonly attributeSuccess: Locator;
    readonly attributeUpdateSuccess: Locator;
    readonly deleteIcon: Locator;
    readonly attributeDeleteSuccess: Locator;
    readonly familyName: Locator;
    readonly familySuccess: Locator;
    readonly familyUpdateSuccess: Locator;
    readonly familyDeleteSuccess: Locator;
    readonly customerfirstname: Locator;
    readonly customerlastname: Locator;
    readonly customeremail: Locator;
    readonly customergender: Locator;
    readonly customerNumber: Locator;
    readonly customercreatedsuccess: Locator;
    readonly viewIcon: Locator;
    readonly customerDeleteSuccess: Locator;
    readonly successGroupMSG: Locator;
    readonly successUpdateMSG: Locator;
    readonly successGroupDeleteMSG: Locator;
    readonly pagetitle: Locator;
    readonly status: Locator;
    readonly metaTitle: Locator;
    readonly urlKey: Locator;
    readonly metaKeywords: Locator;
    readonly metaDescription: Locator;
    readonly successPageCreate: Locator;
    readonly successPageUpdate: Locator;
    readonly successPageDelete: Locator;
    readonly ruleName: Locator;
    readonly ruleDescription: Locator;
    readonly addConditionBtn: Locator;
    readonly selectCondition: Locator;
    readonly conditionName: Locator;
    readonly discountAmmount: Locator;
    readonly sortOrder: Locator;
    readonly channelSelect: Locator;
    readonly customerGroupSelect: Locator;
    readonly toggleInput: Locator;
    readonly cartRuleSuccess: Locator;
    readonly cartRuleCopySuccess: Locator;
    readonly cartRuleEditSuccess: Locator;
    readonly cartRuleDeleteSuccess: Locator;
    readonly catalogRuleCreateSuccess: Locator;
    readonly catalogRuleUpdateSuccess: Locator;
    readonly catalogRuleDeleteSuccess: Locator;
    readonly emailStatusSeletct: Locator;
    readonly emailSuccessMSG: Locator;
    readonly emailDescription: Locator;
    readonly emailUpdateSuccessMSG: Locator;
    readonly emailDeleteSuccessMSG: Locator;
    readonly date: Locator;
    readonly eventCreateSuccess: Locator;
    readonly eventUpdateSuccess: Locator;
    readonly eventDeleteSuccess: Locator;
    readonly subject: Locator;
    readonly event: Locator;
    readonly emailTemplate: Locator;
    readonly selectChannel: Locator;
    readonly customerGroup: Locator;
    readonly campaignStatus: Locator;
    readonly campaignCreateSuccess: Locator;
    readonly campaignUpdateSuccess: Locator;
    readonly campaignDeleteSuccess: Locator;
    readonly entityType: Locator;
    readonly requestPath: Locator;
    readonly targetPath: Locator;
    readonly redirectPath: Locator;
    readonly locale: Locator;
    readonly saveRedirectSuccess: Locator;
    readonly saveRedirectUpdatedSuccess: Locator;
    readonly deleteRedirectSuccess: Locator;
    readonly searchQuery: Locator;
    readonly redirectURL: Locator;
    readonly searchTermCreateSuccess: Locator;
    readonly searchTermUpdateSuccess: Locator;
    readonly searchTermDeleteSuccess: Locator;
    readonly terms: Locator;
    readonly searchSynonymCreateSuccess: Locator;
    readonly searchSynonymUpdateSuccess: Locator;
    readonly searchSynonymDeleteSuccess: Locator;
    readonly fileName: Locator;
    readonly path: Locator;
    readonly sitemapCreateSuccess: Locator;
    readonly sitemapUpdateSuccess: Locator;
    readonly sitemapDeleteSuccess: Locator;
    readonly direction: Locator;
    readonly successLocaleCreate: Locator;
    readonly successLocaleUpdate: Locator;
    readonly successLocaleDelete: Locator;
    readonly successCurrencyCreate: Locator;
    readonly successCurrencyUpdate: Locator;
    readonly successCurrencyDelete: Locator;
    readonly targetCurrency: Locator;
    readonly rate: Locator;
    readonly successExchangeRateCreate: Locator;
    readonly successExchangeRateUpdate: Locator;
    readonly successExchangeRateDelete: Locator;
    readonly description: Locator;
    readonly contactNumber: Locator;
    readonly contactName: Locator;
    readonly email: Locator;
    readonly fax: Locator;
    readonly city: Locator;
    readonly street: Locator;
    readonly postcode: Locator;
    readonly country: Locator;
    readonly state: Locator;
    readonly successInventorySourceCreate: Locator;
    readonly successInventorySourceUpdate: Locator;
    readonly successInventorySourceDelete: Locator;
    readonly inventoryToggle: Locator;
    readonly categoryID: Locator;
    readonly hostname: Locator;
    readonly selectLocale: Locator;
    readonly defaultLocaleID: Locator;
    readonly selectCurrency: Locator;
    readonly baseCurrencyID: Locator;
    readonly metaTitleChannel: Locator;
    readonly seoKeywords: Locator;
    readonly channleCreateSuccess: Locator;
    readonly metaDescriptionChannel: Locator;
    readonly channlUpdateSuccess: Locator;
    readonly channelDeleteSuccess: Locator;
    readonly successUserUpdate: Locator;
    readonly successUserDelete: Locator;
    readonly successUpdateRole: Locator;
    readonly successDeleteRole: Locator;
    readonly theme: Locator;
    readonly themeCreateSuccess: Locator;
    readonly successEditTheme: Locator;
    readonly successDeleteTheme: Locator;
    readonly athorization: Locator;
    readonly successCreateTaxRate: Locator;
    readonly successUpdateTaxRate: Locator;
    readonly identifier: Locator;
    readonly taxRate: Locator;
    readonly successDeleteTaxRate: Locator;
    readonly selectTaxRate: Locator;
    readonly successCreateTaxCategory: Locator;
    readonly successUpdateTaxCategory: Locator;
    readonly successDeleteTaxCategory: Locator;
    readonly selectCountry: Locator;
    readonly saveCartRuleBTN: Locator;

    constructor(page: Page) {
        this.page = page;

        /**
         * create role
         */
        this.createRole = page.locator("a.primary-button:visible");
        this.name = page.locator('input[name="name"]');
        this.selectType = page.locator('select[name="permission_type"]');
        this.roleDescription = page.locator('textarea[name="description"]');
        this.saveRole = page.locator(
            'button.primary-button:visible:has-text("Save Role")',
        );
        this.successRole = page.getByText("Roles Created Successfully");
        this.iconEdit = page.locator(".icon-edit");
        this.successEditRole = page
            .locator("#app")
            .getByText("Roles is updated successfully");

        /**
         * user create
         */
        this.createUser = page.getByRole("button", { name: "Create User" });
        this.confirmPassword = page.locator(
            'input[name="password_confirmation"]',
        );
        this.selectRole = page.locator('select[name="role_id"]');
        this.statusToggle = page.locator('label[for="status"]');
        this.saveUser = page.getByRole("button", { name: "Save User" });
        this.successUser = page.getByText("User created successfully.");

        /**
         * user login
         */
        this.profile = page.locator("div.flex.select-none >> button");
        this.logout = page.getByRole("link", { name: "Logout" });
        this.userEmail = page.locator('input[name="email"]');
        this.userPassword = page.locator('input[name="password"]');

        this.unauthorized = page.getByText("401").first();
        this.createBtn = page.locator(".primary-button");
        this.saveBtn = page.locator("button.secondary-button");
        this.editBtn = page
            .locator("span.cursor-pointer.icon-sort-right")
            .nth(1);
        this.successMSG = page.getByText("Product updated successfully");
        this.viewBtn = page.locator("span.cursor-pointer.icon-sort-right");
        this.copyBtn = page.locator("span.icon-copy");
        this.agreeBtn = page.getByRole("button", {
            name: "Agree",
            exact: true,
        });
        this.copySuccess = page.getByText("Product copied successfully");
        this.selectRowBtn = page.locator(".icon-uncheckbox");
        this.selectAction = page.getByRole("button", { name: "Select Action" });
        this.selectDelete = page.getByRole("link", { name: "Delete" });
        this.productDeleteSuccess = page.getByText(
            "Selected Products Deleted Successfully",
        );
        this.saveCategoryBtn = page.getByRole("button", {
            name: "Save Category",
        });
        this.categorySuccess = page.getByText("Category updated successfully.");
        this.deleteBtn = page.getByRole("link", { name: "Delete" });
        this.categoryDeleteSuccess = page.getByText(
            "The category has been successfully deleted.",
        );
        this.createAttributeBtn = page.getByRole("link", {
            name: "Create Attributes",
        });
        this.fillname = page.locator('input[name="admin_name"]');
        this.fillCode = page.locator('input[name="code"]');
        this.selectTypeAttribute = page.locator('select[name="type"]');
        this.saveAttributeBtn = page.locator("button.primary-button");
        this.attributeSuccess = page.getByText(
            "Attribute created successfully",
        );
        this.attributeUpdateSuccess = page.getByText(
            "Attribute Updated Successfully",
        );
        this.deleteIcon = page.locator(".icon-delete");
        this.attributeDeleteSuccess = page.getByText(
            /Attribute Deleted Successfully|Attribute Deleted Failed/,
        );
        this.familyName = page.locator('input[name="name"]');
        this.familySuccess = page.getByText("Family created successfully.");
        this.familyUpdateSuccess = page.getByText(
            "Family updated successfully.",
        );
        this.familyDeleteSuccess = page.getByText(
            /Family deleted successfully./,
        );
        this.customerfirstname = page.locator('input[name="first_name"]');
        this.customerlastname = page.locator('input[name="last_name"]');
        this.customeremail = page.locator('input[name="email"]');
        this.customergender = page.locator('select[name="gender"]');
        this.customerNumber = page.locator('input[name="phone"]');
        this.customercreatedsuccess = page.getByText(
            "Customer created successfully",
        );
        this.viewIcon = page.locator("a.icon-sort-right.cursor-pointer");
        this.customerDeleteSuccess = page.getByText(
            "Selected data successfully deleted",
        );
        this.successGroupMSG = page.getByText("Group created successfully");
        this.successUpdateMSG = page.getByText("Group Updated Successfully");
        this.successGroupDeleteMSG = page.getByText(
            /Group deleted successfully/i,
        );
        this.pagetitle = page.locator("#page_title");
        this.status = page.locator(".icon-uncheckbox");
        this.metaTitle = page.locator("#meta_title");
        this.urlKey = page.locator("#url_key");
        this.metaKeywords = page.locator("#meta_keywords");
        this.metaDescription = page.locator("#meta_description");
        this.successPageCreate = page.locator("#app p", {
            hasText: "CMS created successfully.",
        });
        this.successPageUpdate = page.locator("#app p", {
            hasText: "CMS updated successfully.",
        });
        this.successPageDelete = page.locator("#app p", {
            hasText: "CMS deleted successfully.",
        });
        this.ruleName = page.locator("#name");
        this.ruleDescription = page.locator("#description");
        this.addConditionBtn = page.locator(
            'div.secondary-button:has-text("Add Condition")',
        );
        this.selectCondition = page.locator(
            'select[id="conditions[0][attribute]"]',
        );
        this.conditionName = page.locator('input[name="conditions[0][value]"]');
        this.discountAmmount = page.locator('input[name="discount_amount"]');
        this.sortOrder = page.locator('input[name="sort_order"]');
        this.channelSelect = page.locator('label[for="channel__1"]');
        this.customerGroupSelect = page.locator(
            'label[for="customer_group__2"]',
        );
        this.toggleInput = page.locator('input[name="status"]');
        this.cartRuleSuccess = page.getByText("Cart rule created successfully");
        this.cartRuleCopySuccess = page.getByText(
            "cart rule copied successfully",
        );
        this.cartRuleEditSuccess = page.getByText(
            "Cart rule updated successfully",
        );
        this.cartRuleDeleteSuccess = page.getByText(
            /Cart Rule Deleted Successfully/i,
        );
        this.catalogRuleCreateSuccess = page.getByText(
            "Catalog rule created successfully",
        );
        this.catalogRuleUpdateSuccess = page.getByText(
            "Catalog rule updated successfully",
        );
        this.catalogRuleDeleteSuccess = page.getByText(
            /Catalog Rule Deleted Successfully/i,
        );
        this.emailStatusSeletct = page.locator('select[name="status"]');
        this.emailSuccessMSG = page.getByText(
            "Email Template created successfully.",
        );
        this.emailDescription = page.locator("#content_ifr");
        this.emailUpdateSuccessMSG = page.getByText("Updated successfully");
        this.emailDeleteSuccessMSG = page.getByText(
            "Template Deleted successfully",
        );
        this.date = page.locator('input[name="date"]');
        this.eventCreateSuccess = page.getByText("Events Created Successfully");
        this.eventUpdateSuccess = page.getByText("Events Updated Successfully");
        this.eventDeleteSuccess = page.getByText("Events Deleted Successfully");
        this.subject = page.locator('input[name="subject"]');
        this.event = page.locator("select[name='marketing_event_id']");
        this.emailTemplate = page.locator(
            "select[name='marketing_template_id']",
        );
        this.selectChannel = page.locator("select[name='channel_id']");
        this.customerGroup = page.locator("select[name='customer_group_id']");
        this.campaignStatus = page.locator(".peer.h-5");
        this.campaignCreateSuccess = page.getByText(
            "Campaign created successfully.",
        );
        this.campaignUpdateSuccess = page.getByText(
            "Campaign updated successfully.",
        );
        this.campaignDeleteSuccess = page.getByText(
            "Campaign deleted successfully",
        );
        this.entityType = page.locator('select[name="entity_type"]');
        this.requestPath = page.getByRole("textbox", { name: "Request Path" });
        this.targetPath = page.getByRole("textbox", { name: "Target Path" });
        this.redirectPath = page.locator('select[name="redirect_type"]');
        this.locale = page.locator('select[name="locale"]');
        this.saveRedirectSuccess = page.getByText(
            "URL Rewrite created successfully",
        );
        this.saveRedirectUpdatedSuccess = page.getByText(
            "URL Rewrite updated successfully",
        );
        this.deleteRedirectSuccess = page.getByText("URL Rewrite deleted");
        this.searchQuery = page.getByRole("textbox", { name: "Search Query" });
        this.redirectURL = page.getByRole("textbox", { name: "Redirect Url" });
        this.locale = page.locator('select[name="locale"]');
        this.searchTermCreateSuccess = page.getByText(
            "Search Term created successfully",
        );
        this.searchTermUpdateSuccess = page.getByText(
            "Search Term updated successfully",
        );
        this.searchTermDeleteSuccess = page.getByText(
            "Search Term deleted successfully",
        );
        this.terms = page.getByRole("textbox", { name: "Terms" });
        this.searchSynonymCreateSuccess = page.getByText(
            "Search Synonym created successfully",
        );
        this.searchSynonymUpdateSuccess = page.getByText(
            "Search Synonym updated successfully",
        );
        this.searchSynonymDeleteSuccess = page.getByText(
            "Search Synonym deleted successfully",
        );
        this.fileName = page.locator('input[name="file_name"]');
        this.path = page.locator('input[name="path"]');
        this.sitemapCreateSuccess = page.getByText(
            "Sitemap created successfully",
        );
        this.sitemapUpdateSuccess = page.getByText(
            "Sitemap updated successfully",
        );
        this.sitemapDeleteSuccess = page.getByText(
            "Sitemap deleted successfully",
        );
        this.direction = page.locator('select[name="direction"]');
        this.successLocaleCreate = page.getByText(
            "Locale created successfully",
        );
        this.successLocaleUpdate = page.getByText(
            "Locale updated successfully",
        );
        this.successLocaleDelete = page.getByText(
            "Locale deleted successfully",
        );
        this.successCurrencyCreate = page.getByText(
            "Currency created successfully",
        );
        this.successCurrencyUpdate = page.getByText(
            "Currency updated successfully",
        );
        this.successCurrencyDelete = page.getByText(
            "Currency deleted successfully",
        );
        this.targetCurrency = page.locator('select[name="target_currency"]');
        this.rate = page.locator('input[name="rate"]');
        this.successExchangeRateCreate = page.getByText(
            "Exchange Rate created successfully",
        );
        this.successExchangeRateUpdate = page.getByText(
            "Exchange Rate updated successfully",
        );
        this.successExchangeRateDelete = page.getByText(
            "Exchange Rate deleted successfully",
        );
        this.description = page.getByRole("textbox", { name: "Description" });
        this.contactNumber = page.getByRole("textbox", {
            name: "Contact Number",
        });
        this.contactName = page.locator("#contact_name");
        this.email = page.getByRole("textbox", { name: "Email" });
        this.fax = page.getByRole("textbox", { name: "Fax" });
        this.city = page.getByRole("textbox", { name: "City" });
        this.street = page.getByRole("textbox", { name: "Street" });
        this.postcode = page.getByRole("textbox", { name: "Postcode" });
        this.country = page.locator("#country");
        this.state = page.locator("#state");
        this.successInventorySourceCreate = page.getByText(
            "Inventory Source Created Successfully",
        );
        this.successInventorySourceUpdate = page.getByText(
            "Inventory Sources Updated Successfully",
        );
        this.successInventorySourceDelete = page.getByText(
            "Inventory Sources Deleted Successfully",
        );
        this.inventoryToggle = page.locator('label[for="inventory_sources_1"]');
        this.categoryID = page.locator("#root_category_id");
        this.hostname = page.locator("#hostname");
        this.selectLocale = page.locator('label[for="locales_1"]');
        this.defaultLocaleID = page.locator("#default_locale_id");
        this.selectCurrency = page.locator('label[for="currencies_1"]');
        this.baseCurrencyID = page.locator("#base_currency_id");
        this.metaTitleChannel = page.locator("#meta_title");
        this.metaDescriptionChannel = page.locator("#meta_description");
        this.seoKeywords = page.locator("#seo_keywords");
        this.channleCreateSuccess = page.getByText(
            "Channel created successfully.",
        );
        this.channlUpdateSuccess = page.getByText(
            "Update Channel Successfully",
        );
        this.channelDeleteSuccess = page.getByText(
            "Channel deleted successfully.",
        );
        this.successUserUpdate = page.getByText("User updated successfully.");
        this.successUserDelete = page.getByText("User deleted successfully.");
        this.successUpdateRole = page.getByText(
            "Roles is updated successfully",
        );
        this.successDeleteRole = page.getByText(
            "Roles is deleted successfully",
        );
        this.theme = page.locator('select[name="theme_code"]');
        this.themeCreateSuccess = page.getByText("Theme created successfully");
        this.successEditTheme = page.getByText("Theme updated successfully");
        this.successDeleteTheme = page.getByText("Theme deleted successfully");
        this.athorization = page.getByText("401");
        this.successCreateTaxRate = page.getByText(
            "Tax Rate created successfully.",
        );
        this.identifier = page.locator('input[name="identifier"]');
        this.taxRate = page.locator('input[name="tax_rate"]');
        this.successUpdateTaxRate = page
            .locator("#app")
            .getByText("Tax Rate Update Successfully");
        this.successDeleteTaxRate = page
            .locator("#app")
            .getByText("Tax Rate delete successfully");
        this.selectTaxRate = page.locator('select[name="taxrates[]"]');
        this.successCreateTaxCategory = page.getByText(
            "New Tax Category created",
        );
        this.successUpdateTaxCategory = page.getByText(
            "Tax Category Successfully Updated.",
        );
        this.successDeleteTaxCategory = page.getByText(
            "Tax Rates Assigned Categories cannot be deleted.",
        );
        this.selectCountry = page.locator('select[name="country"]');
        this.saveCartRuleBTN = page.getByRole("button", {
            name: " Save Cart Rule ",
        });
    }
}
