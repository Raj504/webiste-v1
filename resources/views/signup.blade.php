<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up – GymPass India</title>
<link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>
<body class="auth-page">

<header class="auth-page__topnav">
  <a href="index.html" class="topnav-logo">GymPass<span>.</span>in</a>
  <div style="font-size:13px;color:var(--text-secondary)">Already registered? <a href="login.html" style="color:var(--brand)">Log in</a></div>
</header>

<div class="auth-page__body">
  <div class="auth-page__card">

    <div class="role-toggle">
      <button class="role-toggle__btn is-active" id="btnTraveler" onclick="setRole('traveler')">🧳 I'm a Traveler</button>
      <button class="role-toggle__btn" id="btnOwner" onclick="setRole('owner')">🏋️ I Own a Gym</button>
    </div>

    <div class="step-progress mb-24">
      <div class="step-progress__fill" id="progressFill" style="width:33%"></div>
    </div>

    <!-- ══ TRAVELER FLOW ══ -->
    <div id="travelerFlow">

      <!-- T Step 1 -->
      <div class="screen is-active" id="tS1">
        <div class="t-label mb-8">Step 1 of 3 · Your Info</div>
        <div class="auth-form__title mb-8">Create your account</div>
        <div class="auth-form__sub">Travelers book gyms across India in under a minute.</div>

        <div class="field--row">
          <div class="field">
            <label class="field__label">First Name</label>
            <div class="field__wrap"><span class="field__icon">👤</span><input class="field__input field__input--with-icon" id="tFname" type="text" placeholder="Raj"></div>
          </div>
          <div class="field">
            <label class="field__label">Last Name</label>
            <div class="field__wrap"><span class="field__icon">👤</span><input class="field__input field__input--with-icon" id="tLname" type="text" placeholder="Sharma"></div>
          </div>
        </div>

        <div class="field">
          <label class="field__label">Mobile Number</label>
          <div class="field__phone">
            <div class="field__phone-prefix">🇮🇳 +91</div>
            <input class="field__input" id="tPhone" type="tel" placeholder="98765 43210" maxlength="10" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')">
          </div>
        </div>

        <div class="field">
          <label class="field__label">Home City</label>
          <div class="field__wrap"><span class="field__icon">🏠</span><input class="field__input field__input--with-icon" id="tCity" type="text" placeholder="Mumbai, Delhi, Bangalore…"></div>
        </div>

        <button class="btn btn--primary btn--full btn--lg mb-12" onclick="tNext1()">Continue <span>→</span></button>
        <div class="text-center" style="font-size:13px;color:var(--text-secondary)">Already have an account? <a href="login.html" style="color:var(--brand)">Log in</a></div>
      </div>

      <!-- T Step 2 — OTP -->
      <div class="screen" id="tS2">
        <div class="t-label mb-8">Step 2 of 3 · Verify Phone</div>
        <div class="auth-form__title mb-8">Verify your number</div>
        <div class="auth-form__sub">We sent a 4-digit OTP to your mobile.</div>

        <div class="text-center mb-16" style="font-size:13px;color:var(--text-secondary);line-height:1.7">
          OTP sent to <strong id="tPhoneDisplay" style="color:var(--text-primary)"></strong><br>
          <button class="btn btn--ghost btn--sm mt-8" id="tResend" onclick="tDoResend()" disabled style="font-size:12px;margin-top:8px">
            Resend OTP <span id="tTimer" style="color:var(--text-muted)">(30s)</span>
          </button>
        </div>

        <div class="otp-group mb-20">
          <input class="otp-group__input" type="text" maxlength="1" inputmode="numeric" oninput="otpIn(this,0,'t')" onkeydown="otpKey(event,0,'t')">
          <input class="otp-group__input" type="text" maxlength="1" inputmode="numeric" oninput="otpIn(this,1,'t')" onkeydown="otpKey(event,1,'t')">
          <input class="otp-group__input" type="text" maxlength="1" inputmode="numeric" oninput="otpIn(this,2,'t')" onkeydown="otpKey(event,2,'t')">
          <input class="otp-group__input" type="text" maxlength="1" inputmode="numeric" oninput="otpIn(this,3,'t')" onkeydown="otpKey(event,3,'t')">
        </div>

        <button class="btn btn--primary btn--full btn--lg mb-12" id="tVerifyBtn" onclick="tNext2()">
          <span class="btn__label">Verify OTP</span><div class="btn__spinner"></div>
        </button>
        <button class="btn btn--ghost btn--full" onclick="tGoTo('tS1',33)">← Back</button>
      </div>

      <!-- T Step 3 — Done -->
      <div class="screen" id="tS3">
        <div class="auth-success">
          <span class="auth-success__icon anim-bounce-in">🎉</span>
          <div class="auth-success__title">You're all set!</div>
          <div class="auth-success__sub">Account created. Find gyms near you and never skip a workout while traveling.</div>
          <button class="btn btn--primary btn--lg" onclick="window.location.href='search.html'">🏋️ Find Gyms Near Me →</button>
        </div>
      </div>
    </div>

    <!-- ══ OWNER FLOW ══ -->
    <div id="ownerFlow" style="display:none">

      <!-- O Step 1 -->
      <div class="screen is-active" id="oS1">
        <div class="t-label mb-8">Step 1 of 4 · Owner Details</div>
        <div class="auth-form__title mb-8">Register your gym</div>
        <div class="auth-form__sub">Start accepting traveler bookings and go fully digital.</div>

        <div class="callout mb-16">
          <span class="callout__icon">ℹ️</span>
          Free to list. We take a small commission only when you get a booking — no upfront cost.
        </div>

        <div class="field--row">
          <div class="field">
            <label class="field__label">Your Name</label>
            <div class="field__wrap"><span class="field__icon">👤</span><input class="field__input field__input--with-icon" id="oName" type="text" placeholder="Vikram Singh"></div>
          </div>
          <div class="field">
            <label class="field__label">Mobile Number</label>
            <div class="field__phone">
              <div class="field__phone-prefix">🇮🇳 +91</div>
              <input class="field__input" id="oPhone" type="tel" placeholder="98765 43210" maxlength="10" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'')">
            </div>
          </div>
        </div>

        <button class="btn btn--primary btn--full btn--lg mb-12" onclick="oNext1()">Continue <span>→</span></button>
        <div class="text-center" style="font-size:13px;color:var(--text-secondary)">Already registered? <a href="login.html" style="color:var(--brand)">Log in as owner</a></div>
      </div>

      <!-- O Step 2 — Gym Details -->
      <div class="screen" id="oS2">
        <div class="t-label mb-8">Step 2 of 4 · Gym Details</div>
        <div class="auth-form__title mb-8">Tell us about your gym</div>
        <div class="auth-form__sub">This is what travelers will see when searching.</div>

        <div class="field">
          <label class="field__label">Gym Name</label>
          <div class="field__wrap"><span class="field__icon">🏋️</span><input class="field__input field__input--with-icon" id="oGymName" type="text" placeholder="Iron Temple Gym"></div>
        </div>

        <div class="field--row">
          <div class="field">
            <label class="field__label">City</label>
            <div class="field__wrap">
              <span class="field__icon">📍</span>
              <select class="field__input field__input--with-icon" id="oCity">
                <option value="">Select city</option>
                <option>Rishikesh</option><option>Goa</option><option>Manali</option>
                <option>Varanasi</option><option>Jaipur</option><option>Darjeeling</option><option>Other</option>
              </select>
            </div>
          </div>
          <div class="field">
            <label class="field__label">Area / Locality</label>
            <div class="field__wrap"><span class="field__icon">🗺️</span><input class="field__input field__input--with-icon" id="oArea" type="text" placeholder="Laxman Jhula"></div>
          </div>
        </div>

        <div class="field">
          <label class="field__label">Monthly Membership Rate (₹)</label>
          <div class="field__wrap"><span class="field__icon">💰</span><input class="field__input field__input--with-icon" id="oRate" type="number" placeholder="e.g. 1000" min="200" oninput="updatePricePreview()"></div>
        </div>

        <div id="pricePreview" class="pricing-preview mb-16 hidden">
          <div class="t-label" style="padding:10px 16px 6px;color:var(--brand)">Auto-calculated plan pricing</div>
          <div class="pricing-preview__row"><span>Per Day <span class="t-muted" style="font-size:11px">· 10%</span></span><span class="pricing-preview__price" id="ppDay">₹0</span></div>
          <div class="pricing-preview__row"><span>3 Days <span class="t-muted" style="font-size:11px">· 25%</span></span><span class="pricing-preview__price" id="pp3d">₹0</span></div>
          <div class="pricing-preview__row"><span>7 Days <span class="t-muted" style="font-size:11px">· 50%</span></span><span class="pricing-preview__price" id="pp7d">₹0</span></div>
          <div class="pricing-preview__row"><span style="color:var(--brand);font-weight:600">Monthly</span><span class="pricing-preview__price" id="ppMo">₹0</span></div>
        </div>

        <button class="btn btn--primary btn--full btn--lg mb-12" onclick="oNext2()">Continue <span>→</span></button>
        <button class="btn btn--ghost btn--full" onclick="oGoTo('oS1',25)">← Back</button>
      </div>

      <!-- O Step 3 — OTP -->
      <div class="screen" id="oS3">
        <div class="t-label mb-8">Step 3 of 4 · Verify Phone</div>
        <div class="auth-form__title mb-8">Verify your number</div>
        <div class="auth-form__sub">We'll send booking alerts and payout updates to this number.</div>

        <div class="text-center mb-16" style="font-size:13px;color:var(--text-secondary);line-height:1.7">
          OTP sent to <strong id="oPhoneDisplay" style="color:var(--text-primary)"></strong><br>
          <button class="btn btn--ghost btn--sm" id="oResend" onclick="oDoResend()" disabled style="font-size:12px;margin-top:8px">
            Resend <span id="oTimer" style="color:var(--text-muted)">(30s)</span>
          </button>
        </div>

        <div class="otp-group mb-20">
          <input class="otp-group__input" type="text" maxlength="1" inputmode="numeric" oninput="otpIn(this,0,'o')" onkeydown="otpKey(event,0,'o')">
          <input class="otp-group__input" type="text" maxlength="1" inputmode="numeric" oninput="otpIn(this,1,'o')" onkeydown="otpKey(event,1,'o')">
          <input class="otp-group__input" type="text" maxlength="1" inputmode="numeric" oninput="otpIn(this,2,'o')" onkeydown="otpKey(event,2,'o')">
          <input class="otp-group__input" type="text" maxlength="1" inputmode="numeric" oninput="otpIn(this,3,'o')" onkeydown="otpKey(event,3,'o')">
        </div>

        <button class="btn btn--primary btn--full btn--lg mb-12" id="oVerifyBtn" onclick="oNext3()">
          <span class="btn__label">Verify OTP</span><div class="btn__spinner"></div>
        </button>
        <button class="btn btn--ghost btn--full" onclick="oGoTo('oS2',50)">← Back</button>
      </div>

      <!-- O Step 4 — Payout -->
      <div class="screen" id="oS4">
        <div class="t-label mb-8">Step 4 of 4 · Payout Setup</div>
        <div class="auth-form__title mb-8">Setup payouts</div>
        <div class="auth-form__sub">T+1 auto-transfer to your UPI every day.</div>

        <div class="field">
          <label class="field__label">UPI ID</label>
          <div class="field__wrap"><span class="field__icon">💳</span><input class="field__input field__input--with-icon" id="oUpi" type="text" placeholder="yourname@upi or 9876543210@paytm"></div>
        </div>

        <div class="flex flex-col gap-8 mb-16">
          <div class="flex gap-8 items-start" style="font-size:13px;color:var(--text-secondary)"><span>⚡</span><span><strong style="color:var(--text-primary)">T+1 payouts</strong> — yesterday's earnings hit your UPI by 10AM</span></div>
          <div class="flex gap-8 items-start" style="font-size:13px;color:var(--text-secondary)"><span>📊</span><span><strong style="color:var(--text-primary)">Free dashboard</strong> — track bookings, attendance &amp; revenue</span></div>
          <div class="flex gap-8 items-start" style="font-size:13px;color:var(--text-secondary)"><span>🔒</span><span><strong style="color:var(--text-primary)">Secure</strong> — powered by Razorpay</span></div>
        </div>

        <div class="checkbox-row mb-16">
          <input type="checkbox" id="termsCheck">
          <div class="checkbox-row__text">
            I agree to the <a href="#">Terms of Service</a> and <a href="#">Partner Agreement</a>. I confirm I own/operate this gym.
          </div>
        </div>

        <button class="btn btn--primary btn--full btn--lg mb-12" id="oFinalBtn" onclick="oNext4()">
          <span class="btn__label">Complete Registration 🚀</span><div class="btn__spinner"></div>
        </button>
        <button class="btn btn--ghost btn--full" onclick="oGoTo('oS3',75)">← Back</button>
      </div>

      <!-- O Step 5 — Done -->
      <div class="screen" id="oS5">
        <div class="auth-success">
          <span class="auth-success__icon anim-bounce-in">🏋️</span>
          <div class="auth-success__title">Gym registered!</div>
          <div class="auth-success__sub">You're live on GymPass India. We'll verify your listing within 24 hours.</div>
          <button class="btn btn--primary btn--lg" onclick="window.location.href='dashboard.html'">Go to Dashboard →</button>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
let role='traveler', tTimerIv, oTimerIv;
const prog=document.getElementById('progressFill');

function setRole(r){
  role=r;
  document.getElementById('btnTraveler').classList.toggle('is-active',r==='traveler');
  document.getElementById('btnOwner').classList.toggle('is-active',r==='owner');
  document.getElementById('travelerFlow').style.display=r==='traveler'?'block':'none';
  document.getElementById('ownerFlow').style.display=r==='owner'?'block':'none';
  prog.style.width=r==='traveler'?'33%':'25%';
}

function tGoTo(id,pct){document.querySelectorAll('#travelerFlow .screen').forEach(s=>s.classList.remove('is-active'));document.getElementById(id).classList.add('is-active');prog.style.width=pct+'%';}
function oGoTo(id,pct){document.querySelectorAll('#ownerFlow .screen').forEach(s=>s.classList.remove('is-active'));document.getElementById(id).classList.add('is-active');prog.style.width=pct+'%';}

function tNext1(){
  const name=document.getElementById('tFname').value.trim(),phone=document.getElementById('tPhone').value.trim();
  if(!name||phone.length<10)return;
  document.getElementById('tPhoneDisplay').textContent='+91 '+phone;
  tGoTo('tS2',66); startTimer('tTimer','tResend',tTimerIv,'t');
  setTimeout(()=>document.querySelectorAll('#tS2 .otp-group__input')[0].focus(),100);
}
function tNext2(){
  const otp=Array.from(document.querySelectorAll('#tS2 .otp-group__input')).map(i=>i.value).join('');
  if(otp.length<4)return;
  const btn=document.getElementById('tVerifyBtn');btn.classList.add('is-loading');
  setTimeout(()=>{btn.classList.remove('is-loading');prog.className='step-progress__fill step-progress__fill--done';prog.style.width='100%';tGoTo('tS3',100);},1400);
}
function tDoResend(){document.querySelectorAll('#tS2 .otp-group__input').forEach(i=>{i.value='';i.classList.remove('is-filled');});startTimer('tTimer','tResend',tTimerIv,'t');}

function oNext1(){const n=document.getElementById('oName').value.trim(),p=document.getElementById('oPhone').value.trim();if(!n||p.length<10)return;oGoTo('oS2',50);}
function oNext2(){const n=document.getElementById('oGymName').value.trim(),c=document.getElementById('oCity').value,r=document.getElementById('oRate').value;if(!n||!c||!r)return;const p=document.getElementById('oPhone').value.trim();document.getElementById('oPhoneDisplay').textContent='+91 '+p;oGoTo('oS3',75);startTimer('oTimer','oResend',oTimerIv,'o');setTimeout(()=>document.querySelectorAll('#oS3 .otp-group__input')[0].focus(),100);}
function oNext3(){const otp=Array.from(document.querySelectorAll('#oS3 .otp-group__input')).map(i=>i.value).join('');if(otp.length<4)return;const btn=document.getElementById('oVerifyBtn');btn.classList.add('is-loading');setTimeout(()=>{btn.classList.remove('is-loading');oGoTo('oS4',90);},1400);}
function oNext4(){const upi=document.getElementById('oUpi').value.trim(),terms=document.getElementById('termsCheck').checked;if(!upi||!terms)return;const btn=document.getElementById('oFinalBtn');btn.classList.add('is-loading');setTimeout(()=>{btn.classList.remove('is-loading');prog.className='step-progress__fill step-progress__fill--done';prog.style.width='100%';oGoTo('oS5',100);},1800);}
function oDoResend(){document.querySelectorAll('#oS3 .otp-group__input').forEach(i=>{i.value='';i.classList.remove('is-filled');});startTimer('oTimer','oResend',oTimerIv,'o');}

function otpIn(el,idx,p){el.value=el.value.replace(/\D/g,'');if(el.value){el.classList.add('is-filled');const sc=p==='t'?'tS2':'oS3';const a=document.querySelectorAll(`#${sc} .otp-group__input`);if(idx<3)a[idx+1].focus();}else el.classList.remove('is-filled');}
function otpKey(e,idx,p){if(e.key==='Backspace'){const sc=p==='t'?'tS2':'oS3';const a=document.querySelectorAll(`#${sc} .otp-group__input`);if(!a[idx].value&&idx>0){a[idx-1].value='';a[idx-1].classList.remove('is-filled');a[idx-1].focus();}}}

function startTimer(tid,bid){let s=30;const el=document.getElementById(tid),btn=document.getElementById(bid);btn.disabled=true;const iv=setInterval(()=>{s--;el.textContent=`(${s}s)`;if(s<=0){clearInterval(iv);el.textContent='';btn.disabled=false;}},1000);}

function updatePricePreview(){const r=parseInt(document.getElementById('oRate').value)||0;const p=document.getElementById('pricePreview');if(!r||r<100){p.classList.add('hidden');return;}p.classList.remove('hidden');document.getElementById('ppDay').textContent='₹'+Math.round(r*.1);document.getElementById('pp3d').textContent='₹'+Math.round(r*.25);document.getElementById('pp7d').textContent='₹'+Math.round(r*.5);document.getElementById('ppMo').textContent='₹'+r;}
</script>
</body>
</html>