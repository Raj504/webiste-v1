<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Bookings – GymPass Owner</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">

</head>

<body>
    <div class="dash-layout">
        @include('partials.gym-sidebar')

        <div class="dash-main">
            <header class="dash-topbar">
                <div class="dash-topbar__left">
                    <div class="dash-topbar__title">Bookings</div>
                    <div class="dash-topbar__sub">All traveler & member pass bookings</div>
                </div>
                <div class="dash-topbar__right"><input type="date" value="2025-03-08"
                        style="padding:8px 12px;background:var(--surface-3);border:1px solid var(--border-1);border-radius:var(--r-sm);color:var(--text-primary);font-family:var(--font-body);font-size:12px"><button
                        class="btn btn--ghost btn--sm">⬇️ Export CSV</button></div>
            </header>
            <main class="dash-content">

                <div class="grid-4 mb-24 anim-fade-up">
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--brand">📅</div><span
                                class="badge badge--up">+3</span>
                        </div>
                        <div class="stat-card__value" id="c1">0</div>
                        <div class="stat-card__label">Month Bookings</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--green">💰</div><span
                                class="badge badge--up">+₹640</span>
                        </div>
                        <div class="stat-card__value" id="c2">₹0</div>
                        <div class="stat-card__label">Month Revenue</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--blue">✅</div><span
                                class="badge badge--neutral">—</span>
                        </div>
                        <div class="stat-card__value">86%</div>
                        <div class="stat-card__label">Check-in Rate</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--yellow">⏳</div><span
                                class="badge badge--neutral">—</span>
                        </div>
                        <div class="stat-card__value">3</div>
                        <div class="stat-card__label">Pending Today</div>
                    </div>
                </div>
                <div class="flex items-center gap-8 flex-wrap mb-20 anim-fade-up">
                    <span style="font-size:12px;color:var(--text-secondary)">Filter:</span>
                    <button class="chip is-active" onclick="fc(this,'all')">All</button>
                    <button class="chip" onclick="fc(this,'active')">Active</button>
                    <button class="chip" onclick="fc(this,'checked-in')">Checked In</button>
                    <button class="chip" onclick="fc(this,'expired')">Expired</button>
                    <button class="chip" onclick="fc(this,'pending')">Pending</button>
                    <div class="search-bar ml-auto"><span class="search-bar__icon">🔍</span><input
                            class="search-bar__input" type="text" placeholder="Search name, city…"
                            oninput="search(this.value)"></div>
                </div>
                <div class="panel anim-fade-up">
                    <div class="panel__header">
                        <div class="panel__title">Bookings</div><span class="panel__action" id="bcount">8
                            bookings</span>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Booked</th>
                                <th>Valid Until</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="btbody"></tbody>
                    </table>
                </div>
                <script>
                    const bd = [{
                        e: '👨',
                        n: 'Arjun Sharma',
                        c: 'Bangalore',
                        pl: 'Per Day',
                        am: 80,
                        bk: 'Today 9:10 AM',
                        vl: 'Today',
                        st: 'checked-in'
                    }, {
                        e: '👩',
                        n: 'Priya Mehta',
                        c: 'Delhi',
                        pl: '3 Days',
                        am: 200,
                        bk: 'Today 9:58 AM',
                        vl: 'Mar 11',
                        st: 'active'
                    }, {
                        e: '👦',
                        n: 'Rohit Kumar',
                        c: 'Mumbai',
                        pl: '7 Days',
                        am: 400,
                        bk: 'Mar 6',
                        vl: 'Mar 13',
                        st: 'active'
                    }, {
                        e: '🧑',
                        n: 'Sneha Patel',
                        c: 'Pune',
                        pl: 'Per Day',
                        am: 80,
                        bk: 'Today 12:40 PM',
                        vl: 'Today',
                        st: 'checked-in'
                    }, {
                        e: '👨‍💼',
                        n: 'Aditya Rao',
                        c: 'Hyderabad',
                        pl: 'Monthly',
                        am: 800,
                        bk: 'Mar 1',
                        vl: 'Apr 1',
                        st: 'active'
                    }, {
                        e: '👩',
                        n: 'Kavya Nair',
                        c: 'Chennai',
                        pl: 'Per Day',
                        am: 80,
                        bk: 'Mar 7',
                        vl: 'Mar 7',
                        st: 'expired'
                    }, {
                        e: '🧔',
                        n: 'Vikram T.',
                        c: 'Jaipur',
                        pl: '3 Days',
                        am: 200,
                        bk: 'Mar 5',
                        vl: 'Mar 8',
                        st: 'expired'
                    }, {
                        e: '👩‍💼',
                        n: 'Meera Singh',
                        c: 'Lucknow',
                        pl: '7 Days',
                        am: 400,
                        bk: 'Today 2:20 PM',
                        vl: 'Mar 15',
                        st: 'pending'
                    }];
                    const sm = {
                        'checked-in': '<span class="pill pill--brand">Checked In</span>',
                        'active': '<span class="pill pill--green">Active</span>',
                        'expired': '<span class="pill pill--grey">Expired</span>',
                        'pending': '<span class="pill pill--yellow">Pending</span>'
                    };
                    let cf = 'all',
                        sq = '';

                    function render() {
                        const f = bd.filter(b => (cf === 'all' || b.st === cf) && (!sq || b.n.toLowerCase().includes(sq) || b.c
                            .toLowerCase().includes(sq)));
                        document.getElementById('bcount').textContent = f.length + ' bookings';
                        document.getElementById('btbody').innerHTML = f.map(b =>
                            '<tr><td><div class="user-cell"><div class="user-cell__avatar">' + b.e +
                            '</div><div><div class="user-cell__name">' + b.n + '</div><div class="user-cell__meta">From ' + b.c +
                            '</div></div></div></td><td class="t-mono" style="font-size:11px;color:var(--text-secondary)">' + b.pl +
                            '</td><td style="font-family:var(--font-display);font-weight:700;color:var(--green)">₹' + b.am +
                            '</td><td style="font-size:12px;color:var(--text-secondary)">' + b.bk +
                            '</td><td style="font-size:12px">' + b.vl + '</td><td>' + sm[b.st] +
                            '</td><td><button class="btn btn--ghost btn--sm">View</button></td></tr>').join('');
                    }

                    function fc(btn, f) {
                        document.querySelectorAll('.chip').forEach(c => c.classList.remove('is-active'));
                        btn.classList.add('is-active');
                        cf = f;
                        render();
                    }

                    function search(q) {
                        sq = q.toLowerCase();
                        render();
                    }

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
                    cu('c1', 47);
                    cu('c2', 8640, '₹');
                    render();
                </script>

            </main>
        </div>
    </div>
</body>

</html>
