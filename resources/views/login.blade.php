<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – GymPass India</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>

<body>
    <div class="auth-layout">

        <!-- LEFT BRAND PANEL -->
        <div class="auth-brand">
            <div class="auth-brand__inner">
                <a href="index.html" class="auth-brand__logo anim-fade-up">
                    GymPass<span>.</span>in
                    <span class="auth-brand__logo-badge">India</span>
                </a>

                <div style="margin:auto 0;padding:40px 0">
                    <div class="auth-brand__kicker anim-slide-right">For Travelers &amp; Gym Owners</div>
                    <h1 class="auth-brand__headline anim-fade-up stagger-1">
                        Train anywhere
                        <span class="auth-brand__headline--ghost">in India.</span>
                        <span class="auth-brand__headline--accent">No excuses.</span>
                    </h1>
                    <p class="auth-brand__sub anim-fade-up stagger-2">
                        Day passes, 3-day, 7-day or monthly — book a gym in seconds wherever your travels take you.
                    </p>
                    <div class="flex flex-col gap-12">
                        <div class="feature-card anim-slide-right stagger-2">
                            <div class="feature-card__icon">📍</div>
                            <div>
                                <div class="feature-card__title">Find gyms near you instantly</div>
                                <div class="feature-card__desc">Rishikesh, Goa, Manali &amp; more cities</div>
                            </div>
                        </div>
                        <div class="feature-card anim-slide-right stagger-3">
                            <div class="feature-card__icon">⚡</div>
                            <div>
                                <div class="feature-card__title">Book &amp; pay in under 60 seconds</div>
                                <div class="feature-card__desc">UPI, GPay, PhonePe — no wallet needed</div>
                            </div>
                        </div>
                        <div class="feature-card anim-slide-right stagger-4">
                            <div class="feature-card__icon">📲</div>
                            <div>
                                <div class="feature-card__title">QR pass on your phone</div>
                                <div class="feature-card__desc">Works offline · Show at reception &amp; go</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="auth-brand__proof anim-fade-up">
                    <div class="auth-brand__avatars">
                        <div class="auth-brand__avatar">👨</div>
                        <div class="auth-brand__avatar">👩</div>
                        <div class="auth-brand__avatar">🧑</div>
                        <div class="auth-brand__avatar">👦</div>
                        <div class="auth-brand__avatar">👩‍🦱</div>
                    </div>
                    <div class="auth-brand__proof-text"><strong>1,200+ travelers</strong> have trained across India
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT FORM PANEL -->
        <div class="auth-form">
            <div class="auth-form__inner anim-fade-up">

                <div class="role-toggle">
                    <button class="role-toggle__btn is-active" id="btnTraveler" onclick="setRole('traveler')">🧳 I'm a
                        Traveler</button>
                    <button class="role-toggle__btn" id="btnOwner" onclick="setRole('owner')">🏋️ I'm a Gym
                        Owner</button>
                </div>

                <div class="step-dots">
                    <div class="step-dots__dot is-active" id="dot1"></div>
                    <div class="step-dots__dot" id="dot2"></div>
                </div>

                <!-- Screen: Phone -->
                <div class="screen is-active" id="scPhone">
                    <div class="mb-20">
                        <div class="auth-form__title" id="formTitle">Welcome back</div>
                        <div class="auth-form__sub" id="formSub">New here? <a href="signup.html">Create an account
                                →</a></div>
                    </div>

                    <div class="field">
                        <label class="field__label">Mobile Number</label>
                        <div class="field__phone">
                            <div class="field__phone-prefix">🇮🇳 +91</div>
                            <input class="field__input" type="tel" id="phoneInput" placeholder="98765 43210"
                                maxlength="10" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')">
                        </div>
                        <div class="field__error" id="phoneError">Please enter a valid 10-digit number</div>
                    </div>

                    <button class="btn btn--primary btn--full btn--lg mb-12" id="sendOtpBtn" onclick="sendOTP()">
                        <span class="btn__label">Send OTP via SMS</span>
                        <div class="btn__spinner"></div>
                        <span>→</span>
                    </button>

                    <div class="flex items-center gap-12 mb-16" style="color:var(--text-muted);font-size:12px">
                        <div style="flex:1;height:1px;background:var(--border-1)"></div>or<div
                            style="flex:1;height:1px;background:var(--border-1)"></div>
                    </div>
                    <div class="text-center" style="font-size:13px;color:var(--text-secondary)">
                        Don't have an account? <a href="signup.html" style="color:var(--brand)">Sign up free</a>
                    </div>
                </div>

                <!-- Screen: OTP -->
                <div class="screen" id="scOtp">
                    <div class="mb-16">
                        <div class="auth-form__title">Enter OTP</div>
                        <div class="auth-form__sub" id="otpPhoneDisplay"></div>
                    </div>
                    <div class="text-center mb-16" style="font-size:13px;color:var(--text-secondary);line-height:1.7">
                        OTP sent to <span id="otpPhone" style="color:var(--text-primary);font-weight:600"></span><br>
                        <button class="btn btn--ghost btn--sm mt-8" id="resendBtn" onclick="resendOTP()" disabled
                            style="font-size:12px;margin-top:8px">
                            Resend OTP <span id="otpTimer" style="color:var(--text-muted)">(30s)</span>
                        </button>
                    </div>
                    <div class="otp-group mb-20">
                        <input class="otp-group__input" type="text" maxlength="1" inputmode="numeric"
                            oninput="otpIn(this,0)" onkeydown="otpKey(event,0)">
                        <input class="otp-group__input" type="text" maxlength="1" inputmode="numeric"
                            oninput="otpIn(this,1)" onkeydown="otpKey(event,1)">
                        <input class="otp-group__input" type="text" maxlength="1" inputmode="numeric"
                            oninput="otpIn(this,2)" onkeydown="otpKey(event,2)">
                        <input class="otp-group__input" type="text" maxlength="1" inputmode="numeric"
                            oninput="otpIn(this,3)" onkeydown="otpKey(event,3)">
                    </div>
                    <div class="field__error text-center mb-12" id="otpError" style="display:none">Incorrect OTP.
                        Please try again.</div>
                    <button class="btn btn--primary btn--full btn--lg mb-12" id="verifyBtn" onclick="verifyOTP()">
                        <span class="btn__label">Verify &amp; Login</span>
                        <div class="btn__spinner"></div>
                    </button>
                    <div class="text-center"><a href="#" onclick="goBack()"
                            style="font-size:13px;color:var(--text-secondary)">← Change number</a></div>
                </div>

                <!-- Screen: Success -->
                <div class="screen" id="scSuccess">
                    <div class="auth-success">
                        <span class="auth-success__icon anim-bounce-in">✅</span>
                        <div class="auth-success__title" id="successTitle">You're in!</div>
                        <div class="auth-success__sub" id="successSub">Welcome back. Redirecting…</div>
                        <button class="btn btn--primary btn--lg" onclick="redirectUser()">Continue →</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        let role = 'traveler',
            timerIv;

        function setRole(r) {
            role = r;
            document.getElementById('btnTraveler').classList.toggle('is-active', r === 'traveler');
            document.getElementById('btnOwner').classList.toggle('is-active', r === 'owner');
            document.getElementById('formTitle').textContent = r === 'traveler' ? 'Welcome back' : 'Owner Login';
            document.getElementById('formSub').innerHTML = r === 'traveler' ?
                'New here? <a href="signup.html" style="color:var(--brand)">Create an account →</a>' :
                'New gym? <a href="signup.html" style="color:var(--brand)">Register your gym →</a>';
        }

        function showScreen(id) {
            document.querySelectorAll('.screen').forEach(s => s.classList.remove('is-active'));
            document.getElementById(id).classList.add('is-active');
        }

        function setDots(s) {
            const d1 = document.getElementById('dot1'),
                d2 = document.getElementById('dot2');
            d1.className = 'step-dots__dot';
            d2.className = 'step-dots__dot';
            if (s === 1) d1.classList.add('is-active');
            else {
                d1.classList.add('is-done');
                d2.classList.add('is-active');
            }
        }

        function sendOTP() {
            const v = document.getElementById('phoneInput').value.trim(),
                err = document.getElementById('phoneError'),
                btn = document.getElementById('sendOtpBtn');
            if (v.length !== 10) {
                err.classList.add('is-visible');
                return;
            }
            err.classList.remove('is-visible');
            btn.classList.add('is-loading');
            setTimeout(() => {
                btn.classList.remove('is-loading');
                document.getElementById('otpPhone').textContent = '+91 ' + v;
                document.getElementById('otpPhoneDisplay').textContent = '+91 ' + v;
                showScreen('scOtp');
                setDots(2);
                startTimer();
                document.querySelectorAll('.otp-group__input')[0].focus();
            }, 1200);
        }

        function startTimer() {
            let s = 30;
            clearInterval(timerIv);
            const el = document.getElementById('otpTimer'),
                btn = document.getElementById('resendBtn');
            btn.disabled = true;
            timerIv = setInterval(() => {
                s--;
                el.textContent = `(${s}s)`;
                if (s <= 0) {
                    clearInterval(timerIv);
                    el.textContent = '';
                    btn.disabled = false;
                }
            }, 1000);
        }

        function resendOTP() {
            document.querySelectorAll('.otp-group__input').forEach(i => {
                i.value = '';
                i.classList.remove('is-filled');
            });
            startTimer();
            document.querySelectorAll('.otp-group__input')[0].focus();
        }

        function otpIn(el, idx) {
            el.value = el.value.replace(/\D/g, '');
            if (el.value) {
                el.classList.add('is-filled');
                const a = document.querySelectorAll('.otp-group__input');
                if (idx < 3) a[idx + 1].focus();
            } else el.classList.remove('is-filled');
        }

        function otpKey(e, idx) {
            if (e.key === 'Backspace') {
                const a = document.querySelectorAll('.otp-group__input');
                if (!a[idx].value && idx > 0) {
                    a[idx - 1].value = '';
                    a[idx - 1].classList.remove('is-filled');
                    a[idx - 1].focus();
                }
            }
        }

        function verifyOTP() {
            const otp = Array.from(document.querySelectorAll('.otp-group__input')).map(i => i.value).join('');
            if (otp.length < 4) return;
            const btn = document.getElementById('verifyBtn'),
                err = document.getElementById('otpError');
            btn.classList.add('is-loading');
            err.style.display = 'none';
            setTimeout(() => {
                btn.classList.remove('is-loading');
                if (otp === '0000') {
                    err.style.display = 'block';
                    document.querySelectorAll('.otp-group__input').forEach(i => {
                        i.value = '';
                        i.classList.remove('is-filled');
                    });
                    document.querySelectorAll('.otp-group__input')[0].focus();
                } else {
                    document.getElementById('successTitle').textContent = role === 'owner' ? 'Welcome back!' :
                        'You\'re in!';
                    document.getElementById('successSub').textContent = role === 'owner' ?
                        'Taking you to your gym dashboard…' : 'Welcome back. Find gyms near you!';
                    showScreen('scSuccess');
                }
            }, 1500);
        }

        function goBack() {
            clearInterval(timerIv);
            showScreen('scPhone');
            setDots(1);
        }

        function redirectUser() {
            window.location.href = role === 'owner' ? 'dashboard.html' : 'search.html';
        }
        document.getElementById('phoneInput').addEventListener('keydown', e => {
            if (e.key === 'Enter') sendOTP();
        });
    </script>
</body>

</html>
