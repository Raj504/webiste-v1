<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Iron Temple Gym – GymPass India – GymPass India</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">

</head>

<body>

    <header class="topnav">
        <a href="index.html" class="topnav-logo">GymPass<span>.</span>in</a>
        <nav class="topnav-links">
            <a href="search.html" class="topnav-link">← Back to results</a>
        </nav>
        <div class="topnav-actions">
            <a href="login.html" class="btn btn--ghost btn--sm">Log in</a>
            <a href="signup.html" class="btn btn--primary btn--sm">Sign up</a>
        </div>
    </header>

    <div class="gym-detail-layout">

        <!-- MAIN COLUMN -->
        <div>
            <!-- Hero image area -->
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
                <div class="callout mb-20">
                    <span class="callout__icon">📲</span>
                    <span>Show your QR pass at reception. Works offline. One scan per day maximum.</span>
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
                        <div class="rating-bar__row"><span class="rating-bar__label">5★</span>
                            <div class="rating-bar__track">
                                <div class="rating-bar__fill" style="width:75%"></div>
                            </div><span class="rating-bar__count">31</span>
                        </div>
                        <div class="rating-bar__row"><span class="rating-bar__label">4★</span>
                            <div class="rating-bar__track">
                                <div class="rating-bar__fill" style="width:18%"></div>
                            </div><span class="rating-bar__count">8</span>
                        </div>
                        <div class="rating-bar__row"><span class="rating-bar__label">3★</span>
                            <div class="rating-bar__track">
                                <div class="rating-bar__fill" style="width:5%"></div>
                            </div><span class="rating-bar__count">2</span>
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
                    <div class="review-card__body">"Best gym in Rishikesh for travelers. The QR check-in was smooth,
                        was inside within 30 seconds. Equipment is top-notch. Will definitely use GymPass again!"</div>
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
                    <div class="review-card__body">"Loved the cleanliness and variety of equipment. My only suggestion
                        is to add more dumbbells in the heavier range. But overall 5/5!"</div>
                </div>
            </div>
        </div>

        <!-- STICKY SIDEBAR -->
        <div class="gym-detail-sidebar">
            <div class="booking-card">
                <div class="booking-card__header">
                    <div class="t-label mb-8">Choose your plan</div>
                    <div class="flex flex-col gap-8" id="planOptions">
                        <label class="plan-option is-selected" onclick="selectPlan(this,80,'Per Day')">
                            <div>
                                <div class="plan-option__name">Per Day</div>
                                <div class="plan-option__rate t-muted">₹80/day</div>
                            </div>
                            <div class="plan-option__price t-brand t-display">₹80</div>
                        </label>
                        <label class="plan-option" onclick="selectPlan(this,200,'3 Days')">
                            <div>
                                <div class="plan-option__name">3 Days</div>
                                <div class="plan-option__rate t-muted">₹67/day · Save 16%</div>
                            </div>
                            <div class="plan-option__price t-brand t-display">₹200</div>
                        </label>
                        <label class="plan-option" onclick="selectPlan(this,400,'7 Days')">
                            <div>
                                <div class="plan-option__name">7 Days <span class="badge badge--up">Best Value</span>
                                </div>
                                <div class="plan-option__rate t-muted">₹57/day · Save 29%</div>
                            </div>
                            <div class="plan-option__price t-brand t-display">₹400</div>
                        </label>
                        <label class="plan-option" onclick="selectPlan(this,800,'Monthly')">
                            <div>
                                <div class="plan-option__name">Monthly</div>
                                <div class="plan-option__rate t-muted">₹27/day · Best rate</div>
                            </div>
                            <div class="plan-option__price t-brand t-display">₹800</div>
                        </label>
                    </div>
                </div>
                <div class="booking-card__body">
                    <div class="flex justify-between mb-12" style="font-size:13px">
                        <span class="t-muted">Selected Plan</span>
                        <span id="selPlan" style="font-weight:600">Per Day</span>
                    </div>
                    <div class="flex justify-between mb-16" style="font-size:13px">
                        <span class="t-muted">Total</span>
                        <span id="selPrice" class="t-brand t-display" style="font-size:20px">₹80</span>
                    </div>
                    <div class="flex gap-8 mb-12">
                        <div class="pay-method is-active" onclick="selPay(this)">📱 UPI</div>
                        <div class="pay-method" onclick="selPay(this)">🔵 GPay</div>
                        <div class="pay-method" onclick="selPay(this)">💳 Card</div>
                    </div>
                    <button class="btn btn--primary" style="width:100%;justify-content:center;padding:14px"
                        onclick="book()">Book Now →</button>
                    <p class="t-muted text-center mt-8" style="font-size:11px">QR pass delivered instantly after
                        payment</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking success modal -->
    <div class="modal-overlay" id="successModal" style="display:none">
        <div class="modal">
            <div style="font-size:64px;margin-bottom:16px;text-align:center">✅</div>
            <div class="t-display text-center mb-8" style="font-size:24px">Booking confirmed!</div>
            <p class="t-muted text-center mb-20">Your QR pass is ready. Show this at reception.</p>
            <div class="qr-placeholder">
                <div style="font-size:80px">▣</div>
                <div style="font-family:var(--font-mono);font-size:12px;margin-top:8px" id="bookingId">GP-2025-4982
                </div>
            </div>
            <div class="flex gap-8 mt-20">
                <button class="btn btn--ghost" style="flex:1;justify-content:center"
                    onclick="closeModal()">Close</button>
                <button class="btn btn--primary" style="flex:1;justify-content:center">💾 Save Pass</button>
            </div>
        </div>
    </div>

    <script>
        function showTab(btn, id) {
            document.querySelectorAll('.detail-tab').forEach(t => t.classList.remove('is-active'));
            document.querySelectorAll('.detail-tab-pane').forEach(p => p.classList.remove('is-active'));
            btn.classList.add('is-active');
            document.getElementById('tab-' + id).classList.add('is-active');
        }

        function selectPlan(el, price, name) {
            document.querySelectorAll('.plan-option').forEach(o => o.classList.remove('is-selected'));
            el.classList.add('is-selected');
            document.getElementById('selPlan').textContent = name;
            document.getElementById('selPrice').textContent = '₹' + price;
        }

        function selPay(el) {
            document.querySelectorAll('.pay-method').forEach(m => m.classList.remove('is-active'));
            el.classList.add('is-active');
        }

        function book() {
            document.getElementById('successModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
        }
    </script>

</body>

</html>
