# CHANGELOG for v2.3.x

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

## **Unreleased**

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
