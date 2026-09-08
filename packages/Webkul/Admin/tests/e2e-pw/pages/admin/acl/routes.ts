export interface AclModuleRoute {
    permission: string;
    allowed: string;
    sidebar?: string;
    denied: string[];
}

export const MODULE_PROBES: Record<string, string> = {
    dashboard: "admin/dashboard",
    sales: "admin/sales/orders",
    catalog: "admin/catalog/products",
    customers: "admin/customers",
    cms: "admin/cms",
    marketing: "admin/marketing/promotions/catalog-rules",
    reporting: "admin/reporting/sales",
    settings: "admin/settings/locales",
    configuration: "admin/configuration",
    appearance: "admin/appearance/themes",
};

const MODULE_ROUTES: Omit<AclModuleRoute, "denied">[] = [
    { permission: "dashboard", allowed: "admin/dashboard", sidebar: "/admin/dashboard" },
    { permission: "sales", allowed: "admin/sales/orders", sidebar: "/admin/sales/orders" },
    { permission: "sales.orders", allowed: "admin/sales/orders", sidebar: "/admin/sales/orders" },
    { permission: "sales.transactions", allowed: "admin/sales/transactions", sidebar: "/admin/sales/transactions" },
    { permission: "sales.shipments", allowed: "admin/sales/shipments", sidebar: "/admin/sales/shipments" },
    { permission: "sales.invoices", allowed: "admin/sales/invoices", sidebar: "/admin/sales/invoices" },
    { permission: "sales.refunds", allowed: "admin/sales/refunds", sidebar: "/admin/sales/refunds" },
    { permission: "sales.rma.requests", allowed: "admin/sales/rma/requests", sidebar: "/admin/sales/rma/requests" },
    { permission: "sales.rma.reasons", allowed: "admin/sales/rma/reasons", sidebar: "/admin/sales/rma/reasons" },
    { permission: "sales.rma.rules", allowed: "admin/sales/rma/rules", sidebar: "/admin/sales/rma/rules" },
    { permission: "sales.rma.statuses", allowed: "admin/sales/rma/rma-status", sidebar: "/admin/sales/rma/rma-status" },
    { permission: "catalog", allowed: "admin/catalog/products", sidebar: "/admin/catalog/products" },
    { permission: "catalog.products", allowed: "admin/catalog/products", sidebar: "/admin/catalog/products" },
    { permission: "catalog.categories", allowed: "admin/catalog/categories", sidebar: "/admin/catalog/categories" },
    { permission: "catalog.attributes", allowed: "admin/catalog/attributes", sidebar: "/admin/catalog/attributes" },
    { permission: "catalog.families", allowed: "admin/catalog/families", sidebar: "/admin/catalog/families" },
    { permission: "customers", allowed: "admin/customers", sidebar: "/admin/customers" },
    { permission: "customers.customers", allowed: "admin/customers", sidebar: "/admin/customers" },
    { permission: "customers.groups", allowed: "admin/customers/groups", sidebar: "/admin/customers/groups" },
    { permission: "customers.reviews", allowed: "admin/customers/reviews", sidebar: "/admin/customers/reviews" },
    { permission: "customers.gdpr_requests", allowed: "admin/customers/gdpr", sidebar: "/admin/customers/gdpr" },
    { permission: "marketing", allowed: "admin/marketing/promotions/catalog-rules", sidebar: "/admin/marketing/promotions/catalog-rules" },
    { permission: "marketing.promotions", allowed: "admin/marketing/promotions/catalog-rules", sidebar: "/admin/marketing/promotions/catalog-rules" },
    { permission: "marketing.promotions.catalog_rules", allowed: "admin/marketing/promotions/catalog-rules", sidebar: "/admin/marketing/promotions/catalog-rules" },
    { permission: "marketing.promotions.cart_rules", allowed: "admin/marketing/promotions/cart-rules", sidebar: "/admin/marketing/promotions/cart-rules" },
    { permission: "marketing.communications", allowed: "admin/marketing/communications/email-templates", sidebar: "/admin/marketing/communications/email-templates" },
    { permission: "marketing.communications.email_templates", allowed: "admin/marketing/communications/email-templates", sidebar: "/admin/marketing/communications/email-templates" },
    { permission: "marketing.communications.events", allowed: "admin/marketing/communications/events", sidebar: "/admin/marketing/communications/events" },
    { permission: "marketing.communications.campaigns", allowed: "admin/marketing/communications/campaigns", sidebar: "/admin/marketing/communications/campaigns" },
    { permission: "marketing.search_seo", allowed: "admin/marketing/search-seo/url-rewrites", sidebar: "/admin/marketing/search-seo/url-rewrites" },
    { permission: "marketing.search_seo.url_rewrites", allowed: "admin/marketing/search-seo/url-rewrites", sidebar: "/admin/marketing/search-seo/url-rewrites" },
    { permission: "marketing.search_seo.search_terms", allowed: "admin/marketing/search-seo/search-terms", sidebar: "/admin/marketing/search-seo/search-terms" },
    { permission: "marketing.search_seo.search_synonyms", allowed: "admin/marketing/search-seo/search-synonyms", sidebar: "/admin/marketing/search-seo/search-synonyms" },
    { permission: "marketing.search_seo.sitemaps", allowed: "admin/marketing/search-seo/sitemaps", sidebar: "/admin/marketing/search-seo/sitemaps" },
    { permission: "cms", allowed: "admin/cms", sidebar: "/admin/cms" },
    { permission: "reporting", allowed: "admin/reporting/sales", sidebar: "/admin/reporting/sales" },
    { permission: "reporting.sales", allowed: "admin/reporting/sales", sidebar: "/admin/reporting/sales" },
    { permission: "reporting.customers", allowed: "admin/reporting/customers", sidebar: "/admin/reporting/customers" },
    { permission: "reporting.products", allowed: "admin/reporting/products", sidebar: "/admin/reporting/products" },
    { permission: "settings", allowed: "admin/settings/locales", sidebar: "/admin/settings/locales" },
    { permission: "settings.locales", allowed: "admin/settings/locales", sidebar: "/admin/settings/locales" },
    { permission: "settings.currencies", allowed: "admin/settings/currencies", sidebar: "/admin/settings/currencies" },
    { permission: "settings.exchange_rates", allowed: "admin/settings/exchange-rates", sidebar: "/admin/settings/exchange-rates" },
    { permission: "settings.inventory_sources", allowed: "admin/settings/inventory-sources", sidebar: "/admin/settings/inventory-sources" },
    { permission: "settings.channels", allowed: "admin/settings/channels", sidebar: "/admin/settings/channels" },
    { permission: "settings.users", allowed: "admin/settings/users", sidebar: "/admin/settings/users" },
    { permission: "settings.roles", allowed: "admin/settings/roles", sidebar: "/admin/settings/roles" },
    { permission: "settings.taxes", allowed: "admin/settings/taxes/categories", sidebar: "/admin/settings/taxes/categories" },
    { permission: "settings.taxes.tax_rates", allowed: "admin/settings/taxes/rates", sidebar: "/admin/settings/taxes/rates" },
    { permission: "settings.taxes.tax_categories", allowed: "admin/settings/taxes/categories", sidebar: "/admin/settings/taxes/categories" },
    { permission: "settings.data_transfer", allowed: "admin/settings/data-transfer/imports", sidebar: "/admin/settings/data-transfer/imports" },
    { permission: "configuration", allowed: "admin/configuration", sidebar: "/admin/configuration" },
    { permission: "appearance", allowed: "admin/appearance/themes", sidebar: "/admin/appearance/themes" },
    { permission: "appearance.themes", allowed: "admin/appearance/themes", sidebar: "/admin/appearance/themes" },
    { permission: "appearance.sections", allowed: "admin/appearance/themes/default/sections" },
];

function deniedRoutesFor(route: Omit<AclModuleRoute, "denied">): string[] {
    const module = route.permission.split(".")[0];
    const denied = Object.entries(MODULE_PROBES)
        .filter(([key]) => key !== module)
        .map(([, path]) => path);

    if (
        route.permission !== module
        && MODULE_PROBES[module] !== route.allowed
    ) {
        denied.push(MODULE_PROBES[module]);
    }

    return denied;
}

export const ACL_MODULE_ROUTES: AclModuleRoute[] = MODULE_ROUTES.map((route) => ({
    ...route,
    denied: deniedRoutesFor(route),
}));
