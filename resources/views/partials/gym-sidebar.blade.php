<aside class="sidebar">
    <div class="sidebar__logo">
        <a href="{{ route('index') }}" class="sidebar__wordmark">GymPass<span>.</span>in</a>
        <span class="sidebar__badge">Owner Portal</span>
    </div>
    <nav class="sidebar__nav">
        <a href="{{ route('dashboard') }}" class="sidebar__item"><span class="sidebar__item-icon">📊</span> Dashboard</a>
        <a href="{{ route('bookings') }}" class="sidebar__item"><span class="sidebar__item-icon">📅</span> Bookings <span
                class="nav-badge">3</span></a>
        <a href="{{ route('members') }}" class="sidebar__item"><span class="sidebar__item-icon">👥</span> Members</a>
        <a href="{{ route('qr-scanner') }}" class="sidebar__item"><span class="sidebar__item-icon">📲</span> QR
            Scanner</a>
        <div class="sidebar__section-label">Management</div>
        <a href="{{ route('payouts') }}" class="sidebar__item"><span class="sidebar__item-icon">💰</span> Payouts</a>
        <a href="{{ route('gym-settings') }}" class="sidebar__item"><span class="sidebar__item-icon">⚙️</span> Gym
            Settings</a>
        <a href="{{ route('reviews') }}" class="sidebar__item is-active"><span class="sidebar__item-icon">⭐</span>
            Reviews <span class="nav-badge nav-badge--green">2</span></a>
        <a href="{{ route('analytics') }}" class="sidebar__item"><span class="sidebar__item-icon">📈</span>
            Analytics</a>
    </nav>
    <div class="sidebar__footer">
        <div class="sidebar__profile">
            <div class="sidebar__gym-avatar">🏋️</div>
            <div>
                <div class="sidebar__gym-name">Iron Temple Gym</div>
                <div class="sidebar__gym-status">Live · Rishikesh</div>
            </div>
        </div>
    </div>
</aside>
