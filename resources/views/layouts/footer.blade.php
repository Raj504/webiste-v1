    <!-- FOOTER -->
    <footer>
        <div @class(['footer-inner'])>
            <div @class(['footer-logo'])>
                <a href="{{ route('index') }}" style="text-decoration: none; color: inherit;">
                    Gym<span>Pass</span>.in
                </a>
            </div>
            <div @class(['footer-links'])>
                <a href="#">For Travelers</a>
                <a href="#">For Gym Owners</a>
                <a href="#">Privacy</a>
                <a href="#">Contact</a>
            </div>
        </div>
        <div @class(['footer-copy'])>© 2025 GymPass India. Built for travelers who never skip leg day.</div>
    </footer>

    <script>
        // Counter animation
        function animateCount(el) {
            const target = +el.dataset.count;
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    el.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    el.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        }

        // Scroll reveal
        const revealEls = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((e, i) => {
                if (e.isIntersecting) {
                    setTimeout(() => e.target.classList.add('visible'), i * 80);
                    observer.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.1
        });
        revealEls.forEach(el => observer.observe(el));

        // Stat counter trigger
        const statNums = document.querySelectorAll('.stat-num[data-count]');
        const statsObs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    animateCount(e.target);
                    statsObs.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.5
        });
        statNums.forEach(el => statsObs.observe(el));
    </script>
</body>

</html>