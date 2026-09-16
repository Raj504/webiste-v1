<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>My Bookings – GymPass India</title>
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

    <main style="max-width:880px;margin:40px auto;padding:0 16px;">
        <div class="settings-section__title">My Bookings</div>
        <div class="settings-section__sub">Every pass you've booked, past and present.</div>

        <div class="panel" style="margin-top:20px;">
            <div class="panel__header">
                <div class="panel__title">Bookings</div>
                <span class="panel__action">3 bookings</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Gym</th>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Valid</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="user-cell__name">Iron Temple Gym</div>
                            <div class="user-cell__meta">Rishikesh</div>
                        </td>
                        <td class="t-mono" style="font-size:11px;color:var(--text-secondary);">10 Days Pass</td>
                        <td style="font-family:var(--font-display);font-weight:700;color:var(--green);">₹350</td>
                        <td style="font-size:12px;">15 Sep 2026 – 25 Sep 2026</td>
                        <td><span class="pill pill--green">Active</span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-cell__name">Raj's Gym</div>
                            <div class="user-cell__meta">Ranibagh</div>
                        </td>
                        <td class="t-mono" style="font-size:11px;color:var(--text-secondary);">1 Day Pass</td>
                        <td style="font-family:var(--font-display);font-weight:700;color:var(--green);">₹100</td>
                        <td style="font-size:12px;">06 Sep 2026 – 06 Sep 2026</td>
                        <td><span class="pill pill--grey">Expired</span></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-cell__name">Iron Temple Gym</div>
                            <div class="user-cell__meta">Rishikesh</div>
                        </td>
                        <td class="t-mono" style="font-size:11px;color:var(--text-secondary);">10 Days Pass</td>
                        <td style="font-family:var(--font-display);font-weight:700;color:var(--green);">₹350</td>
                        <td style="font-size:12px;">16 Sep 2026 – 19 Sep 2026</td>
                        <td><span class="pill pill--yellow">pending</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>

</html>
