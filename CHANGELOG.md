# CHANGELOG for v2.3.x

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

## Unreleased

* Fixed the database prefix validation issue in both the GUI and CLI installers.

* Resolved translation issues for the `ca` and `id` locales across the Admin, Shop, and Installer packages.

* Added the capability to run the dev command from the customized theme package without needing to run the publishers repeatedly.

* #10834 [enhancement] - Refined the admin pages and made approximately 95% of them responsive.

* #10836 [fixed] - Fixed the date of birth issue.

* #10838 [fixed] - Fixed the cart issue.

## **v2.3.6 (27th of June 2025)** - *Release*

* Added a method in the Installer class to prompt for a GitHub star once the installation is completed.

* Fixed the blank search issue that was causing all products to be fetched.

* Handled the storage URL directly within the DataGrid class instead of the view.

* Optimized the anonymous file by removing unnecessary currency code, and cleaned up the Maintenance Mode class by removing the unused Database Manager code.

* #10819 [fixed] - Fixed the issue related to maintenance mode.

* #10782 [fixed] - Resolved an issue where the "Bundle Items" section was not visible while creating a Bundle Product in the French locale (APP_LOCALE=fr). This was caused by unescaped apostrophes in translatable strings breaking JavaScript during rendering.

## **v2.3.5 (11th of June 2025)** - *Release*

* Added support for the Indonesian language.

* Fixed an issue in the installer where old values were being retained, causing the first attempt to fail. The installation now completes successfully on the first attempt.

* Added missing event dispatches to ensure proper event flow and handling.

* Added backend validation for image uploads in the image search feature.

* #10802 [fixed] - Removed default address handling from the repository and moved it to the controllers to prevent side effects across customer addresses in the admin panel, shop front, and checkout pages.

## **v2.3.4 (29th of May 2025)** - *Release*

* Fixed the issue where the total amount was displaying incorrectly when customizable options were empty.

* Resolved the sort order issue for paginated attribute options on the category page.

* Fixed the Spanish translation issue on the sidebar, which was caused by the translation key itself being translated.

* Fixed the GDPR translation issue for all locales, which was previously falling back to the English locale.

* Resolved the filter issue for date and datetime types in the DataGrid.

* Fixed the date translation issue that was coming from the Carbon instance.

* Resolved the "break-all" issue on the product view page.

* Fixed the issue where the transaction drawer was not opening as expected.

* Fixed the product DataGrid search issue in Elasticsearch mode when no IDs are present.

* Fixed the multiselect filter and also added support for checkbox filters in both Elasticsearch and database modes.

* Fixed the filter issue for boolean-type attributes in Elasticsearch mode.

* #10788 [fixed] - Added a slight delay of 300ms along with a debounce mechanism to ensure that the dash is removed only after the user stops typing.

* #10735 [fixed] - Fixed the issue with the multiselect filter that was preventing products from being correctly filtered on the category page.

* #10718 [fixed] - Fixed the issue that allowed an admin to delete their own account.

## **v2.3.3 (22nd of May 2025)** - *Release*

* Resolved an issue with the category filter functionality.

* Fixed an issue with search attributes on the category page.

* Addressed a problem with category listings.

* Corrected issues on the search page and toolbar.

* #10784 [fixed] - Fixed store search issue caused by Full Page Cache (FPC).

## **v2.3.2 (21st of May 2025)** - *Release*

* Response cache is enabled by default.

* Fetch priority added for the slider image and product view image.

* Separate configuration provided for speculation APIs for both prerender and prefetch.

* Searchable attributes added on the category view page.

## **v2.3.1 (14th of May 2025)** - *Release*

### Features:

* Side Bar Menu: Introduced a new sidebar menu that provides users with quick access to various sections of the application, improving navigation and user experience.

* Default Menu Feature: Added the ability to set a default menu for users, allowing for a more personalized and streamlined interface. 

* #10679 [enhancement] - Some options in the products need to be enabled by default.

* #10666 [enhancement] - Need to create the configuration in the admin panel for the removed keys from the .env

* #10747 [fixed] - Update Locale Name from 'Canada' to 'Catalan'

## **v2.3.0 (27th of March 2025)** - *Release*

### Features:

**Booking Product**

* Integrated the Booking Product module into the core of Bagisto, allowing native support for booking-based products.

**GDPR Compliance Features**

* Integrated GDPR compliance functionalities into the core of Bagisto, enabling users to manage data protection requests and cookie consent preferences directly within the platform.

**Canadian Locale Support**

* Introduced support for the Canadian locale, enabling users to view the store interface in Canadian Locale.

**AI Model Options Update**

* Revised the available AI models for review translation, replacing older models with the latest versions to enhance performance and accuracy.

**Sitemap Enhancement**

* Introduced new settings to enable or disable the website's sitemap, aiming to improve search engine optimization and enhance user experience.

* Implemented configurable file limit options for the sitemap, providing greater control over its structure and performance.

**Laravel 11 Support**

* Updated the project to be compatible with Laravel 11, ensuring alignment with the latest framework features and improvements.

**Customizable Item Feature for Simple Products**

* Introduced the ability to customize simple products, allowing for enhanced flexibility and personalization options.

* Customer can add the customized content with the product.

**Enhanced Playwright Test Cases**

* Improved test stability and maintainability by implementing best practices,

* #10596 [feature] - In Shop Front, we should have an option to revoke the request which is added.  

* #9953 [enhancement] - Need to have "Back Button" in each view all sections of Reporting Side Menu Options.

* #10501 [enhancement] - Drop down menu showing on mobile screens or reducing the browser window when no drop down available.

* #10591 [enhancement] - GDPR Feature should be multichannel and Multilocale Supported.

* #10597 [enhancement] - GDPR Status in customer profile, First letter must be capital.

* #10598 [enhancement] - Admin End GDPR Type Delete and Update must have first letter capital.

* #10602 [enhancement] - In Shop Front GDPR datagrid, Declined Label Color is not appearing.

* #10603 [enhancement] - Add New Filters in Admin End GDPR Section with Status and Type.

* #10604 [enhancement] - Date filter in shop front for logged in customer is not working properly with System Dark Theme.

* #10610 [enhancement] - Customize Error Page to Replace the Default Index Page

* #10489 [fixed] - Product in Cart Appears for User Despite Being Out of Stock. If the Product is already added in the Cart by the user.

* #10566 [fixed] - Wishlist Price Display Issue with Currency Exchange in Bagisto.

* #10587 [fixed] - Add character Validation in GDPR Agreement Checkbox Label.

* #10589 [fixed] - Add character Validation in GDPR Static Block Identifier and Description.

* #10590 [fixed] - GDPR Cookie Box Must be Responsive. 

* #10592 [fixed] - In GDPR Your Cookie Consent Preference the content which is added should appear properly in Shop Front without Tags.

* #10593 [fixed] - Text Overlapping the URL Rewrite Targeted and Requested Path in URL Rewrite Datagrid.

* #10594 [fixed] - Text Overlapping in the Settings User Section when we add long email.

* #10595 [fixed] - Shop Front -> Logged in user GDPR Request Datagrid in profile there we can see UI issue in Date.

* #10599 [fixed] - Export All GDPR Requests in Admin Panel.

* #10601 [fixed] - GDPR Feature is disabled and still we are able to access with URL. 

* #10605 [fixed] - When we click on Save and Continue for Your Cookie Consent Preferences in Shop Front it is not redirecting to Shop.

* #10606 [fixed] - GDPR Agreement should not be a mandatory field to be selected while registration page. 

* #10618 [fixed] - Priority Detail Not Modify & Maximum no. of URLs per file In Sitemap Configuration.

* #10633 [fixed] - GDPR Data Request Page is Not Responsive on All Devices.

* #10645 [fixed] - ThemeDataGrid Query optimization.

* #10641 [fixed] - Last Product's Quantity Becomes Blank After Deleting a Product from Grouped Productuser.
