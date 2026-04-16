import { Page, expect } from "@playwright/test";
import { loginAsCustomer, addAddress } from "../utils/customer";
import {
    generateFirstName,
    generateLastName,
    generateEmail,
    randomElement,
    generateName,
    generateSlug,
    generateSKU,
    generateDescription,
    generateRandomDate,
    generateHostname,
    generatePhoneNumber,
} from "../utils/faker";
import { fillInTinymce } from "../utils/tinymce";

import { AdminSessionPage } from "../locators/admin/AdminSessionPage";
import { ProductActionPage } from "../locators/admin/catalog/ProductActionPage";
import { OrderActionPage } from "../locators/admin/sales/OrderActionPage";
import { RoleActionPage } from "../locators/admin/settings/RoleActionPage";
import { UserActionPage } from "../locators/admin/settings/UserActionPage";
import { CategoryActionPage } from "../locators/admin/catalog/CategoryActionPage";
import { AttributeActionPage } from "../locators/admin/catalog/AttributeActionPage";
import { FamilyActionPage } from "../locators/admin/catalog/FamilyActionpage";
import { CustomerActionPage } from "../locators/admin/customers/CustomerActionPage";
import { GroupActionPage } from "../locators/admin/customers/GroupActionPage";
import { CMSActionPage } from "../locators/admin/cms/CMSActionPage";
import { CartRuleActionPage } from "../locators/admin/marketing/promotions/CartRuleActionPage";
import { CatalogRuleActionPage } from "../locators/admin/marketing/promotions/CatalogRuleActionPage";
import { EmailTemplateActionPage } from "../locators/admin/marketing/communication/EmailTemplateActionPage";
import { EventActionPage } from "../locators/admin/marketing/communication/EventActionPage";
import { CampaignActionPage } from "../locators/admin/marketing/communication/CampaignActionPage";
import { URLRewriteActionPage } from "../locators/admin/marketing/search-seo/URLRewriteActionPage";
import { SearchTermActionPage } from "../locators/admin/marketing/search-seo/SearchTermActionPage";
import { SearchSynonymsActionPage } from "../locators/admin/marketing/search-seo/SearchSynonymsActionPage";
import { SiteMapActionPage } from "../locators/admin/marketing/search-seo/SiteMapAction";
import { LocaleActionPage } from "../locators/admin/settings/LocaleActionPage";
import { CurrencyActionPage } from "../locators/admin/settings/CurrencyActionPage";
import { ExchangeRateActionPage } from "../locators/admin/settings/ExchangeRateActionPage";
import { InventorySourceActionPage } from "../locators/admin/settings/InventorySourceActionPage";
import { ChannelActionPage } from "../locators/admin/settings/ChannelActionPage";
import { ThemeActionPage } from "../locators/admin/settings/ThemeActionPage";
import { TaxRateActionPage } from "../locators/admin/settings/TaxRateActionPage";
import { TaxCategoryActionPage } from "../locators/admin/settings/TaxCategoryActionPage";
import { RMAActionPage } from "../locators/admin/sales/RMAActionPage";
import { CustomerCheckoutActionPage } from "../locators/shop/CustomerCheckoutActionPage";

const ACL_Routes: Record<
    string,
    {
        allowed: string;
        sidebar: string;
        notAllowed?: string[];
    }
> = {
    "sales->orders": {
        allowed: "admin/sales/orders",
        sidebar: "/admin/sales/orders",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
        ],
    },

    "sales->transactions": {
        allowed: "admin/sales/transactions",
        sidebar: "/admin/sales/transactions",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
        ],
    },

    "sales->shipments": {
        allowed: "admin/sales/shipments",
        sidebar: "/admin/sales/shipments",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "sales->invoices": {
        allowed: "admin/sales/invoices",
        sidebar: "/admin/sales/invoices",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "sales->refunds": {
        allowed: "admin/sales/refunds",
        sidebar: "/admin/sales/refunds",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "sales->rma": {
        allowed: "admin/sales/rma/requests",
        sidebar: "/admin/sales/rma/requests",
        notAllowed: [
            "admin/dashboard",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/sales/refunds",
        ],
    },

    "sales->rma->reason": {
        allowed: "admin/sales/rma/reasons",
        sidebar: "/admin/sales/rma/reasons",
        notAllowed: [
            "admin/dashboard",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/sales/rma/rules",
            "admin/sales/rma/requests",
            "admin/sales/rma/rma-status",
            "admin/sales/rma/custom-fields",
        ],
    },

    "sales->rma->rma_rules": {
        allowed: "admin/sales/rma/rules",
        sidebar: "/admin/sales/rma/rules",
        notAllowed: [
            "admin/dashboard",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/sales/rma/reasons",
            "admin/sales/rma/requests",
            "admin/sales/rma/rma-status",
            "admin/sales/rma/custom-fields",
        ],
    },

    "sales->rma->rma_status": {
        allowed: "admin/sales/rma/rma-status",
        sidebar: "/admin/sales/rma/rma-status",
        notAllowed: [
            "admin/dashboard",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/sales/rma/reasons",
            "admin/sales/rma/rules",
            "admin/sales/rma/requests",
            "admin/sales/rma/custom-fields",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    catalog: {
        allowed: "admin/catalog/products",
        sidebar: "/admin/catalog/products",
        notAllowed: [
            "admin/dashboard",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "catalog->products": {
        allowed: "admin/catalog/products",
        sidebar: "/admin/catalog/products",
        notAllowed: [
            "admin/dashboard",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "catalog->categories": {
        allowed: "admin/catalog/categories",
        sidebar: "/admin/catalog/categories",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "catalog->attributes": {
        allowed: "admin/catalog/attributes",
        sidebar: "/admin/catalog/attributes",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/catalog/categories",
        ],
    },

    "catalog->families": {
        allowed: "admin/catalog/families",
        sidebar: "/admin/catalog/families",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "customers->customers": {
        allowed: "admin/customers",
        sidebar: "/admin/customers",
        notAllowed: [
            "admin/dashboard",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/customers/groups",
            "admin/customers/gdpr",
        ],
    },

    "customers->groups": {
        allowed: "admin/customers/groups",
        sidebar: "/admin/customers/groups",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "customers->reviews": {
        allowed: "admin/customers/reviews",
        sidebar: "/admin/customers/reviews",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "customers->gdpr": {
        allowed: "admin/customers/gdpr",
        sidebar: "/admin/customers/gdpr",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "marketing->promotions": {
        allowed: "admin/marketing/promotions/catalog-rules",
        sidebar: "/admin/marketing/promotions/catalog-rules",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "marketing->promotions->catalog_rules": {
        allowed: "admin/marketing/promotions/catalog-rules",
        sidebar: "/admin/marketing/promotions/catalog-rules",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/marketing/promotions/cart-rules",
        ],
    },

    "marketing->promotions->cart_rules": {
        allowed: "admin/marketing/promotions/cart-rules",
        sidebar: "/admin/marketing/promotions/cart-rules",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/marketing/promotions/catalog-rules",
        ],
    },

    "marketing->communications": {
        allowed: "admin/marketing/communications/email-templates",
        sidebar: "/admin/marketing/communications/email-templates",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "marketing->communications->email": {
        allowed: "admin/marketing/communications/email-templates",
        sidebar: "/admin/marketing/communications/email-templates",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/marketing/promotions/cart-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/marketing/communications/events",
        ],
    },

    "marketing->communications->event": {
        allowed: "admin/marketing/communications/events",
        sidebar: "/admin/marketing/communications/events",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/marketing/communications/email-templates",
        ],
    },

    "marketing->communications->campaign": {
        allowed: "admin/marketing/communications/campaigns",
        sidebar: "/admin/marketing/communications/campaigns",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/marketing/communications/email-templates",
            "admin/marketing/communications/events",
        ],
    },

    "marketing->search_seo": {
        allowed: "admin/marketing/search-seo/url-rewrites",
        sidebar: "/admin/marketing/search-seo/url-rewrites",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/marketing/communications/email-templates",
        ],
    },

    "marketing->search_seo->url_rewrites": {
        allowed: "admin/marketing/search-seo/url-rewrites",
        sidebar: "/admin/marketing/search-seo/url-rewrites",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/search-synonyms",
            "admin/marketing/search-seo/sitemaps",
        ],
    },

    "marketing->search_seo->search_terms": {
        allowed: "admin/marketing/search-seo/search-terms",
        sidebar: "/admin/marketing/search-seo/search-terms",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-synonyms",
            "admin/marketing/search-seo/sitemaps",
        ],
    },

    "marketing->search_seo->search_synonyms": {
        allowed: "admin/marketing/search-seo/search-synonyms",
        sidebar: "/admin/marketing/search-seo/search-synonyms",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/sitemaps",
        ],
    },

    "marketing->search_seo->sitemaps": {
        allowed: "admin/marketing/search-seo/sitemaps",
        sidebar: "/admin/marketing/search-seo/sitemaps",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/search-synonyms",
        ],
    },

    reporting: {
        allowed: "admin/reporting/sales",
        sidebar: "/admin/reporting/sales",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/search-synonyms",
        ],
    },

    "reporting->sales": {
        allowed: "admin/reporting/sales",
        sidebar: "/admin/reporting/sales",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/search-synonyms",
            "admin/reporting/customers",
            "admin/reporting/products",
        ],
    },

    "reporting->customers": {
        allowed: "admin/reporting/customers",
        sidebar: "/admin/reporting/customers",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/search-synonyms",
            "admin/reporting/sales",
            "admin/reporting/products",
        ],
    },

    "reporting->products": {
        allowed: "admin/reporting/products",
        sidebar: "/admin/reporting/products",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/marketing/search-seo/url-rewrites",
            "admin/marketing/search-seo/search-terms",
            "admin/marketing/search-seo/search-synonyms",
            "admin/reporting/sales",
            "admin/reporting/customers",
        ],
    },

    "settings->locales": {
        allowed: "admin/settings/locales",
        sidebar: "/admin/settings/locales",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "settings->currencies": {
        allowed: "admin/settings/currencies",
        sidebar: "/admin/settings/currencies",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/settings/locales",
        ],
    },

    "settings->exchange_rates": {
        allowed: "admin/settings/exchange-rates",
        sidebar: "/admin/settings/exchange-rates",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "settings->users": {
        allowed: "admin/settings/users",
        sidebar: "/admin/settings/users",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "settings->roles": {
        allowed: "admin/settings/roles",
        sidebar: "/admin/settings/roles",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "settings->themes": {
        allowed: "admin/settings/themes",
        sidebar: "/admin/settings/themes",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "settings->taxes_rates": {
        allowed: "admin/settings/taxes/rates",
        sidebar: "/admin/settings/taxes/rates",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/settings/taxes/categories",
            "admin/sales/transactions",
        ],
    },

    "settings->taxes_categories": {
        allowed: "admin/settings/taxes/categories",
        sidebar: "/admin/settings/taxes/categories",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
            "admin/settings/taxes/rates",
        ],
    },

    "settings->taxes": {
        allowed: "admin/settings/taxes/categories",
        sidebar: "/admin/settings/taxes/categories",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    "settings->data_transfer": {
        allowed: "admin/settings/data-transfer/imports",
        sidebar: "/admin/settings/data-transfer/imports",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/configuration",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },

    configuration: {
        allowed: "admin/configuration",
        sidebar: "/admin/configuration",
        notAllowed: [
            "admin/dashboard",
            "admin/catalog/products",
            "admin/customers",
            "admin/cms",
            "admin/marketing/promotions/catalog-rules",
            "admin/reporting/sales",
            "admin/settings/locales",
            "admin/sales/orders",
            "admin/sales/transactions",
        ],
    },
};

export class ACLManagement {
    readonly page: Page;
    readonly roleName: string;
    readonly userName: string;
    readonly userEmail: string;
    readonly adminSessionPage: AdminSessionPage;
    readonly productActionPage: ProductActionPage;
    readonly orderActionPage: OrderActionPage;
    readonly roleActionPage: RoleActionPage;
    readonly userActionPage: UserActionPage;
    readonly categoryActionPage: CategoryActionPage;
    readonly attributeActionPage: AttributeActionPage;
    readonly familyActionPage: FamilyActionPage;
    readonly customersActionPage: CustomerActionPage;
    readonly groupActionPage: GroupActionPage;
    readonly cmsActionPage: CMSActionPage;
    readonly cartRuleActionPage: CartRuleActionPage;
    readonly catalogRuleActionPage: CatalogRuleActionPage;
    readonly emailTemplateActionPage: EmailTemplateActionPage;
    readonly eventActionPage: EventActionPage;
    readonly campaignActionPage: CampaignActionPage;
    readonly urlRewriteActionPage: URLRewriteActionPage;
    readonly searchTermActionPage: SearchTermActionPage;
    readonly searchSynonymsActionPage: SearchSynonymsActionPage;
    readonly siteMapActionPage: SiteMapActionPage;
    readonly localeActionPage: LocaleActionPage;
    readonly currencyActionPage: CurrencyActionPage;
    readonly exchangeRateActionPage: ExchangeRateActionPage;
    readonly inventorySourceActionPage: InventorySourceActionPage;
    readonly channelActionPage: ChannelActionPage;
    readonly themeActionPage: ThemeActionPage;
    readonly taxRateActionPage: TaxRateActionPage;
    readonly taxCategoryActionPage: TaxCategoryActionPage;
    readonly rmaActionPage: RMAActionPage;
    readonly customerCheckoutActionPage: CustomerCheckoutActionPage;

    constructor(page: Page) {
        this.page = page;
        this.roleName = `role-${Date.now()}`;
        this.userName = `user-${Date.now()}`;
        this.userEmail = `user_${Date.now()}_${Math.floor(Math.random() * 10000)}${generateEmail()}`;
        this.adminSessionPage = new AdminSessionPage(this.page);
        this.productActionPage = new ProductActionPage(this.page);
        this.orderActionPage = new OrderActionPage(this.page);
        this.roleActionPage = new RoleActionPage(this.page);
        this.userActionPage = new UserActionPage(this.page);
        this.categoryActionPage = new CategoryActionPage(this.page);
        this.attributeActionPage = new AttributeActionPage(this.page);
        this.familyActionPage = new FamilyActionPage(this.page);
        this.customersActionPage = new CustomerActionPage(this.page);
        this.groupActionPage = new GroupActionPage(this.page);
        this.cmsActionPage = new CMSActionPage(this.page);
        this.cartRuleActionPage = new CartRuleActionPage(this.page);
        this.catalogRuleActionPage = new CatalogRuleActionPage(this.page);
        this.emailTemplateActionPage = new EmailTemplateActionPage(this.page);
        this.eventActionPage = new EventActionPage(this.page);
        this.campaignActionPage = new CampaignActionPage(this.page);
        this.urlRewriteActionPage = new URLRewriteActionPage(this.page);
        this.searchTermActionPage = new SearchTermActionPage(this.page);
        this.searchSynonymsActionPage = new SearchSynonymsActionPage(this.page);
        this.siteMapActionPage = new SiteMapActionPage(this.page);
        this.localeActionPage = new LocaleActionPage(this.page);
        this.currencyActionPage = new CurrencyActionPage(this.page);
        this.exchangeRateActionPage = new ExchangeRateActionPage(this.page);
        this.inventorySourceActionPage = new InventorySourceActionPage(
            this.page,
        );
        this.channelActionPage = new ChannelActionPage(this.page);
        this.userActionPage = new UserActionPage(this.page);
        this.themeActionPage = new ThemeActionPage(this.page);
        this.taxRateActionPage = new TaxRateActionPage(this.page);
        this.taxCategoryActionPage = new TaxCategoryActionPage(this.page);
        this.rmaActionPage = new RMAActionPage(this.page);
        this.customerCheckoutActionPage = new CustomerCheckoutActionPage(
            this.page,
        );
    }

    private async togglePermissionCheckbox(
        permissionValue: string,
        shouldCheck: boolean,
    ): Promise<void> {
        const input = this.page.locator(
            `input[name="permissions[]"][value="${permissionValue}"]`,
        );
        await expect(input).toBeAttached({ timeout: 10000 });

        const isChecked = await input.isChecked();
        if (shouldCheck && !isChecked) {
            await input.locator("..").locator("span").click();
        } else if (!shouldCheck && isChecked) {
            await input.locator("..").locator("span").click();
        }
    }

    async rolePermission(permissionValues: string[]): Promise<void> {
        await this.page.waitForLoadState("networkidle");
        for (const value of permissionValues) {
            await this.togglePermissionCheckbox(value, true);
        }
    }

    async editRolePermission(permissionValues: string[]): Promise<void> {
        await this.page.goto("admin/settings/roles");
        await this.roleActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        for (const value of permissionValues) {
            await this.togglePermissionCheckbox(value, false);
        }
        await this.roleActionPage.saveRole.click();
        await expect(this.roleActionPage.successEditRole).toBeVisible();
    }

    async createRole(
        permissionType: string,
        permissions?: string[],
    ): Promise<void> {
        await this.page.goto("admin/settings/roles");
        await this.roleActionPage.createRole.click();
        await this.roleActionPage.name.fill(this.roleName);
        await this.roleActionPage.selectRoleType.selectOption(permissionType);
        if (
            permissionType === "custom" &&
            permissions &&
            permissions.length > 0
        ) {
            await this.rolePermission(permissions);
        }
        await this.roleActionPage.roleDescription.fill("test description");
        await this.roleActionPage.saveRole.click();
        await expect(this.roleActionPage.successRole.first()).toBeVisible();
    }

    async createUser(): Promise<void> {
        await this.page.goto("admin/settings/users");
        await this.userActionPage.createUser.click();
        await this.userActionPage.name.fill(this.userName);
        await this.userActionPage.selectRole.selectOption({
            label: this.roleName,
        });
        await this.userActionPage.userEmail.fill(this.userEmail);
        await this.userActionPage.userPassword.fill("user123");
        await this.userActionPage.confirmPassword.fill("user123");
        await this.userActionPage.statusToggle.click();
        const toggleInput = await this.userActionPage.statusToggle;
        await expect(toggleInput).toBeChecked();
        await this.userActionPage.saveUser.click();
        await expect(this.userActionPage.successUser.first()).toBeVisible();
    }

    async expectUnauthorizedFor(routes: string[]) {
        for (const route of routes) {
            await this.page.goto(route);
            await expect(this.userActionPage.unauthorized).toBeVisible();
        }
    }

    async expectAuthorizedFor(route: string) {
        await this.page.goto(route);
        await expect(this.userActionPage.unauthorized).not.toBeVisible();
    }

    async verfiyAssignedRole(permissions: string[] = []) {
        await this.page.goto("admin/dashboard");
        await this.page.waitForLoadState("networkidle");
        await this.adminSessionPage.profile.first().click();
        await this.adminSessionPage.logout.click();
        await this.page.goto("admin/login");
        await this.page.waitForURL("**/admin/login");
        await this.page.waitForLoadState("networkidle");
        await this.adminSessionPage.userEmail.fill(this.userEmail);
        await this.adminSessionPage.userPassword.fill("user123");
        await this.adminSessionPage.userPassword.press("Enter");
        await this.page.waitForLoadState("networkidle");

        const sidebar = this.page.locator("div.fixed.top-14 nav");
        let sidebarLinks: string[] = [];

        if (permissions.includes("all")) {
            sidebarLinks = [
                "/admin/dashboard",
                "/admin/sales/orders",
                "/admin/catalog/products",
                "/admin/customers",
                "/admin/cms",
                "/admin/marketing/promotions/catalog-rules",
                "/admin/reporting/sales",
                "/admin/settings/locales",
                "/admin/configuration",
            ];
        } else {
            for (const permission of permissions) {
                const config = ACL_Routes[permission];
                if (!config) continue;

                if (config.notAllowed?.length) {
                    await this.expectUnauthorizedFor(config.notAllowed);
                }

                await this.expectAuthorizedFor(config.allowed);

                sidebarLinks.push(config.sidebar);
            }
        }
        for (const href of sidebarLinks) {
            await expect(
                sidebar.locator(`a[href*="${href}"]`).first(),
            ).toBeVisible();
        }
    }

    async orderCreateVerify() {
        await expect(this.orderActionPage.createBtn).toBeVisible();
        await this.orderActionPage.createBtn.click();
        await expect(this.orderActionPage.saveBtn).toBeVisible();
    }

    async productEditVerify() {
        await expect(this.productActionPage.createBtn).not.toBeVisible();
        await this.productActionPage.editBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.productActionPage.createBtn.click();
        await expect(this.productActionPage.successMSG.first()).toBeVisible();
    }

    async productCopyVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.productActionPage.createBtn).not.toBeVisible();
        await expect(this.productActionPage.viewBtn.nth(1)).not.toBeVisible();
        await this.productActionPage.copyBtn.nth(1).click();
        await this.productActionPage.agreeBtn.click();
        await expect(this.productActionPage.copySuccess.first()).toBeVisible();
    }

    async productDeleteVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.productActionPage.createBtn).not.toBeVisible();
        await expect(this.productActionPage.viewBtn.nth(1)).not.toBeVisible();
        await this.productActionPage.selectRowBtn.nth(2).click();
        await this.productActionPage.selectAction.click();
        await this.productActionPage.selectDelete.click();
        await this.productActionPage.agreeBtn.click();
        await expect(
            this.productActionPage.productDeleteSuccess.first(),
        ).toBeVisible();
    }

    async categoryEditVerify() {
        await expect(this.categoryActionPage.createBtn).not.toBeVisible();
        await this.categoryActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.categoryActionPage.saveCategoryBtn.click();
        await expect(
            this.categoryActionPage.categorySuccess.first(),
        ).toBeVisible();
    }

    async categoryDeleteVerify() {
        await expect(this.categoryActionPage.createBtn).not.toBeVisible();
        await expect(
            this.categoryActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.categoryActionPage.selectRowBtn.nth(1).click();
        await this.categoryActionPage.selectAction.click();
        await this.categoryActionPage.deleteBtn.click();
        await this.categoryActionPage.agreeBtn.click();
        await expect(
            this.categoryActionPage.categoryDeleteSuccess.first(),
        ).toBeVisible();
    }

    async attributeCreateVerify() {
        await expect(this.attributeActionPage.createBtn).toBeVisible();
        await expect(
            this.attributeActionPage.editBtn.first(),
        ).not.toBeVisible();
        await this.attributeActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.attributeActionPage.fillname.fill("Test Attribute");
        await this.attributeActionPage.fillCode.fill("testattribute");
        await this.attributeActionPage.selectTypeAttribute.selectOption("text");
        await this.attributeActionPage.saveAttributeBtn.click();
        await expect(
            this.attributeActionPage.attributeSuccess.first(),
        ).toBeVisible();
    }

    async attributeEditVerify() {
        await expect(this.attributeActionPage.createBtn).not.toBeVisible();
        await expect(this.attributeActionPage.iconEdit.first()).toBeVisible();
        await this.attributeActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.attributeActionPage.saveAttributeBtn.click();
        await expect(
            this.attributeActionPage.attributeUpdateSuccess.first(),
        ).toBeVisible();
    }

    async attributeDeleteVerify() {
        await expect(this.attributeActionPage.createBtn).not.toBeVisible();
        await expect(
            this.attributeActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.attributeActionPage.deleteIcon.first().click();
        await this.attributeActionPage.agreeBtn.click();
        await expect(
            this.attributeActionPage.attributeDeleteSuccess.first(),
        ).toBeVisible();
    }

    async familyCreateVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.familyActionPage.editBtn.first()).not.toBeVisible();
        await this.familyActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.familyActionPage.familyName.fill("Test Family");
        await this.familyActionPage.fillCode.fill("family");
        await this.familyActionPage.createBtn.click();
        await expect(this.familyActionPage.familySuccess.first()).toBeVisible();
    }

    async familyEditVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.familyActionPage.createBtn).not.toBeVisible();
        await this.familyActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.familyActionPage.createBtn.click();
        await expect(
            this.familyActionPage.familyUpdateSuccess.first(),
        ).toBeVisible();
    }

    async familyDeleteVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.familyActionPage.createBtn).not.toBeVisible();
        await expect(this.familyActionPage.editBtn.first()).not.toBeVisible();
        await this.familyActionPage.deleteIcon.first().click();
        await this.familyActionPage.agreeBtn.click();
        await expect(
            this.familyActionPage.familyDeleteSuccess.first(),
        ).toBeVisible();
    }

    async customerCreateVerify() {
        await this.page.goto("admin/customers");
        await this.customersActionPage.createBtn.click();
        await this.customersActionPage.customerfirstname.fill(
            generateFirstName(),
        );
        await this.customersActionPage.customerlastname.fill(
            generateLastName(),
        );
        await this.customersActionPage.customeremail.fill(generateEmail());
        await this.customersActionPage.customergender.selectOption(
            randomElement(["Male", "Female", "Other"]),
        );
        await this.customersActionPage.customerNumber.fill("1234567890");
        await this.customersActionPage.customerNumber.press("Enter");
        await expect(
            this.customersActionPage.customercreatedsuccess.first(),
        ).toBeVisible();
    }

    async customerEditVerify() {
        await this.page.goto("admin/customers");
        await expect(this.customersActionPage.createBtn).not.toBeVisible();
        await expect(this.customersActionPage.viewIcon.first()).toBeVisible();
    }

    async customerDeleteVerify() {
        await expect(this.customersActionPage.createBtn).not.toBeVisible();
        await this.customersActionPage.selectRowBtn.nth(1).click();
        await this.customersActionPage.selectAction.click();
        await this.customersActionPage.deleteBtn.click();
        await this.customersActionPage.agreeBtn.click();
        await expect(
            this.customersActionPage.customerDeleteSuccess.first(),
        ).toBeVisible();
    }

    async groupCreateVerify() {
        await this.groupActionPage.createBtn.click();
        await this.groupActionPage.name.fill(generateName());
        await this.groupActionPage.fillCode.fill("code");
        await this.groupActionPage.createBtn.nth(1).click();
        await expect(
            this.groupActionPage.successGroupMSG.first(),
        ).toBeVisible();
    }

    async groupEditVerify() {
        await expect(this.groupActionPage.createBtn).not.toBeVisible();
        await this.groupActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.groupActionPage.createBtn.click();
        await expect(
            this.groupActionPage.successUpdateMSG.first(),
        ).toBeVisible();
    }

    async groupDeleteVerify() {
        await expect(this.groupActionPage.createBtn).not.toBeVisible();
        await expect(this.groupActionPage.iconEdit.first()).not.toBeVisible();
        await this.groupActionPage.deleteIcon.first().click();
        await this.groupActionPage.agreeBtn.click();
        await expect(
            this.groupActionPage.successGroupDeleteMSG.first(),
        ).toBeVisible();
    }

    async cmsCreateVerify() {
        await this.cmsActionPage.pagetitle.fill(generateName());
        await this.cmsActionPage.urlKey.fill(generateSlug());
        await this.cmsActionPage.metaTitle.fill(generateName());
        await this.cmsActionPage.metaKeywords.fill("keywords");
        await this.cmsActionPage.metaDescription.fill(generateDescription());
        await this.cmsActionPage.channelBTN.first().click();
        await this.cmsActionPage.createBtn.click();
        await expect(this.cmsActionPage.successPageCreate.first()).toBeVisible();
    }

    async cmsEditVerify() {
        await expect(this.cmsActionPage.createBtn).not.toBeVisible();
        await this.cmsActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.cmsActionPage.createBtn.click();
        await expect(this.cmsActionPage.successPageUpdate).toBeVisible();
    }

    async cmsDeleteVerify() {
        await expect(this.cmsActionPage.createBtn).not.toBeVisible();
        await expect(this.cmsActionPage.iconEdit.first()).not.toBeVisible();
        await this.cmsActionPage.deleteIcon.first().click();
        await this.cmsActionPage.agreeBtn.click();
        await expect(this.cmsActionPage.successPageDelete).toBeVisible();
    }

    async cartRuleCreateVerify() {
        await this.cartRuleActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.cartRuleActionPage.ruleName.fill(generateName());
        await this.cartRuleActionPage.promotionRuleDescription.fill(
            generateDescription(),
        );
        await this.cartRuleActionPage.addConditionBtn.click();
        await this.cartRuleActionPage.selectCondition.selectOption(
            "product|name",
        );
        await this.cartRuleActionPage.conditionName.fill(generateName());
        await this.cartRuleActionPage.discountAmmount.fill("10");
        await this.cartRuleActionPage.sortOrder.fill("1");
        await this.cartRuleActionPage.channelSelect.first().click();
        await expect(
            this.cartRuleActionPage.channelSelect.first(),
        ).toBeChecked();
        await this.cartRuleActionPage.customerGroupSelect.first().click();
        await expect(
            this.cartRuleActionPage.customerGroupSelect.first(),
        ).toBeChecked();
        await this.cartRuleActionPage.statusToggle.click();
        await expect(this.cartRuleActionPage.toggleInput).toBeChecked();
        await this.cartRuleActionPage.createBtn.click();
        await expect(
            this.cartRuleActionPage.cartRuleSuccess.first(),
        ).toBeVisible();
    }

    async cartRuleCopyVerify() {
        await expect(this.cartRuleActionPage.createBtn).not.toBeVisible();
        await expect(
            this.cartRuleActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.cartRuleActionPage.copyBtn.first().click();
        // await expect(this.cartRuleActionPage.cartRuleCopySuccess.first()).toBeVisible();
        await expect(this.cartRuleActionPage.saveCartRuleBTN).toBeVisible();
    }

    async cartRuleEditVerify() {
        await expect(this.cartRuleActionPage.createBtn).not.toBeVisible();
        await expect(this.cartRuleActionPage.copyBtn.first()).not.toBeVisible();
        await this.cartRuleActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.cartRuleActionPage.createBtn.click();
        await expect(
            this.cartRuleActionPage.cartRuleEditSuccess.first(),
        ).toBeVisible();
    }

    async cartRuleDeleteVerify() {
        await expect(this.cartRuleActionPage.createBtn).not.toBeVisible();
        await expect(this.cartRuleActionPage.copyBtn.first()).not.toBeVisible();
        await expect(
            this.cartRuleActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.cartRuleActionPage.deleteIcon.first().click();
        await this.cartRuleActionPage.agreeBtn.click();
        await expect(
            this.cartRuleActionPage.cartRuleDeleteSuccess.first(),
        ).toBeVisible();
    }

    async catalogRuleCreateVerify() {
        await this.catalogRuleActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.catalogRuleActionPage.ruleName.fill(generateName());
        await this.catalogRuleActionPage.promotionRuleDescription.fill(
            generateDescription(),
        );
        await this.catalogRuleActionPage.addConditionBtn.click();
        await this.catalogRuleActionPage.selectCondition.selectOption(
            "product|name",
        );
        await this.catalogRuleActionPage.conditionName.fill(generateName());
        await this.catalogRuleActionPage.discountAmmount.fill("10");
        await this.catalogRuleActionPage.sortOrder.fill("1");
        await this.catalogRuleActionPage.channelSelect.first().click();
        await expect(
            this.catalogRuleActionPage.channelSelect.first(),
        ).toBeChecked();
        await this.catalogRuleActionPage.customerGroupSelect.first().click();
        await expect(
            this.catalogRuleActionPage.customerGroupSelect.first(),
        ).toBeChecked();
        await this.catalogRuleActionPage.statusToggle.click();
        await expect(this.catalogRuleActionPage.toggleInput).toBeChecked();
        await this.catalogRuleActionPage.createBtn.click();
        await expect(
            this.catalogRuleActionPage.catalogRuleCreateSuccess.first(),
        ).toBeVisible();
    }

    async catalogRuleEditVerify() {
        await expect(this.catalogRuleActionPage.createBtn).not.toBeVisible();
        await expect(this.catalogRuleActionPage.iconEdit.first()).toBeVisible();
        await this.catalogRuleActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.catalogRuleActionPage.createBtn.click();
        await expect(
            this.catalogRuleActionPage.catalogRuleUpdateSuccess.first(),
        ).toBeVisible();
    }

    async catalogRuleDeleteVerify() {
        await expect(this.catalogRuleActionPage.createBtn).not.toBeVisible();
        await expect(
            this.catalogRuleActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.catalogRuleActionPage.deleteIcon.first().click();
        await this.catalogRuleActionPage.agreeBtn.click();
        await expect(
            this.catalogRuleActionPage.catalogRuleDeleteSuccess.first(),
        ).toBeVisible();
    }

    async communicationEmailTemplateCreateVerify() {
        await this.emailTemplateActionPage.createBtn.click();
        const description = generateDescription();
        await this.emailTemplateActionPage.name.fill("template");
        await fillInTinymce(this.page, "#content_ifr", description);
        await this.emailTemplateActionPage.emailStatusSeletct.selectOption(
            "active",
        );
        await this.emailTemplateActionPage.createBtn.click();
        await expect(
            this.emailTemplateActionPage.emailSuccessMSG.first(),
        ).toBeVisible();
    }

    async communicationEmailTemplateEditVerify() {
        await expect(this.emailTemplateActionPage.createBtn).not.toBeVisible();
        await this.emailTemplateActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.emailTemplateActionPage.createBtn.click();
        await expect(
            this.emailTemplateActionPage.emailUpdateSuccessMSG.first(),
        ).toBeVisible();
    }

    async communicationEmailTemplateDeleteVerify() {
        await expect(
            this.emailTemplateActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.emailTemplateActionPage.deleteIcon.first().click();
        await this.emailTemplateActionPage.agreeBtn.click();
        await expect(
            this.emailTemplateActionPage.emailDeleteSuccessMSG.first(),
        ).toBeVisible();
    }

    async eventCreateVerify() {
        await this.eventActionPage.createBtn.click();
        await this.page.hover('input[name="name"]');
        const inputs = await this.page.$$(
            'textarea.rounded-md:visible, input[type="text"].rounded-md:visible',
        );

        for (let input of inputs) {
            await input.fill(generateName());
        }
        await this.eventActionPage.date.fill(generateRandomDate());
        await this.eventActionPage.createBtn.nth(1).click();
        await expect(
            this.eventActionPage.eventCreateSuccess.first(),
        ).toBeVisible();
    }

    async eventEditVerify() {
        await expect(this.eventActionPage.createBtn).not.toBeVisible();
        await this.eventActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.eventActionPage.createBtn.nth(1).click();
        await expect(
            this.eventActionPage.eventUpdateSuccess.first(),
        ).toBeVisible();
    }

    async eventDeleteVerify() {
        await expect(this.eventActionPage.iconEdit.first()).not.toBeVisible();
        await expect(this.eventActionPage.createBtn.nth(1)).not.toBeVisible();
        await this.eventActionPage.deleteIcon.first().click();
        await this.eventActionPage.agreeBtn.click();
        await expect(
            this.eventActionPage.eventDeleteSuccess.first(),
        ).toBeVisible();
    }

    async campaignCreateVerify() {
        await this.campaignActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.campaignActionPage.name.fill(generateName());
        await this.campaignActionPage.subject.fill(generateName());
        await this.campaignActionPage.event.selectOption({ label: "Birthday" });
        await this.campaignActionPage.emailTemplate.selectOption({
            label: "template",
        });
        await this.campaignActionPage.selectChannel.selectOption("1");
        await this.campaignActionPage.customerGroup.selectOption("1");
        await this.campaignActionPage.campaignStatus.click();
        await this.campaignActionPage.createBtn.click();
        await expect(
            this.campaignActionPage.campaignCreateSuccess.first(),
        ).toBeVisible();
    }

    async campaignEditVerify() {
        await expect(this.campaignActionPage.createBtn).not.toBeVisible();
        await this.campaignActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.campaignActionPage.createBtn.click();
        await expect(
            this.campaignActionPage.campaignUpdateSuccess.first(),
        ).toBeVisible();
    }

    async campaignDeleteVerify() {
        await expect(this.campaignActionPage.createBtn).not.toBeVisible();
        await expect(
            this.campaignActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.campaignActionPage.deleteIcon.first().click();
        await this.campaignActionPage.agreeBtn.click();
        await expect(
            this.campaignActionPage.campaignDeleteSuccess.first(),
        ).toBeVisible();
    }

    async urlRewriteCreateVerify() {
        const seo = {
            url: generateHostname(),
            product: "product",
        };
        await this.urlRewriteActionPage.createBtn.click();
        await this.urlRewriteActionPage.entityType.selectOption(seo.product);
        await this.urlRewriteActionPage.requestPath.fill(seo.url);
        await this.urlRewriteActionPage.targetPath.fill(seo.url);
        await this.urlRewriteActionPage.redirectPath.selectOption("301");
        await this.urlRewriteActionPage.locale.selectOption("en");
        await this.urlRewriteActionPage.createBtn.nth(1).click();
        await expect(
            this.urlRewriteActionPage.saveRedirectSuccess,
        ).toBeVisible();
    }

    async urlRewriteEditVerify() {
        await expect(this.urlRewriteActionPage.createBtn).not.toBeVisible();
        await this.urlRewriteActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.urlRewriteActionPage.createBtn.click();
        await expect(
            this.urlRewriteActionPage.saveRedirectUpdatedSuccess,
        ).toBeVisible();
    }

    async urlRewriteDeleteVerify() {
        await expect(this.urlRewriteActionPage.createBtn).not.toBeVisible();
        await expect(
            this.urlRewriteActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.urlRewriteActionPage.deleteIcon.first().click();
        await this.urlRewriteActionPage.agreeBtn.click();
        await expect(
            this.urlRewriteActionPage.deleteRedirectSuccess,
        ).toBeVisible();
    }

    async searchTermsCreateVerify() {
        await expect(
            this.searchTermActionPage.deleteIcon.first(),
        ).not.toBeVisible();
        await expect(
            this.searchTermActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.searchTermActionPage.createBtn.click();
        await this.searchTermActionPage.searchQuery.fill(generateName());
        await this.searchTermActionPage.redirectURL.fill("https://example.com");
        await this.searchTermActionPage.selectChannel.selectOption("1");
        await this.searchTermActionPage.locale.selectOption("en");
        await this.searchTermActionPage.createBtn.nth(1).click();
        await expect(
            this.searchTermActionPage.searchTermCreateSuccess.first(),
        ).toBeVisible();
    }

    async searchTermsEditVerify() {
        await expect(this.searchTermActionPage.createBtn).not.toBeVisible();
        await this.searchTermActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.searchTermActionPage.createBtn.click();
        await expect(
            this.searchTermActionPage.searchTermUpdateSuccess.first(),
        ).toBeVisible();
    }

    async searchTermsDeleteVerify() {
        await expect(this.searchTermActionPage.createBtn).not.toBeVisible();
        await expect(
            this.searchTermActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.searchTermActionPage.deleteIcon.first().click();
        await this.searchTermActionPage.agreeBtn.click();
        await expect(
            this.searchTermActionPage.searchTermDeleteSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsCreateVerify() {
        await this.searchSynonymsActionPage.createBtn.click();
        await expect(
            this.searchSynonymsActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.searchSynonymsActionPage.name.fill(generateName());
        await this.searchSynonymsActionPage.terms.fill("test, synonym");
        await this.searchSynonymsActionPage.createBtn.nth(1).click();
        await expect(
            this.searchSynonymsActionPage.searchSynonymCreateSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsEditVerify() {
        await expect(this.searchSynonymsActionPage.createBtn).not.toBeVisible();
        await this.searchSynonymsActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.searchSynonymsActionPage.createBtn.click();
        await expect(
            this.searchSynonymsActionPage.searchSynonymUpdateSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsDeleteVerify() {
        await expect(this.searchSynonymsActionPage.createBtn).not.toBeVisible();
        await expect(
            this.searchSynonymsActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.searchSynonymsActionPage.deleteIcon.first().click();
        await this.searchSynonymsActionPage.agreeBtn.click();
        await expect(
            this.searchSynonymsActionPage.searchSynonymDeleteSuccess.first(),
        ).toBeVisible();
    }

    async sitemapCreateVerify() {
        await this.siteMapActionPage.createBtn.click();
        await expect(this.siteMapActionPage.iconEdit.first()).not.toBeVisible();
        await expect(
            this.siteMapActionPage.deleteIcon.first(),
        ).not.toBeVisible();
        await this.siteMapActionPage.fileName.fill("sitemap.xml");
        await this.siteMapActionPage.path.fill("/sitemapxml/test/example/");
        await this.siteMapActionPage.createBtn.nth(1).click();
        await expect(
            this.siteMapActionPage.sitemapCreateSuccess.first(),
        ).toBeVisible();
    }

    async sitemapEditVerify() {
        await expect(this.siteMapActionPage.createBtn).not.toBeVisible();
        await expect(
            this.siteMapActionPage.deleteIcon.first(),
        ).not.toBeVisible();
        await this.siteMapActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.siteMapActionPage.createBtn.click();
        await expect(
            this.siteMapActionPage.sitemapUpdateSuccess.first(),
        ).toBeVisible();
    }

    async sitemapDeleteVerify() {
        await expect(this.siteMapActionPage.createBtn).not.toBeVisible();
        await expect(this.siteMapActionPage.iconEdit.first()).not.toBeVisible();
        await this.siteMapActionPage.deleteIcon.first().click();
        await this.siteMapActionPage.agreeBtn.click();
        await expect(
            this.siteMapActionPage.sitemapDeleteSuccess.first(),
        ).toBeVisible();
    }

    async localeCreateVerify() {
        await this.localeActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.localeActionPage.fillCode.fill("test");
        await this.localeActionPage.name.fill("TestLocale");
        await this.localeActionPage.direction.selectOption("ltr");
        await this.localeActionPage.createBtn.nth(1).click();
        await expect(
            this.localeActionPage.successLocaleCreate.first(),
        ).toBeVisible();
    }

    async localeEditVerify() {
        await expect(this.localeActionPage.createBtn).not.toBeVisible();
        await this.localeActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.localeActionPage.createBtn.click();
        await expect(
            this.localeActionPage.successLocaleUpdate.first(),
        ).toBeVisible();
    }

    async localeDeleteVerify() {
        await expect(this.localeActionPage.createBtn).not.toBeVisible();
        await expect(this.localeActionPage.iconEdit.first()).not.toBeVisible();
        await this.localeActionPage.deleteIcon.first().click();
        await this.localeActionPage.agreeBtn.click();
        await expect(
            this.localeActionPage.successLocaleDelete.first(),
        ).toBeVisible();
    }

    async currencyCreateVerify() {
        await this.currencyActionPage.createBtn.click();
        await expect(
            this.currencyActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.currencyActionPage.fillCode.fill("TST");
        await this.currencyActionPage.name.fill("Test Currency");
        await this.currencyActionPage.createBtn.nth(1).click();
        await expect(
            this.currencyActionPage.successCurrencyCreate.first(),
        ).toBeVisible();
    }

    async currencyEditVerify() {
        await expect(this.currencyActionPage.createBtn).not.toBeVisible();
        await this.currencyActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.currencyActionPage.createBtn.click();
        await expect(
            this.currencyActionPage.successCurrencyUpdate.first(),
        ).toBeVisible();
    }

    async currencyDeleteVerify() {
        await expect(this.currencyActionPage.createBtn).not.toBeVisible();
        await expect(
            this.currencyActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.currencyActionPage.deleteIcon.first().click();
        await this.currencyActionPage.agreeBtn.click();
        await expect(
            this.currencyActionPage.successCurrencyDelete.first(),
        ).toBeVisible();
    }

    async exchangeRateCreateVerify() {
        await this.page.goto("admin/settings/currencies");
        await this.currencyCreateVerify();
        await this.page.goto("admin/settings/channels");
        await this.exchangeRateActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.page.getByText("Test Currency").first().click();
        await this.exchangeRateActionPage.createBtn.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.page.goto("admin/settings/exchange-rates");
        await this.exchangeRateActionPage.createBtn.nth(1).click();
        await this.page.waitForLoadState("networkidle");
        await this.exchangeRateActionPage.targetCurrency.selectOption({
            label: "Test Currency",
        });
        await this.exchangeRateActionPage.rate.fill("100");
        await this.exchangeRateActionPage.createBtn.nth(2).click();
        await expect(
            this.exchangeRateActionPage.successExchangeRateCreate.first(),
        ).toBeVisible();
    }

    async exchangeRateEditVerify() {
        await expect(
            this.exchangeRateActionPage.createBtn.nth(1),
        ).not.toBeVisible();
        await this.exchangeRateActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.exchangeRateActionPage.createBtn.nth(1).click();
        await expect(
            this.exchangeRateActionPage.successExchangeRateUpdate.first(),
        ).toBeVisible();
    }

    async exchangeRateDeleteVerify() {
        await expect(
            this.exchangeRateActionPage.createBtn.nth(1),
        ).not.toBeVisible();
        await expect(
            this.exchangeRateActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.exchangeRateActionPage.deleteIcon.first().click();
        await this.exchangeRateActionPage.agreeBtn.click();
        await expect(
            this.exchangeRateActionPage.successExchangeRateDelete.first(),
        ).toBeVisible();
    }

    async inventorySourceCreateVerify() {
        await this.inventorySourceActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.inventorySourceActionPage.fillCode.fill("testsource");
        await this.inventorySourceActionPage.name.fill(generateName());
        await this.inventorySourceActionPage.description.fill(
            generateDescription(),
        );
        await this.inventorySourceActionPage.contactName.fill(generateName());
        await this.inventorySourceActionPage.enterEmail.fill(generateEmail());
        await this.inventorySourceActionPage.contactNumber.fill(
            generatePhoneNumber(),
        );
        await this.inventorySourceActionPage.fax.fill(generatePhoneNumber());
        await this.inventorySourceActionPage.country.selectOption("IN");
        await this.inventorySourceActionPage.state.selectOption("DL");
        await this.inventorySourceActionPage.city.fill("New Delhi");
        await this.inventorySourceActionPage.street.fill("Some street address");
        await this.inventorySourceActionPage.postcode.fill("110001");
        await this.inventorySourceActionPage.statusToggle.click();
        await this.inventorySourceActionPage.createBtn.click();
        await expect(
            this.inventorySourceActionPage.successInventorySourceCreate.first(),
        ).toBeVisible();
    }

    async inventorySourceEditVerify() {
        await expect(
            this.inventorySourceActionPage.createBtn,
        ).not.toBeVisible();
        await this.inventorySourceActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.inventorySourceActionPage.createBtn.click();
        await expect(
            this.inventorySourceActionPage.successInventorySourceUpdate.first(),
        ).toBeVisible();
    }

    async inventorySourceDeleteVerify() {
        await expect(
            this.inventorySourceActionPage.createBtn,
        ).not.toBeVisible();
        await expect(
            this.inventorySourceActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.inventorySourceActionPage.deleteIcon.first().click();
        await this.inventorySourceActionPage.agreeBtn.click();
        await expect(
            this.inventorySourceActionPage.successInventorySourceDelete.first(),
        ).toBeVisible();
    }

    async channelCreateVerify() {
        await this.inventorySourceActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.channelActionPage.name.fill(generateName());
        const code = generateSlug("_");
        const name = generateName();
        const description = generateDescription();
        await this.channelActionPage.fillCode.fill(code);
        await this.channelActionPage.promotionRuleDescription.fill(description);
        await this.channelActionPage.inventoryToggle.first().click();
        await this.channelActionPage.categoryID.selectOption("1");
        await this.channelActionPage.hostname.fill(generateHostname());
        await this.channelActionPage.selectLocale.first().click();
        await this.channelActionPage.selectCurrency.first().click();
        await this.channelActionPage.baseCurrencyID.selectOption("1");
        await this.channelActionPage.defaultLocaleID.selectOption("1");
        await this.channelActionPage.metaTitleChannel.fill(name);
        await this.channelActionPage.seoKeywords.fill("keywords");
        await this.channelActionPage.metaDescription.fill(description);
        await this.channelActionPage.createBtn.click();
        await expect(
            this.channelActionPage.channleCreateSuccess.first(),
        ).toBeVisible();
    }

    async channelEditVerify() {
        await expect(this.channelActionPage.createBtn).not.toBeVisible();
        await this.channelActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.channelActionPage.createBtn.click();
        await expect(
            this.channelActionPage.channlUpdateSuccess.first(),
        ).toBeVisible();
    }

    async channelDeleteVerify() {
        await expect(this.channelActionPage.createBtn).not.toBeVisible();
        await this.channelActionPage.deleteIcon.first().click();
        await this.channelActionPage.agreeBtn.click();
        await expect(
            this.channelActionPage.channelDeleteSuccess.first(),
        ).toBeVisible();
    }

    async createUserVerify() {
        await this.userActionPage.createUser.click();
        await this.userActionPage.name.fill(this.userName);
        await this.userActionPage.selectRole.selectOption({
            label: this.roleName,
        });
        await this.userActionPage.userEmail.fill(generateEmail());
        await this.userActionPage.userPassword.fill("user123");
        await this.userActionPage.confirmPassword.fill("user123");
        await this.userActionPage.statusToggle.click();
        const toggleInput = await this.userActionPage.statusToggle;
        await expect(toggleInput).toBeChecked();
        await this.userActionPage.saveUser.click();
        await expect(this.userActionPage.successUser.first()).toBeVisible();
    }

    async editUserVerify() {
        await expect(this.userActionPage.createUser).not.toBeVisible();
        await this.userActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.userActionPage.saveUser.click();
        await expect(
            this.userActionPage.successUserUpdate.first(),
        ).toBeVisible();
    }

    async deleteUserVerify() {
        await expect(this.userActionPage.createUser).not.toBeVisible();
        await expect(this.userActionPage.iconEdit.first()).not.toBeVisible();
        await this.userActionPage.deleteIcon.nth(2).click();
        await this.userActionPage.agreeBtn.click();
        await expect(
            this.userActionPage.successUserDelete.first(),
        ).toBeVisible();
    }

    async roleCreateVerify() {
        await this.roleActionPage.createRole.click();
        await this.roleActionPage.name.fill(this.roleName);
        await this.roleActionPage.selectRoleType.selectOption("all");
        await this.roleActionPage.roleDescription.fill("test description");
        await this.roleActionPage.saveRole.click();
        await expect(this.roleActionPage.successRole.first()).toBeVisible();
    }

    async roleEditVerify() {
        await expect(this.roleActionPage.createRole).not.toBeVisible();
        await this.roleActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.roleActionPage.saveRole.click();
        await expect(
            this.roleActionPage.successUpdateRole.first(),
        ).toBeVisible();
    }

    async roleDeleteVerify() {
        await expect(this.roleActionPage.createRole).not.toBeVisible();
        await this.roleActionPage.deleteIcon.nth(2).click();
        await this.roleActionPage.agreeBtn.click();
        await expect(
            this.roleActionPage.successDeleteRole.first(),
        ).toBeVisible();
    }

    async themeCreateVerify() {
        await this.themeActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.themeActionPage.name.fill(generateName());
        await this.themeActionPage.sortOrder.fill("1");
        await this.themeActionPage.selectTypeAttribute.selectOption(
            "product_carousel",
        );
        await this.themeActionPage.selectChannel.selectOption("1");
        await this.themeActionPage.createBtn.nth(1).click();
        await expect(this.themeActionPage.athorization.first()).toBeVisible();
    }

    async themeEditVerify() {
        await expect(this.themeActionPage.createBtn).not.toBeVisible();
        await this.page.waitForLoadState("networkidle");
        await this.themeActionPage.iconEdit.nth(3).click();
        await this.page.waitForLoadState("networkidle");
        await this.themeActionPage.createBtn.click();
        await expect(
            this.themeActionPage.successEditTheme.first(),
        ).toBeVisible();
    }

    async themeDeleteVerify() {
        await expect(this.themeActionPage.createBtn).not.toBeVisible();
        await expect(this.themeActionPage.iconEdit.nth(3)).not.toBeVisible();
        await this.themeActionPage.deleteIcon.first().click();
        await this.themeActionPage.agreeBtn.click();
        await expect(
            this.themeActionPage.successDeleteTheme.first(),
        ).toBeVisible();
    }

    async taxrateCreateVerify() {
        await this.taxRateActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.taxRateActionPage.identifier.fill("test-tax-rate");
        await this.taxRateActionPage.selectCountry.selectOption("IN");
        await this.taxRateActionPage.taxRate.fill("10");
        await this.taxRateActionPage.createBtn.first().click();
        await expect(
            this.taxRateActionPage.successCreateTaxRate.first(),
        ).toBeVisible();
    }

    async taxrateEditVerify() {
        await expect(this.taxRateActionPage.createBtn).not.toBeVisible();
        await this.taxRateActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.taxRateActionPage.createBtn.first().click();
        await expect(
            this.taxRateActionPage.successUpdateTaxRate.first(),
        ).toBeVisible();
    }

    async taxrateDeleteVerify() {
        await expect(this.taxRateActionPage.createBtn).not.toBeVisible();
        await expect(this.taxRateActionPage.iconEdit.first()).not.toBeVisible();
        await this.taxRateActionPage.deleteIcon.first().click();
        await this.taxRateActionPage.agreeBtn.click();
        await expect(
            this.taxRateActionPage.successDeleteTaxRate.first(),
        ).toBeVisible();
    }

    async taxcategoryCreateVerify() {
        await this.taxCategoryActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.taxCategoryActionPage.fillCode.fill("test-tax-category");
        await this.taxCategoryActionPage.name.fill("Test Tax Category");
        await this.taxCategoryActionPage.description.fill(
            "This is a test tax category",
        );
        await this.taxCategoryActionPage.selectTaxRate.selectOption({
            label: "test-tax-rate",
        });
        await this.taxCategoryActionPage.createBtn.nth(1).click();
        await expect(
            this.taxCategoryActionPage.successCreateTaxCategory.first(),
        ).toBeVisible();
    }

    async taxcategoryEditVerify() {
        await expect(this.taxCategoryActionPage.createBtn).not.toBeVisible();
        await this.taxCategoryActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.taxCategoryActionPage.createBtn.first().click();
        await expect(
            this.taxCategoryActionPage.successUpdateTaxCategory.first(),
        ).toBeVisible();
    }

    async taxcategoryDeleteVerify() {
        await expect(this.taxCategoryActionPage.createBtn).not.toBeVisible();
        await expect(
            this.taxCategoryActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.taxCategoryActionPage.deleteIcon.first().click();
        await this.taxCategoryActionPage.agreeBtn.click();
        await expect(
            this.taxCategoryActionPage.successDeleteTaxCategory.first(),
        ).toBeVisible();
    }

    async createOrder() {
        await loginAsCustomer(this.page);
        await this.page.waitForLoadState("networkidle");
        const acceptButton = this.page.getByRole("button", { name: "Accept" });

        if (await acceptButton.isVisible()) {
            await acceptButton.click();
        }
        await addAddress(this.page);
        await this.page.goto("");
        await this.customerCheckoutActionPage.searchInput.fill("simple");
        await this.customerCheckoutActionPage.searchInput.press("Enter");
        await this.customerCheckoutActionPage.addToCartButton.first().click();
        await expect(
            this.customerCheckoutActionPage.addCartSuccess.first(),
        ).toBeVisible();
        await this.customerCheckoutActionPage.shoppingCartIcon.click();
        await this.customerCheckoutActionPage.continueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.customerCheckoutActionPage.clickProcessButton.click();
        await this.customerCheckoutActionPage.chooseShippingMethod.click();
        await this.customerCheckoutActionPage.choosePaymentMethod.click();
        await this.page.waitForTimeout(2000);
        await this.customerCheckoutActionPage.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
    }

    async createSimpleProduct(adminPage) {
        /**
         * Main product data which we will use to create the product.
         */
        const product = {
            name: `simple-${Date.now()}`,
            sku: generateSKU(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
            weight: "25",
        };

        /**
         * Reaching to the create product page.
         */
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );
        await adminPage.getByRole("button", { name: "Create Product" }).click();

        /**
         * Opening create product form in modal.
         */
        await adminPage.locator('select[name="type"]').selectOption("simple");
        await adminPage
            .locator('select[name="attribute_family_id"]')
            .selectOption("1");
        await adminPage.locator('input[name="sku"]').fill(generateSKU());
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
         * After creating the product, the page is redirected to the edit product page, where
         * all the details need to be filled in.
         */
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Save Product")',
        );

        /**
         * Waiting for the main form to be visible.
         */
        await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

        /**
         * General Section.
         */
        await adminPage.locator("#product_number").fill(product.productNumber);
        await adminPage.locator("#name").fill(product.name);
        const name = await adminPage.locator('input[name="name"]').inputValue();

        /**
         * Description Section.
         */
        await adminPage.fillInTinymce(
            "#short_description_ifr",
            product.shortDescription,
        );
        await adminPage.fillInTinymce("#description_ifr", product.description);

        /**
         * Meta Description Section.
         */
        await adminPage.locator("#meta_title").fill(product.name);
        await adminPage.locator("#meta_keywords").fill(product.name);
        await adminPage
            .locator("#meta_description")
            .fill(product.shortDescription);

        /**
         * Image Section.
         */
        // Will add images later.

        /**
         * Price Section.
         */
        await adminPage.locator("#price").fill(product.price);

        /**
         * Shipping Section.
         */
        await adminPage.locator("#weight").fill(product.weight);

        /**
         * Inventories Section.
         */
        await adminPage.locator('input[name="inventories\\[1\\]"]').click();
        await adminPage
            .locator('input[name="inventories\\[1\\]"]')
            .fill("5000");

        /**
         * RMA
         */

        await this.page.locator('label[for="allow_rma"]').click();

        /**
         * Saving the product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
         * Expecting for the product to be saved.
         */
        await expect(adminPage.locator("#app")).toContainText(
            /product updated successfully/i,
        );

        /**
         * Checking the product in the list.
         */
        await adminPage.goto("admin/catalog/products");
        await expect(
            adminPage
                .locator("p.break-all.text-base")
                .filter({ hasText: product.name }),
        ).toBeVisible();
    }

    async rmaCreateVerify() {
        await this.createSimpleProduct(this.page);
        await this.createOrder();
        await this.page.goto("admin/sales/rma/requests");
        await this.rmaActionPage.createBtn.click();
        await this.rmaActionPage.iconEdit.first().click();
        await this.rmaActionPage.checkBox.check();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.resolution.selectOption("cancel_items");
        await this.rmaActionPage.resolution.selectOption("cancel_items");
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.reason.selectOption("1");
        await this.rmaActionPage.rmaQty.fill("1");
        await this.rmaActionPage.info.fill("Changed My Mind.");
        await this.rmaActionPage.createBtn.first().click();
        await expect(this.rmaActionPage.successRMA).toBeVisible();
    }

    async rmaReasonCreateVerify() {
        await this.page.goto("admin/sales/rma/reasons");
        await this.rmaActionPage.createReasonBtn.click();
        await this.rmaActionPage.reasonTitle.fill("Broken Product");
        await this.rmaActionPage.reasonStatus.check();
        await this.rmaActionPage.position.fill("1");
        await this.rmaActionPage.reasonType.selectOption("return");
        await this.rmaActionPage.saveReasonBtn.click();
        await expect(this.rmaActionPage.successReasonCreate).toBeVisible();
    }

    async rmaReasonEditVerify() {
        await this.page.goto("admin/sales/rma/reasons");
        await expect(this.rmaActionPage.createReasonBtn).not.toBeVisible();
        await this.rmaActionPage.iconEdit.first().click();
        await this.rmaActionPage.position.fill("5");
        await this.rmaActionPage.saveReasonBtn.click();
        await expect(this.rmaActionPage.successReasonUpdate).toBeVisible();
    }

    async rmaReasonDeleteVerify() {
        await this.page.goto("admin/sales/rma/reasons");
        await expect(this.rmaActionPage.createReasonBtn).not.toBeVisible();
        await expect(this.rmaActionPage.iconEdit.first()).not.toBeVisible();
        await this.rmaActionPage.deleteIcon.first().click();
        await this.rmaActionPage.agreeBtn.click();
        await expect(this.rmaActionPage.successReasonDelete).toBeVisible();
    }

    async rmaRulesCreateVerify() {
        await this.page.goto("admin/sales/rma/rules");
        await this.rmaActionPage.createRuleBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.ruleTitle.fill("Test Rule1");
        await this.rmaActionPage.reasonStatus.check();
        await this.rmaActionPage.ruleDescription.fill("Test Rule Description");
        await this.rmaActionPage.returnPeriod.fill("15");
        await this.rmaActionPage.saveRuleBtn.click();
        await expect(this.rmaActionPage.successRuleCreate).toBeVisible();
    }

    async rmaRulesEditVerify() {
        await this.page.goto("admin/sales/rma/rules");
        await expect(this.rmaActionPage.createRuleBtn).not.toBeVisible();
        await this.rmaActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.ruleTitle.fill("Test Rule1");
        await this.rmaActionPage.reasonStatus.check();
        await this.rmaActionPage.ruleDescription.fill("Test Rule Description");
        await this.rmaActionPage.returnPeriod.fill("15");
        await this.rmaActionPage.saveRuleBtn.click();
        await expect(this.rmaActionPage.successRuleUpdate).toBeVisible();
    }

    async rmaRulesDeleteVerify() {
        await this.page.goto("admin/sales/rma/rules");
        await expect(this.rmaActionPage.createRuleBtn).not.toBeVisible();
        await expect(this.rmaActionPage.iconEdit).not.toBeVisible();
        await this.rmaActionPage.deleteIcon.first().click();
        await this.rmaActionPage.agreeBtn.click();
        await expect(this.rmaActionPage.successRuleDelete).toBeVisible();
    }

    async rmaStatusCreateVerify() {
        await this.page.goto("admin/sales/rma/rma-status");
        await this.rmaActionPage.createStatusBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.statusTitle.fill("RMA Status");
        await this.rmaActionPage.reasonStatus.click();
        await this.rmaActionPage.saveStatusBtn.click();
        await expect(this.rmaActionPage.successStatusCreate).toBeVisible();
    }

    async rmaStatusEditVerify() {
        await this.page.goto("admin/sales/rma/rma-status");
        await expect(this.rmaActionPage.createStatusBtn).not.toBeVisible();
        await this.rmaActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.statusTitle.fill("RMA Status edited");
        await this.rmaActionPage.saveStatusBtn.click();
        await expect(this.rmaActionPage.successStatusUpdate).toBeVisible();
    }

    async rmaStatusDeleteVerify() {
        await this.page.goto("admin/sales/rma/rma-status");
        await expect(this.rmaActionPage.createStatusBtn).not.toBeVisible();
        await expect(this.rmaActionPage.iconEdit.first()).not.toBeVisible();
        await expect(this.rmaActionPage.deleteIcon.first()).not.toBeVisible();
        await this.rmaActionPage.selectRow.first().click();
        await this.rmaActionPage.selectAction.click();
        await this.rmaActionPage.deleteAction.click();
        await this.rmaActionPage.agreeBtn.click();
        await expect(this.rmaActionPage.successStatusDelete).toBeVisible();
    }
}
