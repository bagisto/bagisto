# CHANGELOG for v1.x.x

#### This changelog consists the bug & security fixes and new features being included in the releases listed below.

## **v1.1.0 (20th of March 2020)** - *Release*

* [feature] Added new booking type product.

* [feature] Impletment compare product feature.

* [feature] Impletment compare product feature.

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

* #2608 [fixed] - Getting exception on creating category.

* #2609 [fixed] - product removed from comparison page when update product by name

* #2611 [fixed] - installer error

* #2613 [fixed] - Propaganistas/Laravel-Intl is abandoned

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

* #2669 [fixed] - Booking product should be removed from the cart when selected slot time expired

* #2671 [fixed] - Error on moving booking product to wishlist

* #2672 [fixed] - wrong price calculated in cart for rental booking

* #2674 [fixed] - Rental booking added to cart without selecting date in velocity

* #2677 [fixed] - error on cart when rental booking update from backend

* #2678 [fixed] - UI issue in rental booking product page

* #2693 [fixed] - Booking product page - add to cart button js error


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