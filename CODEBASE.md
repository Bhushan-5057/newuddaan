# New Udaan — codebase flow

This document describes how the existing Laravel application is organized and how each major flow works. It covers the public website, the member dashboard, the admin panel, the super admin panel, and the franchise panel.

The application is one Laravel 10 project. Those areas are not separate apps. They share one database, one route file, and one set of models. Screens and controllers are split by folder.

## 1. How a request is handled

1. The browser requests a URL.
2. `public/index.php` boots Laravel. This file does not contain page logic.
3. `routes/web.php` matches the URL to a controller method and applies middleware (login, role, permission, site status).
4. The controller in `app/Http/Controllers` reads or writes models in `app/Models`.
5. The controller returns a Blade view from `resources/views`.
6. `app/Providers/AppServiceProvider.php` injects shared data into those views before they render.

### Folder map

| Area | URL prefix | Controller location | Screens |
|---|---|---|---|
| Public website | `/`, `/shop`, `/product/...` | `app/Http/Controllers/Main` | `resources/views/main` |
| Member login | `/login`, `/register` | `app/Http/Controllers/Auth` and `AuthController` | `resources/views/auth` |
| Member dashboard | `/dashboard/user` and `/user/...` | Classes in `app/Http/Controllers` (`User*`, `ProfileController`, `DashboardController`, and others) | `resources/views/user` |
| Admin | `/admin/...` | `app/Http/Controllers/Admin` plus classes named `Admin*` | `resources/views/admin` |
| Super admin | `/superadmin/...` | Classes named `SuperAdmin*` and shared controllers | `resources/views/superadmin` |
| Franchise | `/franchise/...` | `app/Http/Controllers/Franchise` | `resources/views/franchise` |

`DashboardController` is one class with separate methods for the member, admin, super admin, and franchise dashboards. Each method returns a different view.

### Logins

Each panel has its own session guard. A member login does not authorize admin pages.

| Who | Login URL | Guard | After login |
|---|---|---|---|
| Member | `/login` | `web` | `/dashboard/user` |
| Admin | `/admin/login` | `admin` | Admin dashboard, after an OTP step when that path is used |
| Super admin | `/superadmin/login` | `superadmin` | `/dashboard/superadmin` |
| Franchise | `/franchise/login` | `franchise` | Franchise dashboard |

Logout routes are separate: `LogoutController` has `userLogout`, `adminLogout`, `superAdminLogout`, and `franchiseLogout`.

### Permissions

`User::hasPermission($feature, $permission)` checks `role_permissions` for the member or admin role. Sub-admins are checked first against `user_permissions`.

Many routes also use the `checkUserPermission` middleware, for example `checkUserPermission:add fund,can_view`. If the role does not have that flag, the menu link is omitted and the route is refused. Feature names in the menu must match the stored permission text.

### Data added to every page

`AppServiceProvider` does this on each request:

- Shares `RegistrationType` from `advanced_info` where `label` is `reg_type`. Menus use this to decide whether binary tree and left/right controls appear. The value must be exactly `binary`.
- Shares `incometype`: wallet types whose `wallet_type` is `income`, which are enabled for that registration type, and which are active.
- Shares active shop categories (`categories.status = 1`) with every view.
- For a logged-in member, shares `kycState` (`missing`, `pending`, or approved) so the menu can show KYC status. The personal KYC section is ignored because it stays pending for everyone.
- For the admin layout, counts unseen pending withdrawals, principal withdrawals, fund deposits, pending product orders, franchise orders, franchise fund requests, and submitted KYC sections.
- For the super admin layout, counts unseen pending withdrawals and fund deposits.

### Settings tables

Behavior is driven by database rows, not by hard-coded company values.

- `company_info` — company name, address, GSTIN, mobile, email, logo filename, currency, and flags such as `binary_status`. The logo file itself is stored in `public/uploads`. The `value` column stores the filename.
- `advanced_info` — `reg_type`, `min_withdrawal_limit`, `max_withdrawal_limit`, `wd_charges` (percent or flat), and similar limits.

Helpers `company_info()` and `AdvanceInfo` read these rows from controllers and Blade templates.

### Files and media

Uploaded images are not stored as binary in the database and are not sent to Amazon S3. S3 is listed in `config/filesystems.php` but no application code uses that disk.

The database stores a relative path or a filename. The file sits on disk:

| What | Folder | Database |
|---|---|---|
| Product photo | `storage/app/public/products` | `products.imgs` |
| Product gallery | `storage/app/public/products/gallery` | `products.more_imgs` |
| Category image and icon | `storage/app/public/categories/...` | `categories.image`, `categories.icon` |
| Profile photo | `storage/app/public/profile_images` | `users.profile_image` |
| KYC photos | `storage/app/public/kyc/...` | path columns on `kyc_details` |
| Payment slip | `storage/app/public/payment_slips` | fund transaction |
| Franchise payment screenshot | `storage/app/public/fund_proofs` | franchise order image |
| Notice image | `storage/app/public/uploads/news` | notice `img_path` |
| Payment-method image | `storage/app/public/payment_methods` | payment method `image` |
| Company logo and company files | `public/uploads` | `company_info.value` (filename only) |

Public files under `storage/app/public` are served as `/storage/...` after `php artisan storage:link`. Company files are served as `/uploads/...`.

ID-card PDFs turn a photo into a base64 string in memory so dompdf can embed it. That string is not saved.

### Main tables

- `users` — account, `sponsor_id` (sponsor username), `u_sponsor` (sponsor id), and for binary placement `parent_id`, `position` (`1` left, `2` right), `left_id`, `right_id`, `default_side`, `status` (`0` inactive, `1` active).
- `user_wallets` — one row per member. Balances are columns `c1` through `c31`. `c1` is the wallet used for withdrawals.
- `wallet_types` — names and flags for income wallets and team wallets.
- `transactions` — every wallet movement: `tx_type`, `debit_credit`, `amount`, `tx_charge`, `paidAmount`, `open_wallet`, `closing_wallet`, `status` (`0` pending, `1` approved, `2` rejected), `bank_details` (JSON).
- `orders` — shop purchases (`tx_type = product`) and plan topups (`topup`, `retopup`).
- `order_items` — product lines for a shop order (`order_id`, `product_id`, `qty`, `price`, `bv`).
- `kyc_details` — identity, PAN, and bank sections, each with its own status.
- `products`, `categories`, `sub_categories` — the shop catalog.
- `carts`, `wishlists`, `delivery_contacts` — shop session data for a member.
- `role_permissions`, `user_permissions` — menu and route access.
- `franchise_orders`, `transaction_franchises`, `franchise_users` — franchise panel.

## 2. Public website

Controllers: `app/Http/Controllers/Main/HomeController.php`, `ContactController.php`, `PaymentController.php`.

Screens: `resources/views/main`.

The home page, shop, product, category, search, about, and contact routes use `checkSiteStatus`. When the site is marked down, that middleware shows the maintenance view instead of the shop.

### Browse

1. `GET /` loads active categories plus latest, popular, and banner products. Banner products are the latest products in the category whose slug is `banner`.
2. `GET /shop` lists products.
3. `GET /category/{slug}` and `GET /subcategory/{slug}` filter that list.
4. `GET /product/{slug}` shows one product and related products.
5. `GET /search` and `/search-suggestions` search the catalog.
6. `GET /about` renders the about page.
7. `GET /contact` shows the form. `POST /contact/store` saves the message.

Product images on these pages are the stored paths described above.

### Cart and wishlist

1. `POST /product/cart` adds a product to `carts`.
2. `POST /wishlist/add` writes `wishlists`.
3. Counts for the header come from `GET /get-counts`.
4. Viewing the cart, changing quantity, removing a line, checkout, and wishlist management require a member login (`auth:web`, role `user`, and `checkSiteStatus`).

### Checkout and shop order

1. `GET /checkout` loads the cart, subtotal, BV, PV, saved delivery contacts, and wallet balance.
2. `POST /address/store` saves a delivery address. `DELETE /address/delete/{id}` removes one.
3. `POST /place-order` runs `HomeController::placeOrder`:
   - Walks the cart.
   - If the member qualifies for buy-one-get-one, each paid unit adds a free unit. The member is charged only for paid units. BV and PV count the full quantity, including free units.
   - Creates an `orders` row: `tx_type = product`, `tx_id` like `ORD-` plus random characters, `u_code` = member id, amounts, BV, PV, address, contact string, notes, `status = 0` (pending), `payment_status = 0`, `payment_method = bank`.
   - Creates one `order_items` row per line with product id, total quantity, unit price, and BV.
4. `GET /order/invoice/{id}` looks up the order by `tx_id` and renders the HTML page `resources/views/main/product/invoice.blade.php`. This is not the GST PDF. The member order-history button does not call this route. See the invoice section below.

### Cashfree

`POST /payment/initiate`, `GET /payment/callback`, and `POST /payment/webhook` go through `PaymentController` and `app/Helpers/CashfreeHelper.php`.

The helper posts to Cashfree sandbox or production based on `CASHFREE_ENVIRONMENT`, using `CASHFREE_APP_ID` and `CASHFREE_SECRET_KEY`. Those values are still placeholders in `.env`, so this payment path cannot complete until real keys are set. The bank-transfer path in `placeOrder` does not depend on Cashfree.

## 3. Registration and login

### Member registration

`GET /register` shows the form (`AuthController::showRegisterForm`). `POST /register` runs `AuthController::register`.

Live checks used by the form:

- `GET /check-username`
- `GET /check-pan`
- `GET /check-aadhaar`
- `GET /check-unique-name`
- `GET /auth/get-sponsor-name` looks up the sponsor’s name from the typed sponsor id

Registration itself runs in one database transaction:

1. Resolve the sponsor. If a sponsor is required and missing, registration stops.
2. Generate a username and hash the password.
3. Build the user: name parts, gender, address fields, mobile, email, PAN, Aadhaar, unique name, `role = user`, `status = 0` (inactive), `sponsor_id` = sponsor username, `u_sponsor` = sponsor id, `is_free_id = 0`.
4. Binary placement, when the registration code takes the binary branch:
   - A sponsor is required.
   - If the link includes a parent username and `side`, position is `2` for right and `1` for left. If that parent already has someone in that slot, registration fails with “position already occupied”.
   - If there is no explicit parent, `findAvailablePositionOptimized` walks that side, level by level, and takes the first empty slot (up to 10 levels, then falls back to the sponsor).
   - The new user stores `parent_id` and `position`.
5. Insert the user.
6. If binary placement ran, set the parent’s `left_id` (position 1) or `right_id` (position 2) to the new user’s id.
7. Create an empty `user_wallets` row for the new user.
8. Update the sponsor’s direct-count fields on the sponsor wallet.
9. Redirect to `/register-success`.

Important difference: this registration method treats the plan as binary in code. Menus and the dashboard do not. They read `advanced_info.reg_type`. If that value is not `binary`, the tree and left/right controls stay hidden even though new users may still have been stored with `parent_id` and `position`.

There is a second registration path, `registerdapp`, used when the login type is `dapp` instead of `normal`. The normal form is the one described above.

Password reset:

1. `GET /forgot-password` and `POST /forgot-password` send the reset mail.
2. `GET /reset-password/{token}` and `POST /reset-password` set the new password.

Mail uses the SMTP settings in `.env`. If that mailbox is not reachable, the reset mail is not delivered.

### Member login

`GET /login` and `POST /login` use `UserLoginController` when the registration type is normal. The dapp branch uses a wallet signature login instead.

### Admin login

1. `GET /admin/login` and `POST /admin/login` (`AdminLoginController`).
2. OTP: `GET /admin/otp`, `POST /admin/otp/verify`, `POST /admin/otp/resend`.
3. Successful admin logins are written to `admin_login_histories`, including logout time when the admin logs out.

### Super admin and franchise login

- Super admin: `GET /superadmin/login`, `POST` on that prefix, logout `POST /superadmin/logout`.
- Franchise: `GET /franchise/login`, `POST /franchise/login`.

## 4. Member dashboard

All of these routes use middleware `auth:web`, `role:user,web`, and `checkSiteStatus`. The screen shell is `resources/views/user/layouts/app.blade.php`, which includes `user/layouts/header.blade.php`.

`GET /dashboard/user` runs `DashboardController::userdashboard` and renders `resources/views/user/dashboard/index.blade.php`.

The dashboard shows wallet balances, recent binary numbers (`/dashboard/user/recentbinaryData`), recent earnings (`/dashboard/user/recentEarningsData`), rank and bonanza text, and the referral block.

### Referral link and left / right

This block does not draw the tree. It only builds the link new people use to join under this member.

- Plan is `binary` and company `binary_status` is `yes`: show Left, Right, and Auto buttons. Submitting one posts to `POST /user/default-side` (`UserController::updateDefaultSide`) and saves `users.default_side` as `left`, `right`, or `auto`. That is the side used for the next referral. It does not list who is already on the left or right.
- Plan is `binary` and `binary_status` is `no`: show two links, `/register?ref={username}&side=left` and `&side=right`.
- Any other `reg_type`: show one link, `/register?ref={username}`, and no side choice.

### Binary tree

Menu item **Binary Tree** is rendered only when `reg_type` is `binary` and the member has permission **Genealogy Tree** / `can_view`. **Left Team** and **Right Team** have their own permission checks (**Left Team**, **Right Team**). **Direct** needs **Direct Users**. **Team Users** needs **Genealogy Team**.

When the page is allowed:

1. `GET /tree-view` runs `TreeViewController::showTree`.
2. The root is the logged-in member.
3. Children are loaded with `parent_id` = parent id and `position` in `1` (left) or `2` (right). The page does not follow `sponsor_id` alone, and it does not follow `left_id` / `right_id`. Those two columns are updated at registration but can drift from `parent_id` + `position`. The tree trusts `parent_id` + `position` because binary matching income uses the same pair.
4. Only `TREE_RENDER_LEVELS` levels are drawn (default 3, from `config/tree.php`). Deeper people are not on the first paint.
5. An empty slot is drawn as a Register placeholder. A child that exists beyond the depth limit is not drawn as an empty slot.
6. If two users were saved on the same side, the earliest id is kept and the other username is reported as hidden.
7. Clicking a node calls `GET /user/tree/fetch-subtree?id=`. The server allows it only when that id is the viewer or someone in the viewer’s downline (`TreeHelper::isSelfOrDescendantOf`). It then renders `TREE_DRILLDOWN_LEVELS` levels (default 3) under that person and returns the breadcrumb path.

Related genealogy pages:

- `GET /direct-users` — people who joined with this member as sponsor.
- `GET /left-team` and `GET /right-team` — the two legs.
- `GET /team-users` — team list.
- `GET /user/universal-tree` — a text-style tree (`user/genealogy/universal-tree-view.blade.php`) built from genealogy nodes, separate from the binary diagram.

Team totals on the tree page (member count, active count, package BV, active directs) are computed from the downline id lists for the left and right legs.

### Profile, KYC, and ID card

1. `GET /user/profile` shows the profile. `GET /user/profile/edit` and `POST /user/profile/update` save it. A new photo is stored under `profile_images` and the old file is deleted.
2. `POST /profile/update-password` changes the password.
3. `GET /kyc` shows KYC sections.
4. Submit routes: `/kyc/personal/submit`, `/kyc/identity/submit`, `/kyc/pan/submit`, `/kyc/bank/submit`. Each stores its photos under `storage/app/public/kyc/...` and sets that section’s status to submitted. Admin later approves or rejects the section.
5. `GET /user/id-card` renders the card. `GET /user/id-card/download` builds the PDF. Images are inlined as base64 because dompdf cannot read the storage URL.

### Wallet: add, request, transfer, convert

These routes are permission-gated.

1. **Add fund** (`/user/fund/add`) and **add-fund history**. A dapp add posts to `/user/fund/new_fund_add`.
2. **Fund request** (`/user/fundrequest/add`): the member picks a payment method (`user/get-payment-types` loads child methods), uploads a slip, and `storefundrequest` creates a pending `transactions` row with `tx_type = user_deposit`. Admin must approve it before the wallet increases. History is `/fund-request/history`.
3. **Transfer** (`/user/fund/transfer`): move balance to another member (`POST /user/transfer-fund`). History is `/fund-transfer/history`.
4. **Convert** (`/user/walletinternal/transfer`): move between the member’s own wallet columns (`POST /user/wallet/internal-transfer`). History is `/user/fund-convert/history`.

`GET /user/get-username` looks up a username while typing a transfer recipient.

### Topup, subscription, and shop-order history

1. `GET /user/topup/index` and `POST /user/topup/store` buy a plan from the wallet. Related posts: `/user/topup/apistore`, `/user/topup/invest`.
2. `GET /user/Subcription/index` and `POST /user/subcription/store` are the subscription form. Both topup screens use the **Topup** permission.
3. `GET /user/order/history` lists plan orders (`tx_type` topup / retopup) for this member.
4. `GET /user/product-orders` lists shop orders (`tx_type = product`) with their `order_items` and products. This is the page whose Invoice button points at the admin PDF route. A member is not logged in on the admin guard, so that PDF does not open. Details are in the invoice section.
5. `POST /mine` (`RoiController::mineRoi`) is the mining action from the dashboard.

A topup order is what carries package BV used by the tree and by income reports. A shop order is a separate `orders` row and does not by itself run a bank payout.

### Withdrawal

`GET /user/payout` needs permission **Withdrawal_view**. `POST /user/payout/store` (`UserPayoutController::store`) does the following:

1. Identity KYC must be approved.
2. Bank KYC must be approved. Bank name, holder, account number, IFSC, and branch are copied into the transaction as JSON.
3. Amount must be between `min_withdrawal_limit` and `max_withdrawal_limit` (defaults 10 and 2500 if the setting row is missing).
4. Wallet `c1` must cover the full requested amount.
5. Charge is read from `wd_charges`. If the options say percent, charge = amount × percent / 100. Otherwise the charge is a flat value.
6. `paidAmount` = requested amount − charge. This is the payable figure. The wallet is reduced by the full requested amount, not by the payable figure.
7. A `transactions` row is inserted: `tx_type = withdrawal`, `debit_credit = debit`, `status = 0`, `amount` = requested amount, `tx_charge` = charge, `paidAmount` = payable, plus opening and closing wallet.

No bank API is called. The request waits for an admin to approve it. Approval only changes status. It does not transfer money.

`GET /user/my-withdrawals` is the report. It sums pending, approved, and rejected amounts. On that screen only, it also shows a split of 7% + 5% + 2% labeled transaction charge, admin charge, and TDS. That 2% is display math in `myWithdrawals()`. It is not subtracted again when the request is saved, and it is not a column in the admin CSV. The CSV payable column is `paidAmount`, which already had `wd_charges` removed.

### Principal withdrawal

A second flow for package principal, not income:

- `GET /principal-withdrawal` and `POST /principal-withdrawal`
- Report: `GET /user/principal-withdrawal` and its list URL

These rows use `tx_type = Principal_Withdrawal`. Admin sees them as a separate pending count.

### Income, ranks, support, news, bonanza, booster

- `GET /income/details/{slug}` and its history URL show one income type. Permission: **User Income**.
- `GET /user/income-report` and `GET /user/income-report/download` export an Excel report (`IncomeReportController`, Maatwebsite Excel).
- Rank pages: `/user/rank-qualification`, `/user/rank-rankrise`, `/user/rank-achievement`, `/user/royalty-achievement`. Permission: **Reward**.
- Support: list, create, chat, reply, close under `user/support`. Permission: **Support**.
- News: `/news-events` and `/news-events/{id}`.
- Bonanza: `GET /user/bonanza`.
- Booster package: `GET /booster-topup`, `POST /booster-topup`, and `GET /booster/tree`.
- Account settings for payout methods: create, store, delete, set default under `user/accountsettings`.

Dream-car and leadership-circle bonus routes exist as comments and are not active.

## 5. Invoice

Two different invoice outputs exist.

### HTML invoice (member, after checkout)

- Route: `GET /order/invoice/{id}`, name `order.invoice`.
- Controller: `OrderController::invoice`.
- Lookup is by `orders.tx_id`, not by the numeric id.
- View: `resources/views/main/product/invoice.blade.php`.
- Available to a logged-in member because the route sits in the shop auth group.
- The product-order history page does not link here.

### GST PDF (admin)

- Route: `GET /orders/print-invoice/{id}`, name `admin.orders.print-invoice`.
- This route is inside the admin middleware group (`auth:admin`, `admin.session`, `role:admin,subadmin`).
- Controller: `AdminOrderController::printSingleInvoice`.
- Loads the order by numeric id with user and `items.product`.
- View: `resources/views/admin/orders/invoice_pdf.blade.php`, rendered with dompdf on A4.
- Columns: product name, SKU (`p_code`), quantity, price, GST percent, taxable value, then a GST summary with CGST and SGST (half of the GST amount each).
- GST percent comes from `products.gst_percent`, or 18 when that value is empty.
- Company name, address, GSTIN, phone, and email come from `company_info` keys `company_name`, `company_address`, `company_gst`, `company_mobile`, `company_email`.
- If `order_items` is empty, the PDF still opens and the product tables are blank.

`resources/views/user/orders/product_history.blade.php` points the Invoice button at `admin.orders.print-invoice`. A member session fails that middleware, so the PDF is not generated for them. The order list on that same page can still show product name, quantity, BV, and price from `order_items` without opening the PDF.

## 6. Admin panel

Middleware on the main admin group: `auth:admin`, `admin.session`, `role:admin,subadmin`.

Login is `/admin/login`, then OTP when that step is used. Dashboard is `DashboardController::admindashboard` (`resources/views/admin/dashboard/index.blade.php`). It counts users, recent signups, active and pending users, and topup orders.

The layout badges (pending withdrawals, principal withdrawals, fund requests, KYC, product orders, franchise orders, franchise fund requests) come from `AppServiceProvider`.

Admins can open a member session with `POST /admin/impersonate/{id}`.

### Users and KYC

- User list, data endpoint, create, edit, update, delete, and CSV export.
- KYC index, approved list, rejected list. `POST /admin/kyc/{kycDetail}/approve` and `.../reject` update the section status (`AdminKycController`).
- Admin profile and password under `/admin/profile` and `/admin/change-password`.
- Login history with export and clear.

### Funds

`AdminFundController`:

1. **Add fund** credits a member wallet and writes a transaction.
2. **Retrieve fund** debits a member wallet.
3. **Pending fund requests** (`tx_type = user_deposit`, status pending). Approve credits the wallet and sets status approved. Reject sets status rejected. Bulk approve and bulk reject do the same for the selected ids.
4. CSV exports exist for pending, approved, and cancelled fund requests, and for transfer and retrieve histories.
5. **Fund convert history** lists internal wallet conversions.
6. **Power business**: add power and a history page with CSV export. This is separate from the normal wallet credit.

### Withdrawals

`AdminWithdrwalsController`. This does not pay a bank.

1. Pending list: withdrawal transactions with status `0`.
2. Approve (single or bulk) sets `status = 1` and `updated_on`. The wallet was already reduced when the member submitted the request. Approval does not call a bank or crypto API. An older crypto `transfer()` method in this controller is commented out.
3. Reject (single or bulk) sets `status = 2`, stores a reason, and adds `amount` back onto wallet `c1`.
4. **Export** (`GET /admin/withdrwalstransactionsrequest/export`) builds a CSV, not an `.xlsx` file. It includes only the ids the admin selected. Columns: User, Amount, Method, Account / Wallet Details, Charge, Payable, Date. Payable is `paidAmount` (requested amount minus `wd_charges`). Account text is bank name, holder, account number, IFSC, and branch, or a crypto address when the stored JSON is not a bank.
5. Approved export (`exportApprovedCSV`) writes every approved withdrawal, not a selected subset. Cancelled export writes rejected rows.

The sidebar item **Auto Withdrawal** does not run this export. It opens `/admin/run-auto-withdrawal`, which calls the Artisan command `withdrawal:auto`. That command class does not exist, so the button errors. The same missing command is referenced by `/run-auto-withdrawal` and `/cron/run-withdrawal-auto`.

### Shop catalog and product orders

Under `app/Http/Controllers/Admin`:

- Categories: create, update, delete. Deleting a category deletes its products’ image files.
- Subcategories: index, store, update, delete.
- Products: image required on create (jpg, jpeg, png, webp, max 2 MB), optional gallery. Files go to the public disk. Update replaces files and deletes the old ones.
- Stock page.
- Rank reward text: `/admin/rank-rewards`.

Product orders:

- `GET /admin/product-orders/{status}` and a data URL for the table.
- `GET /admin/orders/view/{id}` shows one order with items.
- `POST /admin/orders/update-status/{id}` moves status (pending, shipped, delivered, cancelled). Completed and cancelled orders are locked against further status edits.
- The GST PDF route is the one described in the invoice section.

Topup orders (plan purchases, not shop products) have their own history: `/admin/topup-history`, view, and an admin topup form that can place a topup for a user after `admin/check-user`.

### Franchise management

`Admin\FranchiseController` and `AdminFranchiseOrderController` / `AdminFranchiseFundRequestController`:

- List, create, edit franchise accounts.
- Impersonate a franchise login.
- Approve or reject franchise fund requests.
- Review franchise orders.

### Income, ranks, bonanza, settings, support

- Income report and Excel download (`AdminIncomeReportController`).
- Per-income history under `admin/income/{slug}`.
- Rank income report and royalty report (`RankController`).
- Bonanza: create, edit, list, achievers.
- Company info and advance info CRUD (the same settings the website and dashboard read).
- Payment methods, plans, pin details, custom fields, mail templates, notices.
- Support inbox and chat, parallel to the member support tickets.
- Closing links in the admin header (matching closing, rank closing, rank income distribution, royalty release, auto withdrawal) each call an Artisan command. Those command classes are not in `app/Console/Commands`. `app/Console/Kernel.php` also schedules `roi:calculate`, `binary:roi-matching`, `matching:closing`, and `rank:closing`, and those classes are absent too. Clicking those menu items does not calculate income and does not write the withdrawal CSV.

## 7. Super admin

Middleware: `auth:superadmin`, `superadmin.session`, and role `super admin`.

`/dashboard/superadmin` is `DashboardController::superadmindashboard`.

The panel repeats the operational tools at a higher role: users, company info, advance info, funds (add, retrieve, requests, convert), withdrawals, income reports, mail settings and templates, notices, plans, pins, custom fields, and support. Company settings / role permissions are edited from `/company-settings` (`RolePermissionController`). That table is what `hasPermission()` reads for every role.

Super admin closing URLs (`/run-matching-closing`, `/run-rank-closing`, `/run-rank-distribute`, `/run-monthly-income-release`, `/run-auto-withdrawal`) call the same missing Artisan commands as the admin buttons.

## 8. Franchise panel

Middleware uses the `franchise` guard and `FranchiseSession`.

1. Login at `/franchise/login`.
2. Dashboard: `DashboardController` franchise method, view `franchise.dashboard.index`.
3. Profile view and update, plus a change-password page.
4. Orders: list, create, store, show (`Franchise\OrderController`). A payment screenshot can be stored under `fund_proofs`.
5. Fund request to the company: `fundRequest` / `fundRequestStore`, stored as a franchise transaction. Admin approves or rejects it from the admin franchise fund-request screens.
6. Logout: `POST /franchise/logout`.

Franchise data is in `franchise_users`, `franchise_orders`, `franchise_order_items`, and `transaction_franchises`. It does not use the member `transactions` table for those requests.

## 9. End-to-end flows

### A visitor buys a product

1. Browse `/` or `/shop` and open a product.
2. Add it to the cart. Checkout requires login or registration.
3. Registration stores the member, sponsor, binary slot when that branch runs, and an empty wallet. Status starts inactive.
4. Checkout saves an address and posts `place-order`.
5. An `orders` row (`tx_type = product`, status pending, payment method bank) and `order_items` rows are saved.
6. The member sees the order on `/user/product-orders`. The Invoice button targets the admin PDF and does not open for this session.
7. Admin opens `/admin/product-orders/{status}`, views the order, and updates status. Admin can stream the GST PDF from the admin invoice route.

### A member withdraws

1. Member completes identity and bank KYC. Admin approves both sections.
2. Member opens `/user/payout`, enters an amount inside the configured limits, with enough `c1` balance.
3. Wallet `c1` drops by the full amount. A pending withdrawal transaction stores the charge and the payable amount.
4. Admin sees it on pending withdrawals (badge increments while `is_seen` is 0).
5. Admin exports selected rows to CSV for bank processing outside this app, or approves (status only) or rejects (wallet refunded).

### A member joins under a sponsor

1. Sponsor copies the referral link from the dashboard. The link shape depends on `reg_type` and `binary_status`.
2. New user opens `/register?ref=...` and optionally `&side=left` or `&side=right`.
3. Registration writes `sponsor_id` and, on the binary path, `parent_id` and `position`, then updates the parent’s `left_id` or `right_id`.
4. The sponsor sees that person on the binary tree only when `reg_type` is `binary`, the sponsor has the tree permission, and the new user’s `parent_id` / `position` point at someone in that downline. Otherwise the tree menu is hidden or the slot stays an empty Register box.

### Income closing

The admin and super admin menus, the cron URLs under `/cron/...`, and `app/Console/Kernel.php` name the closing commands. The PHP classes for those commands are not in this repository. Rank, royalty, and matching screens can display history that is already in the database. They cannot generate a new closing until those command classes exist.

## 10. Local run

From the project directory, after PHP 8.1+ with `pdo_mysql` enabled, MySQL running, and `.env` pointed at the local database:

```powershell
php artisan config:clear
php artisan storage:link
php artisan serve
```

Open `http://127.0.0.1:8000`.

Do not run `php artisan migrate --seed` against a database that was imported from the SQL dump. The dump already contains the tables and data.

Node and `npm run dev` are only needed to rebuild `resources/js/wallet`. The shop and dashboards use the files already in `public/assets`.
