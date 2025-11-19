# 🎉 Complete Laravel Eloquent Models Documentation

## Multi-Vendor Marketplace with MLM Support - Laravel 12.2

**Total Models Created: 48 Complete Eloquent Models**

---

## 📊 Models Overview by Category

### 1. **Core System Models (4)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `User` | User authentication & management | Multi-role support (customer/vendor/admin), last login tracking, store credits |
| `UserAddress` | Thai address structure | Default address, billing/shipping types, Thai format (ตำบล/อำเภอ/จังหวัด) |
| `Role` | RBAC role management | System roles protection, permission syncing |
| `Permission` | Granular permissions | Grouped permissions, role assignment |

### 2. **Vendor System Models (3)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `Vendor` | Multi-vendor shop management | Approval workflow, commission rates, shop stats, rating system |
| `VendorDocument` | KYC document verification | Document types (ID, license, tax), expiry tracking |
| `VendorPayout` | Vendor commission payouts | Auto number generation, multiple payment methods, period tracking |

### 3. **Product System Models (6)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `Product` | Core product management | MLM support, variants, stock tracking, special pricing, view counter |
| `ProductVariant` | Product variations | Size/color options, separate pricing & stock |
| `ProductImage` | Product gallery | Primary image, sort order, alt text |
| `Attribute` | Dynamic product attributes | Filterable, multiple types (select/radio/color/image) |
| `AttributeValue` | Attribute options | Color codes, images, sorting |
| `Tag` | Product & blog tagging | Popular tags, multi-use (products/blog) |

### 4. **Category & Brand Models (2)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `Category` | Nested categories | Parent-child hierarchy, breadcrumbs, product count |
| `Brand` | Brand management | Featured brands, logo & banner, SEO fields |

### 5. **Shopping Cart Models (4)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `Cart` | Shopping cart | Coupon application, tax calculation, totals |
| `CartItem` | Cart line items | Price snapshot, variant support, availability check |
| `Wishlist` | Customer wishlist | Toggle add/remove, quick check |
| `RecentlyViewed` | Browsing history | Auto-cleanup (50 items), timestamp tracking |

### 6. **Order Management Models (4)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `Order` | Order processing | Auto order number, status workflow, Thai address, totals |
| `OrderItem` | Order line items | Commission tracking (MLM & vendor), product snapshot |
| `OrderStatusHistory` | Status tracking | Timeline, notes, user tracking, Thai status names |
| `ReturnRequest` | Product returns | Approval workflow, refund methods, auto number |

### 7. **MLM System Models (4)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `MlmPackage` | Commission packages | Multi-level rates (5 levels), percentage/fixed types |
| `MlmCommission` | Commission records | Level tracking, sync status, payment tracking |
| `MlmApiConfig` | External MLM API | Webhook support, sync intervals, connection testing |
| `MlmSyncLog` | Sync tracking | Success rate calculation, duration tracking, error logs |

### 8. **Review & Rating Models (3)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `Review` | Product reviews | Rating (1-5), verified purchase badge, helpful votes, vendor reply |
| `Transaction` | Payment transactions | Gateway integration, auto number, status tracking |
| `Refund` | Refund processing | Gateway refund ID, approval workflow, auto number |

### 9. **Support System Models (2)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `SupportTicket` | Customer support tickets | Priority levels, assignment, response time tracking |
| `SupportMessage` | Ticket messages | Staff/customer replies, attachments, read status |

### 10. **Content Management Models (4)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `Page` | CMS pages | Templates, bilingual (TH/EN), SEO fields |
| `BlogPost` | Blog system | Author, tags, featured posts, reading time, view counter |
| `Faq` | FAQ management | Categories, featured, view counter, sorting |
| `Banner` | Promotional banners | Position-based, date range, mobile images, CTR tracking |

### 11. **Marketing Models (4)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `Coupon` | Discount codes | Percentage/fixed/free shipping, usage limits, min purchase |
| `NewsletterSubscriber` | Email marketing | Verification, unsubscribe tracking, bounce handling |
| `EmailTemplate` | Email templates | Variable replacement, bilingual, system protection |
| `Notification` | User notifications | Type-based, read status, bulk sending, auto cleanup |

### 12. **Settings & Configuration Models (4)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `Setting` | System settings | Key-value store, type casting, grouped settings |
| `ShippingMethod` | Shipping options | Multiple calculation types, carrier tracking, Thai carriers |
| `PaymentGateway` | Payment methods | Fee calculation, currency support, test mode |
| `Media` | File management | Multiple disks (local/S3), thumbnails, file type detection |

### 13. **Inventory & Tracking Models (4)**
| Model | Description | Key Features |
|-------|-------------|--------------|
| `StoreCredit` | Customer credits | Expiry dates, multiple types, balance tracking |
| `InventoryLog` | Stock movement tracking | All transaction types, reference tracking, Thai labels |
| `StockAlert` | Low stock alerts | Auto-detection, acknowledgment workflow, threshold-based |
| `ActivityLog` | Audit trail | Model events, change tracking, auto cleanup |

---

## 🔥 Key Features Across All Models

### ✅ **Relationships**
- Complete Eloquent relationships (belongsTo, hasMany, belongsToMany, morphTo)
- Proper foreign key definitions
- Eager loading support

### ✅ **Scopes**
- Common query shortcuts (active, published, pending, etc.)
- Date-based scopes (recent, expired, valid)
- Status-based filtering

### ✅ **Accessors & Mutators**
- Computed properties (final_price, full_address, etc.)
- URL generation (image_url, shop_url, etc.)
- Human-readable formats (file_size_human, reading_time)

### ✅ **Business Logic Methods**
- Status management (approve, reject, cancel)
- Calculations (calculateTotal, calculateCommission)
- Stock management (incrementStock, decrementStock)
- Auto number generation (order_number, ticket_number, etc.)

### ✅ **Event Handling**
- Boot methods for auto-population
- Slug generation
- Default values
- Cascading updates

### ✅ **Proper Casting**
- Dates (datetime)
- Decimals (price, amount)
- Booleans (is_active, is_featured)
- JSON (settings, attributes, data)
- Integers (quantity, count)

### ✅ **Soft Deletes**
- Applied where appropriate (User, Product, Order, etc.)
- Preserves data integrity

### ✅ **Thai Language Support**
- Thai address structure (ตำบล, อำเภอ, จังหวัด)
- Bilingual fields (name, name_en)
- Thai status names
- THB currency default

---

## 📝 Usage Examples

### **Product Management**
```php
// Create product with MLM
$product = Product::create([
    'vendor_id' => 1,
    'name' => 'สินค้าทดสอบ',
    'price' => 1000,
    'is_mlm_product' => true,
    'mlm_package_id' => 1,
]);

// Check if on sale
if ($product->isOnSale()) {
    echo "ราคาพิเศษ: " . $product->final_price;
}

// Decrement stock
$product->decrementStock(5);
```

### **Order Processing**
```php
// Create order
$order = Order::create([
    'user_id' => auth()->id(),
    'total_amount' => 1500,
    'status' => 'pending',
]);

// Update status
$order->updateStatus('confirmed', 'ยืนยันคำสั่งซื้อแล้ว');

// Check if can be cancelled
if ($order->canBeCancelled()) {
    $order->updateStatus('cancelled');
}
```

### **Cart Management**
```php
// Add item to cart
$cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
$cart->addItem($productId, 2, $variantId);

// Apply coupon
$cart->applyCoupon('SAVE10');

// Calculate totals
$cart->calculateTotals();
```

### **MLM Commission**
```php
// Calculate commission
$commission = $product->calculateMlmCommission(2);

// Distribute to uplines
$mlmPackage->distributeCommissions($userId, $amount, $orderId);

// Get user total commission
$total = MlmCommission::getTotalCommissionByUser($userId, 'paid');
```

### **Vendor Management**
```php
// Approve vendor
$vendor->approve();

// Calculate commission
$commission = $vendor->calculateCommission(1000);

// Update stats
$vendor->updateStats();
$vendor->updateRating();
```

### **Support Tickets**
```php
// Create ticket
$ticket = SupportTicket::create([
    'user_id' => auth()->id(),
    'subject' => 'ปัญหาการชำระเงิน',
    'category' => 'payment',
    'priority' => 'high',
]);

// Add message
$ticket->addMessage('รายละเอียดปัญหา...', auth()->id());

// Assign to staff
$ticket->assignTo($staffId);

// Resolve
$ticket->resolve($staffId);
```

---

## 🗄️ Database Migrations Required

You'll need to create migrations for all 48 models. Use:

```bash
php artisan make:migration create_users_table
php artisan make:migration create_products_table
# ... etc for all models
```

### **Key Migration Considerations:**

1. **Foreign Keys**: Add proper foreign key constraints
2. **Indexes**: Index frequently queried columns (status, user_id, product_id)
3. **Unique Constraints**: email, slug, code, order_number, etc.
4. **Default Values**: Set appropriate defaults
5. **Nullable Fields**: Mark optional fields as nullable

---

## 🔐 Security Considerations

1. **Hidden Attributes**: Sensitive data hidden in API responses
   - `User`: password, remember_token
   - `PaymentGateway`: settings (API keys)
   - `MlmApiConfig`: api_key, api_secret

2. **Mass Assignment Protection**: All models use `$fillable`

3. **System Protection**: 
   - System roles/permissions cannot be deleted
   - System email templates protected

---

## 🚀 Next Steps

### 1. **Create Migrations**
```bash
php artisan make:migration create_all_tables
```

### 2. **Create Seeders**
```bash
php artisan make:seeder RolePermissionSeeder
php artisan make:seeder SettingSeeder
php artisan make:seeder MlmPackageSeeder
```

### 3. **Create Factories**
```bash
php artisan make:factory ProductFactory
php artisan make:factory OrderFactory
```

### 4. **Create Services** (Thin Controllers)
- `OrderService`: Order processing logic
- `CartService`: Cart management
- `CommissionService`: MLM calculations
- `PaymentService`: Payment gateway integration

### 5. **Create Livewire Components**
- Product listing & filters
- Shopping cart
- Checkout process
- Vendor dashboard
- Admin panels

### 6. **API Resources**
```bash
php artisan make:resource ProductResource
php artisan make:resource OrderResource
```

---

## 📚 Additional Resources

- **Laravel Docs**: https://laravel.com/docs/11.x
- **Eloquent Relationships**: https://laravel.com/docs/11.x/eloquent-relationships
- **Query Scopes**: https://laravel.com/docs/11.x/eloquent#query-scopes
- **Accessors & Mutators**: https://laravel.com/docs/11.x/eloquent-mutators

---

## ✨ Summary

**48 Production-Ready Eloquent Models** with:
- ✅ Complete relationships
- ✅ Business logic methods
- ✅ Query scopes
- ✅ Accessors & mutators
- ✅ Event handling
- ✅ Thai language support
- ✅ MLM commission system
- ✅ Multi-vendor support
- ✅ Comprehensive e-commerce features

**Ready for immediate use in your Laravel 12.2 Bagisto-based marketplace!** 🎉
