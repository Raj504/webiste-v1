<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members – GymPass Owner</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">

</head>

<body>
    <div class="dash-layout">

        @include('partials.gym-sidebar')


        <div class="dash-main">
            <header class="dash-topbar">
                <div class="dash-topbar__left">
                    <div class="dash-topbar__title">Members</div>
                    <div class="dash-topbar__sub">Local members &amp; traveler passes</div>
                </div>
                <div class="dash-topbar__right">
                    <button class="btn btn--ghost btn--sm">⬇️ Export</button>
                    <button class="btn btn--primary btn--sm">+ Add Member</button>
                </div>
            </header>

            <main class="dash-content">

                <div class="grid-4 mb-24 anim-fade-up">
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--blue">👥</div>
                            <span class="badge badge--up">+4</span>
                        </div>
                        <div class="stat-card__value" id="m1">0</div>
                        <div class="stat-card__label">Total Members</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--green">✅</div>
                            <span class="badge badge--neutral">—</span>
                        </div>
                        <div class="stat-card__value" id="m2">0</div>
                        <div class="stat-card__label">Active Plans</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--yellow">⏰</div>
                            <span class="badge badge--down">-2</span>
                        </div>
                        <div class="stat-card__value">5</div>
                        <div class="stat-card__label">Renewals Due (7d)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--brand">🧳</div>
                            <span class="badge badge--up">+8</span>
                        </div>
                        <div class="stat-card__value" id="m3">0</div>
                        <div class="stat-card__label">Traveler Passes</div>
                    </div>
                </div>

                <div class="flex items-center gap-8 flex-wrap mb-20 anim-fade-up">
                    <button class="chip is-active" onclick="setFilter(this,'all')">All</button>
                    <button class="chip" onclick="setFilter(this,'local')">Local Members</button>
                    <button class="chip" onclick="setFilter(this,'traveler')">Travelers</button>
                    <button class="chip" onclick="setFilter(this,'expiring')">Expiring Soon</button>
                    <div class="search-bar ml-auto">
                        <span class="search-bar__icon">🔍</span>
                        <input class="search-bar__input" type="text" placeholder="Search member…"
                            oninput="setSearch(this.value)">
                    </div>
                </div>

                <div class="panel anim-fade-up">
                    <div class="panel__header">
                        <div class="panel__title">Members</div>
                        <span class="panel__action" id="memberCount">8 members</span>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Type</th>
                                <th>Plan</th>
                                <th>Days Left</th>
                                <th>Progress</th>
                                <th>Since</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="membertbody"></tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

    <script>
        const members = [{
                e: '👨',
                n: 'Vikram Tiwari',
                c: 'Local · Rishikesh',
                type: 'local',
                pl: 'Monthly',
                days: 14,
                total: 30,
                since: 'Mar 1',
                st: 'active'
            },
            {
                e: '👩',
                n: 'Priya Mehta',
                c: 'Traveler · Delhi',
                type: 'traveler',
                pl: '3 Days',
                days: 2,
                total: 3,
                since: 'Mar 7',
                st: 'active'
            },
            {
                e: '👦',
                n: 'Rohit Kumar',
                c: 'Traveler · Mumbai',
                type: 'traveler',
                pl: '7 Days',
                days: 5,
                total: 7,
                since: 'Mar 3',
                st: 'active'
            },
            {
                e: '👩‍💼',
                n: 'Anita Sharma',
                c: 'Local · Rishikesh',
                type: 'local',
                pl: 'Monthly',
                days: 3,
                total: 30,
                since: 'Feb 8',
                st: 'expiring'
            },
            {
                e: '🧔',
                n: 'Deepak Verma',
                c: 'Local · Rishikesh',
                type: 'local',
                pl: 'Monthly',
                days: 18,
                total: 30,
                since: 'Feb 18',
                st: 'active'
            },
            {
                e: '👩',
                n: 'Sunita Roy',
                c: 'Traveler · Kolkata',
                type: 'traveler',
                pl: 'Per Day',
                days: 0,
                total: 1,
                since: 'Mar 8',
                st: 'active'
            },
            {
                e: '👦',
                n: 'Harsh Gupta',
                c: 'Local · Rishikesh',
                type: 'local',
                pl: 'Monthly',
                days: 6,
                total: 30,
                since: 'Feb 11',
                st: 'expiring'
            },
            {
                e: '👩‍🦱',
                n: 'Kavya Iyer',
                c: 'Traveler · Bangalore',
                type: 'traveler',
                pl: '7 Days',
                days: 7,
                total: 7,
                since: 'Mar 2',
                st: 'active'
            },
        ];

        let activeFilter = 'all';
        let searchQuery = '';

        function render() {
            const rows = members.filter(m => {
                const matchFilter =
                    activeFilter === 'all' ? true :
                    activeFilter === 'expiring' ? m.st === 'expiring' :
                    m.type === activeFilter;
                const matchSearch = !searchQuery || m.n.toLowerCase().includes(searchQuery);
                return matchFilter && matchSearch;
            });

            document.getElementById('memberCount').textContent = rows.length + ' members';
            document.getElementById('membertbody').innerHTML = rows.map(m => {
                const pct = Math.round(((m.total - m.days) / m.total) * 100);
                const isLow = m.days <= 5;
                const barClass = isLow ? 'progress-bar__fill--yellow' : 'progress-bar__fill--green';
                const dayClass = isLow ? 't-yellow' : 't-brand';
                const typeClass = m.type === 'local' ? 'pill--blue' : 'pill--brand';
                const statusPill = m.st === 'expiring' ?
                    '<span class="pill pill--yellow">Expiring</span>' :
                    '<span class="pill pill--green">Active</span>';
                return `
      <tr>
        <td>
          <div class="user-cell">
            <div class="user-cell__avatar">${m.e}</div>
            <div>
              <div class="user-cell__name">${m.n}</div>
              <div class="user-cell__meta">${m.c}</div>
            </div>
          </div>
        </td>
        <td><span class="pill ${typeClass}">${m.type === 'local' ? 'Local' : 'Traveler'}</span></td>
        <td class="t-mono t-muted">${m.pl}</td>
        <td class="t-display ${dayClass}">${m.days}d</td>
        <td>
          <div class="progress-bar">
            <div class="progress-bar__fill ${barClass}" style="width:${pct}%"></div>
          </div>
        </td>
        <td class="t-muted">${m.since}</td>
        <td>${statusPill}</td>
        <td><button class="btn btn--ghost btn--sm">Profile</button></td>
      </tr>`;
            }).join('');
        }

        function setFilter(btn, f) {
            document.querySelectorAll('.chip').forEach(c => c.classList.remove('is-active'));
            btn.classList.add('is-active');
            activeFilter = f;
            render();
        }

        function setSearch(q) {
            searchQuery = q.toLowerCase();
            render();
        }

        function countUp(id, target) {
            const el = document.getElementById(id);
            let v = 0;
            const iv = setInterval(() => {
                v += target / 40;
                if (v >= target) {
                    el.textContent = target;
                    clearInterval(iv);
                } else el.textContent = Math.floor(v);
            }, 25);
        }

        countUp('m1', 34);
        countUp('m2', 26);
        countUp('m3', 18);
        render();
    </script>
</body>

</html>
