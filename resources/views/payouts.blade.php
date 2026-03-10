<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payouts – GymPass Owner</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">

</head>

<body>
    <div class="dash-layout">

        @include('partials.gym-sidebar')


        <div class="dash-main">
            <header class="dash-topbar">
                <div class="dash-topbar__left">
                    <div class="dash-topbar__title">Payouts</div>
                    <div class="dash-topbar__sub">Your earnings &amp; transfer history</div>
                </div>
                <div class="dash-topbar__right">
                    <button class="btn btn--ghost btn--sm">⬇️ Download Statement</button>
                    <button class="btn btn--ghost btn--sm">✏️ Edit UPI</button>
                </div>
            </header>

            <main class="dash-content">

                <!-- PENDING PAYOUT HERO -->
                <div class="payout-hero mb-24 anim-fade-up">
                    <div>
                        <div class="payout-hero__kicker">⏳ Pending Payout · Transfers Tomorrow 10 AM</div>
                        <div class="payout-hero__amount" id="pendAmt">₹0</div>
                        <div class="payout-hero__note">From 28 completed bookings · Auto-transfer to your UPI</div>
                    </div>
                    <div>
                        <div class="t-label mb-8">Your UPI</div>
                        <div class="upi-badge">
                            <span class="upi-badge__icon">💳</span>
                            <div>
                                <div class="upi-badge__id">irontemple@paytm</div>
                                <div class="upi-badge__status">✓ Verified</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STAT CARDS -->
                <div class="grid-4 mb-24 anim-fade-up">
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--green">💰</div>
                            <span class="badge badge--up">+18%</span>
                        </div>
                        <div class="stat-card__value" id="s1">₹0</div>
                        <div class="stat-card__label">This Month</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--blue">📊</div>
                            <span class="badge badge--neutral">—</span>
                        </div>
                        <div class="stat-card__value" id="s2">₹0</div>
                        <div class="stat-card__label">Last Month</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--brand">💸</div>
                            <span class="badge badge--neutral">—</span>
                        </div>
                        <div class="stat-card__value" id="s3">₹0</div>
                        <div class="stat-card__label">All Time Earned</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--yellow">📉</div>
                            <span class="badge badge--neutral">~12%</span>
                        </div>
                        <div class="stat-card__value">₹1,040</div>
                        <div class="stat-card__label">Commission (Month)</div>
                    </div>
                </div>

                <!-- PAYOUT HISTORY + BREAKDOWN -->
                <div class="grid-2 anim-fade-up">

                    <!-- Payout timeline -->
                    <div class="panel">
                        <div class="panel__header">
                            <div class="panel__title">Payout History</div>
                            <span class="panel__action">View all →</span>
                        </div>
                        <div class="panel__body">
                            <div class="timeline">
                                <div class="timeline__item">
                                    <div class="timeline__dot timeline__dot--pending">⏳</div>
                                    <div>
                                        <div class="timeline__date">Mar 9, 2025 · Tomorrow 10 AM</div>
                                        <div class="timeline__title">Weekly Payout</div>
                                        <div class="timeline__amount timeline__amount--yellow">₹2,840</div>
                                        <div class="timeline__note">28 bookings · Pending</div>
                                    </div>
                                </div>
                                <div class="timeline__item">
                                    <div class="timeline__dot timeline__dot--done">✅</div>
                                    <div>
                                        <div class="timeline__date">Mar 2, 2025</div>
                                        <div class="timeline__title">Weekly Payout</div>
                                        <div class="timeline__amount timeline__amount--green">₹2,340</div>
                                        <div class="timeline__note">21 bookings · Transferred</div>
                                    </div>
                                </div>
                                <div class="timeline__item">
                                    <div class="timeline__dot timeline__dot--done">✅</div>
                                    <div>
                                        <div class="timeline__date">Feb 23, 2025</div>
                                        <div class="timeline__title">Weekly Payout</div>
                                        <div class="timeline__amount timeline__amount--green">₹1,960</div>
                                        <div class="timeline__note">18 bookings · Transferred</div>
                                    </div>
                                </div>
                                <div class="timeline__item">
                                    <div class="timeline__dot timeline__dot--done">✅</div>
                                    <div>
                                        <div class="timeline__date">Feb 9, 2025</div>
                                        <div class="timeline__title">First Payout 🎉</div>
                                        <div class="timeline__amount timeline__amount--green">₹640</div>
                                        <div class="timeline__note">6 bookings · Transferred</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue breakdown -->
                    <div class="flex flex-col gap-20">

                        <div class="panel">
                            <div class="panel__header">
                                <div class="panel__title">Revenue by Plan</div>
                            </div>
                            <div class="panel__body flex flex-col gap-16">

                                <div>
                                    <div class="revenue-row mb-8">
                                        <span class="revenue-row__label">Per Day (18 bookings)</span>
                                        <span class="revenue-row__value">₹1,440</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-bar__fill" style="width:34%;background:var(--brand)"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="revenue-row mb-8">
                                        <span class="revenue-row__label">3 Days (8 bookings)</span>
                                        <span class="revenue-row__value">₹1,600</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-bar__fill" style="width:38%;background:var(--blue)">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="revenue-row mb-8">
                                        <span class="revenue-row__label">7 Days (5 bookings)</span>
                                        <span class="revenue-row__value">₹2,000</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-bar__fill progress-bar__fill--green" style="width:47%">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="revenue-row mb-8">
                                        <span class="revenue-row__label">Monthly (3 bookings)</span>
                                        <span class="revenue-row__value">₹2,400</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-bar__fill progress-bar__fill--yellow" style="width:57%">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel">
                            <div class="panel__header">
                                <div class="panel__title">Commission Breakdown</div>
                            </div>
                            <div class="panel__body">
                                <div class="grid-2 gap-12">
                                    <div class="commission-tile">
                                        <div class="commission-tile__label">Gross Revenue</div>
                                        <div class="commission-tile__value">₹9,680</div>
                                    </div>
                                    <div class="commission-tile commission-tile--red">
                                        <div class="commission-tile__label">Platform Fee (12%)</div>
                                        <div class="commission-tile__value">−₹1,162</div>
                                    </div>
                                    <div class="commission-tile commission-tile--green col-2">
                                        <div class="commission-tile__label">You Receive</div>
                                        <div class="commission-tile__value">₹8,518</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        function countUp(id, target, prefix = '') {
            const el = document.getElementById(id);
            let v = 0;
            const iv = setInterval(() => {
                v += target / 40;
                if (v >= target) {
                    el.textContent = prefix + target.toLocaleString('en-IN');
                    clearInterval(iv);
                } else {
                    el.textContent = prefix + Math.floor(v).toLocaleString('en-IN');
                }
            }, 25);
        }

        countUp('pendAmt', 2840, '₹');
        countUp('s1', 8640, '₹');
        countUp('s2', 7320, '₹');
        countUp('s3', 31240, '₹');
    </script>
</body>

</html>
