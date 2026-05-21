<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Settings – GymPass Owner</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">

</head>

<body>
    <div class="dash-layout">

        @include('partials.gym-sidebar')


        <div class="dash-main">
            <header class="dash-topbar">
                <div class="dash-topbar__left">
                    <div class="dash-topbar__title">Gym Settings</div>
                    <div class="dash-topbar__sub">Manage your gym profile, pricing &amp; availability</div>
                </div>
                <div class="dash-topbar__right">
                    <button class="btn btn--ghost btn--sm">👁️ Preview Listing</button>
                </div>
            </header>

            <main class="dash-content">
                <div class="settings-layout anim-fade-up">

                    <!-- LEFT NAV -->
                    <nav class="settings-nav">
                        <div class="settings-nav__item is-active" onclick="showSection('basic', this)">🏋️ Basic Info
                        </div>
                        <div class="settings-nav__item" onclick="showSection('pricing', this)">💰 Pricing</div>
                        <div class="settings-nav__item" onclick="showSection('hours', this)">🕐 Hours</div>
                        <div class="settings-nav__item" onclick="showSection('amenities', this)">✨ Amenities</div>
                        <div class="settings-nav__item" onclick="showSection('notifications', this)">🔔 Notifications
                        </div>
                        <div class="settings-nav__item" onclick="showSection('payout', this)">💳 Payout</div>
                        <div class="settings-nav__item t-red" onclick="showSection('danger', this)">⚠️ Danger Zone</div>
                    </nav>

                    <!-- RIGHT CONTENT -->
                    <div>

                        <!-- BASIC INFO -->
                        <div id="sec-basic" class="settings-section is-active">
                            <div class="settings-section__title">Basic Info</div>
                            <div class="settings-section__sub">Your public gym listing information.</div>
                            <div class="panel">
                                <div class="panel__body">
                                    <div class="field--row">
                                        <div class="field">
                                            <label class="field__label">Gym Name</label>
                                            <input class="field__input" type="text" value="Iron Temple Gym">
                                        </div>
                                        <div class="field">
                                            <label class="field__label">Owner Name</label>
                                            <input class="field__input" type="text" value="Vikram Singh">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="field__label">Address / Locality</label>
                                        <input class="field__input" type="text"
                                            value="Near Ram Jhula, Laxman Jhula Road, Rishikesh">
                                    </div>
                                    <div class="field--row">
                                        <div class="field">
                                            <label class="field__label">City</label>
                                            <select class="field__input">
                                                <option selected>Rishikesh</option>
                                                <option>Goa</option>
                                                <option>Manali</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label class="field__label">Phone</label>
                                            <input class="field__input" type="tel" value="+91 98765 43210">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="field__label">Description</label>
                                        <textarea class="field__input" rows="3">A serious gym with free weights, machines, and experienced trainers. Located walking distance from Ram Jhula bridge. Clean, AC-equipped, open 7 days a week.</textarea>
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">Changes go live immediately</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PRICING -->
                        <div id="sec-pricing" class="settings-section">
                            <div class="settings-section__title">Pricing</div>
                            <div class="settings-section__sub">Set your monthly rate — all other plans are
                                auto-calculated.</div>
                            <div class="panel">
                                <div class="panel__body">
                                    <div class="field">
                                        <label class="field__label">Monthly Membership Rate (₹)</label>
                                        <input class="field__input t-display t-brand" id="rateInput" type="number"
                                            value="800" oninput="updatePricingPreview()">
                                    </div>
                                    <div class="pricing-preview mb-16">
                                        <div class="t-label pricing-preview__label">Auto-calculated plans</div>
                                        <div class="pricing-preview__row">
                                            <span>Per Day <span class="t-muted">· 10% of monthly</span></span>
                                            <span class="pricing-preview__price" id="pd">₹80</span>
                                        </div>
                                        <div class="pricing-preview__row">
                                            <span>3 Days <span class="t-muted">· 25% of monthly</span></span>
                                            <span class="pricing-preview__price" id="p3">₹200</span>
                                        </div>
                                        <div class="pricing-preview__row">
                                            <span>7 Days <span class="t-muted">· 50% of monthly</span></span>
                                            <span class="pricing-preview__price" id="p7">₹400</span>
                                        </div>
                                        <div class="pricing-preview__row">
                                            <span class="t-brand">Monthly</span>
                                            <span class="pricing-preview__price" id="pm">₹800</span>
                                        </div>
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">Applies to future bookings only</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Update
                                            Pricing</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- HOURS -->
                        <div id="sec-hours" class="settings-section">
                            <div class="settings-section__title">Operating Hours</div>
                            <div class="settings-section__sub">Your opening and closing times each day.</div>
                            <div class="panel">
                                <div class="panel__body">
                                    <div class="hours-grid mb-20">
                                        <div class="hour-card" onclick="alert('Edit Mon–Fri hours')">
                                            <div class="hour-card__day">Mon – Fri</div>
                                            <div class="hour-card__time">6:00 AM – 10:00 PM</div>
                                        </div>
                                        <div class="hour-card" onclick="alert('Edit Saturday hours')">
                                            <div class="hour-card__day">Saturday</div>
                                            <div class="hour-card__time">7:00 AM – 9:00 PM</div>
                                        </div>
                                        <div class="hour-card is-closed" onclick="alert('Edit Sunday hours')">
                                            <div class="hour-card__day">Sunday</div>
                                            <div class="hour-card__time">Closed</div>
                                        </div>
                                    </div>
                                    <div class="toggle">
                                        <div class="toggle__info">
                                            <div class="toggle__title">Mark as Closed Today</div>
                                            <div class="toggle__desc">Temporarily close for a holiday or maintenance
                                            </div>
                                        </div>
                                        <label class="toggle__switch">
                                            <input type="checkbox">
                                            <span class="toggle__track"></span>
                                        </label>
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">Travelers see real-time open/closed status</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Save
                                            Hours</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AMENITIES -->
                        <div id="sec-amenities" class="settings-section">
                            <div class="settings-section__title">Amenities</div>
                            <div class="settings-section__sub">Select what your gym offers — shown on your listing.
                            </div>
                            <div class="panel">
                                <div class="panel__body">
                                    <div class="amenity-grid mb-16">
                                        <button class="amenity-chip is-selected"
                                            onclick="this.classList.toggle('is-selected')">🧊 AC</button>
                                        <button class="amenity-chip is-selected"
                                            onclick="this.classList.toggle('is-selected')">🔒 Lockers</button>
                                        <button class="amenity-chip is-selected"
                                            onclick="this.classList.toggle('is-selected')">🚿 Shower</button>
                                        <button class="amenity-chip is-selected"
                                            onclick="this.classList.toggle('is-selected')">🅿️ Parking</button>
                                        <button class="amenity-chip is-selected"
                                            onclick="this.classList.toggle('is-selected')">👨‍💼 Trainer</button>
                                        <button class="amenity-chip is-selected"
                                            onclick="this.classList.toggle('is-selected')">💪 Free Weights</button>
                                        <button class="amenity-chip" onclick="this.classList.toggle('is-selected')">🏊
                                            Pool</button>
                                        <button class="amenity-chip" onclick="this.classList.toggle('is-selected')">🧘
                                            Yoga Room</button>
                                        <button class="amenity-chip" onclick="this.classList.toggle('is-selected')">🥤
                                            Protein Bar</button>
                                        <button class="amenity-chip" onclick="this.classList.toggle('is-selected')">📺
                                            TV / Music</button>
                                        <button class="amenity-chip" onclick="this.classList.toggle('is-selected')">🌐
                                            WiFi</button>
                                        <button class="amenity-chip" onclick="this.classList.toggle('is-selected')">🧺
                                            Towel Service</button>
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">Helps travelers filter gyms by amenity</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Save
                                            Amenities</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- NOTIFICATIONS -->
                        <div id="sec-notifications" class="settings-section">
                            <div class="settings-section__title">Notifications</div>
                            <div class="settings-section__sub">Choose how you want to be notified about bookings.</div>
                            <div class="panel">
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">New Booking Alert</div>
                                        <div class="toggle__desc">Get SMS when a traveler books your gym</div>
                                    </div>
                                    <label class="toggle__switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle__track"></span>
                                    </label>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Payout Notification</div>
                                        <div class="toggle__desc">Get notified when your payout is transferred</div>
                                    </div>
                                    <label class="toggle__switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle__track"></span>
                                    </label>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">New Review Alert</div>
                                        <div class="toggle__desc">Get notified when a member leaves a review</div>
                                    </div>
                                    <label class="toggle__switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle__track"></span>
                                    </label>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Member Renewal Reminders</div>
                                        <div class="toggle__desc">7 days before a local member's plan expires</div>
                                    </div>
                                    <label class="toggle__switch">
                                        <input type="checkbox">
                                        <span class="toggle__track"></span>
                                    </label>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Daily Summary Report</div>
                                        <div class="toggle__desc">SMS with daily bookings &amp; revenue summary at 10
                                            PM</div>
                                    </div>
                                    <label class="toggle__switch">
                                        <input type="checkbox">
                                        <span class="toggle__track"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- PAYOUT -->
                        <div id="sec-payout" class="settings-section">
                            <div class="settings-section__title">Payout Settings</div>
                            <div class="settings-section__sub">How you receive your earnings.</div>
                            <div class="panel">
                                <div class="panel__body">
                                    <div class="field">
                                        <label class="field__label">UPI ID</label>
                                        <input class="field__input" type="text" value="irontemple@paytm">
                                    </div>
                                    <div class="field">
                                        <label class="field__label">Account Holder Name</label>
                                        <input class="field__input" type="text" value="Vikram Singh">
                                    </div>
                                    <div class="callout mb-16">
                                        <span class="callout__icon">ℹ️</span>
                                        Payouts are processed every Monday at 10 AM for the previous week. Minimum
                                        payout threshold is ₹100.
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">UPI changes apply to next payout cycle</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Update Payout
                                            Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DANGER ZONE -->
                        <div id="sec-danger" class="settings-section">
                            <div class="settings-section__title t-red">Danger Zone</div>
                            <div class="settings-section__sub">Irreversible actions — proceed carefully.</div>
                            <div class="panel panel--danger">
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Pause Gym Listing</div>
                                        <div class="toggle__desc">Hide your gym from traveler searches temporarily.
                                            Existing bookings remain valid.</div>
                                    </div>
                                    <button class="btn btn--danger btn--sm"
                                        onclick="if(confirm('Pause your listing?')) alert('Listing paused.')">Pause
                                        Listing</button>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Delete Gym Account</div>
                                        <div class="toggle__desc">Permanently remove your gym and all data. This cannot
                                            be undone.</div>
                                    </div>
                                    <button class="btn btn--danger btn--sm"
                                        onclick="if(confirm('Are you absolutely sure?')) alert('Contact support@gympass.in to complete deletion.')">Delete
                                        Account</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function showSection(id, el) {
            document.querySelectorAll('.settings-section').forEach(s => s.classList.remove('is-active'));
            document.querySelectorAll('.settings-nav__item').forEach(s => s.classList.remove('is-active'));
            document.getElementById('sec-' + id).classList.add('is-active');
            el.classList.add('is-active');
        }

        function updatePricingPreview() {
            const r = parseInt(document.getElementById('rateInput').value) || 0;
            document.getElementById('pd').textContent = '₹' + Math.round(r * 0.10);
            document.getElementById('p3').textContent = '₹' + Math.round(r * 0.25);
            document.getElementById('p7').textContent = '₹' + Math.round(r * 0.50);
            document.getElementById('pm').textContent = '₹' + r;
        }

        function savedFeedback(btn) {
            const orig = btn.textContent;
            btn.textContent = '✓ Saved!';
            btn.style.background = 'var(--green)';
            setTimeout(() => {
                btn.textContent = orig;
                btn.style.background = '';
            }, 2000);
        }
    </script>
</body>

</html>
