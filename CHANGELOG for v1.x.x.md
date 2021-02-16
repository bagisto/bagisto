# CHANGELOG for v1.x.x

#### This changelog consists the bug & security fixes and new features being included in the releases listed below.

## **v1.3.0 (16th of February 2021)** - *Release*

* [feature] - PayPal Smart Button for quick and fast payment.

* [feature] - Email Marketing-Newsletter to send the newsletter to your valuable customers.

* [feature] - Control on Displaying of out of stock Product.

* [feature] - Minimum Order Amount by which it will necessary to meet their order subtotal to make a successful purchase.

* [feature] - Add Video of Your Product.

* [feature] - Maintenance Mode, let search engines know that your site is currently down.

* [feature] - Buy Now at Product Page, Admin can easily enable/disable the Buy Now Button for both themes.

* [feature] - laravel Debugger Mode, This will help the developer/users to see all the packages they are using inside the Webkul folder.

* [enhancement] - Duplicated queries removed.

* [enhancement] - Improved lighthouse score.

* [enhancement] - Slider path should also work in default theme.

* [enhancement] - Add configuration to enable/disable the image search feature.

* [enhancement] - Multi product images should be changed on mouse hover.

* [enhancement] - Add configuration to set admin url while install using bagisto:install command.

* [enhancement] - Add confirm box while moving an item to wishlist from the cart page.

* [enhancement] - Add option to export auto generated coupons in cart rule.

* [enhancement] - Add bulk category delete functionality.

* [enhancement] - Default shop content should be localized.

* [enhancement] - Schedule cron job to process newsletter campaigns mails.

* [enhancement] - Showing configurable product base on catalog rule price.

* [enhancement] - Wishlist and Cart key in Product API.

* [enhancement] - Add a option in the channel configuration to enable/disable wishlist.

* [enhancement] - Add product number.

* #3886 [fixed] - Cart item not bound to the account when login with social login.

* #3785 [fixed] - Product filter is not working according to channel filter.

* #3879 [fixed] - Getting exception on uploading high size image in Invoice Slip Design logo.

* #3954 [fixed] - Featured New Product Adding Special Price the 2 columns in mobile version is broke.

* #3964 [fixed] - pay with paypal smart button is missing in checkout.

* #3940 [fixed] - bagisto:install defines APP_URL to localhost by default.

* #3957 [fixed] - Configurable product parent image is visible in cart instead of variant image.

* #3981 [fixed] - No validation message on creating addresses without required params.

* #4030 [fixed] - featured or new label should not visible if product is not exist in default theme.

* #4061 [fixed] - variant image is not visible when item added in wishlist.

* #4024 [fixed] - customer group price discount is not applied when product price and discount amount is same.

* #4062 [fixed] - search icon in velocity theme is not clickable.

* #4085 [fixed] - Not able to fetch invoice by API.

* #4094 [fixed] - filter is not working properly in case product name contains any special character.

* #4130 [fixed] - event ticket price field accepts negative values.

* #4117 [fixed] - error in booking product while booking a slot.

* #4124 [fixed] - Client Side validation issue in Special Price for ticket booking Product.

* #4191 [fixed] - back date should be disabled in booking products.

* #4105 [fixed] - Issue with total due in cash on delivery.

* #3975 [fixed] - Filter is not working properly for status column in case product has not been name and other required details.

* #4212 [fixed] - Error uninstall developer dependencies going into production mode.

* #4234 [fixed] - default values are not working in system configuration.

* #4228 [fixed] - add a validation if user trying to upload greater size image in image search.

* #3807 [fixed] - Can't locate path for migrations in bagisto/packages.

* #4248 [fixed] - PayPay scripts are being loaded even if inactive.

* #4277 [fixed] - subtotal is not updating when remove product from the minicart.

* #4287 [fixed] - Product locale not correctly selected since admin language select.

* #4119 [fixed] - Admin should not be able to create multiple cart rule using same coupon code.

* #4299 [fixed] - sort by newest first or oldest first doesn't work in category.

* #3932 [fixed] - When copying a product, replicating the image causes it to be volatile.

* #4335 [fixed] - Image search disable functionality is not working.

* #4325 [fixed] - address is not saving on onepage checkout.

* #4394 [fixed] - maintenance mode status is not working in channel section.

* #4372 [fixed] - The brand attribute should be deletable.

* #4393 [fixed] - composer test in windows.

* #4413 [fixed] - getting error when setup fresh bagisto from master.

* #4408 [fixed] - silder image not work.

* #4401 [fixed] - composer install --no-dev , gives error.

* #4192 [fixed] - db connection failed at first time when setup using bagisto:install.

* #4425 [fixed] - saved billing address details are not showing on onepage checkout.

* #4434 [fixed] - missing date picker option in event filter #4434.

* #3374 [fixed] - Social Login Error.

* #4440 [fixed] - getting exception when allow user role with marketing permission.

* #3002 [fixed] - Buggggggg iOS Safari images are stretched.

* #4456 [fixed] - getting exception when filter category, attribute, customer datagrid based on status.

* #4433 [fixed] - getting exception when filter campaigns/email templates based on status.

* #4452 [fixed] - products are not visible on homepage if filter based on ar locale.

* #4457 [fixed] - cart rule status always set as draft.

* #4428 [fixed] - user roles permission option always save when trying to uncheck selected option.

* #4392 [fixed] - Multiple cart rule is getting applied in case condition matches on applying only one coupon.

* #4459 [fixed] - disabled option should not accessible from routes in frontend.

* #4447 [fixed] - Product URl key not accepting value for Chinese, Japnese, Arabic locale.

* #4481 [fixed] - Shipping costs are not calculated with grand total.

* #4399 [fixed] - Role-based security issue - User with no permission has acces to some admin pages/controls.

* #4369 [fixed] - Product filter [Shop By].

* #4486 [fixed] - getting exception when copy cart rule.

* #4386 [fixed] - Slow Category Query.

* #4432 [fixed] - subscribed checkbox should be mark if customer is already subscribed.

* #4435 [fixed] -  customer group condition doesn't match while sending newsletter email.

* #4506 [fixed] -  customer group pricing fixed discount should accept decimal values.

* #4498 [fixed] -  able to delete root category which is selected in channel.

* #4420 [fixed] -  Invoice PDF breaking.

* #3638 [fixed] -  Promotion, combine "percentage" & "fixed amount to whole cart" cart rules get wrong disco * unt amount.

* #3572 [fixed] -  No info about other discounts in case there is multiple group pricing exist in same product.

* #4577 [fixed] -  error on minicart while adding configurable item.

* #4571 [fixed] -  video is not showing for variant product in product page.

* #4566 [fixed] -  wrong attribute are visible on layered navigation.

* #4567 [fixed] -  configurable product variant attribute options are not visible in layered navigation.

* #4590 [fixed] -  getting exception on cart when remove minimum amount from backend.

* #4583 [fixed] -  inactive template are sending in newsletter mail to subscribed users.

* #4596 [fixed] -  Unable to view order at customer end.

* #4495 [fixed] -  Only load the locales that are enabled on the channel.

* #4597 [fixed] -  Add a product to Wishlist twice issue.

* #4620 [fixed] -  value is not saved in fallback locale in configuration section.

* #4573 [fixed] -  out of stock items are visible on category & search page when configuration is disabled.

## **v1.2.0 (8th of September 2020)** - *Release*

* [feature] - Config to add custom css and javascript (Eg. google anlytics)

* [feature] - Added some config for payment method additional information

* #1257 [fixed] - Getting issue in install of v0.1.6

* #3643 [fixed] - Invoices should contain the company/store data

* #3772 [fixed] - Unable to add more than 4 products in related products

* #3781 [fixed] - Getting exception on frontend after installation.

* #3782 [fixed] - Getting error on running php artisan migrate if db_prefix contains decimal value

* #3783 [fixed] - Correct the product name alignment in compare list of default theme.

* #3784 [fixed] - Responsive issue on iPad

* #3786 [fixed] - Default advertisement images should display in images section.

* #3789 [fixed] - Filter conditions need to be change according to Boolean in newsletter subscription grid for subscribed column

* #3791 [fixed] - validation message showing for wrong field while editing user from admin end

* #3792 [fixed] - Recently viewed product is not working for Locales other than default.

* #3799 [fixed] - Featured Products and New Products should not display on front end if admin has set 0 in velocity meta data

* #3800 [fixed] - Header Content Count field should convert the value into 0 if given anything apart from valid data(numeric value)

* #3802 [fixed] - Layout issue while adding linked products

* #3804 [fixed] - uploading image on search is not resulting in any keywords

* #3811 [fixed] - Velocity cms page url should be added by default

* #3819 [fixed] - Export Function not working in Firefox/Linux

* #3822 [fixed] - Publish translations from Webkul\Admin

* #3828 [fixed] - success alert is not visible while removing product from wishlist as guest

* #3831 [fixed] - social icons are not visible in footer

* #3847 [fixed] - QTY not returned to inventory when canceling an order after shipping

* #3852 [fixed] - Product copy functionality is not working properly for configurable products

* #3854 [fixed] - Fix reset password alert message

* #3860 [fixed] - Icon used for the customer note or copy item should not be same

* #3861 [fixed] - Payment Instruction is not implemented for velocity theme

* #3862 [fixed] - Fix Payment Instruction view in checkout page

* #3866 [fixed] - Compare icon is not coming in mobile view as well as layout issue on compare page in mobile view(default theme)

* #3871 [fixed] - downloadable sample link issue in RTL

* #3872 [fixed] - customer profile section having bug in mobile view for default theme

* #3878 [fixed] - Search by name doesn't work when sending a request via API

* #3889 [fixed] - default compare icon should be enabled in french also

* #3890 [fixed] - Add validation at admin end for bundle items qty

* #3894 [fixed] - Customer is not able to increase or decrease the qty of downloadable product from cart in default theme

* #3900 [fixed] - layout issue on cart page in ar (Default theme)



## **v1.2.0-BETA1 (18th of August 2020)** - *Release*

* [feature] - Customer group price for products implemented

* [feature] - Image search feature added with tensorflow.js

* [feature] - Migrated to Laravel 7

* [feature] - Search engine optimization with rich snippet

* [feature] - Blade file tracer

* [feature] - Search with Elastic and Algolia

* [feature] - Support for admin multi theme

* [feature] - One click upgrade

* [feature] - Social login (Facebook, Twitter, Google, Linkedin, Github)

* [feature] - Social share

* [feature] - Store configuration added

* [feature] - Feature to disable compare option

* [feature] - Store configuration added for product listing

* #343 [fixed] - Translation strings are missing from awful amount of controllers when returning responses with flash. And optimise translation strings for faster static translations.

* #824 [fixed] - Framework is not supporting I.E 11 browser.

* #838 [fixed] - Packages as composer dependency

* #985 [fixed] - Dynamically insert products

* #1246 [fixed] - Implement a feature to import product through csv file.

* #1258 [fixed] - If payment is done through paypal then invoice should generate automatically and status of Order should be processing.

* #1362 [fixed] - Site logo and Category Image are broken

* #1370 [fixed] - install fails at the last step

* #1522 [fixed] - Quick Links broken

* #1656 [fixed] - Validation error in Phone Field while Adding Address

* #1981 [fixed] - If customer update his address at time of checkout and save this address then address doesn't get save and also on checkout page old address displays.

* #2009 [fixed] - Using AWS S3 for storage

* #2060 [fixed] - auto generate coupon accordion not getting hidden while selecting no specific coupons

* #2141 [fixed] - SQLSTATE[42S02]: Base table or view not found: 1146 Table '[DB_PREFIX].category_translations' doesn't exist

* #2159 [fixed] - Taking more time to load product details in shopping cart.

* #2415 [fixed] - Add TO CART button should replace by “BOOk NOW” button for booking product.

* #2590 [fixed] - hi everyone i'm wondering if customers after register could give a referral code ??

* #2702 [fixed] - Getting broken image for products and category.

* #2720 [fixed] - Error during migration on php artisan migrate

* #2725 [fixed] - Variants should not be created if cofigurable product created failed

* #2762 [fixed] - Seeder: SQL Error: Duplicate entry for '1' key 'PRIMARY'

* #2766 [fixed] - Needs User friendly UI for the event booking in product page

* #2789 [fixed] - Product channel and locale dropdowns in the admin dashboard not working

* #2795 [fixed] - Cart error merging if you authenticate having items with low stock

* #2800 [fixed] - Add the ability to change Attribute Family for Products

* #2804 [fixed] - There should be order review section instead of complete section on checkout page

* #2805 [fixed] - the sku should be all in caps

* #2844 [fixed] - showing product image of each color in configurable product

* #2863 [fixed] - Search Product Name and Description

* #2868 [fixed] - Cart (customer) address not persisted during the checkout

* #2874 [fixed] - Order, payment process and payment metadata

* #2888 [fixed] - Always Default locale should be selected when add new product for each channels

* #2889 [fixed] - timezone drop down field is not visible in web installer

* #2893 [fixed] - When creating a shipment, display items invoiced

* #2931 [fixed] - Customer pays order in PayPal but there is no record in bagisto

* #2936 [fixed] - change the admin route for another

* #2937 [fixed] - Checkout old theme

* #2942 [fixed] - Randomize New and Featured Products

* #2949 [fixed] - failed to migrate with new database using installer

* #2950 [fixed] - multiple error message on installer

* #2964 [fixed] - Exception when buying non stockable item via API

* #2969 [fixed] - Cancel icon is not visible in velocity theme for customer order detail

* #2971 [fixed] - Need to add the possibility to translate velocity metadata

* #2972 [fixed] - can add to homescreen on mobile device in velocity theme

* #2973 [fixed] - force the execution of the shipping methods trigger

* #2974 [fixed] - Thumbnails are not generating on mobile ifproduct has more than 4 photos

* #2981 [fixed] - When paying with Paypal the user can change the amounts of the products

* #2886 [fixed] - Configuration option for Compare

* #2985 [fixed] - Product category is not saving

* #2987 [fixed] - mult address

* #2990 [fixed] - Facebook Pixel integration for Laravel

* #2991 [fixed] - Filters not showing in small devices

* #2994 [fixed] - Shipping method not getting updated after changing the zip code.

* #2995 [fixed] - filter is not showing when search product from search bar

* #2996 [fixed] - Incomplete products JSON when type is grouped or bundled

* #2997 [fixed] - Category show sidebar

* #3000 [fixed] - arabic product in home page Not lined up in one format

* #3001 [fixed] - Getting Error Exception when view order details

* #3004 [fixed] - Category Deactivation not working

* #3005 [fixed] - One page checkout creating new address in profile every time.

* #3009 [fixed] - Featured product slider and new product slider is not working

* #3011 [fixed] - The product is in cart or not

* #3014 [fixed] - Support for Responsive Admin Panel

* #3018 [fixed] - Icons are not showing on imac

* #3020 [fixed] - Images can not add on velocity theme

* #3021 [fixed] - Deactivating the last category of level1 renders only level2 etc. from deactivated category

* #3022 [fixed] - storage/ should not be included in .gitignore

* #3024 [fixed] - Blank order comment shouldn't added

* #3025 [fixed] - save address return error 500

* #3026 [fixed] - Date validation error when editing Booking Products

* #3029 [fixed] - velocity theme not fully responsive

* #3030 [fixed] - Api for coupons..

* #3032 [fixed] - [Critical] Onecheckout preventing to continue to shipping method after selecting address

* #3035 [fixed] - Please update pwa for bagisto

* #3036 [fixed] - Shipping address options not shown

* #3037 [fixed] - error mysql 8.0.20 bagisto v 1.1.2

* #3038 [fixed] - Trying to get property 'code' of non-object

* #3040 [fixed] - Api logout not working..

* #3044 [fixed] - Getting exception when click on view shopping cart if adding group product in cart that contains variants of configurable product.

* #3047 [fixed] - The qty of configurable product is 0 when merging cart

* #3048 [fixed] - "nwidart/laravel-modules": "^3.2", is the wrong version for laravel 6.*

* #3050 [fixed] - Can't override models

* #3051 [fixed] - error while migrate bagisto manually from console command

* #3053 [fixed] - [Velocity] Checkout: Shipping/Billing Address Name, email does not get updated

* #3054 [fixed] - customer is getting exception while cancel order

* #3061 [fixed] - CORS errors

* #3067 [fixed] - PHP Notice: date_default_timezone_set(): Timezone ID 'Asia/JakartaAsia/Kolkata' is invalid

* #3068 [fixed] - Inactive inventory source are get select in channel and products

* #3070 [fixed] - Edit Attribute -> Add Option or Swatch Item Error 404 for Indonesia(id) country code

* #3073 [fixed] - HTML entities are not being decoded when editing attribute options

* #3076 [fixed] - checkout disable when add new shipping address

* #3077 [fixed] - How to change validation messages to spanish not working

* #3079 [fixed] - Tracking Number in My Account

* #3087 [fixed] - after installation, first product registration does not open detailed page, I only opened from the second product

* #3089 [fixed] - not getting price after changing configurable options

* #3090 [fixed] - error mysql8

* #3095 [fixed] - pending orders detail page is blank when viewing in arabic locale

* #3096 [fixed] - error when add product in compare list from the search product page

* #3097 [fixed] - getting console error when remove cart item

* #3113 [fixed] - catalog storefront configuration for per product page is not working

* #3115 [fixed] - minify the velocity.js for gtmetrix

* #3118 [fixed] - Home page doesn't display categories and language bar doesn't work.

* #3120 [fixed] - admin panel multi locale

* #3131 [fixed] - Velocity theme responsiveness issue after changing the language to Arabic RTL

* #3135 [fixed] - How can I cad comment box in checkout form.

* #3136 [fixed] - configurable product variant name gets removed from the catalog list

* #3140 [fixed] - API for more than one locale !

* #3144 [fixed] - error if selecting only one currency

* #3146 [fixed] - how to configure aws smtp server on bagisto

* #3150 [fixed] - Attribute not showing on the creating new configurable product page

* #3153 [fixed] - Free Shipping and Flat Rate Shipping not desable

* #3158 [fixed] - Column not found: 1054 Unknown column 'symbol' in 'field l

* #3160 [fixed] - Disabled products are not removed from bundles

* #3161 [fixed] - Trying to access array offset on value of type null

* #3164 [fixed] - getting exception when add/edit configurable product

* #3171 [fixed] - fixed amount is applied on product for customer group price instead of apply in %

* #3172 [fixed] - description or name is missing for comparable items if customer login

* #3183 [fixed] - ErrorException

* #3184 [fixed] - Site showing blank page on 404

* #3186 [fixed] - replace payment method text with an image on the checkout page

* #3190 [fixed] - Bagisto v1.1.2 velocity responsive theme issue on iPhone and iPads

* #3191 [fixed] - Bagisto v1.1.2 velocity responsive theme issue on iPhone and iPads

* #3197 [fixed] - Call to undefined function str_limit() when view product in velocity theme

* #3199 [fixed] - Getting exception when click on product.

* #3202 [fixed] - Getting exception in creating grouped product.

* #3203 [fixed] - Getting translation issue in price field of downloadable product.

* #3204 [fixed] - Getting exception when changing currency from search page.

* #3205 [fixed] - Able to create the product without selecting required toggles button.

* #3208 [fixed] - Customer group price functionality is not working.

* #3207 [fixed] - Issue in variant product of configurable, only one variant name display at a time and on refreshing it changes.

* #3214 [fixed] - Getting exception on forgot password link.

* #3215 [fixed] - when updating an attribute to 'use_in_flat', bagisto should update the product_flat table with the values of those products

* #3218 [fixed] - virtual product not shipping step..

* #3219 [fixed] - fix the date/time format in booking products

* #3222 [fixed] - UI issue in event ticket booking special price date field

* #3231 [fixed] - "error!options are missing alert" on home page shouldn't be shown

* #3232 [fixed] - homepage is showing 404 error page in both theme

* #3234 [fixed] - UI Issue for cart, wishlist, compare icon number indicator in RTL

* #3236 [fixed] - selected category gets removed from the search in RTL

* #3235 [fixed] - need space b/w sign in & sign up box in mini login window for RTL

* #3237 [fixed] - Options of attribute not display as per its position

* #3238 [fixed] - Trait 'Illuminate\Foundation\Auth\SendsPasswordResetEmails' not found

* #3240 [fixed] - Payment methods in onepage checkout are not visible completely in RTL

* #3241 [fixed] - login fields(email,passwords) are in the center when in RTL

* #3243 [fixed] - Email settings are empty in backend

* #3246 [fixed] - fix icon layout in edit booking product page for RTL

* #3248 [fixed] - fix css for cancel icon on success alert RTL

* #3249 [fixed] - icons are overlapped in comparison page for RTL

* #3250 [fixed] - find product by image in search attempt to an error if app_url isn't define

* #3254 [fixed] - exception on changing locale to Italian

* #3255 [fixed] - Appointment booking slot duration missing in UI for RTL

* #3265 [fixed] - recently view product heading is overlapped in RTL

* #3270 [fixed] - fix icon design on catalog rule when select special price as condition

* #3272 [fixed] - getting exception when booking product type is not same as cart item for same product id

* #3273 [fixed] - fix calendar icon present at dashboard in RTL

* #3274 [fixed] - Installer Blank Page After Migration

* #3286 [fixed] - fix calendar icon css at admin dashboard

* #3289 [fixed] - Main product is not showing in catalog grid if configurable product hasn't been created completely.

* #3297 [fixed] - getting exception when save booking product from edit page

* #3298 [fixed] - Header content category always redirect to 404 error page

* #3301 [fixed] - fix search keys in search bar for analysed keywords in velocity

* #3303 [fixed] - Getting exception on changing locale when customer has opened downloadable products grid from his account

* #3304 [fixed] - Getting incorrect message on delete all from wishlist in case of guest user only

* #3307 [fixed] - Getting exception on changing locale when customer has opened order grid from his account

* #3309 [fixed] - 500 error when loading /search with "term" in query string

* #3310 [fixed] - Editing product title should not change URL if it has already been set

* #3311 [fixed] - Mobile Bug - Filters disappear when no products match filters

* #3313 [fixed] - In cart, in place of only qty, quantity and price both are coming in ar.

* #3314 [fixed] - admin back to sign in link always redirects to same page after admin login

* #3315 [fixed] - compare option should be remove from customer profile options list if the compare is disable

* #3316 [fixed] - layout issues in checkout page while placing order of booking product

* #3317 [fixed] - Pagination layout should be implemented at customer end

* #3319 [fixed] - Issue in validation message while placing order of booking type product if customer did not fill select rent time

* #3320 [fixed] - Getting exception on frontend when opening a category in which brand is selected as filterable attribute.

* #3321 [fixed] - Comapre button should come between wishlist and cart in arabic also.

* #3322 [fixed] - Title and url both are coming same on layered navigation page in velocity theme

* #3323 [fixed] - text written in search page(for invalid search) should be properly aligned

* #3328 [fixed] - velocity logo and shop by category override

* #3330 [fixed] - Layout issue while adding configurable product to cart in velocity theme

* #3331 [fixed] - layout issue while using filter at any grid(admin end) in ar locale

* #3335 [fixed] - New Label is missing in VelocityTheme

* #3338 [fixed] - bundle option should be marked as mandatory if it's required

* #3339 [fixed] - category display mode options are not working

* #3340 [fixed] - mobile view not able to updated currency

* #3341 [fixed] - filter option should be remove from the search page in mobile view

* #3354 [fixed] - error when upload invalid image/file type in search

* #3356 [fixed] - search term removed from the search bar in default theme

* #3357 [fixed] - Image search feature is not available in mobile view default theme

* #3358 [fixed] - New and sale icon lable on product issue in RTL

* #3360 [fixed] - filter and items per page is getting collapsed in ar

* #3363 [fixed] - null value accepted in filter option at customer end

* #3365 [fixed] - On mobile responsive on ios sign up button is missing

* #3369 [fixed] - getting exception when clicking on any social login icon

* #3371 [fixed] - Easy bug: incorrect PL lang file

* #3373 [fixed] - New Error migration Bagisto Install MySQL/MariaDB

* #3374 [fixed] - Social Login Error

* #3377 [fixed] - error when click to twitter social login

* #3379 [fixed] - Getting error on migration command.

* #3380 [fixed] - Layout issues on mobile view in ar locale

* #3381 [fixed] - Customer city name does not allow hyphen

* #3392 [fixed] - Translation key is not added in order settings

* #3393 [fixed] - Getting broken image link for locales in mobile view.

* #3394 [fixed] - Not able to open menu in mobile view having locale arabic.

* #3395 [fixed] - default group should be set for the social login customer

* #3396 [fixed] - getting error when admin view order placed by social customers

* #3399 [fixed] - Remove from wishlist button is displaying as 1 when mouse not hover on product, this occur in all locale except English.

* #3400 [fixed] - fix UI for the compare page in default theme

* #3409 [fixed] - Wrong validation when remove variant(s) of configurable product

* #3411 [fixed] - Sale level is displaying even if special price date has been already expired

* #3417 [fixed] - social login icons in RTL

* #3422 [fixed] - getting exception when view order of deleted customer

* #3423 [fixed] - Cannot change account password

* #3425 [fixed] - Impossible to connect, please check your Algolia Application Id.

* #3443 [fixed] - customer group price functionality is not working

* #3444 [fixed] - Layout issue on review page

* #3445 [fixed] - filter tag is out of box in search key term for long product name

* #3447 [fixed] - By default social login should be enabled

* #3451 [fixed] - Change request regarding shipments

* #3453 [fixed] - Invoice state is always set to paid

* #3457 [fixed] - number indicator are hidden on wishlist or compare icon RTL format

* #3458 [fixed] - not able to add (virtual,booking etc.) product to compare list by logged in user

* #3460 [fixed] - Add address option should come below in velocity theme

* #3465 [fixed] - When I update the folder name from bagisto to new name, the logo image doesn't appear, why?

* #3467 [fixed] - layout issue at shop end in ar

* #3469 [fixed] - Cannot remove a layered navigation attribute from product

* #3472 [fixed] - layout issue in order and downloadable grid at customer end in mobile view

* #3478 [fixed] - Getting exception on putting limit =0 in url

* #3484 [fixed] - missing product quick view icon on search page

* #3485 [fixed] - layout issue on category page for filterable attributes

* #3486 [fixed] - Compare feature shows wrong attribute value

* #3487 [fixed] - Velocity RTL product image zoom floats right - not showing

* #3488 [fixed] - color attribute default swatch value should be selected as dropdown swatch

* #3489 [fixed] - Sale icon is not showing even if catalog rule is applied for grouped and bundle type products

* #3490 [fixed] - Back icon is not working in catalog and cart rule grid

* #3491 [fixed] - broken image for color image swatch type

* #3494 [fixed] - compare feature is not working properly if admin creates a image type attribute and give attribute code and name "image"

* #3495 [fixed] - image is not coming in compare list for any image type attribute

* #3496 [fixed] - showing values in compare list of those attribute which has been removed from attribute family

* #3498 [fixed] - No.of items in compare should be displayed with compare tab in default theme

* #3501 [fixed] - unable to download files while comparing products

* #3502 [fixed] - Side bar menu is getting removed while editing/adding customer address at admin end

* #3505 [fixed] - No message on removing products or deleting all from compare list in default theme

* #3506 [fixed] - Uploaded image is not showing while editing Advertisement 4,3,2 Images for arabic locale

* #3507 [fixed] - locale filter is not working properly in product grid

* #3508 [fixed] - Error on PL lang file

* #3514 [fixed] - Use "has been" in place of "had been" in mail to warehouse

* #3515 [fixed] - Order id is missing in mail which customer get when admin add any comment in order

* #3519 [fixed] - Customer is getting mails in Arabic for order information and rest mails in English for same order which was placed in ar locale.

* #3520 [fixed] - Admin should get mail according to admin's default locale not customer's locale

* #3521 [fixed] - layout issue in order information in default theme at customer end

* #3522 [fixed] - Admin is not getting mail when customer cancels order

* #3526 [fixed] - On changing current password admin/customer should get mail

* #3530 [fixed] - mobile view sortBy functionality in category page is not working

* #3531 [fixed] - In price filter, allow comma(,) for price in filter as in french locale float values used to be separated by , in stead of .

* #3532 [fixed] - (Mobile view) getting product image issue when set grid as List type in category page

* #3533 [fixed] - Products Per Page configuration is not working properly

* #3534 [fixed] - Add validation for products per page field otherwise if admin entered string value then customer gets exception on category page

* #3535 [fixed] - Sort By configuration from admin end is not working properly

* #3546 [fixed] - Shipping charge is not getting calculated properly in case customer removes the product just before clicking on place order

* #3547 [fixed] - checkout country null issue

* #3548 [fixed] - filter is not working properly in attribute grid at admin end

* #3550 [fixed] - there should be tooltip text on mouse hover on compare icon on product

* #3554 [fixed] - Cart Rule Issue

* #3558 [fixed] - Incorrect price showing for configurable product on front end

* #3561 [fixed] - Customer Revenue is not getting minus after refund

* #3562 [fixed] - getting exception when view category page in frontend

* #3564 [fixed] - getting exception if admin uploads higher size image in image swatch while editing/adding attribute

* #3570 [fixed] - Translation issue on uploading high size image

* #3574 [fixed] - One page Checkout loader hits on adding single digit in phone number

* #3575 [fixed] - Inactive child product shouldn't visible in grouped product

* #3577 [fixed] - Customer is able to place order of more than available qty of any bundle option product

* #3580 [fixed] - Incorrect error message while adding bundle product in cart if no.of bundles contains more qty than available qty

* #3591 [fixed] - Getting exception while using layered navigation filters on category page

* #3595 [fixed] - I would like like to change the admin route or url

* #3604 [fixed] - show percentage in place count number in review in velocity theme

* #3606 [fixed] - showing different number of star in velocity and default theme for same rating

* #3611 [fixed] - The content of CMS page is cropped

* #3615 [fixed] - Getting exception while uploading favicon image if image is of high size

* #3617 [fixed] - Add feature to set the category header content limit in velocity

* #3621 [fixed] - Ui issue when applying filter in mobile view

* #3622 [fixed] - channel filter is not working in cart rule

* #3628 [fixed] - Correct the Success message after updating content in velocity meta data.

* #3629 [fixed] - Filter is not working properly in content list grid for content type column

* #3631 [fixed] - Category slug should not accept values in capital letters while adding content for Header content

* #3636 [fixed] - Correct the Ui of profile in mobile view,there is no difference in field name test and field data.

* #3637 [fixed] - No records founds text in downloadable product section of customer should display in centre, in mobile view.

* #3638 [fixed] - Promotion, combine "percentage" & "fixed amount to whole cart" cart rules get wrong discount amount

* #3642 [fixed] - getting exception when creating configurable product in case of DB_Prefix

* #3649 [fixed] - product datagrid filter layout issue

* #3648 [fixed] - custom file type attribute is not visible in PDP

* #3656 [fixed] - Product name gets blank each time we refresh the product page.

* #3657 [fixed] - Auth user can see all users info by id

* #3674 [fixed] - Bugs on category page for list mode

* #3675 [fixed] - Address icon is overlapping on side bar menu in mobile view

* #3676 [fixed] - all cross selling products are not visible in cart page of velocity theme

* #3678 [fixed] - Customer is able to access downloadable products even when invoice state is pending

* #3688 [fixed] - Select icons should be in right side in RTL on payment page in default theme

* #3689 [fixed] - There should not be sale icon in shopping cart in velocity theme

* #3692 [fixed] - Channel filter is not working properly in product grid

* #3693 [fixed] - There should not be any success message while trying to delete system attributes

* #3700 [fixed] - getting exception while creating refund of order placed by deleted customer

* #3702 [fixed] - On deleting customer their invoice and shipment records are getting disappeared.

* #3704 [fixed] - No alert message while deleting customer with pending or processing order from admin end.

* #3717 [fixed] - Layout issue in order grid at customer end

* #3720 [fixed] - Velocity theme option is missing in channel

* #3723 [fixed] - getting exception when download uploaded file from backend

* #3725 [fixed] - getting exception on comparison page of default theme

* #3727 [fixed] - compare page layout issue for logged in user

* #3735 [fixed] - Make Velocity Meta Data section Channel wise.

* #3740 [fixed] - translation of alert when remove compare item in default theme

* #3742 [fixed] - Invoice, ship and cancel buttons are missing in order placed by guest user

* #3745 [fixed] - getting exception in backend when having DB_PREFIX

* #3747 [fixed] - velocity content header route throwing an exception in spanish locale

* #3751 [fixed] - shipping tab is missing in order details for deleted customer

* #3755 [fixed] - Webinstall - SMTP port is not set

* #3757 [fixed] - broken image on guest wishlist

* #3767 [fixed] - Header-nav on mobile view seem buggy on search item



## **v1.1.2 (24th of March 2020)** - *Release*

* [feature] - Now customer can cancel order.

* [feature] - Auto and manual currency exchange rates update feature added.



* #797 [fixed] - Add new module

* #2453 [fixed] - Velocity theme is not loading on fresh instance

* #2691 [fixed] - Shipping and Payment methods automatically selected on Checkout oage

* #2752 [fixed] - Error when you create or update a new catalog under root

* #2793 [fixed] - Stock Check Incorrect for Configurable Items

* #2826 [fixed] - Not able to view cart icon

* #2869 [fixed] - Updating "Velocity meta data" throws QueryException

* #2871 [fixed] - Refund throws "Undefined index: shipping" error

* #2875 [fixed] - Deleting brands that have been assigned to products causes checkout error

* #2884 [fixed] - Undefined Index slot: when add to cart rental booking

* #2890 [fixed] - cart rule condition (price in cart) always set to equal or less than when select greater than/less than

* #2895 [fixed] - The type hint of view in this blade file is 'address' - there is no tag in any provider which loads view with this type hint.

* #2896 [fixed] - There are two fields with having same value of name attribute one is hidden and other is of its desired type - is this redundant code or its solving any purpose?

* #2897 [fixed] - Inventory status field should be passed through validation for boolean in its backend controller.

* #2898 [fixed] - error when viewing a category and then wanting to change the language of the page in mobile view

* #2899 [fixed] - showing the configured products as radio button

* #2900 [fixed] - getting different variant of a configurable product in front end

* #2901 [fixed] - Error when creating a category

* #2908 [fixed] - A class is missing from the Velocity ProductRepositiry file

* #2914 [fixed] - Filter not showing on mobile, also sorting not working on mobile

* #2915 [fixed] - filters are missing on mobile view.

* #2919 [fixed] - Header Content not working on other languages

* #2925 [fixed] - exception for php version 7.4

* #2938 [fixed] - Extend Model Class

* #2939 [fixed] - get product description for API without html tags

* #2940 [fixed] - creating categories have error

* #2943 [fixed] - Scroll images is not working

* #2945 [fixed] - API product detail return empty array

* #2954 [fixed] - The merging cart function does not work when already added all items of product into customer cart


## **v1.1.0 (24th of March 2020)** - *Release*

* #797 [fixed] - Add new module

* #826 [fixed] - Impossible to create the root directory "".

* #2152 [fixed] - Product images are not showing

* #2329 [fixed] - Getting exception on frontend after updating meta data.

* #2354 [fixed] - possible integrate this payment

* #2543 [fixed] - Sliders Text should be translatable

* #2558 [fixed] - Sliders Text should be translatable

* #2619 [fixed] - Issue when category slug & product slug are same

* #2684 [fixed] - API checkout/cart returns null for guest user

* #2691 [fixed] - Shipping and Payment methods automatically selected on Checkout oage

* #2706 [fixed] - Getting exception on editing category for pt_BR locale in php 7.4

* #2708 [fixed] - Able to create booking product from back date.

* #2713 [fixed] - fix the invoice header in pdf

* #2726 [fixed] - is shop.js the vue framework ??

* #2752 [fixed] - Error when you create or update a new catalog under root

* #2763 [fixed] - error to add rental booking into cart

* #2764 [fixed] - fix UI when select back_date of booking product,the calendar icon is set on another place

* #2765 [fixed] - Email settings configuration values are not write in .env file

* #2768 [fixed] - Getting exception in cart when remove one ticket from event booking from backend

* #2769 [fixed] - Can't delete Exchange Rates data

* #2774 [fixed] - How to add new icon in bagisto admin panel?

* #2775 [fixed] - compare icon is missing in each product for default theme

* #2776 [fixed] - compare option in side bar menu at customer panel should be available

* #2778 [fixed] - Issue in customer profile dropdown.

* #2779 [fixed] - Issue on checkout page, email should ask first as in default theme.

* #2780 [fixed] - Sidebar layout issue.

* #2781 [fixed] - Mobile menu is not showing correct sub-menu

* #2784 [fixed] - One booking for many days slot time issue

* #2785 [fixed] - missing address details in checkout page

* #2786 [fixed] - Getting error message on adding product to compare product from search page.

* #2788 [fixed] - guest_checkout is missing from edit product

* #2790 [fixed] - Minicart disable when use new languages only velocity theme

* #2792 [fixed] - Weight Validation Inconsistencies

* #2793 [fixed] - Stock Check Incorrect for Configurable Items

* #2794 [fixed] - When allow backorder is enabled, display a message available for order rather than in stock.

* #2796 [fixed] - Try to create category in windows 10 getting exception

* #2801 [fixed] - Address with more than 2 lines is not added correctly to the cart_address table

* #2807 [fixed] - Illegal mix of collations

* #2808 [fixed] - Correct the spelling on registration page.

* #2810 [fixed] - UI issue on compare similar item page.

* #2811 [fixed] - how to change checkout proccess

* #2812 [fixed] - getting timezone error while setup

* #2813 [fixed] - Ui issue if there is only one product in compare page.

* #2814 [fixed] - variant product's name aren't update when select their options in Front

* #2818 [fixed] - Not able to view menu in velocity theme on storefront

* #2821 [fixed] - Address Line is Null in Emails

* #2825 [fixed] - PHP Notice:

* #2827 [fixed] - default local not changing in storefront in velocity theme

* #2828 [fixed] - currency change error on velocity theme

* #2829 [fixed] - changing home page content in velocity and npm

* #2832 [fixed] - Illegal mix of collations

* #2834 [fixed] - Layout issue in compare page in pt_BR locale

* #2837 [fixed] - subscription bar content source code is not visible in text editor

* #2840 [fixed] - Velocity theme is not available on fresh install

* #2845 [fixed] - Implement custom RegistrationController

* #2846 [fixed] - does not show next step

* #2847 [fixed] - Class 'Faker\Factory' not found

* #2849 [fixed] - Can not add my stylesheet to Velocity theme

* #2850 [fixed] - admin crash on save configration

* #2851 [fixed] - Fix date picker icon layout at dashboard

* #2856 [fixed] - Issue with Sort by functionality, when open any category it by defaults show Newest First but after changing sort by when again select newest first it shows different product.

* #2865 [fixed] - Save order taking so long time 30s

* #2866 [fixed] - ayout issue when customer save addresses form

* #2871 [fixed] - Refund throws "Undefined index: shipping" error

* #2876 [fixed] - Place order is disable at checkout when select shipping address





## **v1.1.0 (24th of March 2020)** - *Release*

* [feature] Added new booking type product.

* [feature] Impletment compare product feature.

* [feature] Impletment compare product feature.

* #2525 [fixed] - Add more settings to the installers

* #2541 [fixed] - Showing product's price with the price including tax

* #2552 [fixed] - error mysql 8

* #2556 [fixed] - Logo and favicon broken

* #2562 [fixed] - error catalog/categories/create

* #2563 [fixed] - error add in cart

* #2567 [fixed] - Error 404 found when click on compare product image

* #2568 [fixed] - Getting exception when update to default theme from the comparison page

* #2572 [fixed] - custom attribute values are not show in comparison product

* #2573 [fixed] - Add to wishlist icon is missing with each product in comparison page

* #2574 [fixed] - Quick view popup should be closed when click add to compare

* #2575 [fixed] - compare feature is not working from the product page for logged In customer

* #2576 [fixed] - Compare icon is missing for new products

* #2577 [fixed] - GUI installer stuck at Migration & Seed

* #2578 [fixed] - Impossible to create the root directory

* #2579 [fixed] - error menu mobile

* #2580 [fixed] - error recently viewed products in mobile

* #2581 [fixed] - admin/configuration/general/design

* #2583 [fixed] - Display 3D product preivew image

* #2584 [fixed] - Not getting root category name, in categories.

* #2585 [fixed] - Product name , description and short description gets removed on editing the product.

* #2586 [fixed] - APP_TIMEZONE and APP_LOCALE values should be available in env file.

* #2587 [fixed] - Getting some warning during installation.

* #2589 [fixed] - Getting exception on editing header content on php 7.4.

* #2596 [fixed] - Allow Email Verification field is given twice, once in Configure->Customers->Setting and in Configure->Admin->Email.Currently if field is enable from any one grid and disable from other grid, then its not working.

* #2597 [fixed] - Not getting email for "Send Inventory Source Notification E-mail".

* #2599 [fixed] - login required when add compare product from the category page

* #2601 [fixed] - all comparable product remove from list only when single product remove

* #2602 [fixed] - Catalog default image height should be equal to the original image in Velocity

* #2604 [fixed] - Not able to make product as comparable from the category page as logged In user

* #2605 [fixed] - Attribute is comparable (yes/no) option is missing when add new attribute

* #2606 [fixed] - custom attributes are not Visible on Product View Page on Front-end

* #2607 [fixed] - Getting exception on editing category for pt_BR locale in php 7.4

* #2608 [fixed] - Getting exception on creating category.

* #2609 [fixed] - product removed from comparison page when update product by name

* #2611 [fixed] - installer error

* #2612 [fixed] - available slots are not showing for current date even if slot time is not expired

* #2613 [fixed] - Propaganistas/Laravel-Intl is abandoned

* #2614 [fixed] - table booking slot time is expired still exist in cart

* #2619 [fixed] - Issue when category slug & product slug are same

* #2621 [fixed] - i create a site and it is up kind of noting works

* #2626 [fixed] - Tax rates zipcode is still required when enable zip range is disabled

* #2630 [fixed] - Error exception when add booking product

* #2634 [fixed] - console error when select slots in default booking

* #2635 [fixed] - Default Booking details remove from edit page for many booking of one day

* #2636 [fixed] - Error alert when add to cart a simple product from the home page

* #2638 [fixed] - customer status is not translated in customer list

* #2639 [fixed] - category slug field should show warning if saved blank header content

* #2643 [fixed] - Getting exception when add appointment booking

* #2645 [fixed] - Error on adding product to cart

* #2649 [fixed] - Incorrect slot time for one booking many days in product page

* #2650 [fixed] - remove slot duration from the booking product page

* #2654 [fixed] - warning should be removed once slot field is selected

* #2658 [fixed] - slot, duration, break time are not saved for appointment booking

* #2660 [fixed] - guest capacity value is not saved in table booking

* #2661 [fixed] - Charged_per drop down value is not updating for table booking

* #2284 [fixed] - Layout issue in pt_BR locale.

* #2468 [fixed] - Guest user is able to checkout if guest checkout is disabled.

* #2517 [fixed] - Product description text gets selected if click on drop down icon on product page

* #2549 [fixed] - Invoices aren't legally valid.

* #2571 [fixed] - compare icon should classify the total compare product added in the comparison page

* #2592 [fixed] - No menu for the logged in user when clicking over comparison

* #2593 [fixed] - Cannot read property 'disabled' of undefined" on filter price

* #2594 [fixed] - After refund quantity of product increases.

* #2595 [fixed] - Category image size issue in velocity theme.

* #2610 [fixed] - some of the attribute values aren't visible in comparison page

* #2616 [fixed] - Tiny Bug on Admin Pages

* #2637 [fixed] - blank admin page if username contains whitespaces in email configuration

* #2640 [fixed] - product moved to cart still showing in wishlist

* #2641 [fixed] - Issue on wishlist page for guest user

* #2644 [fixed] - Add an option to set encryption to none during installation

* #2646 [fixed] - error missing wishlist or compare icon on mobile view

* #2666 [fixed] - fix the UI for booking product in cart page

* #2667 [fixed] - By default wishlist option is selected in cart

* #2670 [fixed] - Booking product should be removed from the cart when selected slot time expired

* #2669 [fixed] - Browser compatibility issue

* #2671 [fixed] - Error on moving booking product to wishlist

* #2672 [fixed] - wrong price calculated in cart for rental booking

* #2674 [fixed] - Rental booking added to cart without selecting date in velocity

* #2677 [fixed] - error on cart when rental booking update from backend

* #2678 [fixed] - UI issue in rental booking product page

* #2693 [fixed] - Booking product page - add to cart button js error

* #2704 [fixed] - product's assigned category can't be removed

* #2707 [fixed] - Getting exception when generate invoice in appointment booking

* #2715 [fixed] - Error message should throw if "To" time is less than "From".

* #2716 [fixed] - After saving the default booking time product selected time for date range changes to 00:00:00 ,because of which not able to book appointment on frontend.

* #2717 [fixed] - Getting error message on adding rental product in cart if rental booking is not available for that day.

* #2722 [fixed] - warning showing when update event booking cart quantity from the product page

* #2723 [fixed] - Compare product icon on header showing counts of compare product but there are no product in compare list.

* #2724 [fixed] - table bookings quantity should update in existing booking added in cart for same slot/date

* #2726 [fixed] - is shop.js the vue framework ??

* #2732 [fixed] - missing product's quick view in category page


## **v1.0.0 (24th of February 2020)** - *Release*

* #2377 [fixed] - Getting exception on creating a new category under any other category.

* #2378 [fixed] - Exception when adding velocity content page list.

* #2381 [fixed] - Fix UI for linked product (related/upsell/cross sell).

* #2382 [fixed] - If customer use shipping address other than billing address then also its showing the billing address in shipping address section.

* #2384 [fixed] - Vat id validation rule was changed, since then test action has failed.

* #2386 [fixed] - bundle product details in cart page should contains item details.

* #2388 [fixed] - order placed with blank billing address.

* #2390 [fixed] - Add hyphen in Orders->Information section of customer.

* #2391 [fixed] - toogle footer configuration is always true in velocity.

* #2393 [fixed] - Getting exception on adding grouped product to cart.

* #2395 [fixed] - Can not add grouped product in cart more than one time, getting error message.

* #2397 [fixed] - Company Name field is not available in Billing Address form in velocity theme.

* #2398 [fixed] - Mark all mandatory field in customer's billing address form.

* #2399 [fixed] - Layout issue in bundle product.

* #2400 [fixed] - Whole product price should be in bold in bundle product.

* #2403 [fixed] - Ratings icons show in category product view list for 0 rating.

* #2410 [fixed] - error button update cart.

* #2417 [fixed] - Inactive payment method or shipping method are showing in velocity footer content.

* #2424 [fixed] - Exception on frontend when default currency is not selected in currencies.

* #2436 [fixed] - error velocity/meta-data.

* #2438 [fixed] - Add hyphen - with cart discount amount.

* #2439 [fixed] - can't process for further checkout steps until all the address line filled.

* #2435 [fixed] -  error composer install --no-dev.

* #2440 [fixed] - Advertisement Three Images is not working.

* #2449 [fixed] - error clicking empty cart.

* #2451 [fixed] - Invoice totals don't tally when using non-base currency.

* #2458 [fixed] - Payment method is not updating on checkout page.

* #2459 [fixed] - shipping address field warning for guest customer not translated.

* #2463 [fixed] - Tax rate is not update on same product.

* #2468 [fixed] - Guest user is able to checkout if guest checkout is disabled.

* #2469 [fixed] - Displaying wrong amount for bundle product in cart.

* #2479 [fixed] - showing total review in recent view product list.

* #2480 [fixed] - Exception is thrown by mini cart when catalog rule is applied on configurable product.

* #2488 [fixed] - ErrorException When Editing product in different language.

* #2490 [fixed] - missing zip code & country field in checkout page.

* #2491 [fixed] - Exception on Create/Edit bundle product.

* #2494 [fixed] - Product total inventory for all locale is showing wrong.

* #2500 [fixed] - Database reset fails.

* #2519 [fixed] - filter price attribute throwing an exception.

* #2526 [fixed] - Velocity backend route is not accessible in arabic locale.

* #2527 [fixed] - Order datagrid is using static text.

* #2529 [fixed] - [Webkul\Admin] Customer firstname & lastname are using wrong translations

* #2533 [fixed] - Shipment email notification is not sending to customer.

* #2538 [fixed] - unable to place order for virtual & downloadable product.

* #2540 [fixed] - add to cart and whitelist button overlap.

## **v1.0.0-BETA1(5th of February 2020)** - *Release*

* [feature] Updated to laravel version 6.

* [feature] Added four new product types - Group, Bundle, Downloadable and Virtual.

* [feature] Provided new theme (Velocity).

* #1971 [fixed] - Filter is not working properly for id column in autogenerated coupon codes in cart rule.

* #1976 [fixed] - Default attribute set should be selected in root category.

* #1977 [fixed] - On editing the product, selected category for that product is not checked.

* #1978 [fixed] - Getting exception if changing the locale from cart page, if translation is not written for that product.

* #1979 [fixed] - Wrong calculation at customer as well as at admin end in due amount and grandtotal.

* #1980 [fixed] - UI issue in cart on changing locale.

* #1983 [fixed] - Getting exception on deleting admin logo.

* #1986 [fixed] - Subscribe to newsletter does not work.

* #1987 [fixed] - MySQL query very slow if products in category is around 3000

* #1988 [fixed] - Country and City Names in Create Address is not coming based on Locale

* #1994 [fixed] - Tax rate should only depend on zip code, state field should not be mandatory.

* #1997 [fixed] - Getting exception on adding attribute or creating product in bagisto on php version 7.4 .

* #1998 [fixed] - Showing product sale amount as zero when creating a product, and a existing catalog rule apply on it.

* #2001 [fixed] - php artisan route:list throws error.

* #2012 [fixed] - Getting exception when clicking on view all under review section at product page.

* #2033 [fixed] - API route for products throws error

* #2045 [fixed] - Login option is not coming while checkout with existing customer mail id.

* #2051 [fixed] - Forgot password not working due to recent changes in mail keys.

* #2054 [fixed] -Automatically 1st item of bundle is getting selected as a default after saving product.

* #2058 [fixed] - Not getting any validation message if entered admin credentials are wrong.

* #2066 [fixed] - Exception while writing product review.

* #2071 [fixed] - Customer is not getting forget password email.

* #2074 [fixed] - Getting exception while creating bundle type product.

* #2075 [fixed] - Getting exception if trying to select any parent category of root.

* #2087 [fixed] - Getting exception while adding configurable/bundle/grouped/Downloadable Type product to cart.

* #2088 [fixed] - Getting exception on customer login.

* #2089 [fixed] - Info missing on printing invoice at customer and admin end.

* #2114 [fixed] - getting exception while recovering admin password in case admin did not enter the details in env.

* #2118 [fixed] - Installation issue, getting exception on migrate command.

* #2119 [fixed] - confirm password is not matching even if admin is entering similar password in password and confirm password.

* #2120 [fixed] - Not able to add new user as while creating user password its giving error confirm password doesn't match.

* #2124 [fixed] - Able to make all product as default while creating bundle product in select type option.

* #2128 [fixed] - Click on add attribute, error is thrown.

* #2132 [fixed] - Price range slider not displaying.

* #2139 [fixed] - Logic error in exchange rate calculation

* #2143 [fixed] - Attributes filterable checkbox - those who do not know will think that a bug!

* #2145 [fixed] - Emails don't work on registration.

* #2146 [fixed] - Getting exception on creating bundle product without any option.

* #2147 [fixed] - Sort order of bundle product doesn't work.

* #2149 [fixed] - Ui issue when installing through installer.Getting issue on all steps.

* #2162 [fixed] - product's special price should not greater than price

* #2164 [fixed] - Redirect to incorrect url when click on finish button after installing through installer.

* #2165 [fixed] - Incorrect error message for password field of email configuration.

* #2167 [fixed] - Translation issue in Payment description field.

* #2168 [fixed] - locale direction drop down always select ltr.

* #2173 [fixed] - While creating locales value in direction dropdown is in small letters, but when you edit any locale it display in caps.

* #2176 [fixed] - product price section is not getting highlighted if the warning exists

* #2177 [fixed] - Category image can be add from anywhere

* #2181 [fixed] - Getting exception when creating/editing customer address from Admin end.

* #2182 [fixed] - missing option in Customer's Gender at admin end

* #2183 [fixed] - Add toolkit for add address.

* #2185 [fixed] - Issue with configurable product in case of multi-locale. Variation option are not visible.

* #2186 [fixed] - Ui issue in cart for pt_BR locale. Quantity is not visible properly.

* #2190 [fixed] - sku should be shown in product list if new product created

* #2192 [fixed] - For all grid of sales section when you export data in csv file order id heading is mentioned as increment id.

* #2196 [fixed] - No data is visible in state field, issue exist at all section where state field is used.

* #2198 [fixed] - Remove vat id column from customer address list

* #2202 [fixed] - catalog rule is not applied on product if product's special price date expired

* #2203 [fixed] - saved categories are not checked in condition of catalog/cart rule

* #2204 [fixed] - category tree view doesn't visible in catalog rule condition

* #2207 [fixed] - Unable to delete Category.

* #2226 [fixed] - Wrong price of product in case of multiple exchange rates.

* #2225  [fixed] - Not able to export products according to locale.

* #2227 [fixed] - Grand total column is not visible in invoice pdf, also getting incorrect currency symbol for grand total.

* #2237 [fixed] - Error when trying to login with app.php locale set to ja

* #2239 [fixed] - User should not be able to create multiple channel with same hostname.

* #2241 [fixed] - Getting exception when save a category with category logo

* #2242 [fixed] - Velocity Header content status should be enabled by default

* #2243 [fixed] - Remove Image's Height [in Pixel], Image's Width [in Pixel] ,Image Alignment and Number of Subcategory from Configure->velocity theme.

* #2245 [fixed] - slider content is not showing in velocity theme

* #2246 [fixed] - slider disable functionality is not working

* #2248 [fixed] - Provide an option to remove filter in velocity theme.

* #2251 [fixed] - configurable product options could not get select

* #2252 [fixed] - Customer is not able to save his/her address in velocity theme.

* #2253 [fixed] - Customer is not able to update his/her profile in velocity theme.

* #2254 [fixed] - Fix layout for remove button in cart page for guest customer

* #2255 [fixed] - Theme page search bar passing string value

* #2256 [fixed] - close previous popup if clicks on another item

* #2257 [fixed] - User profile drop down option should be highlighted on mouse hover

* #2258 [fixed] - Need space between highlighted text.

* #2259 [fixed] - Not getting category image on category page in velocity theme.

* #2260 [fixed] - Not getting the header content.

* #2261 [fixed] - Not getting option to delete review if customer has reviewed only single product.

* #2262 [fixed] - Issue with multiple images of same product

* #2263 [fixed] - Fix issue at review page

* #2264 [fixed] - Getting internal server error when place an order

* #2265 [fixed] - Migrate issues with Velocity

* #2271 [fixed] - When clicking on dropdown icon of all categories, category list didn't get open.

* #2273 [fixed] - Not getting password reset email for velocity theme

* #2274 [fixed] - Not able to proceed for checkout after checkout/onepage in case of downloadable product.

* #2275 [fixed] - Not able to place order for virtual product.

* #2279 [fixed] - Sort By functionality is not working in velocity theme.

* #2280 [fixed] - Getting small checkbox on refreshing the product page.

* #2281 [fixed] - In minicart, whole minicart container is showing clickable but only image section gets clicked to redirect to product page.

* #2282 [fixed] - Not getting any validation error message if current date of birth is selected in customer profile.

* #2285 [fixed] - Layout issue if category length is large.

* #2288 [fixed] - Getting exception on mass delete of review from admin end.

* #2293 [fixed] - On mouse hover, remove filter should be display as clickable.

* #2295 [fixed] - Admin not able to add address for customer if he add data in Vat id field.

* #2297 [fixed] - Always showing 0 review for product in recently viewed product even if multiple reviews are given to that product.

* #2299 [fixed] - Vat Id field is not given is customer address form.

* #2300 [fixed] - Alignment issue on Billing Information page if user enter an email that already exist.

* #2317 [fixed] - Multiple pop-up opens at a time if product is added in cart and customer click on Welcome Guest to sign-up or login.

* #2318 [fixed] - Slider content is not showing properly on slider in velocity theme.

* #2319 [fixed] - UI issue when customer redirect to reset password page through received email.

* #2320 [fixed] - UI issue in sort by functionality.

* #2325 [fixed] - Left arrow should be out of the image area.

* #2328 [fixed] - Only first three viewed product display in recently viewed section, when customer view 4th product it doesn't get updated.

* #2330 [fixed] - different route found for customer profile edit page for velocity theme

* #2335 [fixed] - Success alert should be shown while adding product into cart

* #2336 [fixed] - Issue with multi level category.

* #2337 [fixed] - Not getting category logo for 3rd level category.

* #2339 [fixed] - Selected content type is not showing in Content Pages List

* #2340 [fixed] - Correct the success message on deleting content.

* #2341 [fixed] - wish listed items should be labeled by move to cart instead of add to cart if product already added in cart

* #2346 [fixed] - Exception when search product based on selected category from search bar

* #2355 [fixed] - UI issue when update product policy

* #2357 [fixed] - Broken image link for locale logo.

* #2362 [fixed] - Page Link Target of header content always save with self option.

* #2366 [fixed] - Not able to add logo for category, after saving the category logo gets removed.

* #2371 [fixed] - Getting exception on updating Category.