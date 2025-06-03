# CHANGELOG for v2.2.x

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

## Unreleased

* Fixed an issue in the installer where old values were being retained, causing the first attempt to fail. The installation now completes successfully on the first attempt.

* Added missing event dispatches to ensure proper event flow and handling.

* #10802 [fixed] - Removed default address handling from the repository and moved it to the controllers to prevent side effects across customer addresses in the admin panel, shop front, and checkout pages.

## **v2.2.8 (29th of May 2025)** - *Release*

* Resolved the filter issue for date and datetime types in the DataGrid.

* Fixed the date translation issue that was coming from the Carbon instance.

* Resolved the "break-all" issue on the product view page.

* Fixed the issue with the multiselect filter that was preventing products from being correctly filtered on the category page.

* Fixed the issue where the transaction drawer was not opening as expected.

* Fixed the product DataGrid search issue in Elasticsearch mode when no IDs are present.

* Fixed the multiselect filter and also added support for checkbox filters in both Elasticsearch and database modes.

* #10788 [fixed] - Added a slight delay of 300ms along with a debounce mechanism to ensure that the dash is removed only after the user stops typing.

* #10718 [fixed] - Fixed the issue that allowed an admin to delete their own account.

## **v2.2.7 (22nd of May 2025)** - *Release*

* Resolved Full Page Cache (FPC) issues affecting category and search result pages.

* Fixed persistent toolbar state issues on the category page.

## **v2.2.6 (14th of May 2025)** - *Release*

* #10612 [feature] - Added confirmation before copying a product.

* #10535 [enhancement] - Added missing 'Overdue' state to the Invoice DataGrid.

* #10671 [enhancement] - Added spacing between shipments.

* #10670 [enhancement] - Added spacing between invoices.

* #10716 [fixed] - Hide 'Cancel' button in Admin Sales → Orders when the invoice is generated.

* #10721 [fixed] - Customer's phone number is not validated on the backend.

* #10722 [fixed] - Postcode backend validation message is not in proper format.

## **v2.2.5 (28th of March 2025)** - *Release*

* Grouped product and bundle product exception fixings.

* Admin panel refinements.

* #10645 [fixed] - ThemeDataGrid Query optimization

* #10641 [fixed] - Last Product's Quantity Becomes Blank After Deleting a Product from Grouped Product

* #10610 [fixed] - Customize Error Page to Replace the Default Index Page

* #10604 [fixed] - Date filter in shop front for logged in customer is not working properly with System Dark Theme.

* #10501 [fixed] - Drop down menu showing on mobile screens or reducing the browser window when no drop down available

* #10566 [fixed] - Wishlist Price Display Issue with Currency Exchange in Bagisto

## **v2.2.4 (14th of February 2025)** - *Release*

* #10440 [fixed] - Upload File Section Appears Empty in Edit Mode of Data Transfer.

* #10443 [fixed] - Inappropriate Validation Message for Invalid Contact Number in Customer XLS File during Data Transfer.

* #10444 [fixed] - Unable to Add or Update Contact Number in Customer via XLS or CSV File Upload in Data Transfer.

* #10445 [fixed] - Proper Date of Birth Format Validation Required in Data Transfer.

* #10451 [fixed] - Guest Checkout value entry should be added in Sample CSV.

* #10471 [fixed] - The customer is receiving two invoice emails after an invoice is created.

* #10472 [fixed] - Attribute Configuration Checkbox Saving Issue

* #10475 [fixed] - Bug Report: Shipping and Handling Price Incorrect in Admin Sales Section.

* #10482 [fixed] - Restrict Attribute Family Code Non-Editable Fields from Backend.

* #10486 [fixed] - UK Postcode.

* #10495 [fixed] - Unable to Make Campaign Inactive in Admin End.

* #10497 [fixed] - Captcha v2 not working.

* #10512 [fixed] - Getting Exceptional Error when saving Shipping Settings details in configurations.

* #10513 [fixed] - Getting Console Errors when updating Grouped product added product QTY in shop front.

* #10515 [fixed] - options table in attribute has wrong alignment.

* #10517 [fixed] - Product attribute label is not translate base on Locale or .env.

* #10518 [fixed] - Attribute options are in the wrong column.

* #10519 [fixed] - Sort and limit is not set on New Products them.

* #10380 [fixed] - Product QTY is not updating when QTY is updated from the Main Cart and Mini Cart in the Additional column of the cart_items table.

* #10384 [fixed] - PayPal Standard is not working Showing return to merchant and also in console we can see the error.

* #10386 [fixed] - A UI issue occurs in the Creating Invoice Drawer when the product name is too long, causing layout distortion or overflow.

* #10387 [fixed] - UI can be improved in the Transaction detail drawer in Admin end and also First Letter of the Status must be Capital.

* #10388 [fixed] - We can save a custom role without selecting any checkboxes for the custom options.

* #10393 [fixed] - If there are more entries in the Filter's Drop-down, we can add a scroller.

* #10407 [fixed] - The information in the 'Content' section of General Configuration does not align with the fields provided for customization.

* #10424 [fixed] - The United Nations (UN) is not a country. It is an international organization founded in 1945 and is composed of member states.

* #10425 [fixed] - Incorrect Warning Message When Deleting Tax Category with Assigned Tax Rates.

* #10426 [fixed] - Default List Mode Setting for Store Front: List View Not Working, Console Errors Appear.

* #10432 [fixed] - Variant or Linked Product Details Missing in Invoices and Shipments for Logged-In Users on Shop Front.

* #10433 [fixed] - Ensure Modal Responsiveness for Large Content.

* #10434 [fixed] - Exceptional Error Appears When Uploading Edited Error File in Data Transfer.

* #10331 [fixed] - Position field in add and edit category pages should only take integer values.

* #10335 [fixed] - DataGrid filter is not working.

* #10341 [fixed] - Getting SQLSTATE[23000] in error msg while mass deletion of few attributes like size, color, weight.

* #10347 [fixed] - Modal not showing upside data or button if the height increased.

* #10349 [fixed] - Need to Add Proper Validation for the Currency Symbol, Decimal, Group Separator and Decimal Separator.

* #10350 [fixed] - We should not be able to edit the currency code in Admin End Settings.

* #10364 [fixed] - Adding dropdown details in a select-type input and then saving it back results in a 404 error.

* #10366 [fixed] - Admin End -> Lines in a Street Address is accepting Decimal Value.

* #10367 [fixed] - The Contact Number section in the Shipping Settings Address needs proper validation.

* #10368 [fixed] - In the Admin Flat Rate Settings, any value can be entered in the Flat Rate Price field, resulting in console errors on the Checkout page.

* #10369 [fixed] - There should be a limit in Order Number Length in Order Settings as we are facing warning message in Checkout Page.

* #10370 [fixed] - We need to add proper validation in the Invoice Settings configuration in Admin End Configurations.

* #10372 [fixed] - Mini Cart Offer Information must have character validation and the content when increased we are unable to see the products added to Mini Cart.

* #10373 [fixed] - Need to add some sort of Limit to the Offer Title and Redirection Title for the Shop Front Header Settings.

* #10375 [fixed] - language file mess up.

* #10377 [fixed] - Tax Rates when created the value of Tax Rate should be in between 0 to 100%.

* #10265 [fixed] - When creating a customer from the admin end, there should be an option to display the Customer ID.

* #10267 [fixed] - Add Scroller in Currency and Locale drop-down for desktop view.

* #10283 [fixed] - Add to cart and Buy now button.

* #10294 [fixed] - Using Image Component twice in Customer Profile Edit page, Image upload issue is appearing.

* #10295 [fixed] - We can save identical slugs for both categories and products. As a result, when I try to view the product page, the category page opens instead.

* #10296 [fixed] - Compare Product Scroller is not appearing.

* #10297 [fixed] - Add Note In the Sales and Customer sections, a character limit should be set. When data is entered without spaces, the page layout becomes distorted.

* #10299 [fixed] - Long Name in Product Name is creating issue in shop front.

* #10307 [fixed] - Add review from the customer end with at least 2 images and remove 1 Image save. In Admin we can see the removed image also.

* #10308 [fixed] - In a configurable product, when editing a variant, the inventory box should have a default entry of zero.

* #10309 [fixed] - Add validation to configurable products to prevent saving if no data is entered for the variant.

* #10310 [fixed] - In the configurable product variant edit, the price appears as NaN if variant details are filled in the order of inventory, weight, and then price. #10310

* #10315 [fixed] - The Header and Footer should Enabled on Checkout Success page. 

* #10323 [fixed] - The product page and admin datagrid should handle long product names effectively.

* #10325 [fixed] - On the bundle product page, if any options contain a special price, then it should show.

* #10330 [fixed] - Categories section not showing in the product edit page when all attributes are moved to the Main column

* #10206 [fixed] - We are able to add Configurable, Grouped, Configurable and Virtual Product in Bundle Product.

* #10207 [fixed] - We are able to add Configurable, Grouped, Configurable and Virtual Product in Grouped Product.

* #10208 [fixed] - Missing theme_code in Theme Customization Retrieval For Shop Theme #10208

* #10209 [fixed] - d without marketing time and saved, after edit we can see the format issue and also we are unable to save it without marketing dates.

* #10212 [fixed] - Contact Us page -> Phone Number Integer validation issue is appearing.

* #10217 [fixed] - Getting Console Error when we add symbols On Search Bar on Shop Front.

* #10228 [fixed] - Create a new Inventory Source -> Status must be off and save. We can see the Inventory Source Still Active.

* #10229 [fixed] - In configurable product variants with multiple inventories, a default value of zero must be filled; otherwise, a validation error appears.

* #10232 [fixed] - PayPal Smart Button order is working without Credentials In Admin end Reorder and Create Order.

* #10234 [fixed] - An exception error occurs when attempting to refund a quantity greater than the invoiced and shipped quantity.

* #10236 [fixed] - Admin Dark Theme Static Content UI issue.

* #10238 [fixed] - Unable to add a Channel 2 product to the cart when logged in to the admin with Channel 1.

* #10240 [fixed] - ACL: A custom role assigned without dashboard access still logs in with the dashboard by default.

* #10242 [fixed] - The 'Add Note' feature for Customer, Order, etc., is not displaying the notification UI correctly in Dark Theme.

* #10248 [fixed] - Add support for Multiselect field type in system configuration #10248

* #10249 [fixed] - Custom Application Logo Not Displayed on Error Page #10249

* #10255 [fixed] - Need to update the warning message for Notification Marked as Read.

* #10256 [fixed] - Dropdown Arrow in RTL view Mass Selection is having minor UI issue.

* #10262 [fixed] - While creating a configurable product from the edit product page, the size and color options appear as "undefined," causing the variant to be invisible on the storefront.

* #10263 [fixed] - The status filters need to be updated to display "Active/Inactive" instead of "True/False" for consistency with the datagrid.

* #10204 [fixed] - Carousel arrows must not appear when there are a limited number of products in Related, Up-Sell, and Cross-Sell products.

* #10199 [fixed] - In the GUI installation, a password validation issue occurs when we enter only three characters, but it still gets saved.

* #10196 [fixed] - In the Attribute, when we create a TextArea type and add a default value, we get a validation error upon saving, but the validation message does not appear.

* #10193 [fixed] - Unassigned Attributes and Right Side Column Not Displaying After Moving Groups to Main Column in Attribute Families.

* #10191 [fixed] - Catalog Rule -> Add Conditions -> One of the details is having translation missing.

* #10189 [fixed] - Cart Rule Categories condition is having issue they are not appearing and also console errors are appearing.

* #10188 [fixed] - Click on Subscribe to Newsletter text on sign-up page we can see console error.

* #10179 [fixed] - while installing admin password validation issue.

* #10173 [fixed] - Minimum Order Amount is not working for discounts applied by Cart Rules.

* #10171 [fixed] - Meta Title is being used as a URL instead of a Slug.

* #10169 [fixed] - Getting console errors, and images are not loading in the mobile view when switching configurable product variants.

* #10168 [fixed] - Censoring product reviewer name.

* #10166 [fixed] - The wishlist quantity issue is occurring with the product. When the quantity of a product in the wishlist is increased and then added to the cart, the quantity of other products in the wishlist is also increased.

* #10163 [fixed] - In Product Review Icon, there the text must appear add image and video.

* #10162 [fixed] - In CMS Pages when we enter incorrect Slug, it should redirect to 404 Page.

* #10161 [fixed] - In Home Page Product Carousal, View All Button is redirect to Home Page.

* #10147 [fixed] - Admin Credentials Prompts Missing During Bagisto Installation via Artisan Command.

* #10144 [fixed] - Configurable product with and the variant has a promotion problem.

## **v2.2.3 (27th of September 2024)** - *Release*

* #10033 [Enhancement] Loader must be added when we create any product, sitemap etc, in the submit button. So that we cannot click them. Single Entry must pass.

* #10072 [Enhancement] Add Footer Text to Invoice PDF.

* #10022 [Fixed] [CMS MODULE] Admin Not Working on Clean Install.

* #10030 [Fixed] A UI issue appears when two modals overlap each other. This occurs when the AI Image Generator is enabled.

* #10080 [Fixed] not work after installation if password contains character #.

* #10082 [Fixed] Getting ? Symbol rather than ₹ (Indian rupee currency in invoice).

* #10094 [Fixed] Remove the state mandatory in the tax rates configuration.

* #10096 [Fixed] In the condition of the cart rule and catalog rule section, float value is not accepted for weight, height, etc.

* #10103 [Fixed] Issue with Language and Channel Mix-up in Admin Product Form.

* #10128 [Fixed] If Automatically generate the invoice after placing status field is disabled then Set the invoice status after creating the invoice to field should be disable on the payment method configuration.

## **v2.2.2 (11th of July 2024)** - *Release*

* Fixed installer issue.

* #9972 [improvements] - The new reviews display system does not make sense

* #9963 [fixed] - When creating a product, getting console error in Number Validation when attribute created. 

* #9971 [fixed] - Images are not changing in mobile view in configurable product according to the Color swatch what is selected. 

* #9976 [fixed] - Added missing CSS classes for dark themes.

* #9980 [fixed] - Fix the translation issue on the error page.

* #9970 [fixed] - Fixed CMS page email send status issue on Update. 

* #9987 [fixed] - Fixed data grid issue save filter button issue.

* #9988 [fixed] - Fixed cart page exception. 

* #9989 [fixed] - Fixed buy-now button issue.

* #9990 [fixed] - Dark Mode UI issue is appearing in Import stock, Import Section where validate is processed. 

* #9990 [fixed] - Fix notification icons for dark theme.


## **v2.2.1 (24th of June 2024)** - *Rele1ase*

* #9955 [fixed] - Cart Rule -> Category is not being retained even after we have selected and saved it.

* #9960 [fixed] - Disabled filterable and add category_id filter to product carousel.

* #9948 [fixed] - few fixes in CLI Installer.

* #9958 [fixed] - Update summary.blade.php.

* #9957 [fixed] - Prevent Template Injection.

* #9950 [fixed] - Fixed multiselect issue.

* #9944 [fixed] - 🐛 Fixed Cart page issue.

* #9945 [fixed] - Updated customers orders view render events.

* #9946 [fixed] - Improve timezone selection during cli install.


## **v2.2.0 (19th of June 2024)** - *Release*

### Datagrid Improvements

1. **Custom Filters:**

* Introduced the feature to save and update custom filters, allowing users to personalize and streamline their search and browsing experience.

2. **Aggregate support**

* Added aggregate functions to the datagrid, allowing users to perform calculations such as sum, average, and count directly within the grid for enhanced data analysis.

3. **Multiple Select and Single Select**

* Introduced the feature to support both single select and multiple select in datagrid filters, providing users with flexible and efficient filtering options.

4. **Column Visibility Control**

* Enhanced datagrid functionality to allow backend control over column visibility. Columns are now visible by default, but can be disabled from visibility with `'visibility' => false.` This allows users to hide columns while retaining the ability to filter data associated with those columns.

5. **Gave single date and datetime support**

* Added support for filtering data by date and date-time, enhancing data analysis capabilities.

6. **Elasticsearch Support for Datagrids**

* Implemented Elasticsearch support for datagrids, enabling faster and more efficient data retrieval and analysis.

### Inclusive and Exclusive Tax Options

* Added the feature to support both inclusive and exclusive tax settings. Users can now choose whether taxes are included in or added to the product price, providing flexibility to accommodate different tax regulations and pricing strategies.

### Estimate Shipping Charges

* Implemented a feature to estimate shipping charges for orders, providing users with upfront cost estimates during the checkout process.

### Order and Reorder Functionality

* Enabled administrators to order and reorder items directly from the admin interface, enhancing management efficiency. 

* Implemented the ability for customers to reorder products directly from their account, streamlining the purchasing process and enhancing user convenience.

### Enhanced Theme Customization

* The theme_customizations table now supports customizations for multiple themes and multiple channels. This enhancement allows you to define different customizations for each theme and channel combination, providing greater flexibility and control over your application's appearance.

* Enhanced the theme customization section by adding a feature to filter attributes.

### Redesigned Invoice Layout:

* Updated the design of the invoice for a more modern and user-friendly appearance.

* Added support for Right-to-Left (RTL) languages, ensuring compatibility and usability for languages that are written from right to left.

* Added support for multiple locales in the invoice, allowing invoices to be generated and displayed in different languages and formats based on user preferences.

### Product Card UI Enhancements

* Upgraded the product cart layout to enhance user experience with a more intuitive and visually appealing design.

### Frontend Fixes and Redesigns for Mobile devices

* Improved mobile view for all front-facing elements, including the review section, footer, product view, and customer account section.

### Added Contact Us Feature

* Implemented a feature allowing users to easily contact the store from the frontend.

### Custom Currency Options

* Enhanced currency creation with additional customization options. Added support for specifying Group Separator, Decimal Separator, and Currency Position during currency setup.

### Media zooming for product and review images 

* Added the feature to zoom in on media for both reviews and products, improving the viewing experience and providing better detail.

### Implemented new features in installer package.

1. **All Currency Support**

* Implemented comprehensive support for all possible currencies during the installation process, ensuring smooth setup and functionality for users worldwide.

2. **Sample Products**

* Added the feature to create sample products during installation using the GUI, enhancing the initial setup experience for users.

### Search Mode Functionality

* Implemented search mode functionality for the product datagrid and Elasticsearch, enabling users to perform advanced searches and retrieve relevant product information efficiently.

### Channel Filter Support

1. **Data Grids:**
    - Users can now filter data by channels directly within data grids, improving data management and analysis capabilities.

2. **Dashboard:**
    - Enhanced the dashboard with channel filter support, allowing users to view analytics and metrics specific to selected channels.

3. **Reporting:**
    - Integrated channel filters into reporting features, enabling users to generate channel-specific reports for detailed analysis.

### Added Channel Selection for Product Creation

*  Implemented a feature that allows users to select the channel while creating a product, enhancing flexibility and management.

### Added Blade File Support in Configuration

* Implemented support for Blade files in configuration settings, enhancing flexibility and customization options.

### Added Login Option in Cart and Checkout

* Implemented a login option directly on the cart and checkout pages, improving accessibility and convenience for users.

### Locale-Based URL Key Implementation

* Implemented locale-based URL keys, improving SEO and localization by generating URLs tailored to different language versions.

### Improved Web Vitals

*  Enhanced web vitals including loading speed, interactivity, and visual stability for a smoother user experience.

* Improved web vitals contribute to better user engagement and satisfaction.

* Optimized web vitals can positively impact search engine rankings and user retention.

### Simplified Tailwind CSS Classes

* Enhanced readability of code by simplifying class definitions.

*  Ensured a uniform look by standardizing how Tailwind CSS classes are applied.

* Tailwind CSS classes clearer by removing unnecessary values.

### Added Debug Mode with Allowed IPs Feature

* Implemented debug mode with IP allowlist configuration.

* This entry clarifies that the debug mode now includes a configuration option (`APP_DEBUG_ALLOWED_IPS=`) in the `.env` file, where IP addresses can be listed comma-separated to restrict access to authorized users during debugging, thereby enhancing security measures.

### Multiple Configuration Settings Support

* Enhanced the configuration management system for better performance and usability.

* Implemented support for multiple configuration settings, allowing users to define and manage various configurations based on their needs.

1. **Breadcrumbs**

* In the General section added new Configuration setting to enable or disable breadcrumbs.

2. **Header Offer Title**

* In the Content section added new Configuration setting to Configure Header Offer Title with offer title, redirection title, and redirection link.

3. **Products configuration**

* In the Products section added new Configuration setting to set up the search engine for product searches, you can choose between a database and Elasticsearch based on your requirements. If you have a large number of products, Elasticsearch is recommended.

* In the Products section added new Configuration setting to add product image placeholders for small, medium, and large sizes.

* In the Products section, added a new configuration setting to allow permission for customer reviews.
 
4. **Inventory Section**

* Added a new configuration section `Inventory`.

* In the Inventory section you can configure product stock options to allow back orders, set minimum and maximum cart quantities, and define out-of-stock thresholds.

5. **Login Options**

* In the Settings section added new configuration setting to Configure login options to determine the redirect page for customers after login.

6. **Create New Account Options**

* In the Settings section added new configuration setting to set options for new accounts, including assigning a default customer group and enabling the newsletter subscription option during sign-up.

6. **Email Settings Section**

* New configuration in the email settings section to configure Contact Name and Contact Email.

7. **Minimum Order Settings**

* In the Order section added new configuration setting to that the order has Include Discount Amount, Include Tax to Amount and the Description in cart page.

8. **Allow Reorder**

* In the Order section added new configuration setting to Enable or disable the reordering feature for admin users.

9. **PDF Print Outs**

* In the Invoice section added new configuration setting to configure PDF Print Outs to display Invoice ID, Order ID in the header, and include the invoice logo.

9. **Inclusive Exclusive Tax**

* In the Tax section added new configuration setting to configure Inclusive Exclusive Taxes.

10. **Checkout**

* Added a new configuration section `Checkout`.

* In the Checkout section you can manage guest checkout, cart page, cross-sell products, and estimated shipping to enhance user convenience and streamline the shopping process for increased sales.

* In the Checkout section you can configure settings for My Cart to show a summary of item quantities and display the total number of items in the cart for easy tracking.

* In the Checkout section you can enable Mini Cart settings to display the mini cart and show Mini Cart Offer Information for quick access to cart details and promotions.

* #9534 [feature] - Logged in User -> Checkout Page -> Default address must be selected.

* #9625 [feature] - Make the email address from the footer of the emails send from admin and shop configurable.

* #9854 [feature] - please support Swiss franc (CHF) as currency during installation. 

* #9758 [improvements] - When we delete a select type attribute options, there we are unable to see the delete warning message.

* #9764 [improvements] - In the Grouped Product feature, there's an issue where the quantity box becomes unresponsive and shifts when we input a quantity of 0, triggering a validation error.

* #9795 [improvements] - Create a new attribute, the labels in locale list should be in Alphabetical Order. 

* #9591 [enhancement] - There should be an Empty Space in Country Drop Down for Tax Inclusive Default Location Calculation.

* #9728 [fixed] - There is a UI issue with the Invoice Datagrid in the Admin-end customer section. The alignment is not appropriate.

* #9739 [fixed] - The UI of the Shipping Address is not displaying properly in the Shop Front Customer Orders section.

* #9880 [fixed] - Toggle Button on status color is not appropriate. 

* #9585 [fixed] - Tax is added to total price, even when catalog prices are including tax.

* #9588 [fixed] - Category Filter -> Max Price Filter issue appearing. Decimal Value entry not appearing.

* #9609 [fixed] - Serialization of 'Closure' is not allowed {"exception":"[object] (Exception(code: 0): Serialization of 'Closure' is not allowed at 

* #9610 [fixed] - Social Share links are not appearing.

* #9612 [fixed] - Getting Exceptional Error in saving categories in different locale. 

* #9644 [fixed] - Fields in configuration that use depends do not respect the type chosen. 

* #9645 [fixed] - While a product has deleted, and the order view and order refund have error report and can't do it continue .

* #9646 [fixed] - Reset Password Form -> Footer text translation is missing.

* #9649 [fixed] - Incomplete Spanish translations.

* #9654 [fixed] - Refunded product status color box is not appearing. 

* #9660 [fixed] - In Mobile view -> I have added all locale but cannot see or scroll all locales.

* #9662 [fixed] - Guest User Name is not appearing in order section of Admin End.

* #9665 [fixed] - Admin End -> Notification Page is not having Shimmer Effect.

* #9666 [fixed] - Admin End -> Datagrid Export button icon is not responding properly. 

* #9669 [fixed] - Unable to Mass Update configurable product inventory of Variants after filling any of the other details. 

* #9666 [fixed] - Admin End -> Datagrid Export button icon is not responding properly. 

* #9673 [fixed] - Admin End -> Reorder Feature -> We are unable to save new created address.

* #9676 [fixed] - Installer translation missing when we are adding locale in it.

* #9679 [fixed] - Getting Console Errors and Blank Page in Reorder Page in Admin Panel if we delete the existing product.

* #9680 [fixed] - In the Reorder Feature, we can see that we are able to click the "-" button of qty when the qty is 1. 

* #9687 [fixed] - In Contact Us form there we should not be able to see the arrows to increase or decrees number.

* #9698 [fixed] - Bagisto Installers refuse to connect to a database without db password.

* #9701 [fixed] - Getting Console Error inMass Delete In Cart Rule Coupons is not working properly. 

* #9705 [fixed] - When creating a Bundle product and selecting options, we can observe a field indicating whether it is required. Even if we select 'no,' we still see the asterisk symbol.

* #9707 [fixed] - value is missing for lines in a street address.

* #9713 [fixed] - VAT ID is missing in address create form.

* #9714 [fixed] - After enabling Buy it now feature from admin and checking the functionality it is not redirecting to checkout page.

* #9717 [fixed] - In the Reorder option, the image of the product appearing is not accurate.

* #9719 [fixed] - price does not change when options changed at configurable product with variant.

* #9726 [fixed] - Name Validation need to be removed from the First Name and Last Name while editing profile from shop front.

* #9733 [fixed] - Unnecessary validation messages are appearing in refreshing the Customer profile edit page.

* #9761 [fixed] - Unable to add products to cart that have variants or options to select. It does not redirect to the Product View Page.

* #9763 [fixed] - There is an issue with Cart Rule Marketing Time regarding the saving of Date and Time.

* #9772 [fixed] - Facebook/Whatsapp Share buttons don't work on mobile.

* #9785 [fixed] - Theme Section -> Image Carousal is not working, getting console errors.

* #9796 [fixed] - Unable to save the image title name when creating a new slider in the Image Carousel theme section of Settings. 

* #9799 [fixed] - Cyrillic is not supported.

* #9801 [fixed] - In Category Page -> Filters are Overlapping into the header.

* #9814 [fixed] - Your configuration files are not serializable.

* #9828 [fixed] - Doesn't work Social login with Google.

* #9845 [fixed] - The Cart Rule is experiencing issues under various conditions and rules.

* #9852 [fixed] - lang=ja, but the name input box can only input letters and numbers.

* #9863 [fixed] - Shipping.blade.php has a bug in client email and admin email.

* #9875 [fixed] - Admin End Mega Search is showing "Undefined" in "Explore all products matching (undefined)," and when clicked, a blank page appears.

* #9883 [fixed] - Error: Extension Error: The validator 'all' must be a function. 

* #9885 [fixed] - Getting issue in Order, Customer and Catalog Datagrid, unable to see the details in some specific locales. 

* #9836 [fixed] - Currency should be showing correct in Arabic locale for Bundle Product.
