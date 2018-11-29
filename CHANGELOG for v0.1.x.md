# CHANGELOG for v0.1.x

#### This changelog consists the bug & security fixes and new features being included in the releases listed below.

## **v0.1.1(13th of November, 2018)** - *Release*

#94 - [fixed] Sign-in page shows signup text(@prashant-webkul)

#95 - [fixed] Buy Now Button does not work(@prashant-webkul)

#96 - [fixed] Search button does not work(@prashant-webkul)

**PR #118** - *List of the features and fixes covered:*

    * [fixed] Email templates logo issue fixed(@jitendra-webkul)
    * [fixed] Front search issue fixed due to hardcoded attribute code in search criteria(@jitendra-webkul)
    * [changed] Versioning of core packages
    * [fixed] Buynow validation fixes(@jitendra-webkul)
    * [feature] New action type added in datagrid
    * [feature] Loader added in storefront product page
    * [fixed] Tax rates and categories form fixes(@jitendra-webkul)
    * [feature] Country state selector added where country and states were there originally in release v0.1.0
    * [feature] Multiple addresses for customers with CRUD
    * [feature] Customer can now make any of his/her existing address a default address
    * [fixed] Customer address 2 form field validation required changed to optional(@jitendra-webkul)
    * [fixed] Tax rates validation fixes for zip ranges(@prashant-webkul)
    * [feature] Print invoice feature added.
    * [changed] Core packages composer file parameter name changed from namespace webkul to bagisto
    * [feature] Payment package added in core packages
    * [feature] Sales module added in admin with orders, invoices and shipments with datagrid
    * [feature] Functionality to indicate the new and featured product in the product's add and edit form
    * [feature] Cart actions more faster in storefront
    * [changed] Responsive styles refined and extended for checkout pages on storefront
    * [fixed] Various UI/UX fixes in store front styles and layouts(@prashant-webkul & @jitendra-webkul)


## **v0.1.0(30th of October 2018)** - *First release*

**PR #117** - *List of the features and fixes covered:*

    * [feature] Add and modify product with simple and configurable types
    * [feature] Add and modify attributes and attribute families for creating products.
    * [feature] Datagrid for all the major core resources added as index for listing core resources like product, attributes.
    * [feature] Add and modify channels for creating multiple storefront.
    * [feature] Add and modify categories to be displayed on storefront.
    * [feature] Add and modify customers.
    * [feature] Add and modify customer groups.
    * [feature] Add and modify customer reviews for moderation by admin.
    * [feature] Add and modify currently logged in admin user details.
    * [feature] Add and modify locales for multiple languages support system wide.
    * [feature] Add and modify currencies to be used in channels
    * [feature] Add and modify currency exchange rate for the stores accepting multiple currencies or using multiple channels.
    * [feature] Add and modify inventory sources with priority to hold products quantities in real time.
    * [feature] Add and modify channels.
    * [feature] Add and modify user from admins access with customer roles.
    * [feature] Add and modify customer roles for users.
    * [feature] Add and modify slider for storefront as a CMS capability.
    * [feature] Add and modify tax categories and tax rates.
    * [feature] Shopping cart in storefront
    * [feature] Wishlist for customer
    * [feature] Single address for customer
    * [feature] Customer can see his reviews in his account section when logged in.
    * [feature] Customer profile edit feature account section when logged in.
    * [feature] Customer can view his orders in account section when logged in.
    * [feature] Customer order notifications via mails.
    * [feature] Multiple locales and currencies on storefront.
    * [feature] Locale translations are stored as a separate file in shop and admin packages.
    * [feature] Single page checkout system for checkout.
    * [feature] Custom themes and assets provisioning included as a integrated package called "theme" in packages.