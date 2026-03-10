<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Scanner – GymPass Owner</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>

<body>
    <div class="dash-layout">

        @include('partials.gym-sidebar')


        <div class="dash-main">
            <header class="dash-topbar">
                <div class="dash-topbar__left">
                    <div class="dash-topbar__title">QR Scanner</div>
                    <div class="dash-topbar__sub">Scan member QR codes for check-in</div>
                </div>
                <div class="dash-topbar__right">
                    <span class="t-mono t-muted" id="liveClock"></span>
                    <div class="pill pill--green">📅 8 check-ins today</div>
                </div>
            </header>

            <main class="dash-content">
                <div class="grid-2 anim-fade-up">

                    <!-- LEFT: SCANNER -->
                    <div class="flex flex-col gap-20">

                        <div class="qr-scanner">
                            <!-- Camera zone -->
                            <div class="qr-scanner__zone">
                                <div class="qr-frame" id="qrFrame" onclick="startScan()">
                                    <div class="qr-frame__scan-line" id="scanLine"></div>
                                    <span class="qr-frame__icon" id="qrIcon">📷</span>
                                </div>
                                <div class="t-heading mb-8" id="scanTitle">Ready to Scan</div>
                                <div class="t-muted mb-20" id="scanSub">Point camera at a member's QR code, or tap to
                                    begin</div>
                                <div class="flex gap-8 justify-center flex-wrap">
                                    <button class="btn btn--primary btn--lg" id="scanBtn" onclick="startScan()">📷
                                        Start Scanning</button>
                                    <button class="btn btn--ghost hidden" id="cancelBtn"
                                        onclick="cancelScan()">Cancel</button>
                                </div>
                            </div>

                            <!-- Result area (shown after scan) -->
                            <div id="resultArea" class="hidden panel__body">
                                <div class="qr-result" id="resultCard">
                                    <span class="qr-result__icon" id="resultIcon"></span>
                                    <div class="qr-result__title" id="resultTitle"></div>
                                    <div class="qr-result__info" id="resultInfo"></div>
                                    <div class="qr-result__details" id="resultDetails"></div>
                                </div>
                                <div class="flex gap-8 mt-12 flex-wrap">
                                    <button class="btn btn--primary" onclick="startScan()">📷 Scan Next</button>
                                    <button class="btn btn--ghost" onclick="resetScanner()">Reset</button>
                                </div>
                            </div>
                        </div>

                        <!-- Manual check-in -->
                        <div class="panel">
                            <div class="panel__header">
                                <div class="panel__title">Manual Booking ID</div>
                            </div>
                            <div class="panel__body">
                                <div class="t-muted mb-12">If QR won't scan, enter the booking ID manually.</div>
                                <div class="flex gap-8">
                                    <input class="field__input flex-1" type="text" placeholder="e.g. GP-2025-4821"
                                        id="manualId" maxlength="20">
                                    <button class="btn btn--primary" onclick="manualCheck()">Check In</button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT: SCAN LOG -->
                    <div class="panel">
                        <div class="panel__header">
                            <div class="panel__title">Today's Check-ins</div>
                            <span class="panel__action" id="logCount">8 total</span>
                        </div>
                        <div id="scanLog">
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--ok"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Arjun Sharma</div>
                                    <div class="scan-log__meta">Per Day · Bangalore</div>
                                </div>
                                <div>
                                    <div class="scan-log__result scan-log__result--ok">✓ Granted</div>
                                    <div class="scan-log__time">9:14 AM</div>
                                </div>
                            </div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--ok"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Priya Mehta</div>
                                    <div class="scan-log__meta">3 Days · Delhi</div>
                                </div>
                                <div>
                                    <div class="scan-log__result scan-log__result--ok">✓ Granted</div>
                                    <div class="scan-log__time">10:02 AM</div>
                                </div>
                            </div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--warn"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Unknown QR</div>
                                    <div class="scan-log__meta">Wrong gym</div>
                                </div>
                                <div>
                                    <div class="scan-log__result scan-log__result--warn">⚠ Wrong Gym</div>
                                    <div class="scan-log__time">11:58 AM</div>
                                </div>
                            </div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--fail"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Vikram T.</div>
                                    <div class="scan-log__meta">Expired pass</div>
                                </div>
                                <div>
                                    <div class="scan-log__result scan-log__result--fail">✗ Denied</div>
                                    <div class="scan-log__time">1:10 PM</div>
                                </div>
                            </div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--ok"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Aditya Rao</div>
                                    <div class="scan-log__meta">Monthly · Hyderabad</div>
                                </div>
                                <div>
                                    <div class="scan-log__result scan-log__result--ok">✓ Granted</div>
                                    <div class="scan-log__time">2:00 PM</div>
                                </div>
                            </div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--ok"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Meera Singh</div>
                                    <div class="scan-log__meta">7 Days · Lucknow</div>
                                </div>
                                <div>
                                    <div class="scan-log__result scan-log__result--ok">✓ Granted</div>
                                    <div class="scan-log__time">2:22 PM</div>
                                </div>
                            </div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--ok"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Shreya Kapoor</div>
                                    <div class="scan-log__meta">7 Days · Bangalore</div>
                                </div>
                                <div>
                                    <div class="scan-log__result scan-log__result--ok">✓ Granted</div>
                                    <div class="scan-log__time">3:45 PM</div>
                                </div>
                            </div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--ok"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Rohit Kumar</div>
                                    <div class="scan-log__meta">3 Days · Mumbai</div>
                                </div>
                                <div>
                                    <div class="scan-log__result scan-log__result--ok">✓ Granted</div>
                                    <div class="scan-log__time">4:01 PM</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script>
        // Rotating demo scenarios
        const scenarios = [{
                type: 'success',
                icon: '✅',
                title: 'Access Granted!',
                info: 'Valid pass · Entry permitted',
                user: 'Rahul Gupta',
                plan: 'Per Day',
                city: 'Jaipur',
                valid: 'Today'
            },
            {
                type: 'success',
                icon: '✅',
                title: 'Access Granted!',
                info: 'Active 7-day pass · Day 3 of 7',
                user: 'Shreya Kapoor',
                plan: '7 Days',
                city: 'Bangalore',
                valid: 'Mar 15'
            },
            {
                type: 'warning',
                icon: '⚠️',
                title: 'Wrong Gym',
                info: 'This QR is valid but for a different gym. You may honor or deny entry.',
                user: 'Dev Malhotra',
                plan: '3 Days',
                city: 'Chennai',
                valid: 'Mar 11'
            },
            {
                type: 'error',
                icon: '❌',
                title: 'Access Denied',
                info: 'Pass has expired. Ask the user to purchase a new plan.',
                user: 'Ananya Sharma',
                plan: 'Per Day',
                city: 'Mumbai',
                valid: 'Expired Mar 7'
            },
        ];

        let scanIndex = 0;
        let scanning = false;

        function startScan() {
            if (scanning) return;
            scanning = true;

            const frame = document.getElementById('qrFrame');
            frame.className = 'qr-frame is-scanning';
            document.getElementById('qrIcon').textContent = '🔍';
            document.getElementById('scanTitle').textContent = 'Scanning…';
            document.getElementById('scanBtn').classList.add('hidden');
            document.getElementById('cancelBtn').classList.remove('hidden');
            document.getElementById('resultArea').classList.add('hidden');
            document.getElementById('resultCard').className = 'qr-result';

            setTimeout(() => {
                scanning = false;
                showResult(scenarios[scanIndex % scenarios.length]);
                scanIndex++;
            }, 2200);
        }

        function showResult(s) {
            const frame = document.getElementById('qrFrame');
            frame.className = 'qr-frame ' +
                (s.type === 'success' ? 'is-success' : s.type === 'error' ? 'is-error' : '');

            document.getElementById('qrIcon').textContent = s.icon;
            document.getElementById('scanTitle').textContent = s.title;
            document.getElementById('cancelBtn').classList.add('hidden');

            const card = document.getElementById('resultCard');
            const mod = s.type === 'error' ? 'error' : s.type === 'warning' ? 'warning' : 'success';
            card.className = `qr-result is-visible qr-result--${mod}`;
            document.getElementById('resultIcon').textContent = s.icon;
            document.getElementById('resultTitle').textContent = s.title;
            document.getElementById('resultInfo').textContent = s.info;

            document.getElementById('resultDetails').innerHTML = `
    <div class="qr-result__detail-item">
      <div class="qr-result__detail-label">Member</div>
      <div class="qr-result__detail-value">${s.user}</div>
    </div>
    <div class="qr-result__detail-item">
      <div class="qr-result__detail-label">Plan</div>
      <div class="qr-result__detail-value">${s.plan}</div>
    </div>
    <div class="qr-result__detail-item">
      <div class="qr-result__detail-label">From</div>
      <div class="qr-result__detail-value">${s.city}</div>
    </div>
    <div class="qr-result__detail-item">
      <div class="qr-result__detail-label">Valid Until</div>
      <div class="qr-result__detail-value">${s.valid}</div>
    </div>`;

            document.getElementById('resultArea').classList.remove('hidden');

            const dotClass = s.type === 'success' ? 'ok' : s.type === 'error' ? 'fail' : 'warn';
            const resultLabel = s.type === 'success' ? '✓ Granted' : s.type === 'error' ? '✗ Denied' : '⚠ Warning';
            addLogEntry(s.user, s.plan, dotClass, resultLabel);
        }

        function addLogEntry(name, plan, cls, result) {
            const now = new Date().toLocaleTimeString('en-IN', {
                hour: '2-digit',
                minute: '2-digit'
            });
            const el = document.createElement('div');
            el.className = 'scan-log__item anim-fade-up';
            el.innerHTML = `
    <div class="scan-log__dot scan-log__dot--${cls}"></div>
    <div class="scan-log__body">
      <div class="scan-log__name">${name}</div>
      <div class="scan-log__meta">${plan}</div>
    </div>
    <div>
      <div class="scan-log__result scan-log__result--${cls}">${result}</div>
      <div class="scan-log__time">${now}</div>
    </div>`;
            const log = document.getElementById('scanLog');
            log.insertBefore(el, log.firstChild);
        }

        function cancelScan() {
            scanning = false;
            resetScanner();
        }

        function resetScanner() {
            document.getElementById('qrFrame').className = 'qr-frame';
            document.getElementById('qrIcon').textContent = '📷';
            document.getElementById('scanTitle').textContent = 'Ready to Scan';
            document.getElementById('scanBtn').classList.remove('hidden');
            document.getElementById('cancelBtn').classList.add('hidden');
            document.getElementById('resultArea').classList.add('hidden');
        }

        function manualCheck() {
            const id = document.getElementById('manualId').value.trim();
            if (!id) return;
            alert(`Checking: ${id}\n\nIn production: validates against DB and grants/denies entry.`);
        }

        function tick() {
            document.getElementById('liveClock').textContent =
                new Date().toLocaleTimeString('en-IN');
        }
        tick();
        setInterval(tick, 1000);
    </script>
</body>

</html>
