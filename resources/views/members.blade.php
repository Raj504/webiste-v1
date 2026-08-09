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
                    <button class="btn btn--primary btn--sm" id="toggleFormBtn" onclick="toggleForm()">+ Add Member</button>
                </div>
            </header>

            <main class="dash-content">

                <div class="grid-4 mb-24 anim-fade-up">
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--blue">👥</div>
                        </div>
                        <div class="stat-card__value" id="statTotal">0</div>
                        <div class="stat-card__label">Total Members</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--green">✅</div>
                        </div>
                        <div class="stat-card__value" id="statActive">0</div>
                        <div class="stat-card__label">Active Plans</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--yellow">⏰</div>
                        </div>
                        <div class="stat-card__value" id="statDueSoon">0</div>
                        <div class="stat-card__label">Renewals Due (7d)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__top">
                            <div class="stat-card__icon stat-card__icon--brand">🧳</div>
                        </div>
                        <div class="stat-card__value" id="statTraveler">0</div>
                        <div class="stat-card__label">Traveler Passes</div>
                    </div>
                </div>

                {{-- ADD / EDIT MEMBER PANEL — UI only, no backend wiring --}}
                <div class="panel mb-20 anim-fade-up hidden" id="memberForm">
                    <div class="panel__header">
                        <div class="panel__title" id="memberFormTitle">Add Member</div>
                    </div>
                    <div class="panel__body">
                        <div class="field--row">
                            <div class="field">
                                <label class="field__label">Name</label>
                                <input class="field__input" type="text" id="f_name" placeholder="Full name">
                            </div>
                            <div class="field">
                                <label class="field__label">Phone</label>
                                <input class="field__input" type="tel" id="f_phone" placeholder="10-digit number">
                            </div>
                        </div>
                        <div class="field--row">
                            <div class="field">
                                <label class="field__label">Email (optional)</label>
                                <input class="field__input" type="email" id="f_email" placeholder="For renewal reminders">
                            </div>
                            <div class="field">
                                <label class="field__label">Start Date</label>
                                <input class="field__input" type="date" id="f_start_date">
                            </div>
                        </div>
                        <div class="field--row">
                            <div class="field">
                                <label class="field__label">Duration</label>
                                <select class="field__input" id="f_duration">
                                    <option value="1_month">1 Month</option>
                                    <option value="3_months">3 Months</option>
                                    <option value="6_months">6 Months</option>
                                    <option value="12_months">12 Months</option>
                                    <option value="custom">Custom (days)</option>
                                </select>
                            </div>
                            <div class="field hidden" id="f_custom_days_wrap">
                                <label class="field__label">Days</label>
                                <input class="field__input" type="number" min="1" id="f_custom_days" placeholder="e.g. 45">
                            </div>
                        </div>
                        <div class="field">
                            <label class="field__label">Notes (optional)</label>
                            <input class="field__input" type="text" id="f_notes" placeholder="Anything worth remembering">
                        </div>
                        <button class="btn btn--primary btn--sm" onclick="saveMember()">+ Add Member</button>
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
                        <span class="panel__action" id="memberCount">0 members</span>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Type</th>
                                <th>Plan</th>
                                <th>Days Left</th>
                                <th>Progress</th>
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
        // ── Mock data — UI reference only, nothing here calls the real API ──────
        // Mirrors the shape returned by GET /owner/gym/members
        let members = [
            { id: 1, name: 'Vikram Tiwari', phone: '9876500001', email: 'vikram@example.com', source: 'manual', start_date: daysAgo(16), due_date: daysFromNow(14), plan_label: '1 Month', st: 'active' },
            { id: 2, name: 'Priya Mehta', phone: '9876500002', email: '', source: 'booking', start_date: daysAgo(1), due_date: daysFromNow(2), plan_label: '3 Days Pass', st: 'active' },
            { id: 3, name: 'Rohit Kumar', phone: '9876500003', email: '', source: 'booking', start_date: daysAgo(2), due_date: daysFromNow(5), plan_label: '7 Days Pass', st: 'active' },
            { id: 4, name: 'Anita Sharma', phone: '9876500004', email: 'anita@example.com', source: 'manual', start_date: daysAgo(27), due_date: daysFromNow(3), plan_label: '1 Month', st: 'active' },
            { id: 5, name: 'Deepak Verma', phone: '9876500005', email: 'deepak@example.com', source: 'manual', start_date: daysAgo(12), due_date: daysFromNow(18), plan_label: '1 Month', st: 'active' },
            { id: 6, name: 'Sunita Roy', phone: '9876500006', email: '', source: 'booking', start_date: daysAgo(0), due_date: daysFromNow(1), plan_label: 'Per Day Pass', st: 'active' },
            { id: 7, name: 'Harsh Gupta', phone: '9876500007', email: 'harsh@example.com', source: 'manual', start_date: daysAgo(24), due_date: daysFromNow(6), plan_label: '1 Month', st: 'active' },
            { id: 8, name: 'Kavya Iyer', phone: '9876500008', email: '', source: 'booking', start_date: daysAgo(0), due_date: daysFromNow(7), plan_label: '7 Days Pass', st: 'active' },
        ];
        let nextId = 9;

        let activeFilter = 'all';
        let searchQuery = '';
        let editingId = null;

        function daysAgo(n) { const d = new Date(); d.setDate(d.getDate() - n); return d.toISOString().slice(0, 10); }
        function daysFromNow(n) { const d = new Date(); d.setDate(d.getDate() + n); return d.toISOString().slice(0, 10); }
        function daysBetween(a, b) { return Math.round((new Date(b) - new Date(a)) / 86400000); }
        function todayIso() { return new Date().toISOString().slice(0, 10); }

        // ── Render ────────────────────────────────────────────────────────────
        function render() {
            const rows = members.filter(m => {
                const isExpiring = daysBetween(todayIso(), m.due_date) <= 7;
                const matchFilter =
                    activeFilter === 'all' ? true :
                    activeFilter === 'expiring' ? isExpiring :
                    activeFilter === 'local' ? m.source === 'manual' :
                    m.source === 'booking';
                const matchSearch = !searchQuery ||
                    m.name.toLowerCase().includes(searchQuery) ||
                    m.phone.includes(searchQuery);
                return matchFilter && matchSearch;
            });

            document.getElementById('memberCount').textContent = rows.length + ' members';
            document.getElementById('membertbody').innerHTML = rows.map(m => {
                const totalDays = Math.max(1, daysBetween(m.start_date, m.due_date));
                const elapsedDays = Math.max(0, daysBetween(m.start_date, todayIso()));
                const daysLeft = Math.max(0, totalDays - elapsedDays);
                const expired = daysLeft <= 0 && new Date(m.due_date) < new Date();
                const isLow = !expired && daysLeft <= 5;
                const pct = expired ? 100 : Math.min(100, Math.round((elapsedDays / totalDays) * 100));
                const barClass = (isLow || expired) ? 'progress-bar__fill--yellow' : 'progress-bar__fill--green';
                const dayClass = (isLow || expired) ? 't-yellow' : 't-brand';
                const typeClass = m.source === 'manual' ? 'pill--blue' : 'pill--brand';
                const statusPill = expired
                    ? '<span class="pill pill--yellow">Expired</span>'
                    : isLow
                        ? '<span class="pill pill--yellow">Expiring</span>'
                        : '<span class="pill pill--green">Active</span>';
                const canRemind = (expired || isLow) && m.email;

                return `
      <tr>
        <td>
          <div class="user-cell">
            <div class="user-cell__avatar">${m.source === 'booking' ? '🧳' : '👤'}</div>
            <div>
              <div class="user-cell__name">${m.name}</div>
              <div class="user-cell__meta">${m.phone}</div>
            </div>
          </div>
        </td>
        <td><span class="pill ${typeClass}">${m.source === 'manual' ? 'Local' : 'Traveler'}</span></td>
        <td class="t-mono t-muted">${m.plan_label}</td>
        <td class="t-display ${dayClass}">${expired ? '0d' : daysLeft + 'd'}</td>
        <td>
          <div class="progress-bar">
            <div class="progress-bar__fill ${barClass}" style="width:${pct}%"></div>
          </div>
        </td>
        <td>${statusPill}</td>
        <td>
          <div class="flex gap-8">
            ${canRemind ? `<button class="btn btn--ghost btn--sm" onclick="sendReminder(${m.id})">✉️ Remind</button>` : ''}
            <button class="btn btn--ghost btn--sm" onclick="editMember(${m.id})">Edit</button>
            <button class="btn btn--ghost btn--sm" onclick="deleteMember(${m.id})">Delete</button>
          </div>
        </td>
      </tr>`;
            }).join('');

            updateStats();
        }

        function updateStats() {
            document.getElementById('statTotal').textContent = members.length;
            document.getElementById('statActive').textContent = members.filter(m => daysBetween(todayIso(), m.due_date) >= 0).length;
            document.getElementById('statDueSoon').textContent = members.filter(m => {
                const d = daysBetween(todayIso(), m.due_date);
                return d >= 0 && d <= 7;
            }).length;
            document.getElementById('statTraveler').textContent = members.filter(m => m.source === 'booking').length;
        }

        // ── Filter / search ──────────────────────────────────────────────────
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

        // ── Add / Edit form — updates the local mock array only ─────────────
        document.getElementById('f_duration').addEventListener('change', (e) => {
            document.getElementById('f_custom_days_wrap').classList.toggle('hidden', e.target.value !== 'custom');
        });

        function toggleForm() {
            const form = document.getElementById('memberForm');
            const isHidden = form.classList.contains('hidden');
            if (isHidden) {
                resetForm();
                form.classList.remove('hidden');
                document.getElementById('toggleFormBtn').textContent = '✕ Close';
            } else {
                form.classList.add('hidden');
                document.getElementById('toggleFormBtn').textContent = '+ Add Member';
            }
        }

        function resetForm() {
            editingId = null;
            document.getElementById('memberFormTitle').textContent = 'Add Member';
            document.getElementById('f_name').value = '';
            document.getElementById('f_phone').value = '';
            document.getElementById('f_email').value = '';
            document.getElementById('f_start_date').value = todayIso();
            document.getElementById('f_duration').value = '1_month';
            document.getElementById('f_custom_days').value = '';
            document.getElementById('f_custom_days_wrap').classList.add('hidden');
            document.getElementById('f_notes').value = '';
        }

        function durationLabel(type, days) {
            return { '1_month': '1 Month', '3_months': '3 Months', '6_months': '6 Months', '12_months': '12 Months', 'custom': `Custom (${days || 0} days)` }[type] || 'Custom';
        }

        function addDuration(startDate, type, days) {
            const d = new Date(startDate);
            if (type === '1_month') d.setMonth(d.getMonth() + 1);
            else if (type === '3_months') d.setMonth(d.getMonth() + 3);
            else if (type === '6_months') d.setMonth(d.getMonth() + 6);
            else if (type === '12_months') d.setMonth(d.getMonth() + 12);
            else d.setDate(d.getDate() + (Number(days) || 30));
            return d.toISOString().slice(0, 10);
        }

        function saveMember() {
            const name = document.getElementById('f_name').value.trim();
            const phone = document.getElementById('f_phone').value.trim();
            if (!name || !phone) return;

            const email = document.getElementById('f_email').value.trim();
            const startDate = document.getElementById('f_start_date').value || todayIso();
            const durationType = document.getElementById('f_duration').value;
            const customDays = document.getElementById('f_custom_days').value;
            const notes = document.getElementById('f_notes').value.trim();
            const dueDate = addDuration(startDate, durationType, customDays);
            const planLabel = durationLabel(durationType, customDays);

            if (editingId !== null) {
                const existing = members.find(m => m.id === editingId);
                Object.assign(existing, { name, phone, email, start_date: startDate, due_date: dueDate, plan_label: planLabel, notes });
            } else {
                // De-duplicate by phone, same as the real backend — reuse the existing row instead of adding a second one
                const existing = members.find(m => m.phone === phone);
                if (existing) {
                    Object.assign(existing, { name, email, start_date: startDate, due_date: dueDate, plan_label: planLabel, notes });
                } else {
                    members.push({ id: nextId++, name, phone, email, source: 'manual', start_date: startDate, due_date: dueDate, plan_label: planLabel, notes });
                }
            }

            toggleForm();
            render();
        }

        function editMember(id) {
            const m = members.find(x => x.id === id);
            if (!m) return;
            editingId = id;
            document.getElementById('memberFormTitle').textContent = 'Renew / Edit Member';
            document.getElementById('f_name').value = m.name;
            document.getElementById('f_phone').value = m.phone;
            document.getElementById('f_email').value = m.email || '';
            document.getElementById('f_start_date').value = todayIso();
            document.getElementById('f_duration').value = '1_month';
            document.getElementById('f_custom_days_wrap').classList.add('hidden');
            document.getElementById('f_notes').value = m.notes || '';
            document.getElementById('memberForm').classList.remove('hidden');
            document.getElementById('toggleFormBtn').textContent = '✕ Close';
        }

        function deleteMember(id) {
            if (!confirm('Remove this member?')) return;
            members = members.filter(m => m.id !== id);
            render();
        }

        function sendReminder(id) {
            const m = members.find(x => x.id === id);
            if (!m) return;
            alert(`Reminder email queued for ${m.name} (${m.email}). Wire this up to POST /owner/gym/members/${id}/send-reminder.`);
        }

        render();
    </script>
</body>

</html>
