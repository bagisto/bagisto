# Bagisto
for test test-cases

# Laravel Bagisto Playwright Test Cases For Bagisto v2.2.2

# Install Node.js dependencies
~~~
npm install
~~~

# Install Playwright Browsers
~~~
npx playwright install --with-deps
~~~

# Run Playwright for Shop Auth tests
~~~
    @npx playwright test tests/shop/auth.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Shop-Auth || echo "Continue"
~~~

# Run Playwright for Shop Cart tests
~~~
    @npx playwright test tests/shop/cart.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Shop-Cart || echo "Continue"
~~~

# Run Playwright for Shop Mini Cart tests
~~~
    @npx playwright test tests/shop/miniCart.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Shop-Minicart || echo "Continue"
~~~

# Run Playwright for Shop Cart Page tests
~~~
    @npx playwright test tests/shop/cartPage.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Shop-Cartpage || echo "Continue"
~~~

# Run Playwright for Shop Wishlist tests
~~~
    @npx playwright test tests/shop/wishlist.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Shop-Wishlist || echo "Continue"
~~~

# Run Playwright for Shop Checkout tests
~~~
    @npx playwright test tests/shop/checkout.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Shop-Checkout || echo "Continue"
~~~

# Run Playwright for Shop Compare tests
~~~
    @npx playwright test tests/shop/compare.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Shop-Compare || echo "Continue"
~~~

# Run Playwright for Shop Product, Category tests
~~~
    @npx playwright test tests/shop/productsCategories.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Shop-Product&Category || echo "Continue"
~~~

# Run Playwright for Shop Customer tests
~~~
    @npx playwright test tests/shop/customer.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Shop-Customer || echo "Continue"
~~~

# Run Playwright for Shop Search tests
~~~
    @npx playwright test tests/shop/search.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Shop-Search || echo "Continue"
~~~

# Run Playwright for Admin Auth tests
~~~
    @npx playwright test tests/admin/auth.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Admin-Auth || echo "Continue"
~~~

# Run Playwright for Admin Sales tests
~~~
    @npx playwright test tests/admin/sales.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Admin-Sales || echo "Continue"
~~~

# Run Playwright for Admin Catalog tests
~~~
    @npx playwright test tests/admin/Catalog --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Admin-Catalog || echo "Continue"
~~~

# Run Playwright for Admin Customers tests
~~~
    @npx playwright test tests/admin/Customers --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Admin-Customers || echo "Continue"
~~~

# Run Playwright for Admin CMS tests
~~~
    @npx playwright test tests/admin/cms.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Admin-CMS || echo "Continue"
~~~

# Run Playwright for Admin Marketing tests
~~~
    @npx playwright test tests/admin/Marketing --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Admin-Marketing || echo "Continue"
~~~

# Run Playwright for Admin Settings tests
~~~
    @npx playwright test tests/admin/Settings --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Admin-Settings || echo "Continue"
~~~

# Run Playwright for Admin Configuration tests
~~~
    @npx playwright test tests/admin/Configuration --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/Admin-Configuration || echo "Continue"
~~~

# Merge Playwright reports
~~~
    npx playwright merge-reports playwright-report/*
~~~
