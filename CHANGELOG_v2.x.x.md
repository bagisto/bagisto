# CHANGELOG for v2.x.x

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

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