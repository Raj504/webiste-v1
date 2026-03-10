<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Find Gyms – GymPass India – GymPass India</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">

</head>

<body>

    <header class="topnav">
        <a href="index.html" class="topnav-logo">GymPass<span>.</span>in</a>
        <div class="topnav-links">
            <div class="search-bar">
                <span class="search-bar__icon">📍</span>
                <input class="search-bar__input" type="text" value="Rishikesh" placeholder="City or area..."
                    id="cityInput">
            </div>
        </div>
        <div class="topnav-actions">
            <a href="login.html" class="btn btn--ghost btn--sm">Log in</a>
            <a href="signup.html" class="btn btn--primary btn--sm">Sign up</a>
        </div>
    </header>

    <div class="search-layout">
        <!-- LIST PANEL -->
        <div class="search-list">
            <div class="search-list__header">
                <div class="flex items-center justify-between mb-8">
                    <div><span class="t-heading" style="font-size:15px">Gyms in Rishikesh</span> <span class="t-muted"
                            style="font-size:12px">· 8 results</span></div>
                    <select class="field-input" style="width:auto;padding:6px 28px 6px 10px;font-size:12px">
                        <option>Sort: Relevance</option>
                        <option>Sort: Price ↑</option>
                        <option>Sort: Rating</option>
                    </select>
                </div>
                <div class="flex gap-8 flex-wrap">
                    <button class="chip is-active" onclick="filterChip(this)">All</button>
                    <button class="chip" onclick="filterChip(this)">AC</button>
                    <button class="chip" onclick="filterChip(this)">Trainer</button>
                    <button class="chip" onclick="filterChip(this)">Open Now</button>
                    <button class="chip" onclick="filterChip(this)">Under ₹100/day</button>
                </div>
            </div>
            <div id="gymList"></div>
        </div>

        <!-- MAP PANEL -->
        <div class="search-map" id="mapPanel">
            <div class="map-bg">
                <div class="map-label">Map · Rishikesh</div>
                <div id="mapPins"></div>
            </div>
            <!-- Detail panel (slides in) -->
            <div class="gym-quick-detail" id="quickDetail" style="display:none">
                <button class="gym-quick-detail__close" onclick="closeDetail()">✕</button>
                <div id="quickDetailBody"></div>
            </div>
        </div>
    </div>

    <script>
        const gyms = [{
                id: 1,
                n: 'Iron Temple Gym',
                a: 'Near Ram Jhula',
                r: 4.8,
                rev: 42,
                day: 80,
                open: true,
                amenities: ['AC', 'Lockers', 'Trainer', 'Free Weights', 'Parking'],
                e: '🏋️',
                x: 42,
                y: 38
            },
            {
                id: 2,
                n: 'Himalaya Fitness Hub',
                a: 'Swarg Ashram Road',
                r: 4.5,
                rev: 28,
                day: 60,
                open: true,
                amenities: ['AC', 'Shower', 'Trainer'],
                e: '⛰️',
                x: 62,
                y: 52
            },
            {
                id: 3,
                n: 'River View Gym',
                a: 'Laxman Jhula Bridge',
                r: 4.7,
                rev: 34,
                day: 100,
                open: false,
                amenities: ['AC', 'Pool', 'Yoga Room', 'Trainer'],
                e: '🌊',
                x: 30,
                y: 65
            },
            {
                id: 4,
                n: 'Yogi Strength Studio',
                a: 'Tapovan Area',
                r: 4.4,
                rev: 19,
                day: 70,
                open: true,
                amenities: ['Trainer', 'Free Weights'],
                e: '🧘',
                x: 75,
                y: 30
            },
            {
                id: 5,
                n: 'Urban Fit Rishikesh',
                a: 'Rishikesh Main Market',
                r: 4.3,
                rev: 15,
                day: 50,
                open: true,
                amenities: ['AC', 'Lockers'],
                e: '💪',
                x: 55,
                y: 70
            },
            {
                id: 6,
                n: 'Ganga CrossFit Box',
                a: 'Badrinath Road',
                r: 4.6,
                rev: 22,
                day: 90,
                open: true,
                amenities: ['AC', 'Trainer', 'Parking'],
                e: '🔥',
                x: 20,
                y: 48
            },
        ];

        function stars(r) {
            const f = Math.round(r);
            return '★'.repeat(f) + '☆'.repeat(5 - f);
        }

        function gymCard(g, active = false) {
            return `<div class="gym-list-card${active?' is-active':''}" onclick="selectGym(${g.id})" data-id="${g.id}">
    <div class="gym-list-card__header">
      <div class="gym-list-card__avatar">${g.e}</div>
      <div class="flex-1">
        <div class="gym-list-card__name">${g.n}</div>
        <div class="gym-list-card__addr t-muted">${g.a}</div>
      </div>
      <div class="text-right">
        <div class="gym-list-card__price t-brand t-display">₹${g.day}<span style="font-size:11px;font-weight:400;color:var(--text-secondary)">/day</span></div>
        <span class="pill ${g.open?'pill--green':'pill--grey'}">${g.open?'Open':'Closed'}</span>
      </div>
    </div>
    <div class="flex items-center gap-8 mt-8">
      <span class="t-yellow" style="font-size:13px">${stars(g.r)}</span>
      <span style="font-size:12px;font-weight:600">${g.r}</span>
      <span class="t-muted" style="font-size:12px">(${g.rev} reviews)</span>
      <div class="flex gap-4 ml-auto flex-wrap">
        ${g.amenities.slice(0,3).map(a=>`<span class="amenity-tag">${a}</span>`).join('')}
      </div>
    </div>
  </div>`;
        }

        function renderList(list) {
            document.getElementById('gymList').innerHTML = list.map(g => gymCard(g)).join('');
        }

        function selectGym(id) {
            document.querySelectorAll('.gym-list-card').forEach(c => c.classList.toggle('is-active', parseInt(c.dataset
                .id) === id));
            document.querySelectorAll('.map-pin').forEach(p => p.classList.toggle('is-active', parseInt(p.dataset.id) ===
                id));
            const g = gyms.find(x => x.id === id);
            showQuickDetail(g);
        }

        function showQuickDetail(g) {
            document.getElementById('quickDetailBody').innerHTML = `
    <div style="font-size:32px;margin-bottom:12px">${g.e}</div>
    <div class="t-heading" style="font-size:18px;margin-bottom:4px">${g.n}</div>
    <div class="t-muted mb-8">${g.a}</div>
    <div class="flex items-center gap-8 mb-16">
      <span class="t-yellow">${stars(g.r)}</span>
      <span style="font-weight:600">${g.r}</span>
      <span class="t-muted">(${g.rev} reviews)</span>
      <span class="pill ${g.open?'pill--green':'pill--grey'} ml-auto">${g.open?'Open Now':'Closed'}</span>
    </div>
    <div class="grid-2 gap-8 mb-16">
      <div class="plan-mini"><div class="t-label">Per Day</div><div class="plan-mini__price t-brand">₹${g.day}</div></div>
      <div class="plan-mini"><div class="t-label">7 Days</div><div class="plan-mini__price t-brand">₹${g.day*5}</div></div>
    </div>
    <div class="flex gap-8 flex-wrap mb-12">
      ${g.amenities.map(a=>`<span class="amenity-tag">${a}</span>`).join('')}
    </div>
    <a href="{{ route('gym-details')}}" class="btn btn--primary" style="width:100%;justify-content:center">View & Book →</a>
  `;
            document.getElementById('quickDetail').style.display = 'block';
        }

        function closeDetail() {
            document.getElementById('quickDetail').style.display = 'none';
        }

        function renderPins(list) {
            document.getElementById('mapPins').innerHTML = list.map(g => `
    <div class="map-pin" data-id="${g.id}" style="left:${g.x}%;top:${g.y}%" onclick="selectGym(${g.id})">
      ₹${g.day}
    </div>`).join('');
        }

        function filterChip(el) {
            document.querySelectorAll('.chip').forEach(c => c.classList.remove('is-active'));
            el.classList.add('is-active');
        }

        renderList(gyms);
        renderPins(gyms);
    </script>

</body>

</html>
