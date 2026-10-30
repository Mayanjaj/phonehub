# Change log and version history

The brief asks for a simple version history (for example Git commits or dated file copies).

> **Honesty note.** The list below shows the order in which features were built. It was written afterwards from the development sequence, not from real commit timestamps. **Do not back-date commits.** Start your real history today and commit after every change you make from now on.

## Start your Git history now
```
cd C:\wamp64\www\phonehub
git init
git add .
git status          (check that config/config.php is NOT listed)
git commit -m "Submission baseline: full PhoneHub Uganda system"
```
Then, after each change you make (a policy edit, a bug fix, a content update), run `git add .` and `git commit -m "what you changed and why"`. Push to a private GitHub repository if you want a link to submit. If you prefer dated copies, zip the folder after each session and name it `phonehub_2026-10-26.zip`.

## Features in the order they were built
1. Business concept, requirements, architecture, database design
2. Project folders, schema, seed data, core helpers (database, sessions, CSRF, authentication)
3. Homepage and hero carousel
4. Catalogue, search, filters, product pages, comparison
5. Shopping cart
6. Accounts, addresses, login throttling
7. Checkout and simulated payment
8. Orders and delivery management
9. Admin dashboard, products, categories, customers, settings
10. Support, policy pages, reports
11. Security hardening, test plan
12. Deployment guide, production SQL
13. Google Analytics with consent, SEO, marketing guide
14. Written report draft
15. Image compression and thumbnails
16. Admin-managed login backgrounds
17. Fixes: template variable collisions, folder forwarding page and base-URL detection, status code 419 changed to 403
18. Receipts, email, order tracking, status emails
19. Hero banner with image or video background
20. Brief alignment: marketing campaign tracker, delivery information page, editable policy details, installable web app, submission documents
21. Customer dashboard completed (Jumia-style overview, order tabs and search, cancel with reason, address book editing, saved items, pending reviews and product ratings, admin review moderation) and a full bug-fix pass: see `docs/CHANGES_customer_dashboard.md`
