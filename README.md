# GymPass India — Project README
> Last updated: 2026-08-16
> For coding agents, new contributors, and handoff reference.

---

## What is GymPass?

GymPass.in is a gym day-pass marketplace for travelers in India. Travelers can book a gym for 1 day, 3 days, 7 days, or monthly — pay via UPI/Razorpay — and walk in with their booking reference. No long-term commitment, no awkward cash negotiations.

**Core idea:** A traveler in Rishikesh for 5 days shouldn't have to pay a full month's gym fee. GymPass lets gym owners list flexible plans, and travelers book exactly what they need.

**Business model:** GymPass collects full payment from travelers via Razorpay, keeps 10% commission, and manually pays the remaining 90% to gym owners via UPI every 3–5 days. A gym-owner side ("Members") tool also lets owners track their own regular/local members, not just day-pass travelers — see [Gym Members](#gym-members-owner-crm) below.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 9 (mid-upgrade from 8; plan is 8→9→10→11→12→13, one hop at a time — see [Laravel Version](#laravel-version--upgrade-status)) |
| PHP | 8.2 (locally and target for prod — previously 7.4) |
| Auth | Laravel Sanctum (token-based) |
| Database | MySQL, hosted on Webeyesoft |
| Frontend | Next.js/React (real app, FE team) + Blade templates (UI reference / some pages fully wired — see [Frontend](#frontend)) |
| Payments | Razorpay — checkout + webhook safety net |
| OTP | MSG91 (dev bypass: OTP `0000` always works in non-production) |
| Hosting | Webeyesoft (`gym.theswarmneeds.in`) — moved off Hostinger |
| Deploy | GitHub Actions → SSH → `git pull` + `composer install` (no rsync) |
| Domain | gym.theswarmneeds.in |

---

## Repository Structure

```
gym-website/                  ← Laravel project root
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── Auth/
│   │   │   │   │   ├── AuthController.php        ← OTP send, verify, register
│   │   │   │   │   └── LoginController.php       ← login, logout, me
│   │   │   │   ├── Owner/
│   │   │   │   │   ├── GymAmenityController.php  ← amenities CRUD
│   │   │   │   │   ├── GymPlanController.php     ← pricing plans CRUD
│   │   │   │   │   └── GymMemberController.php   ← members CRUD + send-reminder
│   │   │   │   ├── BookingController.php         ← create order, verify payment
│   │   │   │   ├── RazorpayWebhookController.php ← backend safety net for payment activation
│   │   │   │   └── NearbyGymController.php       ← haversine gym search
│   │   │   ├── Admin/
│   │   │   │   └── SettlementController.php      ← internal payout tracking (Raj only)
│   │   │   └── GymController.php                 ← show, update, operating hours
│   │   ├── Middleware/
│   │   │   └── AdminBasicAuth.php                ← gates /admin/* via ADMIN_USERNAME/PASSWORD
│   │   └── Requests/Owner/
│   │       ├── CreateGymPlanRequest.php / UpdateGymPlanRequest.php
│   │       ├── SyncAmenitiesRequest.php / AddCustomAmenityRequest.php
│   │       └── AddGymMemberRequest.php / UpdateGymMemberRequest.php
│   ├── Models/
│   │   ├── User.php, Gym.php, GymPlan.php, GymOperatingHour.php, Amenity.php
│   │   ├── Booking.php, Settlement.php, GymMember.php, OtpCode.php
│   ├── Services/
│   │   ├── BookingService.php                    ← createOrder / verifyAndActivate / activateFromWebhook
│   │   ├── RazorpayService.php                   ← order create, signature + webhook verify, commission split
│   │   ├── GymMemberService.php                  ← addOrRenew / syncFromBooking (dedup by phone per gym)
│   │   └── Auth/
│   │       ├── OtpService.php, TempTokenService.php, RegisterService.php, LoginService.php
│   └── Mail/
│       └── MembershipReminderMail.php            ← owner-triggered renewal reminder email
├── database/migrations/        ← users, gyms, otp_codes, gym_plans, gym_operating_hours,
│                                   amenities(+pivot), bookings+settlements, gym_members
├── resources/views/
│   ├── admin/settlements.blade.php               ← internal payout screen
│   ├── emails/membership-reminder.blade.php
│   └── *.blade.php                                ← owner dashboard UI references (see Frontend table)
├── routes/
│   ├── api.php                                    ← all `/api/*`, mostly `auth:sanctum`
│   ├── web.php                                    ← Blade page routes + `/admin/*` (admin.auth)
│   └── health.php                                 ← registered in RouteServiceProvider
└── .github/workflows/deploy.yml                   ← SSH deploy to Webeyesoft
```

**Note on `App\Services` namespacing:** `BookingService`, `RazorpayService`, and `GymMemberService` live directly under `app/Services/` (namespace `App\Services`). `OtpService`, `TempTokenService`, `RegisterService`, `LoginService` live under `app/Services/Auth/` (namespace `App\Services\Auth`). This split is intentional, not inconsistent — don't "fix" it by moving files without checking the declared namespace matches the folder (a past PSR-4 mismatch here caused a `composer install --no-dev` production outage; see [Known Issues](#known-issues--watch-out-for)).

---

## Database Tables

### `users`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| role | enum | `traveler`, `owner` |
| phone | string | unique |
| name | string | |
| email | string | nullable |
| home_city | string | nullable |
| created_at, updated_at, deleted_at | timestamps | soft deletes |

### `gyms`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| user_id | FK → users | gym owner |
| name | string | |
| address_text | string | full address |
| lat | decimal | map coordinates |
| lng | decimal | map coordinates |
| mapbox_place_id | string | nullable |
| city | string | nullable |
| area | string | nullable |
| monthly_rate | int | base rate in ₹ |
| upi_id | string | nullable — for payouts |
| status | enum | `active`, `pending`, `inactive` |
| description | text | nullable |
| created_at, updated_at, deleted_at | timestamps | soft deletes |

Media columns (`cover_photo`, `photos`, `videos`) described in older docs were never actually built — no migration or controller for them exists in this repo. Treat any prior mention of a Media API as aspirational, not shipped.

### `gym_plans`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| gym_id | FK → gyms | |
| name | string | auto-generated e.g. "3 Day Pass" |
| duration | int | e.g. 1, 3, 7 |
| unit | enum | `day`, `month` |
| price | int | in ₹ |
| is_default | boolean | true = seeded at registration, cannot be deleted |
| is_enabled | boolean | owner can toggle off |
| created_at, updated_at | timestamps | |

**Default plans seeded at registration:**
- 1 Day Pass → 10% of monthly_rate
- 3 Day Pass → 25% of monthly_rate
- 7 Day Pass → 50% of monthly_rate
- 1 Month Pass → 100% of monthly_rate

### `gym_operating_hours`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| gym_id | FK → gyms | |
| day | string | `Monday`, `Tuesday` … `Sunday` |
| open_time | time | nullable (null = closed) |
| close_time | time | nullable |
| is_closed | boolean | |
| created_at, updated_at | timestamps | |

### `amenities` + `gym_amenity` (pivot)
Same as before — `amenities` has `name`, `icon`, `is_default` (12 seeded defaults); `gym_amenity` is a composite-key pivot (`gym_id`, `amenity_id`). AC + Lockers + Shower are pre-selected for all new gyms.

### `otp_codes`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| phone | string | |
| code | string | |
| role | string | `traveler` or `owner` |
| expires_at | datetime | 15 min TTL |
| is_used | boolean | |
| attempts | int | max 5/hr rate limit |
| created_at, updated_at | timestamps | |

### `bookings`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| user_id | FK → users | traveler |
| gym_id | FK → gyms | |
| gym_plan_id | FK → gym_plans | |
| booking_ref | string | unique e.g. `GP-2026-XK9F2A` |
| start_date | date | |
| end_date | date | calculated from plan duration |
| razorpay_order_id | string | nullable |
| razorpay_payment_id | string | nullable |
| razorpay_signature | string | nullable — not set when activated via webhook (see below) |
| amount | int | full amount paid in ₹ |
| status | enum | `pending`, `paid`, `cancelled`, `expired` |
| qr_code | string | nullable — unique token (V2 feature, stored but unused in V1 flow) |
| created_at, updated_at | timestamps | |

### `settlements`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| booking_id | FK → bookings | |
| gym_id | FK → gyms | |
| gross_amount | int | what traveler paid e.g. ₹100 |
| commission_amount | int | GymPass keeps e.g. ₹10 |
| payout_amount | int | gym receives e.g. ₹90 |
| commission_pct | decimal | % at time of booking e.g. 10.00 |
| gym_upi_id | string | snapshot of UPI at booking time |
| payout_status | enum | `pending`, `paid` |
| paid_at | timestamp | nullable — when manually paid (via `/admin/settlements`) |
| razorpay_payout_id | string | nullable — for future automation |
| created_at, updated_at | timestamps | |

### `gym_members` — owner CRM (new)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| gym_id | FK → gyms | |
| user_id | FK → users, nullable | set when the member came from a booking (has a traveler account) |
| last_booking_id | FK → bookings, nullable | most recent day-pass tied to this member |
| name | string | |
| phone | string | de-dup key — see below |
| email | string | nullable — required to send a reminder |
| source | enum | `manual` (owner added them) or `booking` (came from a day-pass) — set once, never overwritten |
| start_date | date | latest membership/pass start |
| due_date | date | computed renewal/expiry date |
| plan_label | string | nullable — display label, e.g. "3 Months" or "7 Days Pass" |
| notes | text | nullable |
| last_reminder_sent_at | timestamp | nullable |
| created_at, updated_at | timestamps | |
| — | unique | `(gym_id, phone)` |

**Why the phone-based unique constraint matters:** a repeat traveler who books a new pass, or an owner re-adding/renewing an existing local member, updates the *same* row instead of creating a duplicate. `GymMemberService::syncFromBooking()` runs automatically on every paid booking (called from `BookingService::activate()`) and upserts on `(gym_id, phone)` — a manually-added local member who later books a pass keeps their original `source: manual`, they just get their `due_date` refreshed.

### `personal_access_tokens` (Sanctum)
Standard Sanctum table, no custom model — older docs claimed a custom `PersonalAccessToken` model existed to fix a PHP-7.4 abilities bug; that model does not exist in the current codebase. If you find you actually need one (e.g. hit an abilities-encoding bug), that's new work, not something to "restore."

---

## API Routes

Base URL: `https://gym.theswarmneeds.in/api`

### Public (no auth required)

```
GET  /health                         quick ping
GET  /health/full                    DB status + stats + all routes

POST /auth/send-otp                  send OTP to phone
POST /auth/verify-otp                verify OTP → returns temp_token
POST /auth/register/traveler         register traveler using temp_token
POST /auth/register/owner            register owner + gym + default plans
POST /auth/login/send-otp            send OTP for login
POST /auth/login                     verify OTP → returns access_token

GET  /gyms/nearby                    haversine search ?lat=&lng=&radius=
GET  /gyms/{id}/plans                get plans for a gym

POST /webhooks/razorpay              Razorpay server-to-server event (payment.captured) — see Payment Flow
```

### Protected — any authenticated user (`auth:sanctum`)

```
POST /auth/logout                    revoke token
GET  /auth/me                        current user info
GET  /gyms/{id}                      gym detail
PUT  /gyms/{id}                      update gym info
POST /gyms/{id}/operating-hours      save week schedule
GET  /gyms/{id}/operating-hours      read week schedule

GET  /bookings                       list my bookings
GET  /bookings/{id}                  booking detail + QR code
POST /bookings/create-order          step 1: create Razorpay order
POST /bookings/verify-payment        step 2: verify signature → activate booking
```

### Protected — owner-scoped (`auth:sanctum`, gym resolved from the token)

There's no `role:owner` route middleware — every owner endpoint below resolves `$request->user()->gym` inside the controller and 400s with `gym_not_found` if the authenticated user has none. Keep this pattern for new owner endpoints rather than adding a role middleware.

```
GET    /owner/gym/plans                       list all plans
POST   /owner/gym/plans                       create custom plan {duration, unit, price}
PUT    /owner/gym/plans/{planId}              update price or is_enabled
DELETE /owner/gym/plans/{planId}              delete custom plan (not defaults)

GET    /owner/gym/amenities                   all amenities + is_selected per gym
POST   /owner/gym/amenities/sync              save selection {amenity_ids: []}
POST   /owner/gym/amenities/custom            add new amenity to global list {name, icon}

GET    /owner/gym/members                     list members (manual + booking-sourced), soonest due first
POST   /owner/gym/members                     add member, or renew if phone already exists {name, phone, email?, start_date, duration_type, custom_days?, notes?}
PUT    /owner/gym/members/{memberId}          edit and/or renew
DELETE /owner/gym/members/{memberId}          remove
POST   /owner/gym/members/{memberId}/send-reminder   owner-triggered email reminder (needs member.email)
```

### Internal admin (web routes, HTTP Basic Auth via `admin.auth` — not Sanctum)

```
GET  /admin/settlements                       payout tracking screen (filter ?status=pending|paid)
POST /admin/settlements/{settlement}/toggle-paid   flip a settlement between pending/paid
```

Gated by `ADMIN_USERNAME`/`ADMIN_PASSWORD` in `.env`, not a real user role — there is no admin role in this app. See `app/Http/Middleware/AdminBasicAuth.php`.

---

## Auth Flow

```
1. POST /auth/send-otp      → OTP sent to phone (dev: always 0000)
2. POST /auth/verify-otp    → returns temp_token (15 min, single use)
3. POST /auth/register/*    → consumes temp_token → creates user → returns access_token

Login:
1. POST /auth/login/send-otp → OTP sent
2. POST /auth/login          → returns access_token

All protected routes: Authorization: Bearer {access_token}
```

---

## Payment Flow (Razorpay)

```
1. Traveler selects plan → POST /bookings/create-order
   → creates pending booking in DB
   → creates Razorpay order
   → returns order_id + key_id to FE

2. FE opens Razorpay checkout (JS SDK)
   → traveler pays via UPI/card/netbanking

3. On payment success (client-side) → POST /bookings/verify-payment
   → verifies Razorpay checkout signature
   → activates booking (see below)

3b. Backend safety net → POST /webhooks/razorpay (called by Razorpay's servers, not the FE)
   → verifies the webhook signature (separate secret from checkout — RAZORPAY_WEBHOOK_SECRET)
   → on payment.captured, activates the booking the same way
   → covers the case where the traveler's browser never completes step 3 (closed tab, dropped
     connection) after Razorpay already captured the money

Activation (BookingService::activate(), shared by both paths, idempotent — whichever fires first wins):
   → marks booking paid, issues qr_code (unused in V1 UI)
   → creates settlement record (gross/commission/payout split)
   → syncs the traveler into gym_members (GymMemberService::syncFromBooking)
   → TODO: SMS/WhatsApp/email booking-confirmation notification (not built yet)

Money split: gross=₹100 → commission=₹10 (10%) → payout=₹90 to gym UPI
Manual payout tracked via /admin/settlements, transferred every 3–5 days via GPay/PhonePe
```

---

## Gym Members (owner CRM)

Lets a gym owner track their own regular/local members (not just travelers-via-passes) in one list, so there's a reason to open the dashboard daily instead of only when a booking comes in. Explicitly the foundation for a future paid subscription tier for owners (not built yet).

- Owner adds a member with a name, phone, optional email, a start date (any date — past/today/future), and a duration (1/3/6/12 months or custom days) → due date is computed.
- Every paid day-pass booking auto-syncs into the same list (`source: booking`), de-duplicated by phone per gym — see the `gym_members` table notes above.
- Owner can send a one-off email reminder to a member who's due soon or overdue (`POST /owner/gym/members/{id}/send-reminder`) — **owner-triggered only, no automated scheduler yet** (there's a `TODO` in `GymMemberController::sendReminder` marking where that would hook in).
- Frontend: `app/dashboard/members/page.tsx` in the Next.js repo is fully wired to this API. `resources/views/members.blade.php` here was also updated to match the real field/column shape as a UI-only reference (no backend calls in the Blade version, by design).

---

## Admin Settlements Panel

Internal tool (`/admin/settlements`, Basic Auth) for Raj to manually mark gym-owner payouts as paid after sending the UPI transfer by hand — the checkbox just updates `settlements.payout_status`, it doesn't move money. See `app/Http/Controllers/Admin/SettlementController.php` and `resources/views/admin/settlements.blade.php`.

---

## Deployment

**Host:** Webeyesoft — moved off Hostinger.
- Domain: `gym.theswarmneeds.in`
- Server path: `/home/theswarm/domains/gym.theswarmneeds.in/public_html`
- PHP: 8.2 (upgrade from 7.4 done alongside the Webeyesoft move)

**Deploy:** push to `main` → GitHub Actions (`.github/workflows/deploy.yml`) SSHes into the server and runs:
```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
```
No rsync anymore (the old Hostinger pipeline's rsync-wiped-other-sites landmine is moot — the deploy mechanism itself changed, not just a flag).

**Note:** this workflow has no explicit "Setup PHP" step — it uses whatever `php`/`composer` the SSH session resolves to on the Webeyesoft server. If you ever see version-mismatch weirdness after a deploy, check that first (`php -v` over SSH) rather than assuming it's a code issue.

**Database:** also moved off the old Hostinger MySQL host to a Webeyesoft-hosted DB. Update `.env` (local and live) with the new credentials — as of this writing that migration is still in progress, don't assume `.env`'s `DB_HOST` is already pointed correctly without checking.

**CORS:** handled by Laravel 9's built-in `Illuminate\Http\Middleware\HandleCors` (registered in `app/Http/Kernel.php`) — the third-party `fruitcake/laravel-cors` package used pre-Laravel-9 has been removed. Config unchanged: `config/cors.php`, allowed origins `http://localhost:3000`, `https://gympass-nextjs.vercel.app`.

---

## Laravel Version / Upgrade Status

Currently **Laravel 9.52.22** (upgraded from 8.83.29). Full gap to latest is 8→9→10→11→12→13 (Laravel doesn't support skipping majors) — this was hop 1 of that sequence, done and committed. Each hop gets tested before starting the next; **don't start 9→10 without being asked.**

**Known issue on the current version:** `composer audit` flags unpatched CVEs in the 9.x line (9.52.22 was 9.x's final release, so these will never get a 9.x patch — fixes only exist from 10.48.29+/12.60+/13.10+ onward). One is directly relevant here: CRLF injection in Laravel's built-in `email` validation rule (CVE-2026-48019), used by `AddGymMemberRequest`/`UpdateGymMemberRequest` and feeding into `Mail::to()` in `GymMemberController::sendReminder()`. Not yet mitigated — a reason to keep moving through the upgrade hops rather than treating 9.x as a stable resting point.

No automated test suite exists (`tests/` is the default unmodified Laravel scaffold) — every upgrade hop and every non-trivial change needs manual verification.

---

## Frontend

| Page | File | Status |
|------|------|--------|
| Homepage | `app/page.tsx` (Next.js) | Real — header now correctly reflects logged-in state |
| Search | `app/search/page.tsx` (Next.js) | Real — nearby search, header wired |
| Gym Detail | `app/search/[id]/page.tsx` + `components/GymDetails.tsx` (Next.js) | Real — full Razorpay checkout flow, booking confirmation modal (traveler name/phone, gym location) |
| Gym Detail (reference) | `resources/views/gym-details.blade.php` | Kept in sync with the Next.js booking flow as the canonical UI reference |
| Owner Dashboard shell | `app/dashboard/layout.tsx` + `DashboardSidebar.tsx` (Next.js) | Real |
| Owner Members | `app/dashboard/members/page.tsx` (Next.js) | Real — wired to `/owner/gym/members`, add/edit/delete/remind |
| Owner Members (reference) | `resources/views/members.blade.php` | UI-only reference, matches the real field shape, no backend calls by design |
| Owner Bookings / Payouts / Reviews / Analytics / QR Scanner | Next.js `app/dashboard/*` | Still static mocks — not wired yet |
| Owner Settings (pricing/amenities/hours) | `components/GymSettings/*` (Next.js) | Real |
| Login / Signup | Next.js `app/(auth)/*` | Real — OTP flow wired |

**Design system:** `public/css/shared.css` (Blade side) — single source of truth, 20+ sections. Font: Syne (display), DM Sans (body), JetBrains Mono. Brand color `#FF5C1A` orange, dark theme `--bg #080808`. The Next.js `app/globals.css` mirrors the same tokens/classes.

---

## Key Services

### `OtpService` (`App\Services\Auth`)
- Rate limit: 5 OTPs/hour per phone
- Dev bypass: OTP `0000` always passes in non-production
- When MSG91 is ready: remove bypass block + uncomment `dispatch()` call

### `TempTokenService` (`App\Services\Auth`)
- 64-char random token, Cache-stored, 15 min TTL, single-use
- Bridge between OTP verify and register steps

### `RazorpayService` (`App\Services`)
- `createOrder(amount, receipt, notes)` → Razorpay order
- `verifySignature(orderId, paymentId, signature)` → bool — checkout-flow verification
- `verifyWebhookSignature(rawPayload, signature)` → bool — webhook-flow verification, separate secret (`RAZORPAY_WEBHOOK_SECRET`)
- `calculateSplit(grossAmount)` → `{gross, commission_pct, commission_amount, payout_amount}`

### `BookingService` (`App\Services`)
- `createOrder(user, plan)` → pending booking + Razorpay order
- `verifyAndActivate(bookingId, orderId, paymentId, signature)` → checkout-flow activation
- `activateFromWebhook(orderId, paymentId)` → webhook-flow activation
- both call a shared private `activate()` — mark paid, create settlement, sync `gym_members`, all in one DB transaction

### `GymMemberService` (`App\Services`)
- `addOrRenew(gym, data)` → manual add/renew from the owner dashboard, upserts by `(gym_id, phone)`
- `syncFromBooking(booking)` → auto-called from `BookingService::activate()`
- `calculateDueDate(startDate, durationType, customDays)` → shared duration math

---

## Environment Variables

```env
APP_NAME="GymPass India"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://gym.theswarmneeds.in
APP_KEY=base64:...

DB_CONNECTION=mysql
DB_HOST=...              # Webeyesoft DB host — confirm this is actually updated, see Deployment
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

RAZORPAY_KEY_ID=rzp_live_...
RAZORPAY_KEY_SECRET=...
RAZORPAY_WEBHOOK_SECRET=...      # from Razorpay Dashboard → Settings → Webhooks, NOT the same as key secret
RAZORPAY_COMMISSION_PCT=10

ADMIN_USERNAME=...        # gates /admin/settlements
ADMIN_PASSWORD=...

MAIL_MAILER=smtp          # needs a REAL provider for membership reminders to actually send —
MAIL_HOST=...              # currently often left pointed at a local dev catcher (mailhog), which
MAIL_PORT=...               # fails silently/gracefully (caught, returns server_error) rather than
MAIL_USERNAME=...            # crashing, but won't deliver anything
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=...
```

`CLOUDINARY_*` vars from older docs are not needed — the media upload feature they were for was never actually built (see the `gyms` table note above).

---

## What's Built vs What's Pending

### ✅ Built and working
- OTP auth (send, verify, register traveler, register owner, login, logout)
- Gym registration with default plans seeded
- Operating hours, gym plans, amenities — owner CRUD
- Razorpay payment — checkout flow + webhook safety-net, booking + settlement record
- **Gym Members (owner CRM)** — manual add/renew, auto-sync from bookings, owner-triggered email reminders
- **Admin settlements panel** — manual payout tracking
- Nearby gym search (haversine)
- Health check endpoints
- Next.js frontend: homepage, search, gym detail + full booking flow, login/signup, owner settings, owner members — all real, not mocked
- GitHub Actions deploy to Webeyesoft
- Laravel 9 (hop 1 of the 8→13 upgrade path)

### 📋 Not yet built (V1 remaining)
- Traveler booking history UI (Next.js `app/dashboard/bookings` etc. still static mocks — note this is the *owner*-side bookings mock; a traveler-facing history view doesn't exist either)
- Owner dashboard notifications on new booking
- SMS/WhatsApp/email booking-confirmation to travelers (TODO left in `BookingService`)
- Automated (scheduled) membership-renewal reminders — currently owner-triggered only (TODO left in `GymMemberController`)

### 🔮 V2 / Future
- **QR pass check-in** — `qr_code` column exists, unused in V1 (manual owner notification instead)
- **Razorpay Payouts API** — automate the manual UPI payout step
- **Subscription/paywall for Gym Members feature** — the stated long-term reason it was built
- **MSG91 production** — replace OTP `0000` bypass
- **Continue Laravel upgrade** — 9→10→11→12→13, one hop at a time
- **Media API** (Cloudinary/Unsplash photo picker) — never actually started, would be new work not "resuming"

---

## Known Issues / Watch Out For

1. **Laravel 9.x has unpatched CVEs** (see [Laravel Version](#laravel-version--upgrade-status)) — one directly touches the `email` validation rule used by Gym Members. Don't linger on this version.
2. **PSR-4 namespace/folder mismatch caused a real production outage once** — `BookingService`/`RazorpayService` declare `namespace App\Services` and must physically live in `app/Services/`, not `app/Services/Auth/` (where the OTP/login services correctly live). A misplacement here passed locally (stale/optimized autoloader) but broke on a fresh `composer install --no-dev` in production. Check this if you ever see "Target class does not exist" in prod but not locally.
3. **DB host migration to Webeyesoft may not be finished in `.env` yet** — verify `DB_HOST` before assuming connectivity issues are something else. The old Hostinger DB account was found locked (repeated failed logins) — consistent with the password-exposure issue below; that DB is being retired, don't reuse its password on the new one.
4. **DB password was exposed in chat at least once** — rotate any DB password that's ever been shared in plaintext.
5. **OTP bypass `0000`** works in all non-production environments — must be removed before real users go live (see `OtpService`).
6. **No `role:owner` route middleware exists** — owner-scoping is done in each controller via `$request->user()->gym`. Follow this pattern for new owner endpoints; don't invent a role middleware inconsistent with the rest of the codebase.
7. **`.env` is not in git** — create manually on server after a fresh deploy: `cp .env.example .env && nano .env && php artisan key:generate`.
8. **No automated tests** — `tests/` is the default unmodified Laravel scaffold. Every change needs manual verification (this matters especially for the ongoing Laravel upgrade hops).
9. **Mail isn't configured for real delivery by default** — membership reminders will fail gracefully (caught, returns a clean error) rather than crash, but won't actually send until `MAIL_*` points at a real provider.

---

## Local Development Setup

```bash
# Clone and install
git clone git@github.com:Raj504/webiste-v1.git
cd webiste-v1
composer install

# Environment
cp .env.example .env
php artisan key:generate
# fill in DB credentials for local MySQL, plus RAZORPAY_*, ADMIN_USERNAME/PASSWORD if testing those flows

# Database
php artisan migrate
php artisan db:seed --class=AmenitiesSeeder

# Serve
php artisan serve
# → http://127.0.0.1:8000
```

Requires PHP 8.2+ locally (matches the `^8.0.2` constraint in `composer.json`).

**Test credentials (local):**
- Phone: any number
- OTP: `0000`
- Role: `traveler` or `owner`

---

## Commission Math Example

```
Gym lists 1 Day Pass for ₹100
Traveler pays ₹100 via Razorpay
Razorpay deducts ~₹2.36 (2% + GST)
GymPass receives ~₹97.64
GymPass pays gym ₹90 via UPI (90% of ₹100)
GymPass keeps ~₹7.64 net margin

Settlement record stores:
  gross_amount:      100
  commission_amount: 10   (10% of gross)
  payout_amount:     90   (90% of gross)
  payout_status:     pending → paid (marked manually via /admin/settlements)
```
