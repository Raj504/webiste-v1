<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Settings – GymPass Owner</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">

</head>

<body>
    <div class="dash-layout">

        @include('partials.gym-sidebar')


        <div class="dash-main">
            <header class="dash-topbar">
                <div class="dash-topbar__left">
                    <div class="dash-topbar__title">Gym Settings</div>
                    <div class="dash-topbar__sub">Manage your gym profile, pricing &amp; availability</div>
                </div>
                <div class="dash-topbar__right">
                    <button class="btn btn--ghost btn--sm">👁️ Preview Listing</button>
                </div>
            </header>

            <main class="dash-content">
                <div class="settings-layout anim-fade-up">

                    <!-- LEFT NAV -->
                    <nav class="settings-nav">
                        <div class="settings-nav__item is-active" onclick="showSection('basic', this)">🏋️ Basic Info
                        </div>
                        <div class="settings-nav__item" onclick="showSection('pricing', this)">💰 Pricing</div>
                        <div class="settings-nav__item" onclick="showSection('hours', this)">🕐 Hours</div>
                        <div class="settings-nav__item" onclick="showSection('amenities', this)">✨ Amenities</div>
                        <div class="settings-nav__item" onclick="showSection('notifications', this)">🔔 Notifications
                        </div>
                        <div class="settings-nav__item" onclick="showSection('payout', this)">💳 Payout</div>
                        <div class="settings-nav__item t-red" onclick="showSection('danger', this)">⚠️ Danger Zone</div>
                    </nav>

                    <!-- RIGHT CONTENT -->
                    <div>

                        <!-- BASIC INFO -->
                        <div id="sec-basic" class="settings-section is-active">
                            <div class="settings-section__title">Basic Info</div>
                            <div class="settings-section__sub">Your public gym listing information.</div>
                            <div class="panel">
                                <div class="panel__body">
                                    <div class="field--row">
                                        <div class="field">
                                            <label class="field__label">Gym Name</label>
                                            <input class="field__input" type="text" value="Iron Temple Gym">
                                        </div>
                                        <div class="field">
                                            <label class="field__label">Owner Name</label>
                                            <input class="field__input" type="text" value="Vikram Singh">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="field__label">Address / Locality</label>
                                        <input class="field__input" type="text"
                                            value="Near Ram Jhula, Laxman Jhula Road, Rishikesh">
                                    </div>
                                    <div class="field--row">
                                        <div class="field">
                                            <label class="field__label">City</label>
                                            <select class="field__input">
                                                <option selected>Rishikesh</option>
                                                <option>Goa</option>
                                                <option>Manali</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label class="field__label">Phone</label>
                                            <input class="field__input" type="tel" value="+91 98765 43210">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="field__label">Description</label>
                                        <textarea class="field__input" rows="3">A serious gym with free weights, machines, and experienced trainers. Located walking distance from Ram Jhula bridge. Clean, AC-equipped, open 7 days a week.</textarea>
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">Changes go live immediately</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PRICING -->
                        <div id="sec-pricing" class="settings-section">
                            <div class="settings-section__title">Pricing</div>
                            <div class="settings-section__sub">Set your own price for each plan. Add custom durations or disable plans you don't offer.</div>
                         
                            <!-- Default Plans -->
                            <div class="panel mb-20">
                                <div class="panel__header">
                                    <div class="panel__title">Default Plans</div>
                                    <span class="panel__action">Disable plans you don't want to offer</span>
                                </div>
                                <div class="panel__body" style="padding:0">
                                    <div id="default-plans-list"></div>
                                </div>
                                <div class="save-bar">
                                    <span class="save-bar__note">Changes apply to future bookings only</span>
                                    <button class="btn btn--primary" onclick="saveDefaultPlans()">Save Default Plans</button>
                                </div>
                            </div>
                         
                            <!-- Custom Plans -->
                            <div class="panel">
                                <div class="panel__header">
                                    <div class="panel__title">Custom Plans</div>
                                    <span class="panel__action">Add any duration you want</span>
                                </div>
                                <div class="panel__body" style="padding:0">
                                    <div id="custom-plans-list"></div>
                         
                                    <!-- Add new custom plan -->
                                    <div class="plan-add-row" id="plan-add-form">
                                        <div class="plan-add-row__inputs">
                                            <div class="plan-add-row__duration">
                                                <label class="field__label">Duration</label>
                                                <input class="field__input" type="number" id="newDuration" min="1" max="365" placeholder="e.g. 10">
                                            </div>
                                            <div class="plan-add-row__unit">
                                                <label class="field__label">Unit</label>
                                                <select class="field__input" id="newUnit">
                                                    <option value="day">Day(s)</option>
                                                    <option value="month">Month(s)</option>
                                                </select>
                                            </div>
                                            <div class="plan-add-row__price">
                                                <label class="field__label">Price (₹)</label>
                                                <input class="field__input" type="number" id="newPrice" min="1" placeholder="e.g. 350">
                                            </div>
                                        </div>
                                        <div class="plan-add-row__preview t-muted" id="planPreview">Fill in the fields above</div>
                                        <button class="btn btn--primary btn--sm" onclick="addCustomPlan()">+ Add Plan</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- HOURS -->
                        <div id="sec-hours" class="settings-section">
                            <div class="settings-section__title">Operating Hours</div>
                            <div class="settings-section__sub">Set your opening and closing times for each day.</div>
                            <div class="panel">
                                <div class="panel__body">
                        
                                    <!-- Days -->
                                    <div id="hours-days"></div>
                        
                                    <!-- Divider -->
                                    <div class="divider"></div>
                        
                                    <!-- Closed today toggle -->
                                    <div class="toggle">
                                        <div class="toggle__info">
                                            <div class="toggle__title">Mark as Closed Today</div>
                                            <div class="toggle__desc">Temporarily close for a holiday or maintenance</div>
                                        </div>
                                        <label class="toggle__switch">
                                            <input type="checkbox" id="closedToday" onchange="renderHoursSummary()">
                                            <span class="toggle__track"></span>
                                        </label>
                                    </div>
                        
                                    {{-- <!-- Live summary -->
                                    <div class="callout mt-12" id="hours-summary" style="font-size:12px;line-height:2"></div>
                        
                                    <!-- Presets -->
                                    <div class="flex gap-8 mt-12 mb-16 flex-wrap">
                                        <span class="t-label" style="align-self:center">Quick presets:</span>
                                        <button class="btn btn--ghost btn--sm" onclick="applyHoursPreset('standard')">Standard</button>
                                        <button class="btn btn--ghost btn--sm" onclick="applyHoursPreset('early')">Early Bird</button>
                                        <button class="btn btn--ghost btn--sm" onclick="applyHoursPreset('extended')">Extended</button>
                                        <button class="btn btn--ghost btn--sm" onclick="applyHoursPreset('247')">24 / 7</button>
                                    </div> --}}
                        
                                    <div class="save-bar">
                                        <span class="save-bar__note">Travelers see real-time open/closed status</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Save Hours</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════════════════════
                            AMENITIES SECTION — drop-in replacement for sec-amenities
                            ═══════════════════════════════════════════════════════════ --}}
                        
                        <div id="sec-amenities" class="settings-section">
                            <div class="settings-section__title">Amenities</div>
                            <div class="settings-section__sub">Select what your gym offers — shown on your listing. Add custom amenities if yours isn't listed.</div>
                        
                            <div class="panel mb-20">
                                <div class="panel__header">
                                    <div class="panel__title">Available Amenities</div>
                                    <span class="panel__action" id="amenity-count">0 selected</span>
                                </div>
                                <div class="panel__body">
                                    <div class="amenity-grid" id="amenity-grid"></div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">Helps travelers filter gyms by amenity</span>
                                        <button class="btn btn--primary" onclick="saveAmenities(this)">Save Amenities</button>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Add custom amenity -->
                            <div class="panel">
                                <div class="panel__header">
                                    <div class="panel__title">Add Custom Amenity</div>
                                    <span class="panel__action">Visible to all gym owners</span>
                                </div>
                                <div class="panel__body">
                                    <div class="field--row">
                                        <div class="field">
                                            <label class="field__label">Emoji Icon</label>
                                            <input class="field__input" type="text" id="customIcon"
                                                placeholder="e.g. 🧖" maxlength="4"
                                                style="font-size:20px;text-align:center">
                                        </div>
                                        <div class="field" style="flex:3">
                                            <label class="field__label">Amenity Name</label>
                                            <input class="field__input" type="text" id="customName"
                                                placeholder="e.g. Sauna, Steam Room, Juice Bar..."
                                                maxlength="50">
                                        </div>
                                    </div>
                                    <div class="callout mb-16">
                                        <span class="callout__icon">💡</span>
                                        This amenity will be added to the global list and auto-selected for your gym. Other gym owners can also pick it.
                                    </div>
                                    <button class="btn btn--primary btn--sm" onclick="addCustomAmenity(this)">+ Add Amenity</button>
                                </div>
                            </div>
                        </div>

                        <!-- NOTIFICATIONS -->
                        <div id="sec-notifications" class="settings-section">
                            <div class="settings-section__title">Notifications</div>
                            <div class="settings-section__sub">Choose how you want to be notified about bookings.</div>
                            <div class="panel">
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">New Booking Alert</div>
                                        <div class="toggle__desc">Get SMS when a traveler books your gym</div>
                                    </div>
                                    <label class="toggle__switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle__track"></span>
                                    </label>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Payout Notification</div>
                                        <div class="toggle__desc">Get notified when your payout is transferred</div>
                                    </div>
                                    <label class="toggle__switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle__track"></span>
                                    </label>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">New Review Alert</div>
                                        <div class="toggle__desc">Get notified when a member leaves a review</div>
                                    </div>
                                    <label class="toggle__switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle__track"></span>
                                    </label>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Member Renewal Reminders</div>
                                        <div class="toggle__desc">7 days before a local member's plan expires</div>
                                    </div>
                                    <label class="toggle__switch">
                                        <input type="checkbox">
                                        <span class="toggle__track"></span>
                                    </label>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Daily Summary Report</div>
                                        <div class="toggle__desc">SMS with daily bookings &amp; revenue summary at 10
                                            PM</div>
                                    </div>
                                    <label class="toggle__switch">
                                        <input type="checkbox">
                                        <span class="toggle__track"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- PAYOUT -->
                        <div id="sec-payout" class="settings-section">
                            <div class="settings-section__title">Payout Settings</div>
                            <div class="settings-section__sub">How you receive your earnings.</div>
                            <div class="panel">
                                <div class="panel__body">
                                    <div class="field">
                                        <label class="field__label">UPI ID</label>
                                        <input class="field__input" type="text" value="irontemple@paytm">
                                    </div>
                                    <div class="field">
                                        <label class="field__label">Account Holder Name</label>
                                        <input class="field__input" type="text" value="Vikram Singh">
                                    </div>
                                    <div class="callout mb-16">
                                        <span class="callout__icon">ℹ️</span>
                                        Payouts are processed every Monday at 10 AM for the previous week. Minimum
                                        payout threshold is ₹100.
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">UPI changes apply to next payout cycle</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Update Payout
                                            Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DANGER ZONE -->
                        <div id="sec-danger" class="settings-section">
                            <div class="settings-section__title t-red">Danger Zone</div>
                            <div class="settings-section__sub">Irreversible actions — proceed carefully.</div>
                            <div class="panel panel--danger">
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Pause Gym Listing</div>
                                        <div class="toggle__desc">Hide your gym from traveler searches temporarily.
                                            Existing bookings remain valid.</div>
                                    </div>
                                    <button class="btn btn--danger btn--sm"
                                        onclick="if(confirm('Pause your listing?')) alert('Listing paused.')">Pause
                                        Listing</button>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Delete Gym Account</div>
                                        <div class="toggle__desc">Permanently remove your gym and all data. This cannot
                                            be undone.</div>
                                    </div>
                                    <button class="btn btn--danger btn--sm"
                                        onclick="if(confirm('Are you absolutely sure?')) alert('Contact support@gympass.in to complete deletion.')">Delete
                                        Account</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function showSection(id, el) {
            document.querySelectorAll('.settings-section').forEach(s => s.classList.remove('is-active'));
            document.querySelectorAll('.settings-nav__item').forEach(s => s.classList.remove('is-active'));
            document.getElementById('sec-' + id).classList.add('is-active');
            el.classList.add('is-active');
        }

        function updatePricingPreview() {
            const r = parseInt(document.getElementById('rateInput').value) || 0;
            document.getElementById('pd').textContent = '₹' + Math.round(r * 0.10);
            document.getElementById('p3').textContent = '₹' + Math.round(r * 0.25);
            document.getElementById('p7').textContent = '₹' + Math.round(r * 0.50);
            document.getElementById('pm').textContent = '₹' + r;
        }

        function savedFeedback(btn) {
            const orig = btn.textContent;
            btn.textContent = '✓ Saved!';
            btn.style.background = 'var(--green)';
            setTimeout(() => {
                btn.textContent = orig;
                btn.style.background = '';
            }, 2000);
        }

        const DAYS = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

        let hoursSchedule = DAYS.map((d, i) => ({
            day: d,
            open:   i < 5 ? '06:00' : i === 5 ? '07:00' : '00:00',
            close:  i < 5 ? '22:00' : i === 5 ? '21:00' : '00:00',
            closed: i === 6,
        }));

        function fmtTime(t) {
            const [h, m] = t.split(':').map(Number);
            const ampm = h >= 12 ? 'PM' : 'AM';
            return `${h % 12 || 12}:${String(m).padStart(2,'0')} ${ampm}`;
        }

        function renderHours() {
            const c = document.getElementById('hours-days');
            c.innerHTML = hoursSchedule.map((s, i) => `
                <div class="hours-day-row">
                    <div class="hours-day-label">${s.day}</div>
                    <div class="hours-time-range ${s.closed ? 'is-closed' : ''}" id="hrange-${i}">
                        <input type="time" class="hours-time-input" value="${s.open}"
                            onchange="hoursSchedule[${i}].open=this.value;renderHoursSummary()"
                            aria-label="${s.day} open time">
                        <span class="t-muted">—</span>
                        <input type="time" class="hours-time-input" value="${s.close}"
                            onchange="hoursSchedule[${i}].close=this.value;renderHoursSummary()"
                            aria-label="${s.day} close time">
                    </div>
                    <label class="hours-close-label">
                        <input type="checkbox" ${s.closed ? 'checked' : ''}
                            onchange="hoursSchedule[${i}].closed=this.checked;renderHours()">
                        ${s.closed ? '<span class="pill pill--red">Closed</span>' : '<span>Close</span>'}
                    </label>
                </div>`).join('');
            renderHoursSummary();
        }

        function renderHoursSummary() {
            const closedToday = document.getElementById('closedToday')?.checked;
            const lines = hoursSchedule.map(s =>
                s.closed
                    ? `<strong>${s.day.slice(0,3)}</strong>: Closed`
                    : `<strong>${s.day.slice(0,3)}</strong>: ${fmtTime(s.open)} – ${fmtTime(s.close)}`
            ).join(' &nbsp;·&nbsp; ');
            document.getElementById('hours-summary').innerHTML =
                lines + (closedToday ? ' &nbsp;·&nbsp; <span class="t-red">Closed today (override active)</span>' : '');
        }

        // function applyHoursPreset(key) {
        //     const presets = {
        //         standard: { open:'06:00', close:'22:00', closedDays:[6] },
        //         early:    { open:'05:00', close:'20:00', closedDays:[6] },
        //         extended: { open:'05:00', close:'23:59', closedDays:[] },
        //         '247':    { open:'00:00', close:'23:59', closedDays:[] },
        //     };
        //     const p = presets[key];
        //     hoursSchedule = DAYS.map((d,i) => ({ day:d, open:p.open, close:p.close, closed:p.closedDays.includes(i) }));
        //     renderHours();
        // }



        let gymPlans = [
            { id: 1, name: '1 Day Pass',   duration: 1, unit: 'day',   price: 80,  is_default: true,  is_enabled: true },
            { id: 2, name: '3 Day Pass',   duration: 3, unit: 'day',   price: 200, is_default: true,  is_enabled: true },
            { id: 3, name: '7 Day Pass',   duration: 7, unit: 'day',   price: 400, is_default: true,  is_enabled: true },
            { id: 4, name: '1 Month Pass', duration: 1, unit: 'month', price: 800, is_default: true,  is_enabled: true },
        ];
        
        // ── Render ────────────────────────────────────────────────────────────────────
        function renderPlans() {
            const defaults = gymPlans.filter(p => p.is_default);
            const customs  = gymPlans.filter(p => !p.is_default);
        
            // Default plans
            document.getElementById('default-plans-list').innerHTML = defaults.length
                ? defaults.map(p => planRowHtml(p, false)).join('')
                : '<div class="plans-empty">No default plans found.</div>';
        
            // Custom plans
            document.getElementById('custom-plans-list').innerHTML = customs.length
                ? customs.map(p => planRowHtml(p, true)).join('')
                : '<div class="plans-empty">No custom plans yet. Add one below.</div>';
        
            // Live preview
            updatePlanPreview();
        }
        
        function planRowHtml(p, showDelete) {
            const unitLabel = p.duration === 1 ? p.unit : `${p.unit}s`;
            const meta      = `${p.duration} ${unitLabel}`;
            return `
                <div class="plan-row ${p.is_enabled ? '' : 'is-disabled'}" id="plan-row-${p.id}">
                    <div class="plan-row__info">
                        <div class="plan-row__name">${p.name}</div>
                        <div class="plan-row__meta">${meta}</div>
                        <div class="plan-row__toggle">
                            <label class="toggle__switch" style="width:36px;height:20px">
                                <input type="checkbox" ${p.is_enabled ? 'checked' : ''}
                                    onchange="togglePlan(${p.id}, this.checked)">
                                <span class="toggle__track"></span>
                            </label>
                            <span class="plan-row__toggle-label">${p.is_enabled ? 'Enabled' : 'Disabled'}</span>
                        </div>
                    </div>
                    <div class="plan-price-wrap">
                        <span class="plan-price-wrap__sym">₹</span>
                        <input class="plan-price-input" type="number" min="1"
                            value="${p.price}"
                            onchange="updatePlanPrice(${p.id}, this.value)"
                            aria-label="${p.name} price">
                    </div>
                    ${showDelete
                        ? `<button class="plan-delete-btn" onclick="deleteCustomPlan(${p.id})" title="Delete plan">✕</button>`
                        : '<div></div>'
                    }
                </div>`;
        }
        
        // ── Actions ───────────────────────────────────────────────────────────────────
        function togglePlan(id, enabled) {
            const plan = gymPlans.find(p => p.id === id);
            if (plan) plan.is_enabled = enabled;
            renderPlans();
            // API: PUT /api/owner/gym/plans/{id} { is_enabled: enabled }
        }
        
        function updatePlanPrice(id, price) {
            const plan = gymPlans.find(p => p.id === id);
            if (plan) plan.price = parseInt(price) || 0;
            // API: PUT /api/owner/gym/plans/{id} { price: parseInt(price) }
        }
        
        function saveDefaultPlans() {
            // API: loop through defaults and PUT each one
            // For now just show feedback
            const btn = event.target;
            savedFeedback(btn);
        }
        
        function addCustomPlan() {
            const duration = parseInt(document.getElementById('newDuration').value);
            const unit     = document.getElementById('newUnit').value;
            const price    = parseInt(document.getElementById('newPrice').value);
        
            if (!duration || duration < 1) { alert('Enter a valid duration.'); return; }
            if (!price || price < 1)       { alert('Enter a valid price.'); return; }
        
            // Check duplicate
            const exists = gymPlans.find(p => p.duration === duration && p.unit === unit);
            if (exists) {
                alert(`A ${duration} ${unit} plan already exists. Update its price instead.`);
                return;
            }
        
            const unitLabel = duration === 1 ? unit : `${unit}s`;
            const name      = `${duration} ${unitLabel.charAt(0).toUpperCase() + unitLabel.slice(1)} Pass`;
        
            // Optimistic — add to local state, then API
            const tempId = Date.now();
            gymPlans.push({ id: tempId, name, duration, unit, price, is_default: false, is_enabled: true });
            renderPlans();
        
            // Clear form
            document.getElementById('newDuration').value = '';
            document.getElementById('newPrice').value    = '';
        
            // API: POST /api/owner/gym/plans { duration, unit, price }
            // On success: replace tempId with real id from response
        }
        
        function deleteCustomPlan(id) {
            if (!confirm('Delete this plan? Travelers will no longer see it.')) return;
            gymPlans = gymPlans.filter(p => p.id !== id);
            renderPlans();
            // API: DELETE /api/owner/gym/plans/{id}
        }
        
        // Live preview of the new plan being added
        function updatePlanPreview() {
            const duration = parseInt(document.getElementById('newDuration')?.value);
            const unit     = document.getElementById('newUnit')?.value;
            const price    = parseInt(document.getElementById('newPrice')?.value);
            const preview  = document.getElementById('planPreview');
            if (!preview) return;
        
            if (duration && unit && price) {
                const unitLabel = duration === 1 ? unit : `${unit}s`;
                const name      = `${duration} ${unitLabel.charAt(0).toUpperCase() + unitLabel.slice(1)} Pass`;
                preview.innerHTML = `<span class="t-brand">${name}</span> &nbsp;·&nbsp; ₹${price.toLocaleString('en-IN')}`;
                preview.classList.remove('t-muted');
            } else {
                preview.textContent = 'Fill in the fields above to preview';
                preview.classList.add('t-muted');
            }
        }
        
        // Wire up preview on input


        let allAmenities = [
            { id: 1,  icon: '🧊', name: 'AC',            is_default: true,  is_selected: true  },
            { id: 2,  icon: '🔒', name: 'Lockers',        is_default: true,  is_selected: true  },
            { id: 3,  icon: '🚿', name: 'Shower',         is_default: true,  is_selected: true  },
            { id: 4,  icon: '🅿️', name: 'Parking',        is_default: false, is_selected: false },
            { id: 5,  icon: '👨‍💼', name: 'Trainer',        is_default: false, is_selected: false },
            { id: 6,  icon: '💪', name: 'Free Weights',   is_default: false, is_selected: false },
            { id: 7,  icon: '🏊', name: 'Pool',           is_default: false, is_selected: false },
            { id: 8,  icon: '🧘', name: 'Yoga Room',      is_default: false, is_selected: false },
            { id: 9,  icon: '🥤', name: 'Protein Bar',    is_default: false, is_selected: false },
            { id: 10, icon: '📺', name: 'TV / Music',     is_default: false, is_selected: false },
            { id: 11, icon: '🌐', name: 'WiFi',           is_default: false, is_selected: false },
            { id: 12, icon: '🧺', name: 'Towel Service',  is_default: false, is_selected: false },
        ];

        function renderAmenities() {
            const grid = document.getElementById('amenity-grid');
            if (!grid) return;
        
            grid.innerHTML = allAmenities.map(a => `
                <button class="amenity-chip ${a.is_selected ? 'is-selected' : ''}"
                    onclick="toggleAmenity(${a.id})">
                    <span class="amenity-chip__icon">${a.icon}</span>
                    <span class="amenity-chip__name">${a.name}</span>
                    ${!a.is_default ? '<span class="amenity-chip__badge">Custom</span>' : ''}
                </button>
            `).join('');
        
            updateAmenityCount();
        }

        function updateAmenityCount() {
            const count = allAmenities.filter(a => a.is_selected).length;
            const el = document.getElementById('amenity-count');
            if (el) el.textContent = `${count} selected`;
        }

        function toggleAmenity(id) {
            const a = allAmenities.find(a => a.id === id);
            if (a) a.is_selected = !a.is_selected;
            renderAmenities();
        }


        function saveAmenities(btn) {
            const selectedIds = allAmenities.filter(a => a.is_selected).map(a => a.id);
        
            // API: POST /api/owner/gym/amenities/sync
            // Body: { amenity_ids: selectedIds }
        
            savedFeedback(btn);
        }

        function addCustomAmenity(btn) {
            const icon = document.getElementById('customIcon').value.trim();
            const name = document.getElementById('customName').value.trim();
        
            if (!name) { alert('Please enter an amenity name.'); return; }
        
            // Check duplicate
            if (allAmenities.find(a => a.name.toLowerCase() === name.toLowerCase())) {
                alert('This amenity already exists in the list.');
                return;
            }
        
            // API: POST /api/owner/gym/amenities/custom
            // Body: { name, icon }
            // On success: use real id from response
        
            const tempId = Date.now();
            allAmenities.push({
                id:          tempId,
                icon:        icon || '🏋️',
                name:        name,
                is_default:  false,
                is_selected: true,  // auto-selected
            });
                
            // Clear form
            document.getElementById('customIcon').value = '';
            document.getElementById('customName').value = '';
        
            // Show feedback
            const orig = btn.textContent;
            btn.textContent = '✓ Added!';
            btn.style.background = 'var(--green)';
            setTimeout(() => { btn.textContent = orig; btn.style.background = ''; }, 2000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderPlans();
            ['newDuration', 'newUnit', 'newPrice'].forEach(id => {
                document.getElementById(id)?.addEventListener('input', updatePlanPreview);
            });
            renderHours();
            renderAmenities();
        });
    </script>
</body>

</html>
