<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>My Profile – GymPass India</title>
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

    <main style="max-width:680px;margin:40px auto;padding:0 16px;">
        <div class="settings-section__title">Profile</div>
        <div class="settings-section__sub">Your personal details.</div>

        <div class="panel" style="margin-top:20px;">
            <div class="panel__body">
                <div class="field" style="margin-bottom:24px;">
                    <label class="field__label">Profile Photo</label>
                    <div style="display:flex;align-items:center;gap:16px;margin-top:8px;">
                        <div style="width:64px;height:64px;border-radius:50%;background:var(--brand);color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;">
                            M
                        </div>
                        <button class="btn btn--ghost btn--sm">Change Photo</button>
                    </div>
                </div>

                <div class="field">
                    <label class="field__label">Name</label>
                    <input class="field__input" type="text" value="Manish Saini">
                </div>

                <div class="field--row">
                    <div class="field">
                        <label class="field__label">Email</label>
                        <input class="field__input" type="email" value="manish@example.com">
                    </div>
                    <div class="field">
                        <label class="field__label">Phone</label>
                        <input class="field__input" type="tel" value="9876543210">
                    </div>
                </div>

                <div class="field">
                    <label class="field__label">Home City</label>
                    <input class="field__input" type="text" value="Ramnagar">
                </div>

                <div class="save-bar">
                    <span class="save-bar__note">Changes go live immediately</span>
                    <button class="btn btn--primary">Save Changes</button>
                </div>
            </div>
        </div>
    </main>

</body>

</html>
