<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>GymPass India – Day Passes for Traveling Gymgoers – GymPass India</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>

<body>

    <header class="topnav">
        <a href="{{ route('index') }}" class="topnav-logo">GymPass<span>.</span>in</a>
        <nav class="topnav-links">
            <a href="{{ route('search') }}" class="topnav-link">Find Gyms</a>
            <a href="#how" class="topnav-link">How it Works</a>
            <a href="gym-settings.html" class="topnav-link">For Gyms</a>
        </nav>
        <div class="topnav-actions">
            <a href="{{ route('login') }}" class="btn btn--ghost btn--sm">Log in</a>
            <a href="{{ route('signup') }}" class="btn btn--primary btn--sm">Get Started →</a>
        </div>
    </header>

    <!-- HERO -->
    <section class="hero">
        <div>
            <div class="hero__kicker">Day passes · 3 days · 7 days · Monthly</div>
            <h1 class="hero__headline">
                Train anywhere<br>
                <span class="hero__headline--ghost">in India.</span>
                <span class="t-brand">No excuses.</span>
            </h1>
            <p class="hero__desc">Book a gym in any city, pay via UPI, walk in with your QR pass. Zero friction, zero
                commitment.</p>
            <div class="hero__actions">
                <a href="{{ route('search') }}" class="btn btn--primary btn--lg">🔍 Find Gyms Near Me</a>
                <a href="{{ route('signup') }}" class="btn btn--ghost btn--lg">I Own a Gym →</a>
            </div>
            <div class="hero__stats">
                <div class="text-center">
                    <div class="hero__stat-value t-brand" id="s1">0</div>
                    <div class="hero__stat-label">Gyms Live</div>
                </div>
                <div class="text-center">
                    <div class="hero__stat-value t-brand" id="s2">0</div>
                    <div class="hero__stat-label">Travelers Trained</div>
                </div>
                <div class="text-center">
                    <div class="hero__stat-value t-brand" id="s3">0</div>
                    <div class="hero__stat-label">Cities</div>
                </div>
            </div>
        </div>
    </section>

    <!-- TICKER -->
    <div class="city-ticker">
        <div class="city-ticker__track">
            <span>🏔️ Rishikesh</span><span>🌊 Goa</span><span>🏔️ Manali</span><span>🕌 Varanasi</span>
            <span>🏯 Jaipur</span><span>🍵 Darjeeling</span><span>🌿 Coorg</span><span>🏝️ Andaman</span>
            <span>🏔️ Rishikesh</span><span>🌊 Goa</span><span>🏔️ Manali</span><span>🕌 Varanasi</span>
            <span>🏯 Jaipur</span><span>🍵 Darjeeling</span><span>🌿 Coorg</span><span>🏝️ Andaman</span>
        </div>
    </div>

    <!-- HOW IT WORKS -->
    <section class="section" id="how">
        <div class="section__kicker">Simple by design</div>
        <h2 class="section__title t-display">Three steps. Done.</h2>
        <p class="section__sub">No membership. No forms. Just pick, pay, and train.</p>
        <div class="grid-3 mt-12">
            <div class="how-step">
                <div class="how-step__num">01</div>
                <div class="how-step__icon">📍</div>
                <div class="how-step__title t-heading">Find a gym</div>
                <div class="how-step__desc">Search by city or location. See amenities, pricing, and real reviews from
                    other travelers.</div>
            </div>
            <div class="how-step">
                <div class="how-step__num">02</div>
                <div class="how-step__icon">💸</div>
                <div class="how-step__title t-heading">Book & pay</div>
                <div class="how-step__desc">Pick a plan — per day, 3 days, weekly, or monthly. Pay via UPI, GPay,
                    PhonePe in seconds.</div>
            </div>
            <div class="how-step">
                <div class="how-step__num">03</div>
                <div class="how-step__icon">📲</div>
                <div class="how-step__title t-heading">Scan & train</div>
                <div class="how-step__desc">Get a QR pass on your phone. Show it at reception. Works offline too.</div>
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section class="section"
        style="background:var(--surface-1);border-top:1px solid var(--border-1);border-bottom:1px solid var(--border-1)">
        <div class="section__kicker">Flexible pricing</div>
        <h2 class="section__title t-display">Pay only for what you need</h2>
        <p class="section__sub">Prices are set by each gym. This example shows a ₹800/mo gym.</p>
        <div class="grid-4 mt-12">
            <div class="plan-card">
                <div class="plan-card__label">Per Day</div>
                <div class="plan-card__price">₹80</div>
                <div class="plan-card__rate">per day</div>
                <div class="plan-card__desc">Perfect for a one-off session while passing through.</div>
            </div>
            <div class="plan-card">
                <div class="plan-card__label">3 Days</div>
                <div class="plan-card__price">₹200</div>
                <div class="plan-card__rate">₹67/day · Save 16%</div>
                <div class="plan-card__desc">Weekend or short stay. Most popular for yoga retreaters.</div>
            </div>
            <div class="plan-card plan-card--featured">
                <div class="plan-card__badge">Best Value</div>
                <div class="plan-card__label">7 Days</div>
                <div class="plan-card__price">₹400</div>
                <div class="plan-card__rate">₹57/day · Save 29%</div>
                <div class="plan-card__desc">Week-long trip? Train every day without the commitment.</div>
            </div>
            <div class="plan-card">
                <div class="plan-card__label">Monthly</div>
                <div class="plan-card__price">₹800</div>
                <div class="plan-card__rate">₹27/day · Best rate</div>
                <div class="plan-card__desc">Based in one city for a month? Get the full experience.</div>
            </div>
        </div>
    </section>

    <!-- CITIES -->
    <section class="section">
        <div class="section__kicker">Currently live</div>
        <h2 class="section__title t-display">Cities on GymPass</h2>
        <p class="section__sub mb-20">Starting with India's top traveler destinations.</p>
        <div class="grid-3" id="cityGrid"></div>
    </section>

    <!-- CTA -->
    <section class="section text-center" style="background:var(--surface-1);border-top:1px solid var(--border-1)">
        <div class="section__kicker">Get started today</div>
        <h2 class="section__title t-display mb-12">Never skip a gym<br>while traveling again.</h2>
        <div class="flex gap-12 justify-center flex-wrap">
            <a href="{{ route('search') }}" class="btn btn--primary btn--lg">🔍 Find Gyms</a>
            <a href="{{ route('signup') }}" class="btn btn--ghost btn--lg">Register Your Gym</a>
        </div>
    </section>

    <footer class="site-footer">
        <div class="site-footer__inner">
            <div class="topnav-logo">GymPass<span class="t-brand">.</span>in</div>
            <div class="t-muted" style="font-size:12px">© 2025 GymPass India · Built for travelers who train</div>
            <div class="flex gap-16">
                <a href="#" class="topnav-link">Privacy</a>
                <a href="#" class="topnav-link">Terms</a>
                <a href="{{ route('login') }}" class="topnav-link">Owner Login</a>
            </div>
        </div>
    </footer>

    <script>
        const cities = [{
                e: '🏔️',
                n: 'Rishikesh',
                c: '8 gyms',
                live: true
            },
            {
                e: '🌊',
                n: 'Goa',
                c: 'Coming soon',
                live: false
            },
            {
                e: '🏔️',
                n: 'Manali',
                c: 'Coming soon',
                live: false
            },
            {
                e: '🕌',
                n: 'Varanasi',
                c: 'Coming soon',
                live: false
            },
            {
                e: '🏯',
                n: 'Jaipur',
                c: 'Coming soon',
                live: false
            },
            {
                e: '🍵',
                n: 'Darjeeling',
                c: 'Coming soon',
                live: false
            },
        ];
        document.getElementById('cityGrid').innerHTML = cities.map(c => `
<div class="city-card${c.live?' city-card--live':''}" onclick="window.location='search.html'">
  <span class="city-card__status ${c.live?'city-card__status--live':'city-card__status--soon'}">${c.live?'Live':'Soon'}</span>
  <div class="city-card__emoji">${c.e}</div>
  <div class="city-card__name">${c.n}</div>
  <div class="city-card__count">${c.c}</div>
</div>`).join('');

        function cu(id, t) {
            const el = document.getElementById(id);
            let v = 0;
            const iv = setInterval(() => {
                v += t / 60;
                if (v >= t) {
                    el.textContent = Math.round(t).toLocaleString();
                    clearInterval(iv)
                } else el.textContent = Math.round(v).toLocaleString()
            }, 20);
        }
        cu('s1', 8);
        cu('s2', 1240);
        cu('s3', 1);
    </script>

</body>

</html>
