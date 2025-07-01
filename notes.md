# TODOs
~~- edit form for contact messages.~~
~~- update is_read to true when navigated to edit contact message.~~
~~- ui style for read messages.~~
~~- star infront of message if marked as important.~~
- delete functionality for regions.
- delete functionality for areas.

# Features
- Users
- Products
- Product Categories
- Product Ratings
- Sales
- Delivery Locations
- Delivery Areas
- Blogs
- Contact Messages

# DB DESIGN
```
users {
    id();
    uuid('uuid')->unique();
    string('first_name');
    string('last_name');
    string('email')->unique();
    string('phone_number')->nullable();
    string('secondary_phone_number')->nullable();
    unsignedTinyInteger('role')->default(3);
    boolean('status')->default(true);
    string('image')->nullable();
    timestamp('email_verified_at')->nullable();
    string('password');
    rememberToken();
    timestamps();
}

admin_logs {
    id();
    uuid('uuid')->unique();
    foreignId('admin_id')->nullable()->constrained('users');
    string('action'); // e.g., "Product Update", "Delete User"
    string('ip_address')->nullable();
    text('description')->nullable();
    timestamps();
}

contact_messages {
    id();
    uuid('uuid')->unique();
    string('name');
    string('email');
    string('phone_number');
    string('message', 2000);
    string('response', 2000)->nullable();
    string('notes')->nullable();
    boolean('is_read')->default(0);
    boolean('is_important')->default(0);
    timestamps();
}

blog_categories {
    id();
    uuid('uuid')->unique();
    string('title')->unique();
    string('slug')->unique();
    string('description')->nullable();
    string('image')->nullable();
    timestamps();
}

blogs {
    id();
    uuid('uuid')->unique();
    string('title')->unique();
    string('slug')->unique();
    text('content')->nullable();
    text('excerpt')->nullable(); // Short summary for cards, lists, SEO
    string('image')->nullable();
    json('tags')->nullable();

    $table->boolean('is_featured')->default(false);
    boolean('is_published')->default(true);
    dateTime('published_at')->nullable();
    boolean('noindex')->default(false);
    boolean('nofollow')->default(false);

    $table->unsignedInteger('reading_time')->nullable();
    unsignedInteger('word_count')->nullable();

    $table->string('meta_title')->nullable(); // < 60 chars
    string('meta_description', 500)->nullable(); // < 155 chars
    string('meta_keywords')->nullable();
    string('canonical_url')->nullable();
    json('meta_tags')->nullable();
    json('og_tags')->nullable();
    json('structured_data')->nullable(); // to store dynamic BlogPosting schema or other types, then render directly in Blade views.

    foreignId('blog_category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
    foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
    timestamps();
}

blog_comments {
    id();
    uuid('uuid')->unique();
    text('content');
    boolean('is_visible')->default(true);

    foreignId('user_id')->constrained('users')->cascadeOnDelete();
    foreignId('blog_id')->constrained('blogs')->cascadeOnDelete();
    timestamps();
}

delivery_regions {
    id();
    uuid('uuid')->unique();
    string('name')->unique();
    string('slug')->unique();
    string('country')->default('KE');
    timestamps();
}

delivery_areas {
    id();
    uuid('uuid')->unique();
    string('name')->unique();
    string('slug')->unique();
    decimal('delivery_fee', 10, 2)->default(0.00);
    string('postal_code')->nullable();
    json('coordinates')->nullable();

    foreignId('delivery_region_id')->constrained('delivery_regions')->cascadeOnDelete();
    timestamps();
}

delivery_methods {
    id();
    uuid('uuid')->unique();
    string('name')->unique();
    decimal('base_price', 10, 2);
    unsignedTinyInteger('estimated_days')->nullable();
    timestamps();
}

delivery_area_delivery_methods {
    id();
    uuid('uuid')->unique();
    foreignId('delivery_area_id')->constrained('delivery_areas')->cascadeOnDelete();
    foreignId('delivery_method_id')->constrained('delivery_methods')->cascadeOnDelete();
    decimal('custom_price', 10, 2)->nullable();

    unique(['delivery_area_id', 'delivery_method_id']);
    timestamps();
}

product_categories {
    id();
    uuid('uuid')->unique();
    string('title')->unique();
    string('slug')->unique();
    string('description')->nullable();
    string('image')->nullable();
    timestamps();
}

products {
    id();
    uuid('uuid')->unique();
    string('title')->unique();
    string('slug')->unique();
    string('product_code')->nullable();
    string('sku')->unique();
    string('barcode')->nullable();
    boolean('is_featured')->default(false);
    boolean('is_visible')->default(true);
    decimal('production_cost', 10, 2)->default(0.00);
    decimal('selling_price', 10, 2)->default(0.00);
    decimal('discount_price', 10, 2)->default(0.00)->nullable();
    unsignedTinyInteger('discount_percentage', 10, 2)->default(0)->nullable();
    unsignedSmallInteger('product_measurement')->nullable();
    string('measurement_unit')->nullable();
    boolean('track_inventory')->default(true);
    unsignedSmallInteger('stock_count')->default(0)->nullable();
    unsignedSmallInteger('safety_stock')->default(0)->nullable();
    text('description')->nullable();
    json('attributes')->nullable();
    unsignedSmallInteger('sort_order')->default(0);
    string('meta_title')->nullable();
    string('meta_description', 500)->nullable();
    string('canonical_url')->nullable();
    json('meta_tags')->nullable();
    json('og_tags')->nullable();

    foreignId('product_category_id')->nullable()->constrained('product_categories')->nullOnDelete();
    timestamps();
}

product_images {
    id();
    uuid('uuid')->unique();
    string('image');
    unsignedSmallInteger('sort_order')->default(5);

    foreignId('product_id')->constrained('products');
    timestamps();
}

product_reviews {
    id();
    uuid('uuid')->unique();
    unsignedTinyInteger('rating');
    string('review', 1500);
    string('image')->nullable();
    boolean('is_visible')->default(1);
    unsignedMediumInteger('sort_order')->default(0);

    foreignId('user_id')->constrained('users')->onDelete('cascade');
    foreignId('product_id')->constrained('products')->onDelete('cascade');
    timestamps();
}

product_views {
    id();
    uuid('uuid')->unique();
    foreignId('product_id')->constrained()->onDelete('cascade');
    foreignId('user_id')->nullable()->constrained('users');
    string('ip_address')->nullable();
    string('user_agent')->nullable();
    timestamp('viewed_at');
}

discount_codes {
    unsignedTinyInteger('type');
    decimal('value', 10, 2);
    boolean('is_active')->default(true);
    integer('usage_limit')->nullable();
    timestamp('starts_at')->nullable();
    timestamp('ends_at')->nullable();
    string('applies_to')->nullable();
    json('conditions')->nullable();
}

carts {
    id();
    uuid('uuid')->unique();
    foreignId('user_id')->constrained()->cascadeOnDelete();
    timestamps();
}


cart_items {
    id();
    uuid('uuid')->unique();
    foreignId('cart_id')->constrained()->onDelete('cascade');
    foreignId('product_id')->constrained()->onDelete('cascade');
    foreignId('variant_id')->nullable()->constrained('product_variants');
    unsignedSmallInteger('quantity');
    timestamps();
}

wishlists {
    id();
    uuid('uuid')->unique();
    foreignId('user_id')->constrained()->onDelete('cascade');
    foreignId('product_id')->constrained()->onDelete('cascade');
    timestamps();
}

returns {
    id();
    uuid('uuid')->unique();
    foreignId('sale_id')->constrained()->onDelete('cascade');
    string('reason');
    unsignedTinyInteger('status'); // 0 = Requested, 1 = Approved, 2 = Rejected, 3 = Refunded
    timestamps();
}

sales {
    id();
    uuid('uuid')->unique();
    string('order_number');
    unsignedTInyInteger('order_status')->default(0);
    unsignedTinyInteger('sale_type');
    decimal('production_cost', 10, 2)->default(0.00);
    decimal('total_amount', 10, 2)->default(0.00);
    decimal('amount_paid', 10, 2)->default(0.00);
    unsignedTinyInteger('payment_method')->nullable();
    string('ip_address')->nullable();
    string('user_agent')->nullable();

    foreignId('discount_code_id')->nullable()->constrained('discount_codes')->nullOnDelete();
    foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
    timestamps();
}

sale_items {
    id();
    uuid('uuid')->unique();
    string('title');
    unsignedSmallInteger('quantity')->default(1);
    decimal('production_cost',10,2)->default(0);
    decimal('selling_price',10,2)->default(0);

    foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
    foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
    timestamps();
}

order_deliveries {
    id();
    uuid('uuid')->unique();
    string('name');
    string('email');
    string('phone_number');
    string('address');
    string('additional_information')->nullalbe();
    string('location');
    string('area');
    decimal('shipping_cost');
    unsignedTinyInteger('delivery_status')->default(0);

    foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
    timestamps();
}

payments {
    id();
    uuid('uuid')->unique();
    unsignedTinyInteger('status')->default(0);
    unsignedTinyInteger('payment_method')->nullable();
    unsignedTinyInteger('response_code')->nullable();
    string('response_description')->nullable();
    string('merchant_request_id')->nullable();
    string('checkout_request_id')->nullable();
    string('transaction_reference')->nullable();
    text('customer_message');

    foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
    timestamps();
}
```

# Enums
```
SALES_TYPES = [
    0 = 'Online';
    1 = 'POS';
]

DELIVERY_STATUSES = [
    0 = 'Pending';
    1 = 'Shipped';
    2 = 'Delivered';
]

ORDER_STATUSES = [
    0 = 'Pending';
    1 = 'Delivered';
    2 = 'Cancelled';
]

DISCOUNT_TYPES = [
    0 = 'Amount';
    1 = 'Percentage';
]

PAYMENT_METHODS = [
    0 = 'KCB Mpesa Express';
    1 = 'Cash';
]

PAYMENT_STATUSES = [
    0 = 'Pending';
    1 = 'Paid';
    2 = 'Cancelled';
    3 = 'Failed';
    4 = 'Partially Paid';
]

PAYMENT_RESPONSE_CODES = [
    0 = 'Pending';
    1 = 'Success';
    2 = 'Failed';
]
```


# Potential Improvements
products {
    // Existing fields...
    string('sku')->unique()->nullable(); // Stock Keeping Unit
    string('upc')->nullable(); // Universal Product Code
    string('ean')->nullable(); // European Article Number
    string('isbn')->nullable(); // For books/media
    boolean('is_digital')->default(false); // Digital/physical product
    boolean('is_virtual')->default(false); // Virtual product (services)
    boolean('backorder_allowed')->default(false);
    boolean('manage_stock')->default(true);
    dateTime('available_from')->nullable();
    dateTime('available_to')->nullable();
    unsignedSmallInteger('min_order_qty')->default(1);
    unsignedSmallInteger('max_order_qty')->nullable();
    string('meta_title')->nullable();
    string('meta_description')->nullable();
    string('meta_keywords')->nullable();
    string('condition')->default('new'); // new, refurbished, used
    string('barcode')->nullable();
    string('weight')->nullable();
    string('dimensions')->nullable(); // "LxWxH" format
    string('tax_class')->nullable();
    json('attributes')->nullable(); // For product variations
}
+ string('vendor')->nullable(); // Brands or suppliers
+ boolean('requires_shipping')->default(true);
+ boolean('allow_backorder')->default(false);
+ enum('product_type', ['simple', 'variant', 'bundle'])->default('simple');
+ foreignId('parent_id')->nullable()->constrained('products'); // For variants/bundles
+ integer('weight')->nullable();
+ string('weight_unit')->nullable(); // g, kg, lb
+ string('tags')->nullable(); // For fast search (CSV, or tag relation)

product_tags {
    id();
    string('name')->unique();
}

product_tag_pivot {
    id();
    foreignId('product_id')->constrained('products')->onDelete('cascade');
    foreignId('product_tag_id')->constrained('product_tags')->onDelete('cascade');
}



// New inventory table
inventory {
    id();
    uuid('uuid')->unique();
    string('sku')->unique();
    unsignedInteger('quantity')->default(0);
    unsignedInteger('low_stock_threshold')->default(5);
    boolean('in_stock')->default(true);
    boolean('backorder_allowed')->default(false);
    boolean('stock_managed')->default(true);
    foreignId('product_id')->constrained('products')->cascadeOnDelete();
    timestamps();
}

// New warehouse table
warehouses {
    id();
    uuid('uuid')->unique();
    string('name');
    string('code')->unique();
    string('contact_email');
    string('contact_phone');
    string('address');
    boolean('is_default')->default(false);
    timestamps();
}

// New inventory locations table
inventory_locations {
    id();
    uuid('uuid')->unique();
    string('aisle')->nullable();
    string('rack')->nullable();
    string('shelf')->nullable();
    string('bin')->nullable();
    foreignId('inventory_id')->constrained('inventory')->cascadeOnDelete();
    foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
    timestamps();
}


sales {
    // Existing fields...
    string('order_number')->unique(); // Human-readable order number
    string('customer_note')->nullable();
    string('admin_note')->nullable();
    unsignedTinyInteger('order_status')->default(0);
    string('ip_address')->nullable();
    string('user_agent')->nullable();
    decimal('tax_amount', 10, 2)->default(0.00);
    decimal('discount_amount', 10, 2)->default(0.00);
    decimal('shipping_amount', 10, 2)->default(0.00);
    string('currency')->default('KES');
    string('locale')->default('en_US');
    json('billing_address')->nullable();
    json('shipping_address')->nullable();
    dateTime('fulfilled_at')->nullable();
    dateTime('cancelled_at')->nullable();
}

// New order status history table
order_status_history {
    id();
    uuid('uuid')->unique();
    unsignedTinyInteger('status');
    text('comment')->nullable();
    foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
    foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
    timestamps();
}

// New discount codes table
discount_codes {
    id();
    uuid('uuid')->unique();
    string('code')->unique();
    string('description')->nullable();
    unsignedTinyInteger('type'); // percentage, fixed_amount
    decimal('value', 10, 2);
    decimal('min_order_amount')->nullable();
    unsignedInteger('usage_limit')->nullable();
    unsignedInteger('usage_count')->default(0);
    dateTime('start_date');
    dateTime('end_date');
    boolean('is_active')->default(true);
    boolean('single_use')->default(false);
    json('applicable_categories')->nullable();
    json('applicable_products')->nullable();
    timestamps();
}

// New promotions table
promotions {
    id();
    uuid('uuid')->unique();
    string('name');
    string('description')->nullable();
    dateTime('start_date');
    dateTime('end_date');
    boolean('is_active')->default(true);
    json('conditions')->nullable(); // Rules for promotion
    json('actions')->nullable(); // Discounts/benefits
    timestamps();
}

users {
    // Existing fields...
    date('date_of_birth')->nullable();
    string('gender')->nullable();
    string('company_name')->nullable();
    string('tax_id')->nullable();
    boolean('is_subscribed')->default(false);
    boolean('accepts_marketing')->default(false);
    string('timezone')->nullable();
    string('locale')->default('en_US');
    json('metadata')->nullable();
    softDeletes();
}

// New customer addresses table
customer_addresses {
    id();
    uuid('uuid')->unique();
    string('first_name');
    string('last_name');
    string('company')->nullable();
    string('address1');
    string('address2')->nullable();
    string('city');
    string('state');
    string('postcode');
    string('country');
    string('phone');
    boolean('is_default')->default(false);
    string('address_type'); // billing, shipping
    foreignId('user_id')->constrained('users')->cascadeOnDelete();
    timestamps();
}

product_variants {
    id();
    uuid('uuid')->unique();
    string('sku')->unique();
    decimal('price_adjustment', 10, 2)->default(0.00);
    decimal('weight_adjustment', 10, 2)->default(0.00);
    json('attributes'); // {color: 'red', size: 'XL'}
    foreignId('product_id')->constrained('products')->cascadeOnDelete();
    timestamps();
}

wishlists {
    id();
    uuid('uuid')->unique();
    string('name');
    boolean('is_public')->default(false);
    foreignId('user_id')->constrained('users')->cascadeOnDelete();
    timestamps();
}

wishlist_items {
    id();
    uuid('uuid')->unique();
    foreignId('wishlist_id')->constrained('wishlists')->cascadeOnDelete();
    foreignId('product_id')->constrained('products')->cascadeOnDelete();
    timestamps();
}

product_attributes {
    id();
    uuid('uuid')->unique();
    string('name'); // Color, Size, etc.
    string('slug')->unique();
    string('type'); // select, color, image, etc.
    boolean('is_filterable')->default(false);
    timestamps();
}

product_attribute_values {
    id();
    uuid('uuid')->unique();
    string('value');
    string('color_code')->nullable(); // For color swatches
    string('image')->nullable(); // For image swatches
    unsignedSmallInteger('sort_order')->default(0);
    foreignId('attribute_id')->constrained('product_attributes')->cascadeOnDelete();
    timestamps();
}

product_attribute_mappings {
    id();
    uuid('uuid')->unique();
    foreignId('product_id')->constrained('products')->cascadeOnDelete();
    foreignId('attribute_id')->constrained('product_attributes')->cascadeOnDelete();
    foreignId('attribute_value_id')->constrained('product_attribute_values')->cascadeOnDelete();
    timestamps();
}

product_variants {
    id();
    uuid('uuid')->unique();
    foreignId('product_id')->constrained('products')->onDelete('cascade');
    string('sku')->unique();
    string('barcode')->nullable();
    json('attributes'); // Example: {"size": "M", "color": "Red"}
    decimal('price', 10, 2);
    decimal('compare_at_price', 10, 2)->nullable();
    integer('stock')->default(0);
    boolean('is_default')->default(false);
    timestamps();
}

product_media {
    id();
    foreignId('product_id')->constrained('products')->onDelete('cascade');
    enum('type', ['image', 'video'])->default('image');
    string('url');
    unsignedSmallInteger('sort_order')->default(0);
    timestamps();
}

collections {
    id();
    string('title')->unique();
    string('slug')->index();
    text('description')->nullable();
    boolean('is_visible')->default(true);
    timestamps();
}

collection_product {
    id();
    foreignId('collection_id')->constrained('collections')->onDelete('cascade');
    foreignId('product_id')->constrained('products')->onDelete('cascade');
}

user_activities {
    id();
    foreignId('user_id')->constrained('users')->onDelete('cascade');
    string('action'); // e.g., Viewed Product, Added to Cart
    string('ip_address')->nullable();
    string('user_agent')->nullable();
    json('metadata')->nullable();
    timestamp('performed_at')->default(now());
}

pricing_rules {
    id();
    foreignId('product_id')->constrained()->onDelete('cascade');
    decimal('price', 10, 2);
    string('customer_group')->nullable(); // e.g., VIP, Wholesaler
    string('country_code')->nullable();
    string('region')->nullable();
    timestamps();
}

warehouses {
    id();
    string('name');
    string('location')->nullable();
    timestamps();
}

warehouse_inventory {
    id();
    foreignId('warehouse_id')->constrained();
    foreignId('product_id')->constrained();
    foreignId('variant_id')->nullable()->constrained('product_variants');
    integer('stock')->default(0);
    timestamps();
}

+ string('schema_type')->nullable(); // For structured data: Product, BlogPosting, etc.
+ json('structured_data')->nullable(); // JSON-LD for advanced SEO
