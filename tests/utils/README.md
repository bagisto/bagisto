# Bagisto Playwright Test Cases for Bagisto v2.2.2

This document provides instructions for running Playwright test cases for Bagisto, covering both Shop and Admin ends.

---

## Prerequisites

1. **Install Node.js dependencies**  
   ```bash
   npm install
   ```

2. **Install Playwright Browsers**  
   ```bash
   npx playwright install --with-deps
   ```

---

## Running Playwright Tests

### Shop End Tests

#### Auth Tests
```bash
npx playwright test tests/shop/auth.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/shop/Auth || echo "Continue"
```

#### Add To Cart Tests
```bash
npx playwright test tests/shop/add-to-cart.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/shop/Add-to-cart || echo "Continue"
```

#### Mini Cart Tests
```bash
npx playwright test tests/shop/mini-cart.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/shop/Mini-cart || echo "Continue"
```

#### Cart Tests
```bash
npx playwright test tests/shop/cart.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/shop/Cart || echo "Continue"
```

#### Wishlist Tests
```bash
npx playwright test tests/shop/wishlist.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/shop/Wishlist || echo "Continue"
```

#### Checkout Tests
```bash
npx playwright test tests/shop/checkout.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/shop/Checkout || echo "Continue"
```

#### Compare Tests
```bash
npx playwright test tests/shop/compare.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/shop/Compare || echo "Continue"
```

#### Product-Category Tests
```bash
npx playwright test tests/shop/products-categories.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/shop/Product-category || echo "Continue"
```

#### Customer Tests
```bash
npx playwright test tests/shop/customer.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/shop/Customer || echo "Continue"
```

#### Search Tests
```bash
npx playwright test tests/shop/search.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/shop/Search || echo "Continue"
```

---

### Admin End Tests

#### Auth Tests
```bash
npx playwright test tests/admin/auth.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/admin/Auth || echo "Continue"
```

#### Sales Tests
```bash
npx playwright test tests/admin/sales.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/admin/Sales || echo "Continue"
```

#### Catalog Tests
```bash
npx playwright test tests/admin/Catalog --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/admin/Catalog || echo "Continue"
```

#### Customers Tests
```bash
npx playwright test tests/admin/Customers --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/admin/Customers || echo "Continue"
```

#### CMS Tests
```bash
npx playwright test tests/admin/cms.spec --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/admin/CMS || echo "Continue"
```

#### Marketing Tests
```bash
npx playwright test tests/admin/Marketing --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/admin/Marketing || echo "Continue"
```

#### Settings Tests
```bash
npx playwright test tests/admin/Settings --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/admin/Settings || echo "Continue"
```

#### Configuration Tests
```bash
npx playwright test tests/admin/Configuration --workers=1 --project=chromium --retries=2 --quiet --reporter html --output=playwright-report-temp/admin/Configuration || echo "Continue"
```

---

## Merging Playwright Reports
To merge all the generated reports:
```bash
npx playwright merge-reports playwright-report/*
```