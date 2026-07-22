<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Iron Temple Gym – GymPass India</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">
    {{-- Razorpay checkout script --}}
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body>

    <header class="topnav">
        <a href="{{ route('index') }}" class="topnav-logo">GymPass<span>.</span>in</a>
        <nav class="topnav-links">
            <a href="{{ route('search') }}" class="topnav-link">← Back to results</a>
        </nav>
        <div class="topnav-actions">
            <a href="{{ route('login') }}" class="btn btn--ghost btn--sm" id="loginBtn">Log in</a>
            <a href="{{ route('signup') }}" class="btn btn--primary btn--sm" id="signupBtn">Sign up</a>
        </div>
    </header>

    <div class="gym-detail-layout">

        <!-- MAIN COLUMN -->
        <div>
            <!-- Hero image -->
            <div class="gym-hero-img mb-20">
                <div class="gym-hero-img__inner">🏋️</div>
                <div class="gym-hero-img__overlay">
                    <span class="pill pill--green">Open Now · 6AM–10PM</span>
                </div>
            </div>

            <!-- Gym info -->
            <div class="flex items-start justify-between mb-16 flex-wrap gap-12">
                <div>
                    <h1 class="t-display" style="font-size:28px;margin-bottom:4px">Iron Temple Gym</h1>
                    <div class="flex items-center gap-12">
                        <span class="t-muted">📍 Near Ram Jhula, Rishikesh</span>
                        <span class="t-yellow">★★★★★</span>
                        <span style="font-weight:600">4.8</span>
                        <span class="t-muted">(42 reviews)</span>
                    </div>
                </div>
                <div class="flex gap-8">
                    <button class="btn btn--ghost btn--sm">🔗 Share</button>
                    <button class="btn btn--ghost btn--sm">♡ Save</button>
                </div>
            </div>

            <!-- Tabs -->
            <div class="detail-tabs mb-20">
                <button class="detail-tab is-active" onclick="showTab(this,'overview')">Overview</button>
                <button class="detail-tab" onclick="showTab(this,'amenities')">Amenities</button>
                <button class="detail-tab" onclick="showTab(this,'reviews')">Reviews (42)</button>
            </div>

            <!-- Overview tab -->
            <div class="detail-tab-pane is-active" id="tab-overview">
                <p class="mb-16" style="color:var(--text-secondary);line-height:1.8">
                    A serious gym with free weights, machines, and experienced trainers. Located walking distance from
                    Ram Jhula bridge. Clean, AC-equipped, open 7 days a week. Popular with traveler athletes and yoga
                    retreat guests looking for strength training to complement their yoga practice.
                </p>
                <div class="grid-3 mb-20">
                    <div class="info-tile">
                        <div class="info-tile__icon">⏰</div>
                        <div class="info-tile__label">Hours</div>
                        <div class="info-tile__val">6AM – 10PM</div>
                    </div>
                    <div class="info-tile">
                        <div class="info-tile__icon">📍</div>
                        <div class="info-tile__label">Area</div>
                        <div class="info-tile__val">Ram Jhula</div>
                    </div>
                    <div class="info-tile">
                        <div class="info-tile__icon">📞</div>
                        <div class="info-tile__label">Phone</div>
                        <div class="info-tile__val">+91 98765 43210</div>
                    </div>
                </div>

                {{-- V1 callout — no QR yet --}}
                <div class="callout mb-20">
                    <span class="callout__icon">✅</span>
                    <span>After booking, the gym owner will be notified and your entry will be confirmed. Just show your booking reference at reception.</span>
                </div>
            </div>

            <!-- Amenities tab -->
            <div class="detail-tab-pane" id="tab-amenities">
                <div class="grid-3">
                    <div class="amenity-item">🧊 Air Conditioning</div>
                    <div class="amenity-item">🔒 Lockers</div>
                    <div class="amenity-item">🚿 Shower Room</div>
                    <div class="amenity-item">🅿️ Parking</div>
                    <div class="amenity-item">👨‍💼 Personal Trainer</div>
                    <div class="amenity-item">💪 Free Weights</div>
                    <div class="amenity-item">🏃 Treadmills</div>
                    <div class="amenity-item">📻 Music System</div>
                </div>
            </div>

            <!-- Reviews tab -->
            <div class="detail-tab-pane" id="tab-reviews">
                <div class="flex items-center gap-16 mb-20">
                    <div class="text-center">
                        <div class="t-display t-yellow" style="font-size:48px">4.8</div>
                        <div class="t-yellow" style="font-size:20px">★★★★★</div>
                        <div class="t-muted" style="font-size:12px">42 reviews</div>
                    </div>
                    <div class="flex-1">
                        <div class="rating-bar__row">
                            <span class="rating-bar__label">5★</span>
                            <div class="rating-bar__track"><div class="rating-bar__fill" style="width:75%"></div></div>
                            <span class="rating-bar__count">31</span>
                        </div>
                        <div class="rating-bar__row">
                            <span class="rating-bar__label">4★</span>
                            <div class="rating-bar__track"><div class="rating-bar__fill" style="width:18%"></div></div>
                            <span class="rating-bar__count">8</span>
                        </div>
                        <div class="rating-bar__row">
                            <span class="rating-bar__label">3★</span>
                            <div class="rating-bar__track"><div class="rating-bar__fill" style="width:5%"></div></div>
                            <span class="rating-bar__count">2</span>
                        </div>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-card__header">
                        <div class="flex items-center gap-12">
                            <div class="review-card__avatar">👨</div>
                            <div>
                                <div class="review-card__name">Arjun Sharma</div>
                                <div class="review-card__meta">Traveled from Bangalore · Per Day Pass</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="review-card__stars">★★★★★</div>
                            <div class="review-card__date">Mar 8</div>
                        </div>
                    </div>
                    <div class="review-card__body">"Best gym in Rishikesh for travelers. Check-in was smooth. Equipment is top-notch. Will definitely use GymPass again!"</div>
                </div>
                <div class="review-card">
                    <div class="review-card__header">
                        <div class="flex items-center gap-12">
                            <div class="review-card__avatar">👩</div>
                            <div>
                                <div class="review-card__name">Priya Mehta</div>
                                <div class="review-card__meta">Traveled from Delhi · 3 Days</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="review-card__stars">★★★★★</div>
                            <div class="review-card__date">Mar 7</div>
                        </div>
                    </div>
                    <div class="review-card__body">"Loved the cleanliness and variety of equipment. Overall 5/5!"</div>
                </div>
            </div>
        </div>

        <!-- STICKY SIDEBAR -->
        <div class="gym-detail-sidebar">
            <div class="booking-card">
                <div class="booking-card__header">
                    <div class="t-label mb-8">Choose your plan</div>
                    <div class="flex flex-col gap-8" id="planOptions">
                        {{--
                            In production: FE renders plans dynamically from API response.
                            GET /api/gyms/{id}/plans → returns enabled plans for this gym.
                            Each plan has: id, name, price, duration, unit
                        --}}
                        <div class="plan-option is-selected" onclick="selectPlan(this, 1, 80, '1 Day Pass', '₹80/day')">
                            <div>
                                <div class="plan-option__name">1 Day Pass</div>
                                <div class="plan-option__rate t-muted">₹80/day</div>
                            </div>
                            <div class="plan-option__price t-brand t-display">₹80</div>
                        </div>
                        <div class="plan-option" onclick="selectPlan(this, 2, 200, '3 Day Pass', '₹67/day · Save 16%')">
                            <div>
                                <div class="plan-option__name">3 Day Pass</div>
                                <div class="plan-option__rate t-muted">₹67/day · Save 16%</div>
                            </div>
                            <div class="plan-option__price t-brand t-display">₹200</div>
                        </div>
                        <div class="plan-option" onclick="selectPlan(this, 3, 400, '7 Day Pass', '₹57/day · Save 29%')">
                            <div>
                                <div class="plan-option__name">7 Day Pass <span class="badge badge--up">Best Value</span></div>
                                <div class="plan-option__rate t-muted">₹57/day · Save 29%</div>
                            </div>
                            <div class="plan-option__price t-brand t-display">₹400</div>
                        </div>
                        <div class="plan-option" onclick="selectPlan(this, 4, 800, '1 Month Pass', '₹27/day · Best rate')">
                            <div>
                                <div class="plan-option__name">1 Month Pass</div>
                                <div class="plan-option__rate t-muted">₹27/day · Best rate</div>
                            </div>
                            <div class="plan-option__price t-brand t-display">₹800</div>
                        </div>
                    </div>
                </div>

                <div class="booking-card__body">
                    <div class="flex justify-between mb-12" style="font-size:13px">
                        <span class="t-muted">Selected Plan</span>
                        <span id="selPlan" style="font-weight:600">1 Day Pass</span>
                    </div>
                    <div class="flex justify-between mb-20" style="font-size:13px">
                        <span class="t-muted">Total</span>
                        <span id="selPrice" class="t-brand t-display" style="font-size:22px">₹80</span>
                    </div>

                    {{-- Book Now button --}}
                    <button class="btn btn--primary btn--full" id="bookBtn" onclick="startBooking()" style="padding:14px;justify-content:center">
                        <span id="bookBtnLabel">Book Now →</span>
                        <span id="bookBtnSpinner" class="btn__spinner hidden"></span>
                    </button>

                    {{-- Not logged in state --}}
                    <div id="loginPrompt" class="hidden">
                        <p class="t-muted text-center mt-8" style="font-size:12px">
                            <a href="{{ route('login') }}" class="t-brand">Log in</a> or
                            <a href="{{ route('signup') }}" class="t-brand">sign up</a> to book
                        </p>
                    </div>

                    <p class="t-muted text-center mt-8" style="font-size:11px" id="bookNote">
                        Secure payment via Razorpay · UPI / Card / Netbanking
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ BOOKING SUCCESS MODAL (V1 — no QR) ══ --}}
    <div class="modal-overlay hidden" id="successModal">
        <div class="modal" style="max-width:420px">
            <div style="font-size:64px;margin-bottom:16px;text-align:center">🎉</div>
            <div class="t-display text-center mb-8" style="font-size:24px">Booking Confirmed!</div>
            <p class="t-muted text-center mb-20" style="font-size:14px;line-height:1.6">
                Your booking is confirmed. The gym owner has been notified and your entry is approved.
            </p>

            {{-- Booking ref --}}
            <div class="booking-ref-box">
                <div class="booking-ref-box__label">Your Booking Reference</div>
                <div class="booking-ref-box__ref" id="successRef">GP-2025-XXXXXX</div>
                <button class="booking-ref-box__copy" onclick="copyRef()" id="copyBtn">Copy</button>
            </div>

            {{-- Details --}}
            <div class="booking-summary-row">
                <span class="t-muted">Gym</span>
                <span id="successGym" style="font-weight:600">Iron Temple Gym</span>
            </div>
            <div class="booking-summary-row">
                <span class="t-muted">Plan</span>
                <span id="successPlan" style="font-weight:600">1 Day Pass</span>
            </div>
            <div class="booking-summary-row">
                <span class="t-muted">Valid</span>
                <span id="successDate" style="font-weight:600">—</span>
            </div>
            <div class="booking-summary-row" style="border-bottom:none">
                <span class="t-muted">Amount Paid</span>
                <span id="successAmount" class="t-brand" style="font-weight:700;font-size:16px">₹80</span>
            </div>

            <div class="callout callout--green mt-16 mb-16">
                <span class="callout__icon">💡</span>
                Show your booking reference at the gym reception. They are expecting you.
            </div>

            <button class="btn btn--primary btn--full" style="justify-content:center" onclick="closeModal()">Done</button>
        </div>
    </div>

    {{-- ══ PAYMENT FAILED MODAL ══ --}}
    <div class="modal-overlay hidden" id="failedModal">
        <div class="modal" style="max-width:380px;text-align:center">
            <div style="font-size:56px;margin-bottom:16px">❌</div>
            <div class="t-display mb-8" style="font-size:22px">Payment Failed</div>
            <p class="t-muted mb-20" style="font-size:14px;line-height:1.6" id="failedMsg">
                Something went wrong. Your money has not been deducted.
            </p>
            <div class="flex gap-8">
                <button class="btn btn--ghost" style="flex:1;justify-content:center" onclick="closeFailedModal()">Cancel</button>
                <button class="btn btn--primary" style="flex:1;justify-content:center" onclick="closeFailedModal();startBooking()">Try Again</button>
            </div>
        </div>
    </div>

    <style>
        /* Booking ref box */
        .booking-ref-box {
            background: var(--surface-3);
            border: 1px solid var(--border-1);
            border-radius: var(--r-md);
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            gap: 12px;
        }
        .booking-ref-box__label {
            font-size: 11px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        .booking-ref-box__ref {
            font-family: var(--font-mono);
            font-size: 16px;
            font-weight: 700;
            color: var(--brand);
            letter-spacing: 0.05em;
        }
        .booking-ref-box__copy {
            font-size: 12px;
            padding: 5px 12px;
            border-radius: var(--r-sm);
            border: 1px solid var(--border-1);
            background: var(--surface-2);
            color: var(--text-secondary);
            cursor: pointer;
            flex-shrink: 0;
            font-family: var(--font-body);
            transition: all var(--t-fast);
        }
        .booking-ref-box__copy:hover { color: var(--text-primary); border-color: var(--border-2); }
        .booking-ref-box__copy.copied { color: var(--green); border-color: var(--green); }

        /* Booking summary rows in modal */
        .booking-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-1);
            font-size: 14px;
        }

        /* Book button loading state */
        #bookBtn.is-loading #bookBtnLabel { opacity: 0.5; }
        #bookBtn.is-loading #bookBtnSpinner { display: inline-block !important; }
    </style>

    <script>
        // ── State ─────────────────────────────────────────────────────────────
        // In production: populate from auth token stored in localStorage
        const AUTH_TOKEN = localStorage.getItem('gympass_token') || null;

        // Selected plan state
        let selectedPlanId    = 1;
        let selectedPlanPrice = 80;
        let selectedPlanName  = '1 Day Pass';

        // ── Tab switcher ──────────────────────────────────────────────────────
        function showTab(btn, id) {
            document.querySelectorAll('.detail-tab').forEach(t => t.classList.remove('is-active'));
            document.querySelectorAll('.detail-tab-pane').forEach(p => p.classList.remove('is-active'));
            btn.classList.add('is-active');
            document.getElementById('tab-' + id).classList.add('is-active');
        }

        // ── Plan selection ────────────────────────────────────────────────────
        function selectPlan(el, planId, price, name, rate) {
            document.querySelectorAll('.plan-option').forEach(o => o.classList.remove('is-selected'));
            el.classList.add('is-selected');
            selectedPlanId    = planId;
            selectedPlanPrice = price;
            selectedPlanName  = name;
            document.getElementById('selPlan').textContent  = name;
            document.getElementById('selPrice').textContent = '₹' + price;
        }

        // ── Book Now ──────────────────────────────────────────────────────────
        async function startBooking() {
            // Guard: must be logged in
            if (!AUTH_TOKEN) {
                document.getElementById('loginPrompt').classList.remove('hidden');
                document.getElementById('bookNote').classList.add('hidden');
                return;
            }

            // Set loading state
            const btn = document.getElementById('bookBtn');
            btn.classList.add('is-loading');
            btn.disabled = true;

            try {
                // Step 1 — Create Razorpay order
                const orderRes = await fetch('/api/bookings/create-order', {
                    method:  'POST',
                    headers: {
                        'Content-Type':  'application/json',
                        'Authorization': 'Bearer ' + AUTH_TOKEN,
                        'Accept':        'application/json',
                    },
                    body: JSON.stringify({ plan_id: selectedPlanId }),
                });

                const orderData = await orderRes.json();

                if (!orderData.success) {
                    throw new Error(orderData.message || 'Failed to create order.');
                }

                const order = orderData.data;

                // Step 2 — Open Razorpay checkout
                const options = {
                    key:         order.key_id,
                    amount:      order.amount_paise,
                    currency:    order.currency,
                    order_id:    order.razorpay_order_id,
                    name:        'GymPass India',
                    description: order.plan_name + ' — ' + order.gym_name,
                    image:       '/images/gympass-logo.png', // add your logo
                    theme:       { color: '#FF5C1A' },
                    modal: {
                        ondismiss: () => {
                            // User closed checkout without paying
                            resetBookBtn();
                        }
                    },
                    handler: async (response) => {
                        // Step 3 — Verify payment on our backend
                        await verifyPayment(order, response);
                    },
                };

                const rzp = new Razorpay(options);
                rzp.on('payment.failed', (response) => {
                    resetBookBtn();
                    showFailedModal(response.error.description || 'Payment failed. Please try again.');
                });
                rzp.open();

            } catch (err) {
                resetBookBtn();
                showFailedModal(err.message);
            }
        }

        async function verifyPayment(order, rzpResponse) {
            try {
                const verifyRes = await fetch('/api/bookings/verify-payment', {
                    method:  'POST',
                    headers: {
                        'Content-Type':  'application/json',
                        'Authorization': 'Bearer ' + AUTH_TOKEN,
                        'Accept':        'application/json',
                    },
                    body: JSON.stringify({
                        booking_id:           order.booking_id,
                        razorpay_order_id:    rzpResponse.razorpay_order_id,
                        razorpay_payment_id:  rzpResponse.razorpay_payment_id,
                        razorpay_signature:   rzpResponse.razorpay_signature,
                    }),
                });

                const verifyData = await verifyRes.json();

                resetBookBtn();

                if (!verifyData.success) {
                    throw new Error(verifyData.message || 'Verification failed.');
                }

                showSuccessModal(verifyData.data.booking);

            } catch (err) {
                resetBookBtn();
                showFailedModal('Payment was received but verification failed. Contact support with your booking reference.');
            }
        }

        // ── Modals ────────────────────────────────────────────────────────────
        function showSuccessModal(booking) {
            document.getElementById('successRef').textContent    = booking.booking_ref;
            document.getElementById('successGym').textContent    = booking.gym_name;
            document.getElementById('successPlan').textContent   = booking.plan_name;
            document.getElementById('successDate').textContent   = booking.start_date + (booking.start_date !== booking.end_date ? ' – ' + booking.end_date : '');
            document.getElementById('successAmount').textContent = '₹' + booking.amount;
            document.getElementById('successModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('successModal').classList.add('hidden');
        }

        function showFailedModal(msg) {
            document.getElementById('failedMsg').textContent = msg;
            document.getElementById('failedModal').classList.remove('hidden');
        }

        function closeFailedModal() {
            document.getElementById('failedModal').classList.add('hidden');
        }

        // ── Helpers ───────────────────────────────────────────────────────────
        function resetBookBtn() {
            const btn = document.getElementById('bookBtn');
            btn.classList.remove('is-loading');
            btn.disabled = false;
        }

        function copyRef() {
            const ref = document.getElementById('successRef').textContent;
            navigator.clipboard.writeText(ref).then(() => {
                const btn = document.getElementById('copyBtn');
                btn.textContent = 'Copied!';
                btn.classList.add('copied');
                setTimeout(() => {
                    btn.textContent = 'Copy';
                    btn.classList.remove('copied');
                }, 2000);
            });
        }

        // ── Auth state on load ────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            if (AUTH_TOKEN) {
                // Hide login/signup buttons, show logged-in state
                document.getElementById('loginBtn').style.display  = 'none';
                document.getElementById('signupBtn').style.display = 'none';
                // FE team: replace with user avatar / name dropdown
            }
        });
    </script>

</body>
</html>