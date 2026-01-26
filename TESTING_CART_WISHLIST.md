# Quick Testing Guide: Cart & Wishlist Counts

## Setup

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Clear Caches (if needed)
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### 3. Seed Test User
```bash
php artisan db:seed --class=UserSeeder
```

## Test Scenarios

### Scenario 1: Guest User (Not Logged In)

**Expected Behavior:**
- Cart count: **(0)**
- Wishlist count: **(0)**
- User dropdown shows: "Please login or register first!"
- Shows "Register" and "Login" buttons

**Test Steps:**
1. Open browser in incognito mode
2. Visit `http://localhost:8000`
3. Verify counts show (0)
4. Click user icon
5. Verify guest message appears

---

### Scenario 2: Authenticated User with Empty Cart

**Expected Behavior:**
- Cart count: **(0)**
- Wishlist count: **(0)**
- User dropdown shows: User's name
- Shows "Update Profile" and "Logout" buttons

**Test Steps:**
1. Login with: `user@test.com` / `password123`
2. Verify counts show (0)
3. Click user icon
4. Verify name appears: "Test User"
5. Verify buttons appear

---

### Scenario 3: Authenticated User with Cart Items

**Expected Behavior:**
- Cart count: **(number of items)**
- Wishlist count: **(0 or number of items)**

**Test Steps:**

#### Add Cart Item Manually:
```sql
-- Using phpMyAdmin or MySQL Workbench:
INSERT INTO cart (user_id, pid, name, price, quantity, image) 
VALUES (1, 1, 'Test Product', 999, 2, 'test.jpg');

-- Add another item:
INSERT INTO cart (user_id, pid, name, price, quantity, image) 
VALUES (1, 2, 'Another Product', 1500, 1, 'test2.jpg');
```

#### Verify:
1. Login as user ID 1
2. Refresh page
3. Cart should show: **(2)** 
   *(because there are 2 records, not quantities)*

---

### Scenario 4: Authenticated User with Wishlist Items

**Expected Behavior:**
- Wishlist count: **(number of items)**

**Test Steps:**

#### Add Wishlist Item Manually:
```sql
INSERT INTO wishlist (user_id, pid, name, price, image) 
VALUES (1, 1, 'Wishlist Item', 1200.00, 'wish.jpg');

INSERT INTO wishlist (user_id, pid, name, price, image) 
VALUES (1, 3, 'Another Wish', 2500.00, 'wish2.jpg');
```

#### Verify:
1. Login as user ID 1
2. Refresh page
3. Wishlist should show: **(2)**

---

### Scenario 5: Logout Flow

**Expected Behavior:**
- User is logged out
- Redirected to login page
- Counts reset to (0)

**Test Steps:**
1. Login as any user
2. Verify counts show proper numbers
3. Click user icon
4. Click "Logout" button
5. Confirm dialog
6. Verify redirect to login page
7. Verify counts now show (0)

---

## SQL Queries for Testing

### Check Current User ID
```sql
SELECT * FROM users;
```

### Check Cart Items for User
```sql
SELECT * FROM cart WHERE user_id = 1;
```

### Count Cart Items
```sql
SELECT COUNT(*) FROM cart WHERE user_id = 1;
```

### Check Wishlist Items for User
```sql
SELECT * FROM wishlist WHERE user_id = 1;
```

### Delete All Cart Items
```sql
DELETE FROM cart WHERE user_id = 1;
```

### Delete All Wishlist Items
```sql
DELETE FROM wishlist WHERE user_id = 1;
```

---

## Common Issues & Solutions

### Issue: Counts always show 0
**Possible Causes:**
- Not logged in
- Wrong user_id in database
- Tables don't have data

**Solution:**
```sql
-- Verify your user ID:
SELECT id, name, email FROM users WHERE email = 'user@test.com';

-- Insert test data with correct user_id:
INSERT INTO cart (user_id, pid, name, price, quantity, image) 
VALUES (YOUR_USER_ID, 1, 'Test', 100, 1, 'test.jpg');
```

---

### Issue: "Table 'cart' doesn't exist"
**Solution:**
```bash
php artisan migrate
```

---

### Issue: Counts don't update after adding items
**Solution:**
```bash
# Clear all caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Refresh browser (Ctrl+F5)
```

---

### Issue: Logout button not working
**Check:**
1. CSRF token is present
2. Form method is POST
3. Route is defined: `POST /logout`

**Solution:**
```bash
php artisan route:list | grep logout
```

---

## Quick Database Setup

### Create Sample Data
```sql
-- User (already created by seeder)
-- Email: user@test.com
-- Password: password123
-- User ID: Check with SELECT * FROM users;

-- Cart Items
INSERT INTO cart (user_id, pid, name, price, quantity, image) VALUES
(1, 62, 'B-BAGS', 6999, 1, 'bmix.jpg'),
(1, 63, 'B-TROUSERS', 2500, 2, 't1.jpg'),
(1, 64, 'B-SWIM WEAR', 2999, 1, 's1.jpg');

-- Wishlist Items
INSERT INTO wishlist (user_id, pid, name, price, image) VALUES
(1, 62, 'B-BAGS', 6999.00, 'bmix.jpg'),
(1, 64, 'B-SWIM WEAR', 2999.00, 's1.jpg');
```

After inserting, you should see:
- Cart: **(3)**
- Wishlist: **(2)**

---

## Expected Results Summary

| Scenario | Cart Count | Wishlist Count | Profile Dropdown |
|----------|-----------|----------------|------------------|
| Guest (not logged in) | (0) | (0) | "Please login or register first!" + Login/Register buttons |
| Logged in, no items | (0) | (0) | User name + "Update Profile" + "Logout" |
| Logged in, 3 cart items | (3) | (0) | User name + Profile options |
| Logged in, 2 wishlist items | (0) | (2) | User name + Profile options |
| Logged in, both | (3) | (2) | User name + Profile options |

---

## Verification Checklist

- [ ] Cart count displays correctly for guest users (0)
- [ ] Wishlist count displays correctly for guest users (0)
- [ ] Cart count displays correct number for logged-in users
- [ ] Wishlist count displays correct number for logged-in users
- [ ] Profile dropdown shows user name when logged in
- [ ] Profile dropdown shows login/register when not logged in
- [ ] Logout button appears for logged-in users
- [ ] Logout button is styled correctly (red delete-btn)
- [ ] Logout shows confirmation dialog
- [ ] Logout successfully logs out user
- [ ] Counts reset to 0 after logout
- [ ] Counts update when switching between users

---

## Pro Tips

1. **Use Browser DevTools**: Check Network tab to see if queries are being made
2. **Check Laravel Logs**: `storage/logs/laravel.log` for errors
3. **Use Tinker**: Test View Composer manually
   ```bash
   php artisan tinker
   >>> Auth::id()
   >>> App\Models\Cart::where('user_id', 1)->count()
   ```
4. **Database IDE**: Use phpMyAdmin or MySQL Workbench for easy data manipulation
5. **Multiple Users**: Test with different users to verify isolation

---

## Success Criteria

✅ All counts display correctly for all scenarios
✅ Auth check properly distinguishes logged-in vs guest
✅ Logout works securely with POST request
✅ No errors in browser console
✅ No errors in Laravel logs
✅ Counts update in real-time after page refresh
✅ Different users see their own counts

---

## Need Help?

If counts still don't work:
1. Check `app/Providers/AppServiceProvider.php` - View Composer registered?
2. Check `app/Http/ViewComposers/CartWishlistComposer.php` - File exists?
3. Run `composer dump-autoload`
4. Clear all caches
5. Check database connection in `.env`
6. Verify tables exist: `php artisan migrate:status`
