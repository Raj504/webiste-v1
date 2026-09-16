<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in QR – GymPass Owner</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>

<body>
    <div class="dash-layout">

        @include('partials.gym-sidebar')

        <div class="dash-main">
            <header class="dash-topbar">
                <div class="dash-topbar__left">
                    <div class="dash-topbar__title">Check-in QR</div>
                    <div class="dash-topbar__sub">Download, print, and stick this on your wall — travelers scan it
                        themselves to check in</div>
                </div>
            </header>

            <main class="dash-content">
                <div class="grid-2 anim-fade-up">

                    <!-- LEFT: QR CODE -->
                    <div class="panel">
                        <div class="panel__header">
                            <div class="panel__title">Your Gym's QR Code</div>
                        </div>
                        <div class="panel__body" style="text-align:center;">
                            <div style="width:280px;height:280px;background:#fff;padding:12px;border-radius:8px;margin:0 auto;display:flex;align-items:center;justify-content:center;">
                                <span style="font-size:120px;">▦</span>
                            </div>

                            <div class="flex gap-8 mt-12 justify-center flex-wrap">
                                <button class="btn btn--primary">⬇️ Download</button>
                                <button class="btn btn--ghost">🔄 Regenerate</button>
                            </div>

                            <div class="t-muted mt-12" style="font-size:12px;">
                                Regenerating invalidates any poster you've already printed.
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: RECENT CHECK-INS -->
                    <div class="panel">
                        <div class="panel__header">
                            <div class="panel__title">Recent Check-ins</div>
                            <span class="panel__action">6 total</span>
                        </div>
                        <div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--ok"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Arjun Sharma</div>
                                </div>
                                <div class="scan-log__time">9:14 AM</div>
                            </div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--ok"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Priya Mehta</div>
                                </div>
                                <div class="scan-log__time">10:02 AM</div>
                            </div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--ok"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Aditya Rao</div>
                                </div>
                                <div class="scan-log__time">2:00 PM</div>
                            </div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--ok"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Meera Singh</div>
                                </div>
                                <div class="scan-log__time">2:22 PM</div>
                            </div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--ok"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Shreya Kapoor</div>
                                </div>
                                <div class="scan-log__time">3:45 PM</div>
                            </div>
                            <div class="scan-log__item">
                                <div class="scan-log__dot scan-log__dot--ok"></div>
                                <div class="scan-log__body">
                                    <div class="scan-log__name">Rohit Kumar</div>
                                </div>
                                <div class="scan-log__time">4:01 PM</div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</body>

</html>
