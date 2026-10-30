# PhoneHub Uganda

B2C smartphone and accessories e-commerce system. PHP 8, MySQL, Bootstrap 5, vanilla JavaScript.
Payments use the server-side PesaJet integration when enabled. The integration is disabled by default until sandbox credentials, API responses, HTTPS, and merchant webhook requirements are verified.

## Folder layout
```
phonehub/
  public/       web root (point Apache here): pages, assets, uploads
  src/          Core (Database, Session, Csrf, Auth, Validator, ImageUpload, Analytics),
                Services (Cart, Order, Appearance), Repositories (all SQL), policies, helpers
  templates/    header, footer, admin layout, cards, consent banner
  config/       config.php (local, git-ignored), config.example.php, config.production.example.php
  database/     schema.sql, seed.sql, seed_test_products.sql, production versions,
                create_admin.php, create_app_user.sql, compress_existing_images.php
  docs/         test_plan.csv, security_summary.md, deployment_guide.md,
                analytics_marketing_guide.md, verification_notes.md, htaccess_shared_host.txt
```

## Quick start (WAMP on Windows)
1. Copy this folder to `C:\wamp64\www\phonehub\`.
2. In phpMyAdmin, Import `database/schema.sql`, then `database/seed.sql` (optional: `seed_test_products.sql`).
3. `config/config.php` holds WAMP defaults (user root, empty password). Edit it if yours differ.
   Open `http://localhost/phonehub/` (it forwards to `public/`). The site detects its own address, so no setting is needed.
   For a cleaner address, set up the virtual host in `docs/deployment_guide.md` (Part A).
   If you see "Forbidden", you are probably looking at the wrong folder: see Troubleshooting below.
4. Create the first admin: `php database\create_admin.php` (full path to php.exe if needed).
5. Open `/test_setup.php` and fix any FAIL lines. In `php.ini`, set `upload_max_filesize = 8M` and `post_max_size = 32M`.
6. In Admin > Settings, add your real store details and at least one delivery area, or checkout stays blocked.
7. To enable PesaJet, add the `pesajet` section from `config/config.example.php` to the private `config/config.php`, set sandbox credentials, run `database/migrate_add_pesajet_payments.sql`, and verify create/status responses before production.
8. Run the tests in `docs/test_plan.csv` and record real results.

## Before going live
- Use `schema_production.sql` (never `schema.sql`, which drops the database) and follow `docs/deployment_guide.md`.
- Do NOT upload the local `config/config.php` (it has development defaults). Create the production one from `config.production.example.php`.
- Delete `public/test_setup.php`. Remove `[TEST]` products. Resolve every [CONFIRM] / [VERIFY] in the policy pages.
- Read `docs/verification_notes.md` for what has and has not been checked.

## Troubleshooting: "Forbidden" or "Not Found" on localhost
- The project must be at `C:\wamp64\www\phonehub\` (NOT `...\phonehub\phonehub\`). You should see `public`, `src` and `index.php` directly inside it.
- Open `http://localhost/phonehub/` or `http://localhost/phonehub/public/`. Never open `/src/`, `/config/`, `/database/`, `/templates/` or `/docs/`: those are blocked on purpose and show Forbidden.
- Check the last lines of `C:\wamp64\logs\apache_error.log` for the exact reason.
- If you edited `httpd-vhosts.conf`, the `<Directory>` block needs `Require all granted` and `AllowOverride All`.

## Receipts, email and order tracking
- Customers can print a receipt (`receipt.php`), email it to themselves, and follow an order step by step (`track.php`) from My orders.
- A receipt email is sent after payment, and update emails when staff change an order's status. This needs SMTP settings: see `docs/email_setup.md`.
- Test your email settings with `php database\send_test_email.php you@example.com`.

## What is in this folder (everything lives here)
| Folder / file | What it is |
|---|---|
| `public/` | The website (point Apache here, or open `/phonehub/`, which forwards to it) |
| `src/`, `templates/` | PHP code: core helpers, services, repositories, page layouts |
| `config/` | `config.php` (yours, keep private), `config.example.php`, `config.production.example.php` |
| `database/` | SQL files (schema, seed, production versions), admin and password-reset scripts, image and email tools, your migrations |
| `storage/` | Email logs and saved email copies (blocked from the web) |
| `docs/` | Test plan (`test_plan.csv`), security summary, deployment guide, email setup, analytics and marketing guide, verification notes, link to the project report |

## Brief alignment, policy details, marketing and installable app
- `docs/submission/` holds the documents for the BSIT 63102 brief (coverage map, AI prompt log, code guide, GA4 and marketing templates, platform justification, requirements traceability).
- Admin > **Policy Details** completes the highlighted gaps on the policy pages. Admin > **Marketing** records campaigns and shows tagged links and results.
- The Delivery page lists your active delivery areas, fees and estimates.
- The site is an installable web app (works on localhost or HTTPS) and shows an offline page when the server cannot be reached.

## Updating an EXISTING database
New installs get everything from `database/schema.sql`. If your database already exists, run the migration files you have not run yet, once each, in phpMyAdmin (select `phonehub_uganda`, SQL tab):
- `database/migrate_add_favourites.sql` and `database/migrate_add_hero_slides.sql` (your earlier features), if not already applied
- `database/migrate_add_marketing.sql` (campaign tracker). Until you run it, the Marketing page shows a setup message and the shop keeps working.
- `database/migrate_add_customer_dashboard.sql` (recently viewed, inbox unread marker, product reviews). Safe to run more than once. Until you run it, the dashboard still works but reviews show a setup message and recently viewed items last only for the browser session.

## Customer dashboard (My account)
Modelled on Jumia's account area. Every page is scoped to the logged-in customer.
| Page | What it does |
|---|---|
| `account/index.php` | Overview: actions needed (unpaid orders, products to rate), order/saved/spend tiles, account details, default address, recent orders, latest messages, recently viewed |
| `account/orders.php` | Orders in tabs (Ongoing, Delivered, Cancelled, All) with search by order number or product, and pages |
| `account/order.php` | Order details: progress tracker, items with Buy again and Rate, payment, delivery, cancel with a reason (until the order is being prepared) |
| `account/inbox.php` | Every order update and support request, with an unread badge |
| `account/reviews.php`, `account/review.php` | Pending reviews and My reviews; only customers who received a product can review it |
| `account/favourites.php` | Saved items with Add to cart |
| `account/recently_viewed.php` | Products viewed, synced across devices |
| `account/addresses.php` | Address book: add, edit, delete, set default (prefills checkout) |
| `account/profile.php` | Personal details, password, account activity, close account |
Staff moderate reviews under Admin > **Reviews**. What changed and why: `docs/CHANGES_customer_dashboard.md`.
