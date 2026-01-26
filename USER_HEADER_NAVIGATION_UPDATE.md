# User Header Navigation Update

## Overview
Updated the user header navigation in the master layout to match the functionality from the old `user_header.php` file, including dynamic cart/wishlist counts and proper authentication checks.

## What Was Implemented

### 1. **Cart and Wishlist Models**

Created two new models to handle cart and wishlist data:

#### `app/Models/Cart.php`
- Table: `cart`
- No timestamps
- Relationships: belongs to User and Product
- Fields: `user_id`, `pid`, `name`, `price`, `quantity`, `image`

#### `app/Models/Wishlist.php`
- Table: `wishlist`
- No timestamps
- Relationships: belongs to User and Product
- Fields: `user_id`, `pid`, `name`, `price`, `image`

### 2. **Database Migrations**

Created migrations for cart and wishlist tables:

#### `database/migrations/2026_01_27_000001_create_cart_table.php`
```sql
- id (bigint, primary key)
- user_id (bigint)
- pid (bigint) - product id
- name (varchar 100)
- price (integer)
- quantity (integer)
- image (varchar 100)
```

#### `database/migrations/2026_01_27_000002_create_wishlist_table.php`
```sql
- id (bigint, primary key)
- user_id (bigint)
- pid (bigint) - product id
- name (varchar 255)
- price (decimal 10,2)
- image (varchar 255)
```

### 3. **View Composer for Global Data Sharing**

#### `app/Http/ViewComposers/CartWishlistComposer.php`
- Automatically calculates cart and wishlist counts for authenticated users
- Shares `$cartCount` and `$wishlistCount` variables with ALL views
- Returns 0 for guest users (not logged in)
- Query executed once per page load, shared globally

**How it works:**
```php
if (Auth::check()) {
    $cartCount = Cart::where('user_id', Auth::id())->count();
    $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
} else {
    $cartCount = 0;
    $wishlistCount = 0;
}
```

### 4. **AppServiceProvider Update**

#### `app/Providers/AppServiceProvider.php`
- Registered the `CartWishlistComposer` to share data with all views
- Uses `View::composer('*', CartWishlistComposer::class)`
- Runs automatically on every page load

### 5. **Master Layout Update**

#### `resources/views/layouts/app.blade.php`

**Auth Check Implementation:**
- ✅ Shows user name when logged in: `{{ Auth::user()->name }}`
- ✅ Shows "Update Profile" link for authenticated users
- ✅ Shows logout button (POST form) for authenticated users
- ✅ Shows "Login" and "Register" buttons for guests

**Cart Count Display:**
- ✅ Shows dynamic cart count: `({{ $cartCount }})`
- ✅ Shows dynamic wishlist count: `({{ $wishlistCount }})`
- ✅ Automatically updates based on database queries
- ✅ Shows 0 for guest users

**Logout Button Styling:**
- ✅ Uses POST request (secure, follows Laravel standards)
- ✅ Includes CSRF token protection: `@csrf`
- ✅ Styled as `delete-btn` class (red button)
- ✅ Confirmation dialog: `onclick="return confirm(...)"`
- ✅ Full width button with proper cursor styling

### 6. **User Model Relationships**

#### `app/Models/User.php`
Added relationships for better data access:
- `cartItems()` - hasMany relationship with Cart
- `wishlistItems()` - hasMany relationship with Wishlist
- `orders()` - hasMany relationship with Order
- `messages()` - hasMany relationship with Message

## Key Features

✅ **Dynamic Cart Count** - Shows real-time count from database
✅ **Dynamic Wishlist Count** - Shows real-time count from database
✅ **Auth Check** - Properly distinguishes between logged-in and guest users
✅ **Secure Logout** - POST request with CSRF protection
✅ **Guest View** - Shows Login/Register buttons when not authenticated
✅ **User Profile** - Shows name and profile options when authenticated
✅ **Global Data Sharing** - View Composer shares counts with all views
✅ **Performance** - Counts cached per request, not queried multiple times

## How It Works

### Flow Diagram:
```
1. User visits any page
   ↓
2. AppServiceProvider loads CartWishlistComposer
   ↓
3. Composer checks if user is authenticated
   ↓
4. If authenticated: Query cart & wishlist counts from database
   If guest: Set counts to 0
   ↓
5. Share $cartCount and $wishlistCount with all views
   ↓
6. Views automatically have access to these variables
```

### Header Display Logic:
```
IF user is logged in (Auth::check()):
    - Show: User Name
    - Show: Update Profile button
    - Show: Logout button (POST form)
    - Show: Cart count (from database)
    - Show: Wishlist count (from database)
ELSE:
    - Show: "Please login or register first!"
    - Show: Register button
    - Show: Login button
    - Show: Cart count (0)
    - Show: Wishlist count (0)
```

## Testing

### Step 1: Run Migrations
```bash
php artisan migrate
```

This will create the `cart` and `wishlist` tables.

### Step 2: Test as Guest
1. Visit any page while logged out
2. Verify cart shows: (0)
3. Verify wishlist shows: (0)
4. Click user icon
5. Verify "Please login or register first!" message
6. Verify "Register" and "Login" buttons appear

### Step 3: Test as Authenticated User
1. Login with test credentials
2. Click user icon
3. Verify your name appears
4. Verify "Update Profile" button appears
5. Verify "Logout" button appears as a red button

### Step 4: Test Cart Count
1. Manually insert a cart item:
```sql
INSERT INTO cart (user_id, pid, name, price, quantity, image) 
VALUES (1, 1, 'Test Product', 100, 1, 'test.jpg');
```
2. Refresh the page
3. Verify cart shows: (1)

### Step 5: Test Logout
1. Click the "Logout" button
2. Confirm the dialog
3. Verify you're redirected to login page
4. Verify you're logged out (cart shows 0 again)

## Security Features

### 1. **CSRF Protection**
The logout form includes `@csrf` directive:
```blade
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="delete-btn">Logout</button>
</form>
```

### 2. **POST Request for Logout**
- Prevents CSRF attacks
- Can't be triggered via GET request
- Follows Laravel best practices
- Matches modern security standards

### 3. **Auth Guard Separation**
- Users use default `web` guard
- Admins use `admin` guard
- Prevents cross-authentication

## Performance Considerations

### Query Optimization
- Counts are queried ONCE per page load via View Composer
- Results are shared with all views
- No duplicate queries
- Uses `count()` which is optimized by database

### Caching Opportunity (Future Enhancement)
You can add caching to improve performance:
```php
$cartCount = Cache::remember("cart_count_{$userId}", 60, function() use ($userId) {
    return Cart::where('user_id', $userId)->count();
});
```

## Files Created/Modified

### Created:
- `database/migrations/2026_01_27_000001_create_cart_table.php`
- `database/migrations/2026_01_27_000002_create_wishlist_table.php`
- `app/Models/Cart.php`
- `app/Models/Wishlist.php`
- `app/Http/ViewComposers/CartWishlistComposer.php`
- `USER_HEADER_NAVIGATION_UPDATE.md` (this file)

### Modified:
- `app/Providers/AppServiceProvider.php` - Registered View Composer
- `resources/views/layouts/app.blade.php` - Updated header navigation
- `app/Models/User.php` - Added cart/wishlist relationships

## Troubleshooting

### Issue: Cart count shows 0 even with items
**Solution:** 
- Check if user is logged in
- Verify cart table has records with correct user_id
- Run `php artisan cache:clear`

### Issue: "Class CartWishlistComposer not found"
**Solution:**
- Run `composer dump-autoload`
- Check file is in correct location: `app/Http/ViewComposers/CartWishlistComposer.php`

### Issue: Logout button not styled correctly
**Solution:**
- Clear browser cache
- Verify `style.css` is loaded
- Check for CSS conflicts

### Issue: Variables not available in views
**Solution:**
- Verify View Composer is registered in AppServiceProvider
- Check composer class namespace is correct
- Try `php artisan view:clear`

## Next Steps

1. **Implement Cart Functionality:**
   - Add to cart feature
   - Update cart quantities
   - Remove from cart
   - View cart page

2. **Implement Wishlist Functionality:**
   - Add to wishlist feature
   - Remove from wishlist
   - Move from wishlist to cart
   - View wishlist page

3. **Add Profile Update:**
   - Create profile update controller
   - Create profile update view
   - Implement password change

4. **Performance Optimization:**
   - Add Redis caching for counts
   - Implement cart count update via AJAX
   - Add real-time updates with WebSockets (optional)

## Summary

The user header navigation now:
- ✅ Dynamically shows cart and wishlist counts
- ✅ Properly distinguishes between authenticated and guest users
- ✅ Uses secure POST logout with CSRF protection
- ✅ Matches the old system's functionality
- ✅ Follows Laravel best practices
- ✅ Optimized with View Composer for global data sharing

All data is shared globally via the View Composer, making it available in every view without additional queries!
