import { Locator, Page } from "@playwright/test";

export class ACLAdminLocators {
    constructor(private page: Page) {}

    /** ---------------- COMMON / GENERIC ---------------- */

    get addCartSuccess() {
        return this.page.getByText("Item Added Successfully");
    }
    get addConditionBtn() {
        return this.page.locator(
            'div.secondary-button:has-text("Add Condition")',
        );
    }
    get addToCartButton() {
        return this.page.locator(
            "(//button[contains(@class,'secondary-button')])[2]",
        );
    }
    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }
    get athorization() {
        return this.page.getByText("401");
    }

    get attributeDeleteSuccess() {
        return this.page.getByText(/Attribute Deleted/);
    }
    get attributeSuccess() {
        return this.page.getByText("Attribute created successfully");
    }
    get attributeUpdateSuccess() {
        return this.page.getByText("Attribute Updated Successfully");
    }

    get baseCurrencyID() {
        return this.page.locator("#base_currency_id");
    }

    get campaignCreateSuccess() {
        return this.page.getByText("Campaign created successfully.");
    }
    get campaignDeleteSuccess() {
        return this.page.getByText("Campaign deleted successfully");
    }
    get campaignStatus() {
        return this.page.locator(".peer.h-5");
    }
    get campaignUpdateSuccess() {
        return this.page.getByText("Campaign updated successfully.");
    }

    get cartRuleCopySuccess() {
        return this.page.getByText("cart rule copied successfully");
    }
    get cartRuleDeleteSuccess() {
        return this.page.getByText(/Cart Rule Deleted Successfully/i);
    }
    get cartRuleEditSuccess() {
        return this.page.getByText("Cart rule updated successfully");
    }
    get cartRuleSuccess() {
        return this.page.getByText("Cart rule created successfully");
    }

    get catalogRuleCreateSuccess() {
        return this.page.getByText("Catalog rule created successfully");
    }
    get catalogRuleDeleteSuccess() {
        return this.page.getByText(/Catalog Rule Deleted Successfully/i);
    }
    get catalogRuleUpdateSuccess() {
        return this.page.getByText("Catalog rule updated successfully");
    }

    get categoryDeleteSuccess() {
        return this.page.getByText(
            "The category has been successfully deleted.",
        );
    }
    get categoryID() {
        return this.page.locator("#root_category_id");
    }
    get categorySuccess() {
        return this.page.getByText("Category updated successfully.");
    }

    get channelDeleteSuccess() {
        return this.page.getByText("Channel deleted successfully.");
    }
    get channelSelect() {
        return this.page.locator('label[for="channel__1"]');
    }
    get channleCreateSuccess() {
        return this.page.getByText("Channel created successfully.");
    }
    get channlUpdateSuccess() {
        return this.page.getByText("Update Channel Successfully");
    }

    get checkBox() {
        return this.page.locator('input[name^="isChecked["]');
    }

    get choosePaymentMethod() {
        return this.page.getByAltText("Money Transfer");
    }
    get chooseShippingMethod() {
        return this.page.getByText("Free Shipping").first();
    }

    get city() {
        return this.page.getByRole("textbox", { name: "City" });
    }

    get clickPlaceOrderButton() {
        return this.page.getByRole("button", { name: "Place Order" });
    }
    get clickProcessButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    get conditionName() {
        return this.page.locator('input[name="conditions[0][value]"]');
    }

    get confirmPassword() {
        return this.page.locator('input[name="password_confirmation"]');
    }

    get contactName() {
        return this.page.locator("#contact_name");
    }
    get contactNumber() {
        return this.page.getByRole("textbox", { name: "Contact Number" });
    }

    get ContinueButton() {
        return this.page.locator(
            '(//a[contains(.," Continue to Checkout ")])[1]',
        );
    }

    get copyBtn() {
        return this.page.locator("span.icon-copy");
    }
    get copySuccess() {
        return this.page.getByText("Product copied successfully");
    }

    get country() {
        return this.page.locator("#country");
    }

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get createRMAReason() {
        return this.page.getByRole("button", { name: " Create RMA Reason " });
    }
    get createRMAStatus() {
        return this.page.getByRole("button", { name: "Create RMA Status" });
    }

    get createRole() {
        return this.page.locator("a.primary-button:visible");
    }
    get createUser() {
        return this.page.getByRole("button", { name: "Create User" });
    }

    get customercreatedsuccess() {
        return this.page.getByText("Customer created successfully");
    }
    get customerDeleteSuccess() {
        return this.page.getByText("Selected data successfully deleted");
    }

    get customeremail() {
        return this.page.locator('input[name="email"]');
    }
    get customerfirstname() {
        return this.page.locator('input[name="first_name"]');
    }
    get customergender() {
        return this.page.locator('select[name="gender"]');
    }
    get customerGroup() {
        return this.page.locator("select[name='customer_group_id']");
    }
    get customerGroupSelect() {
        return this.page.locator('label[for="customer_group__2"]');
    }
    get customerlastname() {
        return this.page.locator('input[name="last_name"]');
    }
    get customerNumber() {
        return this.page.locator('input[name="phone"]');
    }

    get date() {
        return this.page.locator('input[name="date"]');
    }

    get defaultLocaleID() {
        return this.page.locator("#default_locale_id");
    }

    get deleteBtn() {
        return this.page.getByRole("link", { name: "Delete" });
    }
    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }
    get deleteRedirectSuccess() {
        return this.page.getByText("URL Rewrite deleted");
    }

    get description() {
        return this.page.getByRole("textbox", { name: "Description" });
    }
    get direction() {
        return this.page.locator('select[name="direction"]');
    }

    get discountAmmount() {
        return this.page.locator('input[name="discount_amount"]');
    }

    get editBtn() {
        return this.page.locator("span.cursor-pointer.icon-sort-right").nth(1);
    }
    get editIcon() {
        return this.page.locator("a.icon-edit");
    }

    get emailDeleteSuccessMSG() {
        return this.page.getByText("Template Deleted successfully");
    }
    get emailStatusSeletct() {
        return this.page.locator('select[name="status"]');
    }
    get emailSuccessMSG() {
        return this.page.getByText("Email Template created successfully.");
    }
    get emailTemplate() {
        return this.page.locator("select[name='marketing_template_id']");
    }
    get emailUpdateSuccessMSG() {
        return this.page.getByText("Updated successfully");
    }

    get enterEmail() {
        return this.page.getByRole("textbox", { name: "Email" });
    }

    get entityType() {
        return this.page.locator('select[name="entity_type"]');
    }

    get event() {
        return this.page.locator("select[name='marketing_event_id']");
    }
    get eventCreateSuccess() {
        return this.page.getByText("Events Created Successfully");
    }
    get eventDeleteSuccess() {
        return this.page.getByText("Events Deleted Successfully");
    }
    get eventUpdateSuccess() {
        return this.page.getByText("Events Updated Successfully");
    }

    get familyDeleteSuccess() {
        return this.page.getByText(/Family deleted successfully./);
    }
    get familyName() {
        return this.page.locator('input[name="name"]');
    }
    get familySuccess() {
        return this.page.getByText("Family created successfully.");
    }
    get familyUpdateSuccess() {
        return this.page.getByText("Family updated successfully.");
    }

    get fax() {
        return this.page.getByRole("textbox", { name: "Fax" });
    }

    get fileName() {
        return this.page.locator('input[name="file_name"]');
    }

    get fillCode() {
        return this.page.locator('input[name="code"]');
    }
    get fillname() {
        return this.page.locator('input[name="admin_name"]');
    }

    get hostname() {
        return this.page.locator("#hostname");
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get identifier() {
        return this.page.locator('input[name="identifier"]');
    }

    get info() {
        return this.page.locator('textarea[name="information"]');
    }

    get inventoryToggle() {
        return this.page.locator('label[for="inventory_sources_1"]');
    }

    get locale() {
        return this.page.locator('select[name="locale"]');
    }

    get logout() {
        return this.page.getByRole("link", { name: "Logout" });
    }

    get metaDescription() {
        return this.page.locator("#meta_description");
    }
    get metaKeywords() {
        return this.page.locator("#meta_keywords");
    }
    get metaTitle() {
        return this.page.locator("#meta_title");
    }
    get metaTitleChannel() {
        return this.page.locator("#meta_title");
    }

    get name() {
        return this.page.locator('input[name="name"]');
    }

    get pagetitle() {
        return this.page.locator("#page_title");
    }

    get path() {
        return this.page.locator('input[name="path"]');
    }

    get position() {
        return this.page.locator('input[name="position"]');
    }

    get postcode() {
        return this.page.getByRole("textbox", { name: "Postcode" });
    }

    get productDeleteSuccess() {
        return this.page.getByText("Selected Products Deleted Successfully");
    }

    get profile() {
        return this.page.locator("div.flex.select-none >> button");
    }

    get promotionRuleDescription() {
        return this.page.locator("#description");
    }

    get rate() {
        return this.page.locator('input[name="rate"]');
    }

    get reason() {
        return this.page.locator('select[name="rma_reason_id"]');
    }
    get reasonStatus() {
        return this.page.locator('label[for="status"]');
    }
    get reasonTitle() {
        return this.page.locator('input[name="title"]');
    }
    get reasonType() {
        return this.page.locator('select[name="resolution_type[]"]');
    }

    get redirectPath() {
        return this.page.locator('select[name="redirect_type"]');
    }
    get redirectURL() {
        return this.page.getByRole("textbox", { name: "Redirect Url" });
    }

    get requestPath() {
        return this.page.getByRole("textbox", { name: "Request Path" });
    }

    get resolution() {
        return this.page.locator('select[name^="resolution_type"]');
    }

    get returnPeriod() {
        return this.page.getByPlaceholder("Return Period (Days)");
    }

    get rmaQTY() {
        return this.page.locator('input[name^="rma_qty"]');
    }

    get rmaRulesCreate() {
        return this.page.getByRole("button", { name: "Create RMA Rules" });
    }

    get rmaSelection() {
        return this.page.locator('select[name="rma_rule_id"]');
    }

    get rmaStatusTitle() {
        return this.page.getByRole("textbox", { name: "Title" });
    }

    get roleDescription() {
        return this.page.locator('textarea[name="description"]');
    }

    get ruleDeleteSuccessMSG() {
        return this.page.getByText("RMA Rules deleted successfully.");
    }

    get ruleDescription() {
        return this.page.getByRole("textbox", { name: "Rules Description" });
    }

    get ruleName() {
        return this.page.locator("#name");
    }

    get ruleSuccessMSG() {
        return this.page.getByText("RMA Rules created");
    }

    get ruleSuccessUpdatedMSG() {
        return this.page.getByText("RMA Rules updated successfully.");
    }

    get ruleTitle() {
        return this.page.getByRole("textbox", { name: "Rules Title" });
    }

    get saveAttributeBtn() {
        return this.page.locator("button.primary-button");
    }

    get saveBtn() {
        return this.page.locator("button.secondary-button");
    }

    get saveCartRuleBTN() {
        return this.page.getByRole("button", { name: " Save Cart Rule " });
    }

    get saveCategoryBtn() {
        return this.page.getByRole("button", { name: "Save Category" });
    }

    get saveReason() {
        return this.page.getByRole("button", { name: "Save Reason" });
    }

    get saveReasonDeleteSuccess() {
        return this.page.getByText("Reason deleted successfully.");
    }

    get saveReasonSuccess() {
        return this.page.getByText("Reason created successfully.");
    }

    get saveReasonUpdateSuccess() {
        return this.page.getByText("Reason updated successfully.");
    }

    get saveRedirectSuccess() {
        return this.page.getByText("URL Rewrite created successfully");
    }

    get saveRedirectUpdatedSuccess() {
        return this.page.getByText("URL Rewrite updated successfully");
    }

    get saveRole() {
        return this.page.locator('button.primary-button:has-text("Save Role")');
    }

    get saveRule() {
        return this.page.getByRole("button", { name: "Save RMA Rules" });
    }

    get saveStatus() {
        return this.page.getByRole("button", { name: "Save RMA Status" });
    }

    get saveUser() {
        return this.page.getByRole("button", { name: "Save User" });
    }

    get searchInput() {
        return this.page.getByRole("textbox", { name: "Search products here" });
    }

    get searchQuery() {
        return this.page.getByRole("textbox", { name: "Search Query" });
    }

    get searchSynonymCreateSuccess() {
        return this.page.getByText("Search Synonym created successfully");
    }
    get searchSynonymDeleteSuccess() {
        return this.page.getByText("Search Synonym deleted successfully");
    }
    get searchSynonymUpdateSuccess() {
        return this.page.getByText("Search Synonym updated successfully");
    }

    get searchTermCreateSuccess() {
        return this.page.getByText("Search Term created successfully");
    }
    get searchTermDeleteSuccess() {
        return this.page.getByText("Search Term deleted successfully");
    }
    get searchTermUpdateSuccess() {
        return this.page.getByText("Search Term updated successfully");
    }

    get selectAction() {
        return this.page.getByRole("button", { name: "Select Action" });
    }

    get selectChannel() {
        return this.page.locator("select[name='channel_id']");
    }

    get selectCondition() {
        return this.page.locator('select[id="conditions[0][attribute]"]');
    }

    get selectCountry() {
        return this.page.locator('select[name="country"]');
    }

    get selectCurrency() {
        return this.page.locator('label[for="currencies_1"]');
    }

    get selectDelete() {
        return this.page.getByRole("link", { name: "Delete" });
    }

    get selectLocale() {
        return this.page.locator('label[for="locales_1"]');
    }

    get selectRole() {
        return this.page.locator('select[name="role_id"]');
    }

    get selectRoleType() {
        return this.page.locator('select[name="permission_type"]');
    }

    get selectRowBtn() {
        return this.page.locator(".icon-uncheckbox");
    }

    get selectTaxRate() {
        return this.page.locator('select[name="taxrates[]"]');
    }

    get selectTypeAttribute() {
        return this.page.locator('select[name="type"]');
    }

    get seoKeywords() {
        return this.page.locator("#seo_keywords");
    }

    get ShoppingCartIcon() {
        return this.page.locator("(//span[contains(@class,'icon-cart')])[1]");
    }

    get sitemapCreateSuccess() {
        return this.page.getByText("Sitemap created successfully");
    }
    get sitemapDeleteSuccess() {
        return this.page.getByText("Sitemap deleted successfully");
    }
    get sitemapUpdateSuccess() {
        return this.page.getByText("Sitemap updated successfully");
    }

    get sortOrder() {
        return this.page.locator('input[name="sort_order"]');
    }

    get state() {
        return this.page.locator("#state");
    }

    get statusBTN() {
        return this.page.locator(".icon-uncheckbox");
    }

    get statusDeleteSuccess() {
        return this.page.getByText("Selected rma status deleted successfully.");
    }

    get statusSuccess() {
        return this.page.getByText("RMA Status created");
    }

    get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    get statusUpdateSuccess() {
        return this.page.getByText("RMA Status updated successfully.");
    }

    get street() {
        return this.page.getByRole("textbox", { name: "Street" });
    }

    get subject() {
        return this.page.locator('input[name="subject"]');
    }

    get successAdminRMA() {
        return this.page.getByText("RMA created successfully.");
    }

    get successCreateTaxCategory() {
        return this.page.getByText("Tax category created successfully.");
    }

    get successCreateTaxRate() {
        return this.page.getByText("Tax Rate created successfully.");
    }

    get successCurrencyCreate() {
        return this.page.getByText("Currency created successfully");
    }

    get successCurrencyDelete() {
        return this.page.getByText("Currency deleted successfully");
    }

    get successCurrencyUpdate() {
        return this.page.getByText("Currency updated successfully");
    }

    get successDeleteRole() {
        return this.page.getByText("Roles is deleted successfully");
    }

    get successDeleteTaxCategory() {
        return this.page.getByText(
            "Tax Rates Assigned Categories cannot be deleted.",
        );
    }

    get successDeleteTaxRate() {
        return this.page.getByText("Tax Rate delete successfully");
    }

    get successDeleteTheme() {
        return this.page.getByText("Theme deleted successfully");
    }

    get successEditRole() {
        return this.page.getByText("Roles is updated successfully");
    }

    get successEditTheme() {
        return this.page.getByText("Theme updated successfully");
    }

    get successExchangeRateCreate() {
        return this.page.getByText("Exchange Rate created successfully");
    }

    get successExchangeRateDelete() {
        return this.page.getByText("Exchange Rate deleted successfully");
    }

    get successExchangeRateUpdate() {
        return this.page.getByText("Exchange Rate updated successfully");
    }

    get successGroupDeleteMSG() {
        return this.page.getByText(/Group deleted successfully/i);
    }

    get successGroupMSG() {
        return this.page.getByText("Group created successfully");
    }

    get successInventorySourceCreate() {
        return this.page.getByText("Inventory Source Created Successfully");
    }

    get successInventorySourceDelete() {
        return this.page.getByText("Inventory Sources Deleted Successfully");
    }

    get successInventorySourceUpdate() {
        return this.page.getByText("Inventory Sources Updated Successfully");
    }

    get successLocaleCreate() {
        return this.page.getByText("Locale created successfully");
    }

    get successLocaleDelete() {
        return this.page.getByText("Locale deleted successfully");
    }

    get successLocaleUpdate() {
        return this.page.getByText("Locale updated successfully");
    }

    get successMSG() {
        return this.page.getByText("Product updated successfully");
    }

    get successPageCreate() {
        return this.page.getByText("CMS created successfully.");
    }

    get successPageDelete() {
        return this.page.getByText("CMS deleted successfully.");
    }

    get successPageUpdate() {
        return this.page.getByText("CMS updated successfully.");
    }

    get successRole() {
        return this.page.getByText("Roles Created Successfully");
    }

    get successUpdateMSG() {
        return this.page.getByText("Group Updated Successfully");
    }

    get successUpdateRole() {
        return this.page.getByText("Roles is updated successfully");
    }

    get successUpdateTaxCategory() {
        return this.page.getByText("Tax Category Successfully Updated.");
    }

    get successUpdateTaxRate() {
        return this.page.getByText("Tax Rate Update Successfully");
    }

    get successUser() {
        return this.page.getByText("User created successfully.");
    }

    get successUserDelete() {
        return this.page.getByText("User deleted successfully.");
    }

    get successUserUpdate() {
        return this.page.getByText("User updated successfully.");
    }

    get targetCurrency() {
        return this.page.locator('select[name="target_currency"]');
    }

    get targetPath() {
        return this.page.getByRole("textbox", { name: "Target Path" });
    }

    get taxRate() {
        return this.page.locator('input[name="tax_rate"]');
    }

    get terms() {
        return this.page.getByRole("textbox", { name: "Terms" });
    }

    get toggleInput() {
        return this.page.locator('input[name="status"]');
    }

    get unauthorized() {
        return this.page.getByText("401").first();
    }

    get urlKey() {
        return this.page.locator("#url_key");
    }

    get userEmail() {
        return this.page.locator('input[name="email"]');
    }

    get userPassword() {
        return this.page.locator('input[name="password"]');
    }

    get viewBtn() {
        return this.page.locator("span.cursor-pointer.icon-sort-right");
    }

    get viewIcon() {
        return this.page.locator("a.icon-sort-right.cursor-pointer");
    }
}
