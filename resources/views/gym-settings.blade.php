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
                        <div class="settings-nav__item is-active" onclick="showSection('basic', this)">🏋️ Basic Info</div>
                        <div class="settings-nav__item" onclick="showSection('pricing', this)">💰 Pricing</div>
                        <div class="settings-nav__item" onclick="showSection('hours', this)">🕐 Hours</div>
                        <div class="settings-nav__item" onclick="showSection('amenities', this)">✨ Amenities</div>
                        <div class="settings-nav__item" onclick="showSection('media', this)">📸 Photos &amp; Videos</div>
                        <div class="settings-nav__item" onclick="showSection('notifications', this)">🔔 Notifications</div>
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
                                        <input class="field__input" type="text" value="Near Ram Jhula, Laxman Jhula Road, Rishikesh">
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
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PRICING -->
                        <div id="sec-pricing" class="settings-section">
                            <div class="settings-section__title">Pricing</div>
                            <div class="settings-section__sub">Set your monthly rate — all other plans are auto-calculated.</div>
                            <div class="panel">
                                <div class="panel__body">
                                    <div class="field">
                                        <label class="field__label">Monthly Membership Rate (₹)</label>
                                        <input class="field__input t-display t-brand" id="rateInput" type="number" value="800" oninput="updatePricingPreview()">
                                    </div>
                                    <div class="pricing-preview mb-16">
                                        <div class="t-label pricing-preview__label">Auto-calculated plans</div>
                                        <div class="pricing-preview__row">
                                            <span>Per Day <span class="t-muted">· 10% of monthly</span></span>
                                            <span class="pricing-preview__price" id="pd">₹80</span>
                                        </div>
                                        <div class="pricing-preview__row">
                                            <span>3 Days <span class="t-muted">· 25% of monthly</span></span>
                                            <span class="pricing-preview__price" id="p3">₹200</span>
                                        </div>
                                        <div class="pricing-preview__row">
                                            <span>7 Days <span class="t-muted">· 50% of monthly</span></span>
                                            <span class="pricing-preview__price" id="p7">₹400</span>
                                        </div>
                                        <div class="pricing-preview__row">
                                            <span class="t-brand">Monthly</span>
                                            <span class="pricing-preview__price" id="pm">₹800</span>
                                        </div>
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">Applies to future bookings only</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Update Pricing</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- HOURS -->
                        <div id="sec-hours" class="settings-section">
                            <div class="settings-section__title">Operating Hours</div>
                            <div class="settings-section__sub">Your opening and closing times each day.</div>
                            <div class="panel">
                                <div class="panel__body">
                                    <div class="hours-grid mb-20">
                                        <div class="hour-card" onclick="alert('Edit Mon–Fri hours')">
                                            <div class="hour-card__day">Mon – Fri</div>
                                            <div class="hour-card__time">6:00 AM – 10:00 PM</div>
                                        </div>
                                        <div class="hour-card" onclick="alert('Edit Saturday hours')">
                                            <div class="hour-card__day">Saturday</div>
                                            <div class="hour-card__time">7:00 AM – 9:00 PM</div>
                                        </div>
                                        <div class="hour-card is-closed" onclick="alert('Edit Sunday hours')">
                                            <div class="hour-card__day">Sunday</div>
                                            <div class="hour-card__time">Closed</div>
                                        </div>
                                    </div>
                                    <div class="toggle">
                                        <div class="toggle__info">
                                            <div class="toggle__title">Mark as Closed Today</div>
                                            <div class="toggle__desc">Temporarily close for a holiday or maintenance</div>
                                        </div>
                                        <label class="toggle__switch">
                                            <input type="checkbox">
                                            <span class="toggle__track"></span>
                                        </label>
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">Travelers see real-time open/closed status</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Save Hours</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AMENITIES -->
                        <div id="sec-amenities" class="settings-section">
                            <div class="settings-section__title">Amenities</div>
                            <div class="settings-section__sub">Select what your gym offers — shown on your listing.</div>
                            <div class="panel">
                                <div class="panel__body">
                                    <div class="amenity-grid mb-16">
                                        <button class="amenity-chip is-selected" onclick="this.classList.toggle('is-selected')">🧊 AC</button>
                                        <button class="amenity-chip is-selected" onclick="this.classList.toggle('is-selected')">🔒 Lockers</button>
                                        <button class="amenity-chip is-selected" onclick="this.classList.toggle('is-selected')">🚿 Shower</button>
                                        <button class="amenity-chip is-selected" onclick="this.classList.toggle('is-selected')">🅿️ Parking</button>
                                        <button class="amenity-chip is-selected" onclick="this.classList.toggle('is-selected')">👨‍💼 Trainer</button>
                                        <button class="amenity-chip is-selected" onclick="this.classList.toggle('is-selected')">💪 Free Weights</button>
                                        <button class="amenity-chip" onclick="this.classList.toggle('is-selected')">🏊 Pool</button>
                                        <button class="amenity-chip" onclick="this.classList.toggle('is-selected')">🧘 Yoga Room</button>
                                        <button class="amenity-chip" onclick="this.classList.toggle('is-selected')">🥤 Protein Bar</button>
                                        <button class="amenity-chip" onclick="this.classList.toggle('is-selected')">📺 TV / Music</button>
                                        <button class="amenity-chip" onclick="this.classList.toggle('is-selected')">🌐 WiFi</button>
                                        <button class="amenity-chip" onclick="this.classList.toggle('is-selected')">🧺 Towel Service</button>
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">Helps travelers filter gyms by amenity</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Save Amenities</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PHOTOS & VIDEOS -->
                        <div id="sec-media" class="settings-section">
                            <div class="settings-section__title">Photos &amp; Videos</div>
                            <div class="settings-section__sub">Show travelers what your gym looks like — first impressions matter.</div>

                            <!-- Cover Photo -->
                            <div class="panel mb-20">
                                <div class="panel__header">
                                    <div class="panel__title">Cover Photo</div>
                                    <span class="panel__action">Shown at top of your listing</span>
                                </div>
                                <div class="panel__body">
                                    <!-- onclick now opens the picker instead of file input -->
                                    <div class="media-cover" id="coverDrop" onclick="openPicker()" ondragover="dragOver(event)" ondragleave="dragLeave(event)" ondrop="dropCoverFile(event)">
                                        <div class="media-cover__placeholder" id="coverPlaceholder">
                                            <div class="media-cover__icon">🖼️</div>
                                            <div class="media-cover__title">Click to upload or choose a photo</div>
                                            <div class="media-cover__hint">Upload from device · or pick from Unsplash</div>
                                        </div>
                                        <img class="media-cover__preview hidden" id="coverPreview" alt="Cover preview">
                                    </div>
                                    <div class="flex gap-8 mt-12">
                                        <button class="btn btn--primary btn--sm" onclick="savedFeedback(this)">Save Cover Photo</button>
                                        <button class="btn btn--ghost btn--sm" onclick="removeCover()">Remove</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Gym Photos -->
                            <div class="panel mb-20">
                                <div class="panel__header">
                                    <div class="panel__title">Gym Photos</div>
                                    <span class="panel__action" id="photoCount">3 / 10 photos</span>
                                </div>
                                <div class="panel__body">
                                    <div class="media-grid" id="photoGrid">
                                        <div class="media-thumb">
                                            <img src="https://placehold.co/200x150/111/333?text=Gym+Floor" alt="Gym floor" class="media-thumb__img">
                                            <div class="media-thumb__overlay">
                                                <button class="media-thumb__remove" onclick="removePhoto(this)" title="Remove">✕</button>
                                                <span class="media-thumb__label">Main floor</span>
                                            </div>
                                        </div>
                                        <div class="media-thumb">
                                            <img src="https://placehold.co/200x150/111/333?text=Weights+Area" alt="Weights area" class="media-thumb__img">
                                            <div class="media-thumb__overlay">
                                                <button class="media-thumb__remove" onclick="removePhoto(this)" title="Remove">✕</button>
                                                <span class="media-thumb__label">Weights area</span>
                                            </div>
                                        </div>
                                        <div class="media-thumb">
                                            <img src="https://placehold.co/200x150/111/333?text=Cardio+Zone" alt="Cardio zone" class="media-thumb__img">
                                            <div class="media-thumb__overlay">
                                                <button class="media-thumb__remove" onclick="removePhoto(this)" title="Remove">✕</button>
                                                <span class="media-thumb__label">Cardio zone</span>
                                            </div>
                                        </div>
                                        <div class="media-thumb media-thumb--add" onclick="document.getElementById('photoInput').click()">
                                            <div class="media-thumb__add-icon">+</div>
                                            <div class="media-thumb__add-label">Add Photo</div>
                                        </div>
                                    </div>
                                    <input type="file" id="photoInput" accept="image/*" multiple class="hidden" onchange="addPhotos(this)">
                                    <div class="callout mt-12">
                                        <span class="callout__icon">💡</span>
                                        Add photos of equipment, changing rooms, and parking. Gyms with 6+ photos get 3× more bookings.
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">Drag to reorder · Max 10 photos</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Save Photos</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Gym Tour Video -->
                            <div class="panel">
                                <div class="panel__header">
                                    <div class="panel__title">Gym Tour Video</div>
                                    <span class="panel__action">Optional but recommended</span>
                                </div>
                                <div class="panel__body">
                                    <div class="media-video-tabs mb-16">
                                        <button class="media-video-tab is-active" onclick="switchVideoTab('youtube', this)">YouTube / Reels Link</button>
                                        <button class="media-video-tab" onclick="switchVideoTab('upload', this)">Upload Video</button>
                                    </div>
                                    <div id="videoTab-youtube">
                                        <div class="field">
                                            <label class="field__label">YouTube or Instagram Reels URL</label>
                                            <div class="field__wrap">
                                                <span class="field__icon">🔗</span>
                                                <input class="field__input field__input--with-icon" type="url" id="videoUrl" placeholder="https://youtube.com/watch?v=..." oninput="previewVideo(this.value)">
                                            </div>
                                        </div>
                                        <div class="media-video-preview hidden" id="videoPreview">
                                            <div class="media-video-preview__thumb">
                                                <div class="media-video-preview__play">▶</div>
                                            </div>
                                            <div class="media-video-preview__meta">
                                                <div class="media-video-preview__title" id="videoTitle">Gym Tour Video</div>
                                                <div class="media-video-preview__url t-muted" id="videoUrlDisplay"></div>
                                            </div>
                                            <button class="btn btn--ghost btn--sm" onclick="clearVideo()">Remove</button>
                                        </div>
                                    </div>
                                    <div id="videoTab-upload" class="hidden">
                                        <div class="media-upload-zone" onclick="document.getElementById('videoInput').click()">
                                            <div class="media-upload-zone__icon">🎥</div>
                                            <div class="media-upload-zone__title">Click to upload a video</div>
                                            <div class="media-upload-zone__hint">MP4, MOV · Max 100MB · Under 2 minutes recommended</div>
                                        </div>
                                        <input type="file" id="videoInput" accept="video/*" class="hidden" onchange="handleVideoUpload(this)">
                                        <div class="media-upload-progress hidden" id="uploadProgress">
                                            <div class="flex justify-between mb-8">
                                                <span class="t-muted" id="uploadFileName"></span>
                                                <span class="t-muted" id="uploadPct">0%</span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-bar__fill progress-bar__fill--green" id="uploadBar" style="width:0%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">Shown on your gym listing page</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Save Video</button>
                                    </div>
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
                                    <label class="toggle__switch"><input type="checkbox" checked><span class="toggle__track"></span></label>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Payout Notification</div>
                                        <div class="toggle__desc">Get notified when your payout is transferred</div>
                                    </div>
                                    <label class="toggle__switch"><input type="checkbox" checked><span class="toggle__track"></span></label>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">New Review Alert</div>
                                        <div class="toggle__desc">Get notified when a member leaves a review</div>
                                    </div>
                                    <label class="toggle__switch"><input type="checkbox" checked><span class="toggle__track"></span></label>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Member Renewal Reminders</div>
                                        <div class="toggle__desc">7 days before a local member's plan expires</div>
                                    </div>
                                    <label class="toggle__switch"><input type="checkbox"><span class="toggle__track"></span></label>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Daily Summary Report</div>
                                        <div class="toggle__desc">SMS with daily bookings &amp; revenue summary at 10 PM</div>
                                    </div>
                                    <label class="toggle__switch"><input type="checkbox"><span class="toggle__track"></span></label>
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
                                        Payouts are processed every Monday at 10 AM for the previous week. Minimum payout threshold is ₹100.
                                    </div>
                                    <div class="save-bar">
                                        <span class="save-bar__note">UPI changes apply to next payout cycle</span>
                                        <button class="btn btn--primary" onclick="savedFeedback(this)">Update Payout Details</button>
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
                                        <div class="toggle__desc">Hide your gym from traveler searches temporarily. Existing bookings remain valid.</div>
                                    </div>
                                    <button class="btn btn--danger btn--sm" onclick="if(confirm('Pause your listing?')) alert('Listing paused.')">Pause Listing</button>
                                </div>
                                <div class="toggle">
                                    <div class="toggle__info">
                                        <div class="toggle__title">Delete Gym Account</div>
                                        <div class="toggle__desc">Permanently remove your gym and all data. This cannot be undone.</div>
                                    </div>
                                    <button class="btn btn--danger btn--sm" onclick="if(confirm('Are you absolutely sure?')) alert('Contact support@gympass.in to complete deletion.')">Delete Account</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- ══ PICKER MODAL ══ -->
    <div class="picker-overlay hidden" id="pickerOverlay" onclick="handleOverlayClick(event)">
        <div class="picker">
            <div class="picker__sidebar">
                <div class="picker__sidebar-title">Source</div>
                <div class="picker__tab is-active" onclick="switchPickerTab('device', this)">
                    <span class="picker__tab-icon">💻</span> My Device
                </div>
                <div class="picker__tab" onclick="switchPickerTab('unsplash', this)">
                    <span class="picker__tab-icon">🌄</span> Unsplash
                    <span class="badge badge--brand" style="margin-left:auto;font-size:9px">Free</span>
                </div>
            </div>
            <div class="picker__content">
                <div class="picker__header">
                    <div class="picker__header-title" id="pickerTitle">Upload from Device</div>
                    <button class="picker__close" onclick="closePicker()">✕</button>
                </div>

                <!-- My Device panel -->
                <div class="picker__panel is-active" id="ppanel-device">
                    <div class="picker__dropzone" id="pickerDropzone"
                        ondragover="pickerDragOver(event)" ondragleave="pickerDragLeave(event)" ondrop="pickerDrop(event)"
                        onclick="document.getElementById('pickerFileInput').click()">
                        <div class="picker__dropzone-icon">📁</div>
                        <div class="picker__dropzone-title">Select Files to Upload</div>
                        <div class="picker__dropzone-hint">or Drag and Drop, Copy and Paste Files</div>
                        <button class="btn btn--ghost btn--sm" onclick="event.stopPropagation(); document.getElementById('pickerFileInput').click()">Browse Files</button>
                    </div>
                    <input type="file" id="pickerFileInput" accept="image/*" class="hidden" onchange="handlePickerFile(this)">
                </div>

                <!-- Unsplash panel -->
                <div class="picker__panel" id="ppanel-unsplash">
                    <div class="picker__search">
                        <div class="search-bar">
                            <span class="search-bar__icon">🔍</span>
                            <input class="search-bar__input" type="text" placeholder="Search gym, fitness, workout…" id="unsplashSearch" onkeydown="if(event.key==='Enter') searchUnsplash()">
                            <button class="btn btn--primary btn--sm" onclick="searchUnsplash()" style="margin:4px 0">Search</button>
                        </div>
                    </div>
                    <div class="picker__grid-wrap">
                        <div class="picker__grid" id="unsplashGrid"></div>
                    </div>
                    <div class="picker__footer">
                        <div>
                            <div class="picker__footer-info" id="pickerFooterInfo">Search for gym photos above</div>
                            <div class="picker__attribution">Photos by <a href="https://unsplash.com" target="_blank">Unsplash</a></div>
                        </div>
                        <button class="btn btn--primary btn--sm hidden" id="usePhotoBtn" onclick="useSelectedPhoto()">Use Photo →</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ── Settings ──────────────────────────────────────────────────────────
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
            setTimeout(() => { btn.textContent = orig; btn.style.background = ''; }, 2000);
        }

        // ── Cover photo helpers ───────────────────────────────────────────────
        function setCoverPhoto(src) {
            document.getElementById('coverPreview').src = src;
            document.getElementById('coverPreview').classList.remove('hidden');
            document.getElementById('coverPlaceholder').classList.add('hidden');
        }

        function removeCover() {
            document.getElementById('coverPreview').classList.add('hidden');
            document.getElementById('coverPlaceholder').classList.remove('hidden');
            document.getElementById('coverPreview').src = '';
        }

        // Drag & drop directly onto the cover zone (without opening picker)
        function dragOver(e) { e.preventDefault(); document.getElementById('coverDrop').classList.add('is-dragover'); }
        function dragLeave()  { document.getElementById('coverDrop').classList.remove('is-dragover'); }
        function dropCoverFile(e) {
            e.preventDefault();
            document.getElementById('coverDrop').classList.remove('is-dragover');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = ev => { setCoverPhoto(ev.target.result); };
                reader.readAsDataURL(file);
            }
        }

        // ── Gym photos ────────────────────────────────────────────────────────
        let photoCount = 3;
        const maxPhotos = 10;

        function addPhotos(input) {
            const files = Array.from(input.files);
            const remaining = maxPhotos - photoCount;
            if (files.length > remaining) alert(`You can only add ${remaining} more photo(s).`);
            files.slice(0, remaining).forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = e => {
                    const grid = document.getElementById('photoGrid');
                    const addTile = grid.querySelector('.media-thumb--add');
                    const thumb = document.createElement('div');
                    thumb.className = 'media-thumb anim-fade-up';
                    thumb.innerHTML = `<img src="${e.target.result}" alt="Gym photo" class="media-thumb__img"><div class="media-thumb__overlay"><button class="media-thumb__remove" onclick="removePhoto(this)" title="Remove">✕</button></div>`;
                    grid.insertBefore(thumb, addTile);
                    photoCount++;
                    updatePhotoCount();
                    if (photoCount >= maxPhotos) addTile.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            });
            input.value = '';
        }

        function removePhoto(btn) {
            btn.closest('.media-thumb').remove();
            photoCount = Math.max(0, photoCount - 1);
            updatePhotoCount();
            document.querySelector('.media-thumb--add').classList.remove('hidden');
        }

        function updatePhotoCount() {
            document.getElementById('photoCount').textContent = `${photoCount} / ${maxPhotos} photos`;
        }

        // ── Video ─────────────────────────────────────────────────────────────
        function switchVideoTab(tab, btn) {
            document.querySelectorAll('.media-video-tab').forEach(t => t.classList.remove('is-active'));
            btn.classList.add('is-active');
            document.getElementById('videoTab-youtube').classList.toggle('hidden', tab !== 'youtube');
            document.getElementById('videoTab-upload').classList.toggle('hidden', tab !== 'upload');
        }

        function previewVideo(url) {
            const preview = document.getElementById('videoPreview');
            if (!url || (!url.includes('youtube') && !url.includes('youtu.be') && !url.includes('instagram'))) { preview.classList.add('hidden'); return; }
            document.getElementById('videoTitle').textContent = 'Gym Tour Video';
            document.getElementById('videoUrlDisplay').textContent = url.length > 50 ? url.slice(0, 50) + '...' : url;
            preview.classList.remove('hidden');
        }

        function clearVideo() { document.getElementById('videoUrl').value = ''; document.getElementById('videoPreview').classList.add('hidden'); }

        function handleVideoUpload(input) {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];
            if (file.size > 100 * 1024 * 1024) { alert('Video too large. Maximum size is 100MB.'); return; }
            const progress = document.getElementById('uploadProgress');
            progress.classList.remove('hidden');
            document.getElementById('uploadFileName').textContent = file.name;
            let pct = 0;
            const iv = setInterval(() => {
                pct += Math.random() * 12;
                if (pct >= 100) { pct = 100; clearInterval(iv); }
                document.getElementById('uploadBar').style.width = Math.round(pct) + '%';
                document.getElementById('uploadPct').textContent = Math.round(pct) + '%';
            }, 200);
        }

        // ── Picker modal ──────────────────────────────────────────────────────
        // Replace 'YOUR_UNSPLASH_ACCESS_KEY' with your key from unsplash.com/developers
        const UNSPLASH_KEY = 'YOUR_UNSPLASH_ACCESS_KEY';
        let selectedUnsplashPhoto = null;

        function openPicker() {
            document.getElementById('pickerOverlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            if (document.getElementById('unsplashGrid').children.length === 0) {
                loadDefaultPhotos();
            }
        }

        function closePicker() {
            document.getElementById('pickerOverlay').classList.add('hidden');
            document.body.style.overflow = '';
            selectedUnsplashPhoto = null;
            document.querySelectorAll('.picker__img-tile').forEach(t => t.classList.remove('is-selected'));
            document.getElementById('usePhotoBtn').classList.add('hidden');
        }

        function handleOverlayClick(e) {
            if (e.target === document.getElementById('pickerOverlay')) closePicker();
        }

        function switchPickerTab(tab, el) {
            document.querySelectorAll('.picker__tab').forEach(t => t.classList.remove('is-active'));
            document.querySelectorAll('.picker__panel').forEach(p => p.classList.remove('is-active'));
            el.classList.add('is-active');
            document.getElementById('ppanel-' + tab).classList.add('is-active');
            document.getElementById('pickerTitle').textContent = tab === 'device' ? 'Upload from Device' : 'Pick from Unsplash';
        }

        // My Device tab
        function handlePickerFile(input) {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];
            if (file.size > 5 * 1024 * 1024) { alert('File too large. Maximum size is 5MB.'); return; }
            const reader = new FileReader();
            reader.onload = e => { setCoverPhoto(e.target.result); closePicker(); };
            reader.readAsDataURL(file);
        }

        function pickerDragOver(e) { e.preventDefault(); document.getElementById('pickerDropzone').classList.add('is-dragover'); }
        function pickerDragLeave() { document.getElementById('pickerDropzone').classList.remove('is-dragover'); }
        function pickerDrop(e) {
            e.preventDefault();
            document.getElementById('pickerDropzone').classList.remove('is-dragover');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = ev => { setCoverPhoto(ev.target.result); closePicker(); };
                reader.readAsDataURL(file);
            }
        }

        // Unsplash tab
        function loadDefaultPhotos() { fetchUnsplash('gym fitness'); }

        function searchUnsplash() {
            const q = document.getElementById('unsplashSearch').value.trim() || 'gym fitness';
            fetchUnsplash(q);
        }

        async function fetchUnsplash(query) {
            const grid = document.getElementById('unsplashGrid');
            const info = document.getElementById('pickerFooterInfo');
            grid.innerHTML = Array(9).fill('<div class="picker__skeleton"></div>').join('');
            info.textContent = 'Searching…';

            try {
                // when Unsplash key is ready: uncomment below and delete the demo block
                // const res = await fetch(
                //   `https://api.unsplash.com/search/photos?query=${encodeURIComponent(query)}&per_page=12&orientation=landscape`,
                //   { headers: { Authorization: `Client-ID ${UNSPLASH_KEY}` } }
                // );
                // const data = await res.json();
                // renderPhotos(data.results, data.total);

                // ── DEMO MODE (no key needed) ──
                await new Promise(r => setTimeout(r, 600));
                const demos = Array.from({length: 9}, (_, i) => ({
                    id: `d${i}`,
                    urls: {
                        small: `https://source.unsplash.com/400x300/?${encodeURIComponent(query)}&sig=${i}`,
                        full:  `https://source.unsplash.com/1280x720/?${encodeURIComponent(query)}&sig=${i}`,
                    },
                    user: { name: 'Unsplash' },
                    alt_description: query,
                }));
                renderPhotos(demos, demos.length);
                // ── END DEMO MODE ──

            } catch(err) {
                grid.innerHTML = `<div class="picker__empty"><div class="picker__empty-icon">😕</div>Could not load photos. Check your connection.</div>`;
            }
        }

        function renderPhotos(photos, total) {
            const grid = document.getElementById('unsplashGrid');
            document.getElementById('pickerFooterInfo').textContent = `${total} photos found`;
            if (!photos.length) {
                grid.innerHTML = `<div class="picker__empty"><div class="picker__empty-icon">🔍</div>No photos found. Try a different search.</div>`;
                return;
            }
            grid.innerHTML = photos.map(p => `
                <div class="picker__img-tile" data-url="${p.urls.full}" data-credit="${p.user.name}" onclick="selectPhoto(this)">
                    <img src="${p.urls.small}" alt="${p.alt_description || ''}" loading="lazy">
                    <div class="picker__img-tile__credit">📷 ${p.user.name}</div>
                </div>`).join('');
        }

        function selectPhoto(el) {
            document.querySelectorAll('.picker__img-tile').forEach(t => t.classList.remove('is-selected'));
            el.classList.add('is-selected');
            selectedUnsplashPhoto = { url: el.dataset.url, credit: el.dataset.credit };
            document.getElementById('usePhotoBtn').classList.remove('hidden');
        }

        function useSelectedPhoto() {
            if (!selectedUnsplashPhoto) return;
            setCoverPhoto(selectedUnsplashPhoto.url);
            closePicker();
        }   
    </script>
</body>

</html>