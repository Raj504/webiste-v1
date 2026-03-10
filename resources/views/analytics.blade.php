<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Analytics – GymPass Owner</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">

<body>
    <div class="dash-layout">
        @include('partials.gym-sidebar')
        <div class="dash-main">
            <header class="dash-topbar">
                <div class="dash-topbar__left">
                    <div class="dash-topbar__title">Analytics</div>
                    <div class="dash-topbar__sub">Gym performance & booking trends</div>
                </div>
                <div class="dash-topbar__right">
                    <div class="period-tabs"><button class="period-tab"
                            onclick="this.closest('.period-tabs').querySelectorAll('.period-tab').forEach(t=>t.classList.remove('is-active'));this.classList.add('is-active')">7d</button><button
                            class="period-tab is-active"
                            onclick="this.closest('.period-tabs').querySelectorAll('.period-tab').forEach(t=>t.classList.remove('is-active'));this.classList.add('is-active')">30d</button><button
                            class="period-tab"
                            onclick="this.closest('.period-tabs').querySelectorAll('.period-tab').forEach(t=>t.classList.remove('is-active'));this.classList.add('is-active')">3m</button><button
                            class="period-tab"
                            onclick="this.closest('.period-tabs').querySelectorAll('.period-tab').forEach(t=>t.classList.remove('is-active'));this.classList.add('is-active')">All</button>
                    </div><button class="btn btn--ghost btn--sm">⬇️ Export</button>
                </div>
            </header>
            <main class="dash-content">
                <div class="grid-4 mb-24 anim-fade-up">
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--brand">📅</div><span
                                class="badge badge--up">+23%</span>
                        </div>
                        <div class="stat-card__value" id="a1">0</div>
                        <div class="stat-card__label">Total Bookings</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--green">💰</div><span
                                class="badge badge--up">+18%</span>
                        </div>
                        <div class="stat-card__value" id="a2">₹0</div>
                        <div class="stat-card__label">Total Revenue</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--blue">👥</div><span
                                class="badge badge--up">+11%</span>
                        </div>
                        <div class="stat-card__value" id="a3">0</div>
                        <div class="stat-card__label">Unique Members</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--yellow">🔁</div><span
                                class="badge badge--up">+5%</span>
                        </div>
                        <div class="stat-card__value">34%</div>
                        <div class="stat-card__label">Repeat Traveler Rate</div>
                    </div>
                </div>

                <div class="panel mb-20 anim-fade-up">
                    <div class="panel__header">
                        <div class="panel__title">Daily Revenue — Last 30 Days</div><span
                            style="font-family:var(--font-display);font-size:14px;font-weight:700;color:var(--green)">₹9,680
                            total</span>
                    </div>
                    <div class="panel__body">
                        <div class="chart-container">
                            <div class="chart-y-axis">
                                <span>₹600</span><span>₹450</span><span>₹300</span><span>₹150</span><span>₹0</span>
                            </div>
                            <div class="chart-bars-wrap">
                                <div class="chart-bars" id="revChart"></div>
                                <div class="chart-x-labels" id="revLabels"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid-2 mb-20 anim-fade-up">
                    <div class="panel">
                        <div class="panel__header">
                            <div class="panel__title">Bookings by Plan Type</div>
                        </div>
                        <div class="panel__body">
                            <div class="donut-wrap">
                                <svg width="120" height="120" viewBox="0 0 36 36">
                                    <circle cx="18" cy="18" r="14" fill="none" stroke="var(--border-1)"
                                        stroke-width="4" />
                                    <circle cx="18" cy="18" r="14" fill="none" stroke="var(--brand)"
                                        stroke-width="4" stroke-dasharray="30 70" stroke-dashoffset="25"
                                        transform="rotate(-90 18 18)" />
                                    <circle cx="18" cy="18" r="14" fill="none" stroke="var(--blue)"
                                        stroke-width="4" stroke-dasharray="22 78" stroke-dashoffset="-5"
                                        transform="rotate(-90 18 18)" />
                                    <circle cx="18" cy="18" r="14" fill="none" stroke="var(--green)"
                                        stroke-width="4" stroke-dasharray="28 72" stroke-dashoffset="-27"
                                        transform="rotate(-90 18 18)" />
                                    <circle cx="18" cy="18" r="14" fill="none"
                                        stroke="var(--yellow)" stroke-width="4" stroke-dasharray="20 80"
                                        stroke-dashoffset="-55" transform="rotate(-90 18 18)" />
                                </svg>
                                <div class="donut-legend">
                                    <div class="donut-legend__item">
                                        <div class="donut-legend__dot" style="background:var(--brand)"></div><span>Per
                                            Day</span><span class="donut-legend__pct t-brand">30%</span>
                                    </div>
                                    <div class="donut-legend__item">
                                        <div class="donut-legend__dot" style="background:var(--blue)"></div><span>3
                                            Days</span><span class="donut-legend__pct t-blue">22%</span>
                                    </div>
                                    <div class="donut-legend__item">
                                        <div class="donut-legend__dot" style="background:var(--green)"></div><span>7
                                            Days</span><span class="donut-legend__pct t-green">28%</span>
                                    </div>
                                    <div class="donut-legend__item">
                                        <div class="donut-legend__dot" style="background:var(--yellow)"></div>
                                        <span>Monthly</span><span class="donut-legend__pct t-yellow">20%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel__header">
                            <div class="panel__title">Check-in Heatmap (Hour of Day)</div>
                        </div>
                        <div class="panel__body">
                            <div id="heatmap"></div>
                            <div class="heatmap-legend">
                                Low
                                <div class="heatmap-legend__swatch" style="background:rgba(255,92,26,.08)"></div>
                                <div class="heatmap-legend__swatch" style="background:rgba(255,92,26,.3)"></div>
                                <div class="heatmap-legend__swatch" style="background:rgba(255,92,26,.6)"></div>
                                <div class="heatmap-legend__swatch" style="background:rgba(255,92,26,1)"></div>
                                High
                            </div>
                        </div>
                    </div>
                </div>

                <div class="anim-fade-up">
                    <div class="t-label mb-12">AI Insights</div>
                    <div class="grid-3">
                        <div class="insight-card">
                            <div class="insight-card__icon" style="background:var(--green-dim)">📈</div>
                            <div>
                                <div class="insight-card__title">Peak booking window</div>
                                <div class="insight-card__text">72% of bookings happen between 6–9 AM. Consider a
                                    "Morning Pass" discount to fill off-peak hours.</div>
                            </div>
                        </div>
                        <div class="insight-card">
                            <div class="insight-card__icon" style="background:var(--blue-dim)">🧳</div>
                            <div>
                                <div class="insight-card__title">Traveler origins</div>
                                <div class="insight-card__text">Most travelers are from Mumbai (32%), Delhi (28%), and
                                    Bangalore (18%). Focus marketing in these cities.</div>
                            </div>
                        </div>
                        <div class="insight-card">
                            <div class="insight-card__icon" style="background:var(--yellow-dim)">💡</div>
                            <div>
                                <div class="insight-card__title">Revenue opportunity</div>
                                <div class="insight-card__text">Upgrading travelers from Per Day to 3-Day pass
                                    increases avg booking value by ₹120. Reply to reviews to boost ranking.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    const days = ['F7', 'F8', 'F9', 'F10', 'F11', 'F12', 'F13', 'F14', 'F15', 'F16', 'F17', 'F18', 'F19', 'F20', 'F21',
                        'F22', 'F23', 'F24', 'F25', 'F26', 'F27', 'F28', 'M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M7', 'M8'
                    ];
                    const vals = [80, 160, 80, 240, 320, 400, 280, 480, 320, 200, 160, 360, 240, 480, 400, 320, 200, 560, 480, 320, 400,
                        560, 480, 640, 320, 480, 560, 400, 480, 560
                    ];
                    const maxV = Math.max(...vals);
                    const chart = document.getElementById('revChart'),
                        labels = document.getElementById('revLabels');
                    vals.forEach((v, i) => {
                        const b = document.createElement('div');
                        b.className = 'chart-bar ' + (v >= 400 ? 'chart-bar--brand' : 'chart-bar--dim');
                        b.style.cssText = `height:${(v/maxV)*100}%;animation:scale-up .4s ease ${i*.012}s both`;
                        b.innerHTML = '<div class="chart-bar__tip">' + v + '</div>';
                        chart.appendChild(b);
                        const l = document.createElement('div');
                        l.className = 'chart-x-label';
                        l.textContent = i % 5 === 0 ? days[i] : '';
                        labels.appendChild(l);
                    });
                    const hours = ['6AM', '7AM', '8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM', '6PM', '7PM',
                        '8PM', '9PM'
                    ];
                    const hd = [
                        [2, 3, 4, 3, 4, 2, 1],
                        [5, 6, 7, 6, 7, 5, 3],
                        [8, 9, 10, 9, 10, 7, 5],
                        [7, 8, 9, 8, 9, 8, 6],
                        [4, 5, 6, 5, 6, 5, 4],
                        [3, 4, 4, 4, 5, 4, 3],
                        [2, 3, 3, 3, 4, 5, 4],
                        [2, 2, 3, 2, 3, 4, 4],
                        [3, 3, 4, 3, 4, 5, 5],
                        [4, 4, 5, 4, 5, 6, 6],
                        [5, 6, 7, 6, 7, 7, 7],
                        [7, 8, 9, 8, 9, 8, 7],
                        [9, 10, 10, 10, 10, 9, 8],
                        [8, 8, 9, 9, 9, 8, 7],
                        [6, 7, 8, 7, 8, 7, 6],
                        [3, 4, 5, 4, 5, 5, 4]
                    ];
                    const hm = document.getElementById('heatmap');
                    hd.forEach((row, ri) => {
                        const div = document.createElement('div');
                        div.className = 'heatmap-row';
                        const lbl = document.createElement('div');
                        lbl.className = 'heatmap-row__label';
                        lbl.textContent = hours[ri];
                        div.appendChild(lbl);
                        row.forEach(v => {
                            const c = document.createElement('div');
                            c.className = 'heatmap-cell';
                            c.style.background = 'rgba(255,92,26,' + (v / 10) + ')';
                            c.title = v + ' check-ins';
                            div.appendChild(c);
                        });
                        hm.appendChild(div);
                    });

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
                    cu('a1', 147);
                    cu('a2', 9680, '₹');
                    cu('a3', 89);
                </script>
            </main>
        </div>
    </div>
</body>

</html>
