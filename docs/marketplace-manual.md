# Bagisto Multi-Vendor Marketplace — Product Manual

## Overview

The Bagisto Multi-Vendor Marketplace turns your Bagisto e-commerce store into a
platform where multiple sellers can register, list products, receive orders, and
get paid — all managed from a single admin panel.

---

## 1. Admin Guide

### 1.1 Marketplace Settings

Navigate to **Admin > Configuration > Marketplace > Settings > General**.

| Setting                   | Default | Description                                      |
|---------------------------|---------|--------------------------------------------------|
| Enable Marketplace        | On      | Master toggle for the marketplace feature.       |
| Default Commission (%)    | 10      | Platform fee deducted from every seller sale.    |
| Seller Approval Required  | Yes     | New sellers must be approved by an admin.        |
| Product Approval Required | Yes     | Seller product listings must be approved first.  |

### 1.2 Managing Sellers

**Admin > Marketplace > Sellers**

- **View all sellers** — see shop name, status, approval, and commission.
- **Edit a seller** — change commission percentage, toggle approved/active.
- **Approve a seller** — click Approve to activate a pending seller account.
- **Delete a seller** — permanently removes the seller and cascades to their
  products, orders, transactions, and reviews.

### 1.3 Managing Seller Products

**Admin > Marketplace > Seller Products**

- Review products that sellers have listed.
- **Approve** a product to make it visible on the storefront.
- **Delete** a listing to remove a seller's product assignment.

### 1.4 Managing Seller Orders

**Admin > Marketplace > Seller Orders**

- View all orders attributed to sellers.
- Each entry shows order totals, commission breakdown, and status.
- Click an order to see full details including the parent order.

### 1.5 Transactions / Payouts

**Admin > Marketplace > Transactions**

- View the full transaction history (credits and debits) for all sellers.
- **Create a manual transaction** to record a payout:
  - Select the seller.
  - Choose type: Credit (payment to seller) or Debit (charge-back).
  - Enter amount and payment method (bank transfer, PayPal, etc.).
  - Add an optional comment.

### 1.6 Seller Reviews

**Admin > Marketplace > Reviews**

- Moderate customer reviews submitted for sellers.
- **Approve** — makes the review visible on the seller's public profile.
- **Reject** — hides the review.
- **Delete** — permanently removes the review.

---

## 2. Seller Guide

### 2.1 Registering as a Seller

1. Log in to the storefront as a customer.
2. Go to `/marketplace/seller/register`.
3. Fill in:
   - **Shop Title** (required) — your public store name.
   - **URL Slug** (required) — unique URL-friendly identifier (e.g. `joes-electronics`).
   - **Description** — tell buyers about your shop.
   - **Contact & Address** — phone, address, city, country, postcode.
4. Submit. If admin approval is required, your account will be pending until
   an admin activates it.

### 2.2 Seller Dashboard

Once approved, visit `/marketplace/seller/dashboard` to see:

- **Total Sales** — gross revenue from all your orders.
- **Total Commission** — platform fees deducted.
- **Total Earnings** — your net earnings (sales minus commission).
- **Balance** — current payout balance (credits minus debits).

### 2.3 Managing Your Products

**Marketplace > My Products**

- **Add a Product** — select an existing catalog product, set your condition
  (New / Used / Refurbished), optionally set a custom price, and add a
  description.
- **Edit** — update condition, price, or description.
- **Delete** — remove your listing (does not delete the catalog product).

If product approval is enabled, new listings are pending until an admin
approves them.

### 2.4 Viewing Your Orders

**Marketplace > My Orders**

- See all orders that include your products.
- Each entry shows the order total, commission amount, your earnings, and
  current status.
- Click an order to view full details.

### 2.5 Viewing Your Reviews

**Marketplace > My Reviews**

- See all reviews customers have left for your shop.
- Reviews include a 1-5 star rating, title, and comment.
- Only admin-approved reviews are visible on your public profile.

### 2.6 Updating Your Account

**Marketplace > My Account**

- Update your shop title, description, phone, and address details.
- Your URL slug cannot be changed after registration.

---

## 3. Customer Guide

### 3.1 Browsing Sellers

- Visit `/marketplace/sellers` to see all active sellers.
- Click a seller to view their public profile, product listings, and reviews.

### 3.2 Leaving a Review

1. Navigate to a seller's profile page.
2. Fill in the review form:
   - **Rating** — 1 to 5 stars.
   - **Title** — short summary.
   - **Comment** — your detailed feedback (up to 2000 characters).
3. Submit. Your review will be visible once an admin approves it.

---

## 4. Commission & Financial Flow

```
Customer places order ($100)
        |
        v
Platform calculates commission (10% default, or seller-specific override)
        |
        v
Seller Order created:
    Grand Total:     $100.00
    Commission:       $10.00
    Seller Earnings:  $90.00
        |
        v
Admin creates payout transaction (credit $90 to seller)
        |
        v
Seller balance updated
```

- Commissions are calculated per-order based on the seller's effective rate.
- If a seller has a custom commission percentage set, it overrides the default.
- Payouts are manual — an admin creates a credit transaction to record payment.

---

## 5. URL Reference

| Page                     | URL                                      | Auth Required |
|--------------------------|------------------------------------------|---------------|
| Seller Listing (public)  | `/marketplace/sellers`                   | No            |
| Seller Profile (public)  | `/marketplace/seller/{slug}`             | No            |
| Seller Registration      | `/marketplace/seller/register`           | Customer      |
| Seller Dashboard         | `/marketplace/seller/dashboard`          | Seller        |
| Seller Products          | `/marketplace/seller/products`           | Seller        |
| Seller Orders            | `/marketplace/seller/orders`             | Seller        |
| Seller Reviews           | `/marketplace/seller/reviews`            | Seller        |
| Seller Account           | `/marketplace/seller/account`            | Seller        |
| Admin Sellers            | `/admin/marketplace/sellers`             | Admin         |
| Admin Seller Products    | `/admin/marketplace/seller-products`     | Admin         |
| Admin Seller Orders      | `/admin/marketplace/seller-orders`       | Admin         |
| Admin Transactions       | `/admin/marketplace/transactions`        | Admin         |
| Admin Reviews            | `/admin/marketplace/reviews`             | Admin         |

---

## 6. Database Tables

| Table                             | Purpose                                  |
|-----------------------------------|------------------------------------------|
| `marketplace_sellers`             | Seller profiles and shop details         |
| `marketplace_seller_products`     | Product listings by sellers              |
| `marketplace_seller_orders`       | Per-seller order line items & commission  |
| `marketplace_seller_transactions` | Credit/debit transaction ledger          |
| `marketplace_seller_reviews`      | Customer reviews and ratings for sellers |

---

## 7. Configuration Keys

Used in code via `core()->getConfigData(...)`:

| Key                                                    | Type    | Description             |
|--------------------------------------------------------|---------|-------------------------|
| `marketplace.settings.general.status`                  | Boolean | Marketplace enabled     |
| `marketplace.settings.general.commission_percentage`   | Numeric | Default commission %    |
| `marketplace.settings.general.seller_approval_required`| Boolean | Require seller approval |
| `marketplace.settings.general.product_approval_required`| Boolean| Require product approval|
