# CHANGELOG for v0.1.x

#### This changelog consists the bug & security fixes and new features being included in the releases listed below.

## **v0.1.2(30th of November, 2018)** - *Release*

* [feature] Paypal integration for online payments

* [feature] Newsletter subscription

* [feature] Email Verification for customers

* [feature] News letter grid for Admin

* #185 - [fixed] Search not working in responsive mode

* #187 - [fixed] We can add column "Group Name" instead of Group Id and also add this column in filter in Customers Grid

* #247 - [fixed] Displaying wrong number of products and sales in category, on dashboard

* #207 - [fixed] Two button are not required to save address

* #119 - [fixed] Set value in the login form fields(on Demo)

* #245 - [fixed] Add Sales and Customers also in Custom Permission option of Access Control

* #126 - [fixed] Add asterisk symbol on SKU field

* #224 - [fixed] Status column in Invoice remains blank

* #192 - [fixed] Not able to checkout with different shipping address

* #188 - [fixed] Unable to delete Customer Group

* #151 - [fixed] Description and Short description field are throwing validation error message even if description is written

* #162 - [fixed] No response when click on "Add to Cart" for configurable product on home page and category page

* #180 - [fixed] Not accepting the code for Currency if it is already used in locales

* #121 - [fixed] buy now button is not working on index page for some products

* #134 - [fixed] Unable to login with the user account that is created in user grid with custom access

* #154 - [fixed] While creating channel Description field,Home Page Content and footer _content is required, but it doesn't throw any validation error if we leave that field blank

* #146 - [fixed] Tax is not added on product at checkout

* #161 - [fixed] Inappropriate validation message(System wide fix is applied for validation messages)

* #142 - [fixed] Correct the spelling of "default" in theme

* #165 - [fixed] If a Product is selected as disabled at time of creation,then also it is visible at store front

* #133 - [fixed] Add asterisk symbol for email field in Add User page

* #175 - [fixed] Getting exception in deleting attributes

* #165 - [feature] No grid is available in back-end to manage Newsletter

* #235 - [fixed] User name should display on account dropdown(In case of signed-in user)

* #200 - [fixed] Getting exception when applying filter on Exchange Rates grid

* #225 - [fixed] Slider button should display as clickable on mouse hover

* #148 - [fixed] Search Functionality is not working in all grid of Admin panel

* #216 - [added] Add a Column "Channel" to verify from which channel order has been placed and also add this column in filter

* #237 - [fixed] Incorrect response message after deleting product from wishlist

* #201 - [fixed] Getting exception when applying filter in slider

* #202 - [fixed] Correct the background text of filter field in Taxes grid.Text should be according to selected column(placeholder)

* #232 - [added] Add sorting functionality in column "Name" of Inventory Grid

* #230 - [added] Add Sorting functionality on column "Status" of Product Grid

* #229 - [added] Add sorting functionality on Column "Type" in Product Grid, so that product can be sorted according to type

* #244 - [fixed] Getting exception when applying sorting on Tax Rate

* #242 - [fixed] Confirmation message should be "Do you really want to edit this record?", on editing slider

* #236 - [fixed] Incorrect response message after removing a product from cart

* #206 - [fixed] Correct the confirmation message in pop-up when click on Edit User

* #174 - [fixed] Getting Exception while applying filter on category page

* #149 - [fixed] Getting exception in creating configurable product.

* #184 - [fixed] Product page mandatory fields are missing '*' or asterisk as failing to indicate required field and inappropriate validation message

* #162 - [fixed] No response when click on "Add to Cart" for configurable product on home page and category page

* #164 - [fixed] Loss of data from content field of Slider

* #166 - [fixed] Getting 404 error on deleting order

* #178 - [fixed] Change the Column Name

* #129 - [fixed] Getting issue when deleting orders.Mass action is not working in any grid(Getting Internal server error) while updating status(mass actions will return in next release and the issue will remain open till next release)

* #181 - [fixed] Change the column name in filter from "Target Currency" to "Currency Name"

* #172 - [fixed] Getting 500 Internal Server Error on updating Users

* #173 - [fixed] Getting 500 Internal Server Error on Updating taxes

* #177 - [fixed] Getting Exception when clicking on column locale

* #125 - [fixed] Delete button is not available for mass delete of Products in Product Grid(mass actions will return in next release and the issue will remain open till next release)

* #132 - [fixed] Issue in Mass Deletion.This issue exists for every grid(mass actions will return in next release and the issue will remain open till next release)

* #137 - [fixed] By default Gender is selected as Male for every customer

* #141 - [fixed] Subscribe button on storefront is unresponsive

* #145 - [fixed] Edit Functionality of Tax Categories is not Working

* #157 - [fixed] Old password check in edit profile ain't working for customers

* #120 - [fixed] After signup on the frontend, the customer is still not the signup page

* #139 - [fixed] Encountered exception while changing locale on storefront

* #144 - [fixed] "Move All Products To Cart" and "Delete All" link is not working in Wishlist grid(Move all products to cart is removed and delete all works now)

* #150 - [fixed] If currency changes on store front then on admin panel in Order Grid currency changes according to store front

* [fixes] More ACL added.


## **v0.1.1(13th of November, 2018)** - *Release*

* #94 - [fixed] Sign-in page shows signup text(@prashant-webkul)

* #95 - [fixed] Buy Now Button does not work(@prashant-webkul)

* #96 - [fixed] Search button does not work(@prashant-webkul)

* #97 - [fixed] client side validation / js validation is missing at login page

* #98 - [fixed] No warning before removing the item from the cart

* #114 - [fixed] Invoice printing added as a feature

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

* [changed] Core packages composer file parameter name changed from namespace webkul to bagisto

* [feature] Payment package added in core packages

* [feature] Sales module added in admin with orders, invoices and shipments with datagrid

* [feature] Functionality to indicate the new and featured product in the product's add and edit form

* [feature] Cart actions more faster in storefront

* [changed] Responsive styles refined and extended for checkout pages on storefront

* [fixed] Various UI/UX fixes in store front styles and layouts(@prashant-webkul & @jitendra-webkul)


## **v0.1.0(30th of October 2018)** - *First release*

* [feature] Add and modify product with simple and configurable types

* [feature] Add and modify attributes and attribute families for creating products

* [feature] Datagrid for all the major core resources added as index for listing core resources like product, attributes

* [feature] Add and modify channels for creating multiple storefront

* [feature] Add and modify categories to be displayed on storefront

* [feature] Add and modify customers

* [feature] Add and modify customer groups
* [feature] Add and modify customer reviews for moderation by admin

* [feature] Add and modify currently logged in admin user details

* [feature] Add and modify locales for multiple languages support system wide

* [feature] Add and modify currencies to be used in channels

* [feature] Add and modify currency exchange rate for the stores accepting multiple currencies or using multiple channels

* [feature] Add and modify inventory sources with priority to hold products quantities in real time

* [feature] Add and modify channels

* [feature] Add and modify user from admins access with customer roles

* [feature] Add and modify customer roles for users

* [feature] Add and modify slider for storefront as a CMS capability

* [feature] Add and modify tax categories and tax rates

* [feature] Shopping cart in storefront

* [feature] Wishlist for customer

* [feature] Single address for customer

* [feature] Customer can see his reviews in his account section when logged in

* [feature] Customer profile edit feature account section when logged in

* [feature] Customer can view his orders in account section when logged in
* [feature] Customer order notifications via mails

* [feature] Multiple locales and currencies on storefront

* [feature] Locale translations are stored as a separate file in shop and admin packages

* [feature] Single page checkout system for checkout

* [feature] Custom themes and assets provisioning included as a integrated package called "theme" in packages
