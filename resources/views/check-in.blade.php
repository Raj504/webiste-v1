<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Gym Check-in – GymPass India</title>
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
            {{-- UI reference only, no real session/auth wiring here --}}
            <details class="profile-dropdown">
                <summary class="profile-dropdown__trigger">
                    <span class="profile-dropdown__avatar">M</span>
                    Hi, Manish
                </summary>
                <div class="profile-dropdown__menu">
                    <a href="{{ route('profile') }}" class="profile-dropdown__item">👤 Profile</a>
                    <a href="{{ route('my-bookings') }}" class="profile-dropdown__item">📅 My Bookings</a>
                    <a href="{{ route('index') }}" class="profile-dropdown__item">🚪 Log out</a>
                </div>
            </details>
        </div>
    </header>

    <main style="max-width:420px;margin:40px auto;padding:0 16px;">
        <div class="settings-section__sub mb-20" style="text-align:center;">
            This page documents every outcome a traveler can see after scanning a gym's QR code — only one of these
            renders at a time on the real page.
        </div>

        {{-- STATE 1: Confirm step, before tapping — the actual "landing" state --}}
        <div class="panel mb-20">
            <div class="panel__body" style="text-align:center;">
                <div style="font-size:40px;margin-bottom:8px;">📍</div>
                <div class="t-heading mb-8">Checking in at</div>
                <div class="settings-section__title" style="margin-bottom:4px;">Iron Temple Gym</div>
                <div class="t-muted mb-20">Rishikesh</div>
                <button class="btn btn--primary btn--lg">✅ Confirm Check-in</button>
            </div>
        </div>

        {{-- STATE 2: Granted --}}
        <div class="panel mb-20">
            <div class="panel__body" style="text-align:center;">
                <div style="font-size:40px;margin-bottom:8px;">✅</div>
                <div class="t-heading mb-8">You're checked in!</div>
                <div class="t-muted">Checked in at Iron Temple Gym!</div>
            </div>
        </div>

        {{-- STATE 3: Denied — no valid pass at this gym --}}
        <div class="panel mb-20">
            <div class="panel__body" style="text-align:center;">
                <div style="font-size:40px;margin-bottom:8px;">❌</div>
                <div class="t-heading mb-8">Check-in denied</div>
                <div class="t-muted mb-20">You don't have a pass at this gym. Book one to check in.</div>
                <a href="{{ route('search') }}" class="btn btn--primary">🔍 Find a pass</a>
            </div>
        </div>

        {{-- STATE 4: Denied — pass expired --}}
        <div class="panel">
            <div class="panel__body" style="text-align:center;">
                <div style="font-size:40px;margin-bottom:8px;">❌</div>
                <div class="t-heading mb-8">Check-in denied</div>
                <div class="t-muted">Your pass expired on 14 Sep 2026.</div>
            </div>
        </div>
    </main>

</body>

</html>
