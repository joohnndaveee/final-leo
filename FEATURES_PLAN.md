# Marketplace System — Feature Plan & Requirements (English)

## 1) Goal & Scope
Build a marketplace with three roles: **Admin**, **Seller**, **Customer**. The system includes:
- Seller onboarding with **registration fee** (GCash) + **admin approval**
- Seller **monthly subscription fee** (GCash) with due-date tracking
- Products, orders, feedback, notifications
- In-app messaging (role-based rules)
- Admin dashboard metrics and configurable branding
- Privacy controls (hide sensitive seller financial totals from admin)

---

## 2) Roles & Permissions (High-Level)

### Admin
- Manages users (customers) and sellers
- Approves/rejects seller onboarding and subscription payments
- Views payment histories (registration + subscription) and dashboard totals
- Can message **sellers only** (not customers)
- Can view anonymous/contact messages
- Can configure branding (logo/background)
- Must **NOT** see seller “total sales/wallet balance” (privacy)

### Seller
- Registers as seller with required form + registration fee proof (GCash)
- Pays monthly subscription fee (separate from registration fee)
- Manages store profile + products + order statuses
- Can message **admin and customers**
- Receives notifications for low stock, upcoming subscription due date, and completed orders
- Views comments/feedback for their products
- Has a seller dashboard with total sales (based on order totals) + product count

### Customer
- Registers/logs in with profile details (name, username, contact, address, etc.)
- Browses/searches products, favorites, cart
- Checkout with **Cash on Delivery** (auto-fill name/address)
- Can cancel order within 30 minutes after placing
- Can mark order as received/complete
- Tracks order status and order history

---

## 3) Core Workflows

### 3.1 Seller Registration (with Registration Fee)
**Seller submits:**
- Store owner name
- Store name
- Email (Gmail or any email)
- Password
- Cellphone number
- Payment method: GCash
- GCash number used to pay
- GCash reference number
- Upload proof (screenshot/image)
- (Optional but recommended) date/time of payment

**System behavior:**
- Seller account starts in `pending_registration_approval`
- Admin reviews details + proof
- Admin sets registration fee payment status:
  - `pending` (submitted but not reviewed)
  - `approved` (verified paid)
  - `rejected` (invalid/insufficient proof)

**Important rule:**
- If **rejected**, the seller is blocked/removed from continuing.
  - Recommended implementation: **soft-delete** or **blocked status** (safer than hard-delete for audit).

### 3.2 Monthly Subscription (GCash, Separate)
- Monthly subscription fee: **500**
- Same GCash proof process:
  - reference number + screenshot upload
- Admin verifies and sets status:
  - `pending`, `approved`, `rejected`
- Track subscription:
  - next due date
  - grace period policy (recommended below)

**Recommendation (policy):**
- If subscription is overdue past grace period, seller becomes `inactive`:
  - cannot add products
  - cannot accept new orders
  - can still view history and pay to reactivate

### 3.3 Order Lifecycle (Seller-managed statuses)
Recommended statuses:
- `processing`
- `shipped`
- `delivered`
- `completed` (set when customer confirms received)

Customer action:
- After receiving product, customer clicks **“Order Received / Complete”**
- System triggers seller notification: “{CustomerName} marked order as received.”

Cancellation rule:
- Customer can cancel **within 30 minutes** after order placed
- After 30 minutes, cancellation not allowed (unless you add admin support later)

---

## 4) Feature Requirements (By Role)

### 4.1 Admin Requirements

**A) User/Seller management**
- Remove/disable customers
- Seller rejection flow:
  - when seller’s registration status is rejected, seller is blocked/removed from seller access

**B) Payment verification & history**
- Admin can mark seller registration fee as `approved/pending/rejected`
- Admin can mark subscription fee as `approved/pending/rejected`
- Payment history must exist for:
  - registration fee payments
  - subscription payments
- Each payment record should store:
  - seller id
  - type: `registration` or `subscription`
  - amount
  - reference number
  - proof image
  - submitted timestamp
  - reviewed by admin id + reviewed timestamp
  - status

**C) Messaging**
- Admin can **reply/chat with sellers**
- Admin **cannot** chat with customers
- Admin can view messages submitted via “Contact Us” from:
  - anonymous (no account)
  - logged-in users (optional)

**D) Privacy controls**
- Admin must NOT see:
  - seller wallet balance
  - seller total sales money amount (recommended: show only operational stats if needed, like number of orders)

**E) Dashboard & branding**
Admin dashboard shows:
- total registered sellers
- total customers/users
- total registration fees collected (separate)
- total subscription fees collected (separate)

On click of totals:
- show transaction history filtered to `approved` payments

Admin can configure:
- platform logo
- background image/theme

---

### 4.2 Seller Requirements

**A) Seller onboarding & profile**
- Seller registration form includes payment proof fields
- Seller can update store profile (edit all store information)

**B) Subscription**
- Pay monthly subscription via GCash proof submission
- Seller sees subscription status and due date

**C) Product management**
- Add/update/delete products
- Product image: **1 image only**
- Stock updates supported

**D) Notifications**
- Low stock notification when stock < 10
- Subscription due soon notification (define window, recommended: 7 days before)
- Order completion notification when customer marks received/completed

**E) Messaging**
- Seller can chat with admin
- Seller can chat with customers (reply/receive)

**F) Orders & feedback**
- Order management: processing/shipped/delivered
- View comments/feedback per product

**G) Seller dashboard**
- Total sales (based on product orders total)
- Total products added

---

### 4.3 Customer Requirements

**A) Account**
- Register and login
- Registration includes: name, username, contact number, address, etc.

**B) Shopping**
- Browse and search products
- Add to cart
- Add to favorites

**C) Checkout**
- Cash on delivery only
- Auto-fill name and address in checkout form

**D) Orders**
- Track order status (processing/shipped/delivered)
- Cancel within 30 minutes after order placed
- Mark order received/complete
- View order history (date/time, items, status)

---

## 5) Suggested Data Model (Recommended)
Minimum entities:
- `users` (customer accounts)
- `sellers` (seller profile + status fields)
- `products` (seller_id, name, price, stock, image_url, etc.)
- `orders` (customer_id, seller_id, totals, status, timestamps)
- `order_items`
- `payments` (seller_id, type, amount, reference_no, proof_url, status, reviewed_by, timestamps)
- `messages` (threads + messages; role-based rules)
- `contact_messages` (anonymous/contact-us submissions)
- `notifications` (recipient_type/id, type, message, read_at, created_at)
- `settings` (logo/background + other admin configs)
- `feedback` (product_id, customer_id, rating/comment, timestamps)

---

## 6) Rules & Edge Cases (Decisions To Make)
- What happens to existing orders if seller becomes inactive (subscription overdue)?
- Can a rejected seller re-apply, or must admin manually restore?
- Should customers be able to message sellers before ordering?
- When exactly is `delivered` set (seller action) vs `completed` (customer action)?
- If customer never clicks “received”, do you auto-complete after X days?

Recommended defaults:
- Seller can’t accept new orders when inactive; existing orders continue.
- Rejected seller can re-apply only by creating a new application (keeps audit history).
- Delivered = seller sets; Completed = customer sets (or auto after 7 days delivered).

---

## 7) Implementation Plan (Milestones)

### Milestone 1 — Roles, Auth, Permissions
- Add role-based access control (Admin/Seller/Customer)
- Enforce messaging restrictions (admin↔seller only)

### Milestone 2 — Seller Onboarding + Registration Fee Verification
- Seller registration form (with proof upload + reference number)
- Admin review screen + approve/reject
- Seller status enforcement

### Milestone 3 — Subscription Module
- Subscription payment submission
- Admin verification
- Due date tracking + inactive status rules

### Milestone 4 — Products + Orders
- Seller product CRUD (1 image)
- Customer browsing/search/cart/favorites
- COD checkout with address autofill
- Order lifecycle + 30-minute cancel rule

### Milestone 5 — Notifications + Feedback
- Low stock notifications
- Due-date reminders
- Completion notifications
- Product feedback/comments view

### Milestone 6 — Admin Dashboard + Payment Analytics + History
- Totals: registration vs subscription (separate)
- Click-through approved transaction histories
- Hide seller total sales/wallet from admin

### Milestone 7 — Admin Branding Settings
- Logo/background upload + settings page
- Apply branding across UI

---

## 8) Acceptance Checklist (Quick)
- Admin cannot see seller wallet/total sales amount
- Admin can approve/reject registration and subscription payments
- Payment histories exist for both fee types
- Admin can chat sellers, not customers
- Anonymous contact messages visible to admin
- Seller can chat admin + customers; customer can complete orders
- Customer cancel within 30 minutes works reliably
- Dashboard totals show correct separate sums + history drilldown



Naa sab diay ko tuod e dugang sa amoa system awa

Admin

-Generate sales and system reports (monthly/yearly)
Export payment history (PDF/Excel)
-Manage categories
-Monitor reported products or users


Seller

	•	View sales analytics (daily/monthly)
	•	Download sales report
	•	Auto-disable product if out of stock
	•	Promotional discounts
	•	Voucher creation
	•	Featured product option


Customer
	•	Receive notifications about order updates
	•	Receive confirmation SMS or email
	•	Track delivery timeline
	•	Report seller or product
	•	Edit profile information
	•	Change password