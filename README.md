# GymPass India — Project README
> Last updated: August 2026
> For coding agents, new contributors, and handoff reference.

---

## What is GymPass?

GymPass.in is a gym day-pass marketplace for travelers in India. Travelers can book a gym for 1 day, 3 days, 7 days, or monthly — pay via UPI/Razorpay — and walk in with their booking reference. No long-term commitment, no awkward cash negotiations.

**Core idea:** A traveler in Rishikesh for 5 days shouldn't have to pay a full month's gym fee. GymPass lets gym owners list flexible plans, and travelers book exactly what they need.

**Business model:** GymPass collects full payment from travelers via Razorpay, keeps 10% commission, and manually pays the remaining 90% to gym owners via UPI every 3–5 days.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 8 (PHP 7.4 on Hostinger, PHP 8.2 locally) |
| Auth | Laravel Sanctum (token-based) |
| Database | MySQL |
| Frontend | Static Blade templates (UI reference) — React/Next.js (FE team builds this) |
| Payments | Razorpay |
| OTP | MSG91 (dev bypass: OTP `0000` always works) |
| Hosting | Hostinger shared hosting |
| Deploy | GitHub Actions → SSH → rsync |
| Domain | gympass.meritsphere.in |

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
│   │   │   │   │   ├── GymMediaController.php    ← photos/videos (ON HOLD)
│   │   │   │   │   └── GymPlanController.php     ← pricing plans CRUD
│   │   │   │   ├── BookingController.php         ← create order, verify payment
│   │   │   │   └── NearbyGymController.php       ← haversine gym search
│   │   │   └── GymController.php                 ← show, update, operating hours
│   │   ├── Middleware/
│   │   │   ├── CorsMiddleware.php                ← custom CORS (registered first)
│   │   │   └── EnsureUserHasRole.php             ← role:owner guard
│   │   └── Requests/Owner/
│   │       ├── UploadCoverRequest.php
│   │       ├── UnsplashCoverRequest.php
│   │       ├── UploadPhotosRequest.php
│   │       ├── UnsplashPhotoRequest.php
│   │       ├── ReorderPhotosRequest.php
│   │       ├── SaveVideoUrlRequest.php
│   │       ├── UploadVideoRequest.php
│   │       ├── CreateGymPlanRequest.php
│   │       ├── UpdateGymPlanRequest.php
│   │       ├── SyncAmenitiesRequest.php
│   │       └── AddCustomAmenityRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Gym.php
│   │   ├── GymPlan.php
│   │   ├── GymOperatingHours.php
│   │   ├── Amenity.php
│   │   ├── Booking.php
│   │   ├── Settlement.php
│   │   ├── OtpCode.php
│   │   └── PersonalAccessToken.php               ← custom Sanctum model (PHP 7.4 fix)
│   └── Services/
│       ├── OtpService.php
│       ├── TempTokenService.php
│       ├── RegisterService.php
│       ├── LoginService.php
│       ├── BookingService.php
│       ├── RazorpayService.php
│       └── Media/
│           ├── CloudinaryService.php             ← ON HOLD
│           └── GymMediaService.php               ← ON HOLD
├── database/migrations/
│   ├── create_users_table.php
│   ├── create_gyms_table.php
│   ├── create_otp_codes_table.php
│   ├── create_gym_plans_table.php
│   ├── create_gym_operating_hours_table.php
│   ├── create_amenities_tables.php               ← amenities + gym_amenity pivot
│   ├── create_bookings_and_settlements_table.php
│   ├── add_media_columns_to_gyms_table.php       ← ON HOLD
│   └── add_duration_unit_to_gym_plans_table.php
├── routes/
│   ├── api.php
│   └── health.php                                ← registered in RouteServiceProvider
└── .github/workflows/deploy.yml
```

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
| cover_photo | json | nullable — ON HOLD `{url, cloudinary_id, source}` |
| photos | json | ON HOLD — array of photo objects |
| videos | json | ON HOLD — `{youtube, instagram, upload}` |
| created_at, updated_at, deleted_at | timestamps | soft deletes |

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
| type | string | legacy column — kept for backward compat |
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

### `amenities`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| name | string | unique e.g. "AC", "Sauna" |
| icon | string | emoji e.g. "🧊" |
| is_default | boolean | true = seeded (12 defaults) |
| created_at, updated_at | timestamps | |

**Default amenities:** AC, Lockers, Shower, Parking, Trainer, Free Weights, Pool, Yoga Room, Protein Bar, TV/Music, WiFi, Towel Service.
AC + Lockers + Shower are pre-selected for all new gyms.

### `gym_amenity` (pivot)
| Column | Type | Notes |
|--------|------|-------|
| gym_id | FK → gyms | composite PK |
| amenity_id | FK → amenities | composite PK |

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
| booking_ref | string | unique e.g. `GP-2025-XK9F2A` |
| start_date | date | |
| end_date | date | calculated from plan duration |
| razorpay_order_id | string | nullable |
| razorpay_payment_id | string | nullable |
| razorpay_signature | string | nullable |
| amount | int | full amount paid in ₹ |
| status | enum | `pending`, `paid`, `cancelled`, `expired` |
| qr_code | string | nullable — unique token (V2 feature, stored but unused) |
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
| paid_at | timestamp | nullable — when manually paid |
| razorpay_payout_id | string | nullable — for future automation |
| created_at, updated_at | timestamps | |

### `personal_access_tokens` (Sanctum)
Standard Sanctum table. Custom `PersonalAccessToken` model registered in `AppServiceProvider` to fix PHP 7.4 double-encoding bug with abilities.

---

## API Routes

Base URL: `https://gympass.meritsphere.in/api`

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
GET  /gyms/{id}/plans                get enabled plans for a gym
```

### Protected — any authenticated user (`auth:sanctum`)

```
POST /auth/logout                    revoke token
GET  /auth/me                        current user info
GET  /gyms/{id}                      gym detail
PUT  /gyms/{id}                      update gym info
POST /gyms/{id}/operating-hours      save week schedule

GET  /bookings                       list my bookings
GET  /bookings/{id}                  booking detail + QR code
POST /bookings/create-order          step 1: create Razorpay order
POST /bookings/verify-payment        step 2: verify signature → activate booking
```

### Protected — owner only (`auth:sanctum` + `role:owner`)

```
GET  /owner/gym/plans                list all plans
POST /owner/gym/plans                create custom plan {duration, unit, price}
PUT  /owner/gym/plans/{planId}       update price or is_enabled
DEL  /owner/gym/plans/{planId}       delete custom plan (not defaults)

GET  /owner/gym/amenities            all amenities + is_selected per gym
POST /owner/gym/amenities/sync       save selection {amenity_ids: []}
POST /owner/gym/amenities/custom     add new amenity to global list {name, icon}

POST /owner/gym/cover/upload         upload cover photo (multipart)         ← ON HOLD
POST /owner/gym/cover/unsplash       set cover from Unsplash {url, photographer_name} ← ON HOLD
DEL  /owner/gym/cover                remove cover                           ← ON HOLD
GET  /owner/gym/photos               list gym photos                        ← ON HOLD
POST /owner/gym/photos/upload        upload photos (multipart: photos[])    ← ON HOLD
POST /owner/gym/photos/unsplash      add Unsplash photo                     ← ON HOLD
PUT  /owner/gym/photos/reorder       reorder {ids: [uuid...]}               ← ON HOLD
DEL  /owner/gym/photos/{photoId}     delete photo                           ← ON HOLD
POST /owner/gym/videos/url           save YT/Instagram URL {url, source}    ← ON HOLD
POST /owner/gym/videos/upload        upload video (multipart)               ← ON HOLD
DEL  /owner/gym/videos/{source}      remove video                           ← ON HOLD
```

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

3. On payment success → POST /bookings/verify-payment
   → verifies Razorpay signature
   → marks booking as paid
   → creates settlement record (gross/commission/payout split)
   → returns booking_ref + qr_code (qr_code unused in V1)

Money split: gross=₹100 → commission=₹10 (10%) → payout=₹90 to gym UPI
Manual payout every 3–5 days via GPay/PhonePe to gym owner's UPI ID
```

---

## Deployment

**Server:** Hostinger shared hosting
- IP: `82.25.125.167` · Port: `65002` · User: `u791137564`
- Laravel root: `/home/u791137564/domains/meritsphere.in/public_html/gympass`
- Document root: `.../gympass/public`
- PHP: 7.4 (upgrade to 8.2 planned before go-live)

**Deploy command (from local):**
```bash
composer deploy-to-live
# = git push origin main → triggers GitHub Actions
```

**GitHub Actions workflow:**
1. Checkout code
2. Setup PHP 7.4
3. `composer install --no-dev`
4. SSH setup (secret: `HOSTINGER_SSH_KEY`)
5. rsync (NO `--delete` flag — previously wiped other sites)
6. Post-deploy: `migrate --force`, `config:clear`, `cache:clear`, `route:clear`, `view:clear`

**CORS:** Custom `CorsMiddleware` registered as first middleware in `Kernel.php`. Allowed origins: `http://localhost:3000`, `https://gympass-nextjs.vercel.app`.

---

## Frontend (UI Reference Only)

The Blade templates are static UI references — the FE team builds in React/Next.js.

| Page | File | Status |
|------|------|--------|
| Homepage | `index.blade.php` | Static — FE to wire up |
| Search | `search.html` | Static — FE to wire up |
| Gym Detail | `gym-detail.blade.php` | Static + Razorpay JS wired |
| Owner Dashboard | `dashboard.html` | Static reference |
| Owner Bookings | `bookings.html` | Static reference |
| Owner Members | `members.html` | Static reference |
| Owner QR Scanner | `qr-scanner.html` | Static reference (V2) |
| Owner Payouts | `payouts.html` | Static reference |
| Owner Reviews | `reviews.html` | Static reference |
| Owner Analytics | `analytics.html` | Static reference |
| Owner Settings | `gym-settings.html` | Working JS — hours/plans/amenities |
| Login | `login.html` | Static reference |
| Signup | `signup.html` | Static reference |

**Design system:** `shared.css` — single source of truth, 20+ sections.
- Font: Syne (display), DM Sans (body), JetBrains Mono
- Brand color: `#FF5C1A` orange
- Dark theme: `--bg #080808`

---

## Key Services

### `OtpService`
- Rate limit: 5 OTPs/hour per phone
- Dev bypass: OTP `0000` always passes in non-production
- When MSG91 is ready: remove bypass block + uncomment `dispatch()` call

### `TempTokenService`
- 64-char random token, Cache-stored, 15 min TTL, single-use
- Bridge between OTP verify and register steps

### `RazorpayService`
- `createOrder(amount, receipt, notes)` → Razorpay order
- `verifySignature(orderId, paymentId, signature)` → bool
- `calculateSplit(grossAmount)` → `{gross, commission_pct, commission_amount, payout_amount}`

### `BookingService`
- `createOrder(user, plan)` → pending booking + Razorpay order
- `verifyAndActivate(bookingId, ...)` → verifies sig + marks paid + creates settlement (DB transaction)

---

## Environment Variables

```env
APP_NAME="GymPass India"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://gympass.meritsphere.in
APP_KEY=base64:...

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u791137564_gym_website
DB_USERNAME=u791137564_rajaryan8930
DB_PASSWORD=...

RAZORPAY_KEY_ID=rzp_test_...
RAZORPAY_KEY_SECRET=...
RAZORPAY_COMMISSION_PCT=10

CLOUDINARY_CLOUD_NAME=...       ← not yet configured (media ON HOLD)
CLOUDINARY_API_KEY=...
CLOUDINARY_API_SECRET=...
```

---

## What's Built vs What's Pending

### ✅ Built and working
- OTP auth (send, verify, register traveler, register owner, login, logout)
- Gym registration with default plans seeded
- Operating hours — custom per-day time picker, save to `gym_operating_hours`
- Gym plans — custom duration+unit+price, enable/disable, owner CRUD
- Amenities — global list, gym selection pivot, add custom amenity
- Razorpay payment — create order, verify payment, booking + settlement record
- Nearby gym search (haversine)
- Health check endpoints (`/api/health`, `/api/health/full`)
- GitHub Actions deploy pipeline
- CORS fix for React frontend
- Owner dashboard UI (static Blade reference)

### 🔴 On Hold (built, not activated)
- **Media API** — Cloudinary + Unsplash photo picker — `GymMediaController`, `CloudinaryService`, `GymMediaService` all built, migration ready, just not deployed/configured yet
- **Homepage nearby gyms** — geolocation + skeleton loaders UI ready in `outputs/gympass-homepage/`
- **Hours presets** — Standard/Early Bird/Extended/24-7 quick preset buttons built but removed from UI

### 📋 Not yet built (V1 remaining)
- Traveler booking history UI
- Gym owner dashboard notifications on new booking
- Owner payout tracking UI (mark settlement as paid)
- Gym owner — view bookings/members from their gym

### 🔮 V2 / Future
- **QR pass** — `qr_code` column already in `bookings` table, QR scanner page built (`qr-scanner.html`). V1 flow: payment verified → notify owner → manual check-in. V2: traveler shows QR → owner scans → auto check-in
- **Razorpay Payouts API** — automate manual UPI payouts via Laravel scheduled job (`ProcessSettlements` command)
- **Unsplash production approval** — submit after go-live (currently on demo 50 req/hr)
- **MSG91 production** — replace OTP `0000` bypass
- **PHP 8.2 upgrade** — Hostinger hPanel → PHP Configuration (2 min change)
- **Daily backups** — upgrade Hostinger plan to get daily instead of weekly backups

---

## Known Issues / Watch Out For

1. **PHP 7.4 on server** — no nullsafe `?->` operator, no named arguments. Use `optional()` instead.
2. **No `--delete` in rsync** — previously wiped `buagstore.in` and `meritsphere.in`. This flag is permanently removed from `deploy.yml`.
3. **OTP bypass** — `0000` works in all non-production environments. Remove before going live with real users.
4. **DB password exposed** — was shared in chat during `.env` setup. Change it in hPanel before launch.
5. **Sanctum abilities bug** — fixed via custom `PersonalAccessToken` model in `AppServiceProvider`. Do not remove.
6. **`.env` not in git** — create manually on server after first deploy: `cp .env.example .env && nano .env && php artisan key:generate`
7. **Weekly backups only** — Hostinger plan has weekly automated backups. Upgrade to daily before launch.

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
# fill in DB credentials for local MySQL

# Database
php artisan migrate
php artisan db:seed --class=AmenitiesSeeder

# Serve
php artisan serve
# → http://127.0.0.1:8000
```

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
  payout_status:     pending → paid (when manually transferred)
```