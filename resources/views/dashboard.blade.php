<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Dashboard – GymPass Owner</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">

</head>

<body>
    <div @class(['dash-layout'])>
        @include('partials.gym-sidebar')

        <div @class(['dash-main'])>
            <header @class(['dash-topbar'])>
                <div @class(['dash-topbar__left'])>
                    <div @class(['dash-topbar__title'])>Dashboard</div>
                    <div @class(['dash-topbar__sub'])>Today at a glance</div>
                </div>
                <div @class(['dash-topbar__right'])><button @class(['btn', 'btn--ghost', 'btn--sm'])>Mar 8, 2025</button><a
                        href="qr-scanner.html" @class(['btn', 'btn--primary', 'btn--sm'])>📲 Scan QR</a></div>
            </header>
            <main @class(['dash-content'])>

                <div @class(['grid-4', 'mb-24', 'anim-fade-up'])>
                    <div @class(['stat-card'])>
                        <div @class(['stat-card__top'])>
                            <div @class(['stat-card__icon', 'stat-card__icon--brand'])>📅</div><span @class(['badge', 'badge--up'])>+3
                                today</span>
                        </div>
                        <div @class(['stat-card__value']) id="s1">0</div>
                        <div @class(['stat-card__label'])>Today's Bookings</div>
                    </div>
                    <div @class(['stat-card'])>
                        <div @class(['stat-card__top'])>
                            <div @class(['stat-card__icon', 'stat-card__icon--green'])>💰</div><span @class(['badge', 'badge--up'])>+₹640</span>
                        </div>
                        <div @class(['stat-card__value']) id="s2">₹0</div>
                        <div @class(['stat-card__label'])>Today's Revenue</div>
                    </div>
                    <div @class(['stat-card'])>
                        <div @class(['stat-card__top'])>
                            <div @class(['stat-card__icon', 'stat-card__icon--blue'])>👥</div><span @class(['badge', 'badge--neutral'])>this
                                week</span>
                        </div>
                        <div @class(['stat-card__value']) id="s3">0</div>
                        <div @class(['stat-card__label'])>Active Members</div>
                    </div>
                    <div @class(['stat-card'])>
                        <div @class(['stat-card__top'])>
                            <div @class(['stat-card__icon', 'stat-card__icon--yellow'])>⭐</div><span @class(['badge', 'badge--neutral'])>42
                                reviews</span>
                        </div>
                        <div @class(['stat-card__value'])>4.8</div>
                        <div @class(['stat-card__label'])>Avg Rating</div>
                    </div>
                </div>

                <div @class(['grid-2', 'mb-20', 'anim-fade-up'])>
                    <div @class(['panel'])>
                        <div @class(['panel__header'])>
                            <div @class(['panel__title'])>Revenue — Last 7 Days</div><span
                                @class(['t-label', 't-brand'])>₹4,480 total</span>
                        </div>
                        <div @class(['panel__body'])>
                            <div @class(['chart-container'])>
                                <div @class(['chart-y-axis'])>
                                    <span>₹800</span><span>₹600</span><span>₹400</span><span>₹200</span><span>₹0</span>
                                </div>
                                <div @class(['chart-bars-wrap'])>
                                    <div @class(['chart-bars']) id="revChart"></div>
                                    <div @class(['chart-x-labels']) id="revLabels"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div @class(['panel'])>
                        <div @class(['panel__header'])>
                            <div @class(['panel__title'])>Today's Check-ins</div><a href="qr-scanner.html"
                                @class(['panel__action'])>Scan QR →</a>
                        </div>
                        <div id="checkinList"></div>
                    </div>
                </div>

                <div @class(['grid-2', 'anim-fade-up'])>
                    <div @class(['panel'])>
                        <div @class(['panel__header'])>
                            <div @class(['panel__title'])>Recent Bookings</div><a href="bookings.html"
                                @class(['panel__action'])>View all →</a>
                        </div>
                        <table @class(['data-table']) id="recentTable">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Plan</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="recentTbody"></tbody>
                        </table>
                    </div>

                    <div>
                        <div @class(['panel', 'mb-20'])>
                            <div @class(['panel__header'])>
                                <div @class(['panel__title'])>Pending Payout</div><a href="payouts.html"
                                    @class(['panel__action'])>Details →</a>
                            </div>
                            <div @class(['panel__body'])>
                                <div @class(['payout-hero__amount']) style="font-size:36px" id="payoutAmt">₹0</div>
                                <div style="font-size:12px;color:var(--text-secondary);margin-top:4px">Transfers
                                    tomorrow 10 AM → <span @class(['t-mono'])
                                        style="font-size:12px">irontemple@paytm</span></div>
                            </div>
                        </div>
                        <div @class(['callout', 'callout--green'])>
                            <span @class(['callout__icon'])>⭐</span>
                            <div><strong style="color:var(--text-primary)">2 new reviews</strong> received today. Reply
                                to boost your ranking. <a href="reviews.html" style="color:var(--brand)">View reviews
                                    →</a></div>
                        </div>
                    </div>
                </div>

                <script>
                    const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    const vals = [320, 480, 240, 560, 400, 640, 480];
                    const maxV = Math.max(...vals);
                    const chart = document.getElementById('revChart'),
                        labels = document.getElementById('revLabels');
                    vals.forEach((v, i) => {
                        const b = document.createElement('div');
                        b.className = 'chart-bar ' + (v >= 500 ? 'chart-bar--brand' : 'chart-bar--dim');
                        b.style.cssText = `height:${(v/maxV)*100}%;animation:scale-up .4s ease ${i*.06}s both`;
                        b.innerHTML = `<div @class(['chart-bar__tip'])>₹${v}</div>`;
                        chart.appendChild(b);
                        const l = document.createElement('div');
                        l.className = 'chart-x-label';
                        l.textContent = days[i];
                        labels.appendChild(l);
                    });
                    const checkins = [{
                        n: 'Arjun Sharma',
                        p: 'Per Day',
                        t: '9:14 AM'
                    }, {
                        n: 'Priya Mehta',
                        p: '3 Days',
                        t: '10:02 AM'
                    }, {
                        n: 'Sneha Patel',
                        p: 'Per Day',
                        t: '12:45 PM'
                    }, {
                        n: 'Aditya Rao',
                        p: 'Monthly',
                        t: '2:00 PM'
                    }];
                    document.getElementById('checkinList').innerHTML = checkins.map(c =>
                        `<div @class(['scan-log__item'])><div @class(['scan-log__dot', 'scan-log__dot--ok'])></div><div @class(['scan-log__body'])><div @class(['scan-log__name'])>${c.n}</div><div @class(['scan-log__meta'])>${c.p}</div></div><div @class(['scan-log__time'])>${c.t}</div></div>`
                    ).join('');
                    const bookings = [{
                        e: '👨',
                        n: 'Arjun Sharma',
                        c: 'Bangalore',
                        pl: 'Per Day',
                        am: 80,
                        st: 'checked-in'
                    }, {
                        e: '👩',
                        n: 'Priya Mehta',
                        c: 'Delhi',
                        pl: '3 Days',
                        am: 200,
                        st: 'active'
                    }, {
                        e: '👦',
                        n: 'Rohit Kumar',
                        c: 'Mumbai',
                        pl: '7 Days',
                        am: 400,
                        st: 'active'
                    }, {
                        e: '👩‍💼',
                        n: 'Meera Singh',
                        c: 'Lucknow',
                        pl: '7 Days',
                        am: 400,
                        st: 'pending'
                    }];
                    const sm = {
                        'checked-in': '<span @class(['pill', 'pill--brand'])>Checked In</span>',
                        'active': '<span @class(['pill', 'pill--green'])>Active</span>',
                        'pending': '<span @class(['pill', 'pill--yellow'])>Pending</span>'
                    };
                    document.getElementById('recentTbody').innerHTML = bookings.map(b =>
                        `<tr><td><div @class(['user-cell'])><div @class(['user-cell__avatar'])>${b.e}</div><div><div @class(['user-cell__name'])>${b.n}</div><div @class(['user-cell__meta'])>From ${b.c}</div></div></div></td><td @class(['t-mono']) style="font-size:11px;color:var(--text-secondary)">${b.pl}</td><td style="font-family:var(--font-display);font-weight:700;color:var(--green)">₹${b.am}</td><td>${sm[b.st]}</td></tr>`
                    ).join('');

                    function cu(id, t, p = '') {
                        const el = document.getElementById(id);
                        let c = 0;
                        const iv = setInterval(() => {
                            c += t / 40;
                            if (c >= t) {
                                el.textContent = p + t;
                                clearInterval(iv);
                            } else el.textContent = p + Math.floor(c);
                        }, 25);
                    }
                    cu('s1', 8);
                    cu('s2', 640, '₹');
                    cu('s3', 26);
                    cu('payoutAmt', 2840, '₹');
                </script>

            </main>
        </div>
    </div>
</body>

</html>
