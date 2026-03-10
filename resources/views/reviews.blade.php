<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews – GymPass Owner</title>
    <link rel="stylesheet" href="{{ asset('css/shared.css') }}">
</head>

<body>
    <div class="dash-layout">
        @include('partials.gym-sidebar')

        <div class="dash-main">
            <header class="dash-topbar">
                <div class="dash-topbar__left">
                    <div class="dash-topbar__title">Reviews</div>
                    <div class="dash-topbar__sub">Member feedback for Iron Temple Gym</div>
                </div>
                <div class="dash-topbar__right">
                    <span class="pill pill--yellow">⭐ 4.8 avg rating</span>
                </div>
            </header>

            <main class="dash-content">

                <!-- RATING HERO -->
                <div class="rating-hero mb-24 anim-fade-up">

                    <!-- Big score -->
                    <div class="text-center">
                        <div class="rating-hero__big">4.8</div>
                        <div class="rating-hero__stars">⭐⭐⭐⭐⭐</div>
                        <div class="rating-hero__sub">42 reviews</div>
                    </div>

                    <!-- Star breakdown bars -->
                    <div>
                        <div class="rating-bar__row">
                            <div class="rating-bar__label">5★</div>
                            <div class="rating-bar__track">
                                <div class="rating-bar__fill" style="width:75%"></div>
                            </div>
                            <div class="rating-bar__count">31</div>
                        </div>
                        <div class="rating-bar__row">
                            <div class="rating-bar__label">4★</div>
                            <div class="rating-bar__track">
                                <div class="rating-bar__fill" style="width:18%"></div>
                            </div>
                            <div class="rating-bar__count">8</div>
                        </div>
                        <div class="rating-bar__row">
                            <div class="rating-bar__label">3★</div>
                            <div class="rating-bar__track">
                                <div class="rating-bar__fill" style="width:5%"></div>
                            </div>
                            <div class="rating-bar__count">2</div>
                        </div>
                        <div class="rating-bar__row">
                            <div class="rating-bar__label">2★</div>
                            <div class="rating-bar__track">
                                <div class="rating-bar__fill" style="width:2%"></div>
                            </div>
                            <div class="rating-bar__count">1</div>
                        </div>
                        <div class="rating-bar__row">
                            <div class="rating-bar__label">1★</div>
                            <div class="rating-bar__track">
                                <div class="rating-bar__fill" style="width:0%"></div>
                            </div>
                            <div class="rating-bar__count">0</div>
                        </div>
                    </div>

                    <!-- Quick stats -->
                    <div class="flex flex-col gap-12">
                        <div class="commission-tile commission-tile--green">
                            <div class="commission-tile__label">Would recommend</div>
                            <div class="commission-tile__value">91%</div>
                        </div>
                        <div class="commission-tile">
                            <div class="commission-tile__label">Platform average</div>
                            <div class="commission-tile__value">4.3 ★</div>
                        </div>
                    </div>

                </div>

                <!-- FILTERS -->
                <div class="flex gap-8 flex-wrap items-center mb-20 anim-fade-up">
                    <button class="chip is-active" onclick="filterReviews(this,'all')">All Reviews</button>
                    <button class="chip" onclick="filterReviews(this,'new')">New (Unread)</button>
                    <button class="chip" onclick="filterReviews(this,'5')">5 Stars</button>
                    <button class="chip" onclick="filterReviews(this,'4')">4 Stars</button>
                    <button class="chip" onclick="filterReviews(this,'low')">3 Stars &amp; Below</button>
                    <button class="chip" onclick="filterReviews(this,'no-reply')">No Reply</button>
                </div>

                <!-- REVIEW CARDS -->
                <div id="reviewList" class="anim-fade-up">

                    <!-- Review 1 — NEW, no reply -->
                    <div class="review-card review-card--new" data-stars="5" data-new="1" data-replied="0">
                        <div class="review-card__header">
                            <div class="flex gap-12 items-center">
                                <div class="review-card__avatar">👨</div>
                                <div>
                                    <div class="review-card__name">Arjun Sharma <span
                                            class="badge badge--new">NEW</span></div>
                                    <div class="review-card__meta">Traveled from Bangalore · Per Day Pass</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="review-card__stars">★★★★★</div>
                                <div class="review-card__date">Today</div>
                            </div>
                        </div>
                        <div class="review-card__body">
                            "Best gym in Rishikesh for travelers. The QR check-in was so smooth — was inside within 30
                            seconds of arriving. Equipment is top-notch and AC was working great. Will definitely use
                            GymPass again on my next trip!"
                        </div>
                        <div class="review-card__footer">
                            <div class="review-card__booking-ref">Verified booking · GP-2025-4923</div>
                            <div class="flex gap-8">
                                <button class="btn btn--ghost btn--sm" onclick="toggleReply(this)">💬 Reply</button>
                                <button class="btn btn--ghost btn--sm">🚩 Report</button>
                            </div>
                        </div>
                        <div class="review-reply__form">
                            <textarea class="field__input" placeholder="Write a public reply…" rows="3"></textarea>
                            <div class="flex gap-8 mt-8">
                                <button class="btn btn--primary btn--sm" onclick="submitReply(this)">Post Reply</button>
                                <button class="btn btn--ghost btn--sm"
                                    onclick="this.closest('.review-reply__form').style.display='none'">Cancel</button>
                            </div>
                        </div>
                    </div>

                    <!-- Review 2 — NEW, no reply -->
                    <div class="review-card review-card--new" data-stars="5" data-new="1" data-replied="0">
                        <div class="review-card__header">
                            <div class="flex gap-12 items-center">
                                <div class="review-card__avatar">👩</div>
                                <div>
                                    <div class="review-card__name">Priya Mehta <span
                                            class="badge badge--new">NEW</span></div>
                                    <div class="review-card__meta">Traveled from Delhi · 3 Days Pass</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="review-card__stars">★★★★★</div>
                                <div class="review-card__date">Yesterday</div>
                            </div>
                        </div>
                        <div class="review-card__body">
                            "Really impressed with the cleanliness and variety of equipment. The locker room is
                            well-maintained. Would recommend to all travelers in Rishikesh!"
                        </div>
                        <div class="review-card__footer">
                            <div class="review-card__booking-ref">Verified booking · GP-2025-4802</div>
                            <div class="flex gap-8">
                                <button class="btn btn--ghost btn--sm" onclick="toggleReply(this)">💬 Reply</button>
                            </div>
                        </div>
                        <div class="review-reply__form">
                            <textarea class="field__input" placeholder="Write a public reply…" rows="3"></textarea>
                            <div class="flex gap-8 mt-8">
                                <button class="btn btn--primary btn--sm" onclick="submitReply(this)">Post
                                    Reply</button>
                                <button class="btn btn--ghost btn--sm"
                                    onclick="this.closest('.review-reply__form').style.display='none'">Cancel</button>
                            </div>
                        </div>
                    </div>

                    <!-- Review 3 — replied -->
                    <div class="review-card" data-stars="5" data-replied="1">
                        <div class="review-card__header">
                            <div class="flex gap-12 items-center">
                                <div class="review-card__avatar">👦</div>
                                <div>
                                    <div class="review-card__name">Rohit Kumar</div>
                                    <div class="review-card__meta">Traveled from Mumbai · 7 Days Pass</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="review-card__stars">★★★★★</div>
                                <div class="review-card__date">Mar 6</div>
                            </div>
                        </div>
                        <div class="review-card__body">
                            "Used the 7-day pass during a yoga retreat week. Iron Temple is perfect for serious training
                            alongside yoga. Great cardio equipment and dedicated stretching area. Staff is friendly and
                            welcoming."
                        </div>
                        <div class="review-reply">
                            <div class="review-reply__label">🏋️ Your Reply</div>
                            <div class="review-reply__text">Thank you so much Rohit! We're glad you enjoyed your stay.
                                The stretching area was recently upgraded — great to hear it's being used. Hope to see
                                you again! 🙏</div>
                        </div>
                    </div>

                    <!-- Review 4 — 4 stars, no reply -->
                    <div class="review-card" data-stars="4" data-replied="0">
                        <div class="review-card__header">
                            <div class="flex gap-12 items-center">
                                <div class="review-card__avatar">👩‍💼</div>
                                <div>
                                    <div class="review-card__name">Sneha Patel</div>
                                    <div class="review-card__meta">Traveled from Pune · Per Day Pass</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="review-card__stars">★★★★☆</div>
                                <div class="review-card__date">Mar 5</div>
                            </div>
                        </div>
                        <div class="review-card__body">
                            "Good gym overall. Equipment is well-maintained. Gets crowded during evening peak hours (6–8
                            PM) but morning sessions are perfect. QR check-in worked flawlessly."
                        </div>
                        <div class="review-card__footer">
                            <div class="review-card__booking-ref">Verified booking · GP-2025-4689</div>
                            <div class="flex gap-8">
                                <button class="btn btn--ghost btn--sm" onclick="toggleReply(this)">💬 Reply</button>
                            </div>
                        </div>
                        <div class="review-reply__form">
                            <textarea class="field__input" placeholder="Write a public reply…" rows="3"></textarea>
                            <div class="flex gap-8 mt-8">
                                <button class="btn btn--primary btn--sm" onclick="submitReply(this)">Post
                                    Reply</button>
                                <button class="btn btn--ghost btn--sm"
                                    onclick="this.closest('.review-reply__form').style.display='none'">Cancel</button>
                            </div>
                        </div>
                    </div>

                    <!-- Review 5 — 3 stars, needs attention -->
                    <div class="review-card" data-stars="3" data-replied="0">
                        <div class="review-card__header">
                            <div class="flex gap-12 items-center">
                                <div class="review-card__avatar">🧔</div>
                                <div>
                                    <div class="review-card__name">Vikram T.</div>
                                    <div class="review-card__meta">Traveled from Jaipur · 3 Days Pass</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="review-card__stars">★★★☆☆</div>
                                <div class="review-card__date">Feb 28</div>
                            </div>
                        </div>
                        <div class="review-card__body">
                            "Decent gym. The AC wasn't working properly on day 2. Staff said they'd fix it but it was
                            still warm on day 3. Equipment is good though — free weights section is well-stocked."
                        </div>
                        <div class="review-card__footer">
                            <div class="review-card__booking-ref">Verified booking · GP-2025-4512</div>
                            <div class="flex gap-8">
                                <button class="btn btn--ghost btn--sm" onclick="toggleReply(this)">💬 Reply</button>
                            </div>
                        </div>
                        <div class="review-reply__form">
                            <textarea class="field__input" placeholder="Acknowledge the issue and assure a fix…" rows="3"></textarea>
                            <div class="flex gap-8 mt-8">
                                <button class="btn btn--primary btn--sm" onclick="submitReply(this)">Post
                                    Reply</button>
                                <button class="btn btn--ghost btn--sm"
                                    onclick="this.closest('.review-reply__form').style.display='none'">Cancel</button>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleReply(btn) {
            const card = btn.closest('.review-card');
            const form = card.querySelector('.review-reply__form');
            const isOpen = form.style.display === 'block';
            form.style.display = isOpen ? 'none' : 'block';
            if (!isOpen) form.querySelector('textarea').focus();
        }

        function submitReply(btn) {
            const form = btn.closest('.review-reply__form');
            const text = form.querySelector('textarea').value.trim();
            if (!text) return;

            const card = form.closest('.review-card');
            let reply = card.querySelector('.review-reply');
            if (!reply) {
                reply = document.createElement('div');
                reply.className = 'review-reply';
                card.insertBefore(reply, form);
            }
            reply.innerHTML = `
    <div class="review-reply__label">🏋️ Your Reply</div>
    <div class="review-reply__text">${text}</div>`;
            form.style.display = 'none';
            card.dataset.replied = '1';
        }

        function filterReviews(btn, filter) {
            document.querySelectorAll('.chip').forEach(c => c.classList.remove('is-active'));
            btn.classList.add('is-active');

            document.querySelectorAll('.review-card').forEach(card => {
                const stars = parseInt(card.dataset.stars || 5);
                const isNew = card.dataset.new === '1';
                const replied = card.dataset.replied === '1';

                const show =
                    filter === 'all' ? true :
                    filter === 'new' ? isNew :
                    filter === '5' ? stars === 5 :
                    filter === '4' ? stars === 4 :
                    filter === 'low' ? stars <= 3 :
                    filter === 'no-reply' ? !replied : true;

                card.style.display = show ? 'block' : 'none';
            });
        }
    </script>
</body>

</html>
