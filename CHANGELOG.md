# CHANGELOG for v2.x.x

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

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


## **v2.2.1 (24th of June 2024)** - *Release*

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


## **v2.1.2 (8th of March 2024)** - *Release*
* #9547 [improvements] - Revamped completed checkout flow.

* #9537 [fixed] - Unable to Checkout Product when Address is created from user profile and then proceed for checkout. Email is a mandatory field at checkout.

* #9544 [fixed] - 500 Internal Server error in console as Transaction Datagrid Not working.

* #9551 [fixed] - Admin End -> Sales -> Transactions Datagrid is having status row status entries UI is not appropriate and also translations is missing for Payment Method.

* #9550 [fixed] - Downloadable Product Datagrid in customer profile -> Status Row is having missing status entries.

* #9564 [fixed] - Date Filters not working in Transaction section in Admin End.

* #9568 [fixed] - In Customer Profile Reviews -> there we are able to see only 5 reviews in total, but I have approved more reviews for the same user. 

* #9567 [fixed] - After applying a filter on the orders page , Sidebar Scroller should be working - Customer end.

* #9579 [fixed] - Fix issue related to moving items between the wishlist and cart with quantities.

* #9543 [fixed] - Once the user has requested for password reset, then again if user raises the request there validation message appears "We cannot find a user with that email address."

## **v2.1.1 (27th of February 2024)** - *Release*

* #9501 [fixed] - Can't Checkout

* #9442 [fixed] - Mobile view should be responsive -Front end.

* #9462 [fixed] - Logged In User Address, when address is being deleted Header is Highlighted.

* #9489 [fixed] - Type filter in product catalog not working.

* #9500 [fixed] - Price Filter is not responsive in Category Page both RTL and LTR view.

* #9514 [fixed] - Allow Image and File Upload Size is accepting any values which are entered.

## **v2.1.0 (15th of February 2024)** - *Release*

## New Feature: Data Transfer/Import
An enhanced import implementation now facilitates the rapid importation of ten thousand products in just 1-2 minutes. This improved efficiency streamlines data integration, offering businesses a quicker and more effective solution for managing extensive product catalogs and boosting operational agility.

## New Feature: AI Integration

We're excited to unveil a groundbreaking enhancement to your Bagisto e-commerce experience - AI Integration. This powerful addition introduces advanced capabilities that leverage artificial intelligence for various aspects of your online store.

Key Features:

1. **Content Generation:**
   - Harness the power of AI to intelligently generate compelling and relevant content for your product descriptions, ensuring an engaging shopping experience.

2. **Image Generation:**
   - Seamlessly generate visually stunning images using AI, enhancing your product visuals and overall aesthetics.

3. **Review Translation:**
   - Break language barriers by employing AI to translate customer reviews, allowing your products to reach a global audience.

4. **Personalized Checkout Messages:**
   - Enhance the checkout experience with personalized messages tailored to individual customers, creating a more intimate and customer-centric interaction.

##  New Feature: Search & SEO Enhancements

**URL Rewrite**

* The URL rewrite feature in a Bagisto e-commerce store allows developers to dynamically modify the URL structure of their e-commerce store. This functionality is particularly useful for creating more user-friendly and SEO-friendly URLs, as well as for managing redirects.

Key Benefits:

  1. URL Redirects:
      Set up redirects from old URLs to new ones for seamless transitions.

  2. SEO Optimization:
      Create meaningful and keyword-rich URLs to improve search engine optimization.


**Search Term Tracking for SEO and Marketing Insights**

* This feature aims to gather and record search terms used by visitors within your e-commerce store. This collected data serves multiple purposes. This functionality is valuable for several reasons, primarily for SEO enhancement and marketing insights.

Key Benefits:

  1. SEO Optimization: Now, your store can dynamically adapt to popular search queries. By analyzing and leveraging the collected search terms, we enhance content, metadata, and keyword targeting, potentially boosting your store's visibility on search engines.

  2. User Behavior Insights: Gain a deeper understanding of user search patterns. This invaluable information provides insights into user preferences, trends, and behavior, allowing you to refine your marketing strategies and optimize content accordingly.

**Search Synonyms**

* you have the option to include search synonyms, which can drive traffic to your e-commerce store's products.

Key Benefits:

1. Improved Search Relevance: Link synonyms to primary search terms for more comprehensive and relevant results.

2. Enhanced User Experience: Offer expanded search results through synonyms, ensuring users find relevant content easily.

* #8811 [feature] - While clicking on the view all it should be opened the separate page for new/feature products.

* #8900 [feature] - data-grid start and end date filter.

* #9428 [feature] - Added excel support for import.
.
* #9431 [feature] - Added XLSX file support in export.

* #9432 [feature] - Create a search bar for settings to be searched.

* #9144 [feature] - Checkout Experience.

* #8444 [improvements] - In the theme customization, In the image carousel there should be shown image preview.

* #8826 [improvements] - It should be a loader while clicking load more button on the category page.

* #8934 [improvements] - Add to Cart button should be disable when the product is Out of Stock and we have disabled the Backorder functionality.

* #8908 [improvements] - In the channel page setting should be Maintenance mode.

* #8945 [improvements] - Condition type check in rule validator.

* #8955 [improvements] - There should be a product redirection on the mini/main cart page.

* #8971 [improvements] - Shrink the white space so the listing will be viewed properly.

* #8978 [improvements] - In RTL View, Arabic Translation, Checkout page, Price and QTY details UI need to be improved.

* #9008 [improvements] - In Shop Front -> Customer/Guest User -> Review attachment should not be a mandatory field.

* #9018 [improvements] - In Dark Theme -> Calender box should be in Dark Theme.

* #9021 [improvements] - In Configure section -> General Settings -> There we should show by default Kg as weight.

* #9032 [improvements] - Created a new user with selected newsletter subscription, In User profile again we can see that we are able to see the checkbox to subscribe for newsletter.
* #9227 [improvement] - getProductIdAttribute() does not work as expected.

* #8288 [fixed] - Getting an exception while using duplicate SKU in the variant product.

* #8393 [fixed] - Last option is not visible in the setting in the sidebar of admin panel.

* #8516 [fixed] - While trying to create/edit a select type attribute with image swatches. it is showing only broken images.

* #8592 [fixed] - Getting a few issues with the boolean filters.

* #8636 [fixed] - The Calendar should be shown dark in the dark theme.

* #8673 [fixed] - Select File button should be in the Dark in the Dark theme.

* #8689 [fixed] - HTML/CSS theme editor should be shown dark in the Dark theme.

* #8733 [fixed] - It continues Purchase Funnel loading and showing the errors in the console.

* #8737 [fixed] - No data found default message is missing in the report listing page.

* #8756 [fixed] - Incorrect translations used for Money Transfer payment method.

* #8776 [fixed] - Getting shimmer issue on the purchase funnel in the sales reporting.

* #8777 [fixed] - The refund section is moving right on refresh in the sales reports. 

* #8779 [fixed] - Social login appears twice on the login page.

* #8793 [fixed] - Getting an error while running migrate command.

* #8800 [fixed] - Not able to remove the attribute's option while editing the related attribute from the admin panel. 

* #8801 [fixed] - Not able to create a Catalog rule. it shows an exception in the console and shows a blank page for the same.

* #8809 [fixed] - Draggable functionality is not working for Attribute Options at Admin end.

* #8816 [fixed] - The remove product flash message should appear up on the mini cart drawer.

* #8821 [fixed] - First Name and Last Name, We are able to fill any data in Registration form.

* #8822 [fixed] - Category description is not working based on locales.

* #8824 [fixed] - The category visible on menu toggle button is not working.

* #8832 [fixed] - On admin panel, design option inside configure menu, checkbox not clickable

* #8835 [fixed] - Translation missing in Settings -> Roles menu -> Create roles page

* #8838 [fixed] - On the shop, in additional information options value should be shown accordingly.

* #8851 [fixed] - Warning Messages are overlapping in Home Page Header contents.

* #8852 [fixed] - Product Reviews -> Customer Profile Image is not visible if customer has applied a profile picture.

* #8862 [fixed] - The customer is receiving the refund confirmation email twice, and the invoice confirmation email is not being sent to the admin.

* #8866 [fixed] - Invoice section at the Admin end, after selecting Transaction check box responsive issue is appearing.

* #8867 [fixed] - The visitor graph should be blank if there are zero visits inside the sales/reporting.

* #8868 [fixed] - Required field section in Address are un-selected from admin end, still in Customer Address, it is processing with Mandatory Fields.

* #8870 [fixed] - Tax applied but categories are not visible.

* #8873 [fixed] - The order cancellation emails are not being sent correctly. The emails are not being sent to the intended recipients.

* #8874 [fixed] - Email Verification -> Mail is not showing User Name.

* #8877 [fixed] - After sending duplicate invoice to User, User Name is not appearing properly.

* #8882 [fixed] - the Date option Effect should be affected on mouse hover it is visible permanently.

* #8883 [fixed] - The hover effect is missing on the total sale interval option in the Dark theme.

* #8885 [fixed] - Responsive issue on Top Selling Products By Revenue Graph inside the Reporting/Products.

* #8886 [fixed] - In Customer Group pricing, Change the text "qty" to "Minimum QTY" 

* #8888 [fixed] - The canceled order quantity is still visible in the pending order on product edit page.

* #8889 [fixed] - On the notification page, No record found message should be shown in Grey color in the Dark theme

* #8890 [fixed] - On the notification page, in the Dark theme selected tab underline is not visible.

* #8892 [fixed] - Today's total order and order count are not appearing on the Admin dashboard.

* #8921 [fixed] - Admin end -> Configure -> Shipping Methods and Payment Methods, Shipping and Payment Method Status and Mandatory field response is not appropriate.

* #8927 [fixed] - Duplicate Invoice mail is not sent to the customer or guest user even mail details are added.

* #8931 [fixed] - On the checkout page, without adding an address and clicking on the confirm button so get the error in the console.

* #8932 [fixed] - Channel setting section are not working.

* #8935 [fixed] - Inventory is not reducing after purchasing the downloadable product. 

* #8937 [fixed] - On the Admin dashboard, in today's details, it shows two icons for negative values. it should be shown only one down arrow. 

* #8940 [fixed] - On the listing page, select mode as list and change the sorting so the product image is not updating.

* #8942 [fixed] - Update the warning message in Update Exchange rate in Settings -> Exchange rates.

* #8947 [fixed] - On the cart/catalog rule create/edit page, condition fields show a validate message after entering the correct value. 

* #8951 [fixed] - After selecting the shipping method change the condition. so input fields are not reset on the cart rule create/edit page.

* #8963 [fixed] - On the admin panel, the Category page is not loading. 

* #8969 [fixed] - In the Cart rule there should be a date & time calendar. currently it shows only date.

* #8970 [fixed] - After adding the date in the cart rule, while editing the cart rule it shows the date fields as Text fields.

* #8980 [fixed] - Creating a customer from admin, login temporary password details are not received on the mail.

* #8981 [fixed] - Adding Future Dates in Reportings Total Sales, there in calender filter when we add future date, in console we can see 500 Internal Server Error.

* #8982 [fixed] - Translation missing in when Paypal Standard Payment Order is cancelled.

* #8986 [fixed] - Creating Cart rule with Condition on Product price in cart, the price field is showing unnecessary validation.

* #9005 [fixed] - unable to see submenu on users with limited roles.

* #9007 [fixed] - Refund Tab in shop front Customer Orders section is having translation issue.

* #9020 [fixed] - Edit any CMS page and then click on the SEO URL, it is showing 404 error.

* #9022 [fixed] - Bundle Product -> Multiselect Option -> Selected product is not highlighted in the store front product page. 

* #9030 [fixed] - In Bundle Product -> Required Field is not working properly in Radio Type

* #9031 [fixed] - In Bundle Product -> Checkbox Is required is selected "NO" still validation is appearing.

* #9049 [fixed] - Italian translation is mixed with Spanish in Admin panel

* #9064 [fixed] - Calender in cart rule and Catalog rule on Special Price From/To should be as per core and dark theme calender should be dark.

* #9401 [fixed] - When editing the currency, there is no proper validation in the code field.

* #9396 [fixed] - Elastic Search is having issue related to the Price Storage. 500 Internal Server Errors are appearing in Console.

* #9386 [fixed] - Cannot save without adding data row(s) in edit theme page, especially image_carousel and services_content type.

* #9369 [fixed] - Cannot create/edit themes. For example:Slider Carousels.

* #9368 [fixed] - Validation Required for Images sizes in Products configurations in Admin Configurations.

* #9360 [fixed] - Cash on delivery payment method available when ordering only downloadable or virtual products.

* #9343 [fixed] - Unable to Add Images in Mass at configurable product Varients.

* #9334 [fixed] - Create/Edit Product -> Locale Change Dropdown should follow Alphabatical Order.

* #9329 [fixed] - Catalog Rules Active despite Set as Inactive and 'Does Not Contain' Conditions Reverting to 'Contains' in the Italian Admin Panel of Bagisto 2.0

* #9328 [fixed] - Unknown array key "it"' error when saving a new image_carousel theme without images in the Italian admin panel of Bagisto 2.0

* #9317 [fixed] - In Related Product, Cross Sell, Up Sell Product and Store Front Configurations at Admin end, Negative values and Decimal's are also accepted. 

* #9299 [fixed] - RTL View -> Configurable Product -> Varient Selection dropdown arrow is having UI issue.

* #9292 [fixed] - UI issue in the Admin login and customer login button in GUI installation.

* #9280 [fixed] - UI issue in Locale Dropdown in RTL view.

* #9275 [fixed] - Unable to load JS file after running npm run Build command in shop package.

* #9335 [fixed] - When we update catalog rule then api is not workign properly

* #9235 [fixed] - Issue in updating customer profile

* #9207 [fixed] - Missing required parameter for [Route: shop.product_or_category.index]

* #9200 [fixed] - Admin menu is not display.

* #9194 [fixed] - Getting 500 Internal Server error in console when we edit a user after assigning custom roles.

* #9192 [fixed] - Edit option is not appearing in Arabic locale RTL view for Locale and Currency Settings.

* #9189 [fixed] - Admin Dashboard -> Customer details are overlapping Today's Details.

* #9187 [fixed] - Admin End, Arabic Locale -> Product Edit/Create -> Price Section is having UI issue.

* #9189 [fixed] - The product video is not playing on the product view page of the shop.

* #9135 [fixed] - Cross Sell, Up-sell and related product information is not appearing in admin end if we create or edit any product in another locale. 

* #9108 [fixed] - 500 | server error when "Transactions" section being clicked on "Sales" Tab

* #9096 [fixed] - Cart Rule -> Uses per customer is consumed by the user, after again applying the coupon empty warning message is appearing.

* #9093 [fixed] - Getting Exceptional Error in Configur -> Order Settings section, when we save the data. 

* #9091 [fixed] - Tax Rates -> States madatory Field is not working properly.

* #9025 [fixed] - The catalog rule and cart rule have been successfully created; however, the applied discount is not reflecting on the shop.

* #9076 [fixed] - Inventory Sources once Inactive done, we are unable to active that. 

* #9077 [fixed] - Magic AI Configuration is disable, still we are able to see the Magic AI details in Categories.

* #9399 [fixed] - Error in Inserting Products with Video in the Bagisto Administrator Panel

* #9414 [fixed] - Catalog issue when select condition fixed.

* #9415 [fixed] - Unable to mass update inventory in configurable Product, Console errors are appearing.

* #8896 [fixed] - If the email verification option is enabled so Customer/Admin both are not getting successful registration messages.

* #9448 [fixed] - Image uploading issue.

* #9442 [fixed] - Mobile view should be responsive -Front end.

* #9409 [fixed] - Getting Exceptional Error in Product Preview in Admin End.

* #9407 [fixed] - Unable to mass update inventory in configurable Product, Console errors are appearing.

## **v2.0.0 (21st of October 2023)** - *Release*

### Features:


**Full Page Cache Support**

* Implemented Full Page Cache support to improve performance.

**Octane Support**

* Added support of Laravel Octane to enhance application speed and efficiency.

**Theme:**

  * Added Dark theme support, providing users with the option to switch to a darker color scheme for improved visibility in 
  low-light environments.

**Dashboard:**

  * Added Visitors graph feature, allowing users to visualize website traffic and visitor trends over time.

**Sales Reports** 

  * Added Total Sales graph feature, allowing users to visualize sales data over time.

  * Introducing Purchase Funnel analytics to track customer conversion stages.

  * Implemented Abandoned Carts report to monitor incomplete purchase attempts.

  * Introduced Total Orders tracking feature, providing insights into overall order volumes.

  * Added Average Order Value metric to help analyze customer spending habits.

  * Implemented Shipping Collected report, detailing shipping revenue collected from orders.

  * Added Refunds report, allowing users to track refunded orders and their values.

  * Introduced Top Payment Methods feature, displaying popular payment methods used by customers.

 **Customer Reports** 

  * Added Total Customers report, providing an overview of the total number of customers.

  * Implemented Customers With Most Sales report, highlighting customers with the highest sales.

  * Introduced Customers With Most Orders report, displaying customers with the highest order counts.

  * Added Customers Traffic report, providing insights into customer website traffic.

  * Implemented Top Customer Groups feature, showing the most active customer groups.

  * Introduced Customers With Most Reviews report, displaying customers who have submitted the most product reviews.

 **Product Reports**

  * Added Sold Products Quantity report, detailing the total quantity of products sold.

  * Implemented Products Added To Wishlist report, tracking products added to users' wishlists.

  * Introduced Top Selling Products By Revenue report, highlighting products with the highest revenue.

  * Added Top Selling Products By Quantity report, displaying products sold in the highest quantities.

  * Implemented Products With Most Reviews report, showing products that received the most reviews.

  * Introduced Products With Most Visits report, providing insights into products visited most frequently.

  **Installer Package:**

  * Introduced a separate installer package, providing users with a standalone tool to set up and configure the application easily through a user-friendly installation process.

  * Redesigned Installer UI: Revamped the user interface of the installer for a more intuitive and visually appealing installation experience.


* Removed the **Booking Product Package**, discontinuing support for booking-related features in the application. Users will no longer be able to utilize booking functionality through this package.

* #8623 [enhancement] - Footer Copyright Year Displays Previous Year Instead of Current Year

* #8493 [enhancement] - we can remove the homepage configuration from the configuration.

* #8443 [enhancement] - Add tooltip for height and width for the image carousel in the theme customization.

* #8429 [enhancement] - On the customer profile edit page it should be shown dash (-) if the fields are blank.

* #8421 [enhancement] - Not showing message with tax included on the product page. 

* #8319 [enhancement] - If the admin user have not permission to edit/delete other users so the option should be hide/disabled.

* #8302 [enhancement] - On the shopping cart, move to wishlist option should disabled or hided for guest users. 

* #8713 [fixed] - The customer receives the shipment confirmation email twice, and the email sent to the inventory source contact address does not display the correct name.

* #8705 [fixed] - When updating the logo from the channel settings, the change is not reflected on the shop as the new logo.

* #8696 [fixed] - The customer is getting order confirmation mail twice. 

* #8691 [fixed] - If there is a pending order and delete that product. On the admin panel order history page, it continues to load but is not able to view the listing.

* #8675 [fixed] - On the payment method page in configuration, A few fields are not dark in the Dark theme.

* #8671 [fixed] - Shipping title and rate input fields should be dark in the dark theme.

* #8666 [fixed] - Should hover on the Filter button in the Dark theme. 

* #8663 [fixed] - After creating the Customer group price with the Discount price type. It does not show quantity on the product edit page. 

* #8660 [fixed] - Compatible the admin panel calendar icon with multiple browsers. 

* #8659 [fixed] - Products are not visible on Shop if tax inclusive is enabled. 

* #8655 [fixed] - On the Admin panel review page, It should not be shown the extra (/) separator. 

* #8652 [fixed] - Increase the Clickable area of the Customer address menus.

* #8651 [fixed] - If the image is added to the review, on the customer's review view page it shows the added image in the review instead of the product image.

* #8649 [fixed] - Not getting phone number on customer address edit page which is saved from customer address create page.

* #8648 [fixed] - The customer tried to date filter on the orders page, After selecting the date from the calendar the calendar redirected to the top-left for select time and the filter drawer was closed. 

* #8643 [fixed] - On the add/edit attribute page, Attribute options table heading should be in the Dark. 

* #8641 [fixed] - While adding the customer's address from the admin panel, getting a translation issue in the VAT ID input fields.

* #8635 [fixed] - while creating configurable products Configurable Attribute options are going out to the option fields.

* #8633 [fixed] - After deleting the category from mass action got a translation issue in the successfully deleted notification.

* #8631 [fixed] - Getting translation issues while adding downloadable product to the cart from the home/listing page.

* #8630 [fixed] - Getting UI issue in condition dropdown on the catalog rule edit page. 

* #8603 [fixed] - SQL error occurs when clicking on Print in the frontend invoice.

* #8600 [fixed] - Getting translation issues in the sort options on the Category carousel edit page.

* #8595 [fixed] - If the search mode is Elastic search gets an exception on product creation.

* #8591 [fixed] - On the product page, the Filter should be highlighted if the status filter is applied.

* #8585 [fixed] - On the currency page, the Code & name show data from each other fields.

* #8584 [fixed] - Getting an exception while creating catalog rule. 

* #8575 [fixed] - In the guest user review form, Customer Name field is missing. 

* #8570 [fixed] - The edit CMS page shows multiple required messages for multiple channels. It should show the single required message for the required field.

* #8560 [fixed] - If the guest user review is disabled still able to give a review on the products.

* #8559 [fixed] - Product image count should be shown if the image is added one or more.

* #8558 [fixed] - Configuration options in the attribute creation form are not updating from the edit attribute page. 

* #8556 [fixed] - Is required option in the attribute creation form is not working on the edit attribute page.

* #8555 [fixed] - In the mobile view, Language and currency options are not visible on the header menu navigation bar. 

* #8546 [fixed] - Getting a translation issue in add an attachment on the product review page.

* #8534 [fixed] - Getting image size and default image text issue in the grouped/bundle product image.

* #8528 [fixed] - Variant images should be visible in the image view container on the product view page on Shop. 

* #8527 [fixed] - Not showing checkbox options in the edit attribute page.

* #8525 [fixed] - While adding content in the text area attribute in the product creation/edition and getting HTML tags of the text on Shop.

* #8511 [fixed] - On the listing page, From page 2 onward select 50 or other options per page, It should be shown a listing accordingly.

* #8509 [fixed] - Getting an exception while entered duplicate attribute code in the attribute creation form.

* #8497 [fixed] - After defining the image/file attribute upload size from the configuration it shows a blank page and has an error in the console on the edit/create product page.

* #8496 [fixed] - Default list mode is not working.

* #8495 [fixed] - In the category page on sorting, by default option is visible 12. It should be shown the lowest value that is saved from the admin panel configuration.

* #8493 [fixed] - we can remove the homepage configuration from the configuration.

* #8491 [fixed] - Not working product carousel limit in the theme customization. 

* #8485 [fixed] - Getting error while changing admin locale from env file. 

* #8482 [fixed] - On the locale page in the Admin panel, the table headings show in the column.

* #8480 [fixed] - Getting customers to delete issue in the user role. 

* #8464 [fixed] - Only Image extensions should be accepted in the add image option of theme customization. 

* #8457 [fixed] - Required message is missing in the product carousel's title field.

* #8456 [fixed] - Getting an exception while disabling the footer link from the theme. 

* #8454 [fixed] - Sort-order fields should not accept negative values in the theme. 

* #8452 [fixed] - In the theme customization, limit fields should not accept negative value.

* #8450 [fixed] - On the shop, arrowheads should be visible while slider have two or more.

* #8447 [fixed] - The add slider should be clickable in the image carousel on the admin panel.

* #8445 [fixed] - While adding a slider image, image fields are required still I'm able to add a slider without an image.

* #8431 [fixed] - Getting translation issue on the bottom of the customer registration form.

* #8430 [fixed] - Getting spacing issue between message and email on the customer profile edit page.

* #8426 [fixed] - Create/Edit category, product mode is set to product only, the description is still required.

* #8424 [fixed] - If the field is required it should be shown in the asterisk sign (*).

* #8419 [fixed] - Shipping methods title should be required.

* #8414 [fixed] - Getting an exception on the shop while replacing the sort order of image carousel and static conent (Offer Information).

* #8410 [fixed] - Getting an exception while trying to add an additional image in the image slider from the admin panel for the carousel.

* #8408 [fixed] - Getting translation issue in the order shipped mail subject. 

* #8403 [fixed] - Getting image size issue in the category carousel on the Shop. 

* #8402 [fixed] - While deleting the admin user him/herself got errors in the console.

* #8401 [fixed] - While creating an Admin user so getting translation issues in success notification. 

* #8391 [fixed] - Getting errors in the console while creating tax.

* #8390 [fixed] - Getting an error in the console on the payment method page in the Admin panel.

* #8385 [fixed] - Getting translation issue while entering wrong password in the Admin user deletion form. 

* #8378 [fixed] - Not able to delete category.

* #8372 [fixed] - Enabling the toggle button and it is showing required message. 

* #8371 [fixed] - Flat rate shipping is not working according to Admin configuration.

* #8367 [fixed] - Wishlist sharing option is not working. 

* #8360 [fixed] - Lines in a Street Address option is not working in the registered user address create/edit form. 

* #8358 [fixed] - If the fields are required asterisk sign is still missing on the shipping origin form. 

* #8357 [fixed] - Custom CSS and JS functionality should work on the shop only. 

* #8356 [fixed] - The compare option is disabled from the admin panel it is still showing on the shop. 

* #8355 [fixed] - Image search functionality is not working. 

* #8353 [fixed] - The image search option is disabled still, it is showing in the search bar.

* #8351 [fixed] - Getting error in the console while open the wishlist page.

* #8347 [fixed] - While deleting the customer profile, the page loads without entering the current password and shows the wrong password notification. 

* #8345 [fixed] - Getting translation issues while updating the password and entering the wrong password in the current password field. 

* #8343 [fixed] - Date of Birth field is missing in the customer edit profile form.

* #8342 [fixed] - Getting translation issues while customer updating the profile. 

* #8341 [fixed] - Getting issues in the customer image on the edit profile page. 

* #8340 [fixed] - If the user has not selected the subscribe option, still it is subscribed on the customer registration form.

* #8332 [fixed] - Getting translation issues while deleting the channel.

* #8330 [fixed] - Not deleting the added image in the locale.

* #8328 [fixed] - Image/File attribute values should be downloadable in the shop on the additional information tab.

* #8327 [fixed] - Getting an exception while creating an attribute and using space in attribute code. 

* #8324 [fixed] - Getting image size and default image text issue on the added products in the related, cross, and up sell.

* #8323 [fixed] - The product listing page does not show the correct quantity count.

* #8320 [fixed] - While admin user trying to delete him/herself, getting an error in the console

* #8316 [fixed] - Getting translation issue in the delete button tooltip in the admin user listing page.

* #8314 [fixed] - While editing the Admin user, it shows the Confirm Password not matching notification. 

* #8313 [fixed] - The image field is required to be able to create without adding an image in the admin user create from.

* #8312 [fixed] - Getting an exception while editing the Channel 

* #8307 [fixed] - Theme options should be active after installation. 

* #8304 [fixed] - Clicking on remove or move to wishlist option without selecting product. it is showing successful notification.

* #8290 [fixed] - In Category, the filterable attribute options are not updating. 

* #8282 [fixed] - Getting an exception while changing the locale on the shop.

* #8281 [fixed] - Should be shown default image if the image is not added in the locale. 

* #8279 [fixed] - On the Admin panel, Added image is not visible while editing the locale.

* #8278 [fixed] - Not working delete attachment option while customer giving review. 

* #8274 [fixed] - Getting errors in the console. Giving a review a second time on the same product.

* #8273 [fixed] - Root category is visible in the category carousel on the homepage.

* #8272 [fixed] - Not showing category image on the homepage.

* #8270 [fixed] - On the Shop, Sample is not added still it is showing the option to download sample in the downloadable product.
 
* #8268 [fixed] - Not showing product video on the product page on the shop.

* #8267 [fixed] - On the edit attribute page, not updating the configuration options.

* #8266 [fixed] - On the Product create/edit page, not showing options to add details for image/file attribute.

* #8261 [fixed] - Not showing product and description on the category page in Shop.

* #8257 [fixed] - Getting error in console while deleting the email template. 

* #8254 [fixed] - Not working autogenerated coupons functionality in the cart rule.

* #8253 [fixed] - Prefix/Suffix code should not be a required field in the automatically generated cart rule coupons.

* #8251 [fixed] - The Phone input field is not a required field in the customer create/edit form.

* #8249 [fixed] - Getting translation issue in notification while Admin trying to log in the inActive user. 

* #8247 [fixed] - While creating a customer from the admin panel, it shows a duplicate customer group select option. 

* #8245 [fixed] - Customer log-in from the admin panel is redirected to the same tab.

* #8244 [fixed] - Cancel order by Admin notification is not showing properly.

* #8242 [fixed] - Configurable parent product quantity status showing out of stock

* #8238 [fixed] - Getting image size and default image text issue in the variant product image.

* #8235 [fixed] - Getting an exception on the dashboard after deleting the products with mass delete action.

* #8234 [fixed] - Getting translation issue in the Attribute.

* #8230 [fixed] - Placeholder is missing in the input fields.

* #8228 [fixed] - Slug is not updating. it is taking previous slug. 

* #8226 [fixed] - The input validation field should be shown according to selected attribute type.

* #8224 [fixed] - Not showing selected option in the display mode of the root category in the admin panel. 

* #8221 [fixed] - Getting an exception on the shop after installation.

* #8207 [fixed] - Not getting selected price type in the customer group price on the product edit page.

* #8206 [fixed] - Not getting input value in the price of the customer group price in the edit product page.

* #8195 [fixed] - After performing mass action, mass selection should disappear from the product page on the admin panel.

* #8190 [fixed] - On the admin panel, getting duplicate email title name in the send duplicate invoice pop-up.

* #8190 [fixed] - Getting duplicate tax heading of the tax in the invoice on the Admin panel.

* #8183 [fixed] - Not generating slug of the category while creating new category. 

* #8180 [fixed] - Getting 404 not found error while creating customer group discount. 

* #8179 [fixed] - All groups option is missing in customer group discount. 

* #8178 [fixed] - In the Order history on the admin panel, Find an extra filter as image.

* #8174 [fixed] - Not working filter in the order history page in the admin panel.

* #8172 [fixed] - If the listing page is blank still it is exporting it should be shown the notification as nothing to export. 

* #8170 [fixed] - Unable to apply filter on customers with gender, address count, order count and revenue

* #8169 [fixed] - Datagrid improvements and bugs

* #8168 [fixed] - Catalog Rule validation issue

* #8167 [fixed] - Unable to create new category due to validation issue from the backend admin panel 

* #8166 [fixed] - Chart not updating after date filter

* #8165 [fixed] - Internal server error on console during fresh installation 

* #8164 [fixed] - Loader has to be shown after clicking on place order button

* #8163 [fixed] - Product filter should be working properly 

* #8162 [fixed] - Not able to disable guest checkout 

* #8160 [fixed] - Unable to update default attribute from the admin panel 

* #8159 [fixed] - Configuration is not updating

* #8158 [fixed] - Unable to edit User in admin panel

* #8157 [fixed] - Not able to save tax configuration

* #8156 [fixed] - Getting error in installation with prefix. 

* #8155 [fixed] - Translation issue in configuration

* #8152 [fixed] - Not able to save cms pages

* #8151 [fixed] - Getting translation issue in forget password page for not found email.

* #8146 [fixed] - Getting translation issue in the forget password page.

* #8077 [fixed] - If there is no content in the stock threshold so it is not showing simulation.

* #8075 [fixed] - Getting an error while installing the Bagisto v2.x with single command 

* #8071 [fixed] - If the navigation collapse, customer icon is getting small in the overall details on the admin panel.


## **v2.0.0-BETA-1 (5th of September 2023)** - *Release*


### User Interface Enhancements:

* Completely revamped the visual appearance of both the admin and shop sections, introducing fresh new themes.

* Applied a modern and stylish theme to the admin section, elevating the user interface and overall user experience.

* Transformed the shop section with a modern theme, delivering an improved user interface and shopping experience.

* Reimagined key elements such as product pages, category listings, cart pages, compare pages, the review section, mini-cart, and checkout process, creating a seamless and cohesive shopping journey.


### Styling and Framework Updates:

* Integrated Tailwind CSS: We've migrated our styling approach from traditional CSS to the powerful Tailwind CSS framework, resulting in a more utility-first and responsive design system.

* Blade Components Integration: Our application now features Blade components for enhanced UI rendering.

* Reusable Blade Components: Introducing a new set of reusable Blade components, providing extensive customization options.


### Development and Tooling Improvements:

* Added a Vite configuration file (vite.config.js) to streamline project setup with Vite.

* Leveraged Vite's features for faster development and efficient module handling.

* Implemented customized data grids for improved data visualization and interaction.

* Enhanced filter functionality, enabling refined data exploration.

* Introduced advanced filter options for users to narrow down data based on multiple criteria.

* Added a Mega Search feature to the admin panel for enhanced data discovery.

* Restructured the controllers directory, grouping related controllers for better code organization.

* Improved code recoverability by moving controllers into more appropriate subdirectories.


### Code Refactoring and Cleanup:

* Enhanced separation of concerns by aligning data-grids with their respective views and sections.

* Improved Blade files to better match the views or functionalities they serve.

* Refined routes based on their corresponding Blade files.

* Improved route URLs based on directory structure.

* Restructured the language files to enhance localization management.

* Organized language files hierarchically, corresponding to their respective views.


### Simplification and Dependencies:

* Custom CSS Stylesheets Removed: As part of our transition to Tailwind CSS, we have phased out custom CSS stylesheets, simplifying our codebase and reducing style management complexity.

* Vue.js Components Replaced: In this release, we've replaced Vue.js components with Blade components.

* Removed unnecessary webpack-related dependencies from package.json.

* Deleted webpack.config.js, as it is no longer needed with the Vite setup.
