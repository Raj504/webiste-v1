<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymPass India – Day Pass for Travelers</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --orange: #FF5C1A;
            --orange-light: #FF7A3D;
            --dark: #0D0D0D;
            --dark2: #181818;
            --dark3: #222;
            --text: #F0EDE8;
            --muted: #888;
            --border: #2A2A2A;
            --card: #161616;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--dark);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            overflow-x: hidden;
        }

        /* ── NAV ── */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 48px;
            background: rgba(13, 13, 13, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 92, 26, 0.1);
            animation: slideDown 0.6s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .logo {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .logo span {
            color: var(--orange);
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .nav-links a {
            font-size: 14px;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--text);
        }

        .nav-cta {
            background: var(--orange);
            color: #fff;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s, transform 0.2s;
        }

        .nav-cta:hover {
            background: var(--orange-light);
            transform: translateY(-1px);
        }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 48px 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 60% 50% at 70% 50%, rgba(255, 92, 26, 0.12) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 20% 80%, rgba(255, 92, 26, 0.06) 0%, transparent 60%);
        }

        /* Animated grid lines */
        .hero-grid {
            position: absolute;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(255, 92, 26, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 92, 26, 0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridSlide 20s linear infinite;
        }

        @keyframes gridSlide {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(60px);
            }
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 680px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 92, 26, 0.1);
            border: 1px solid rgba(255, 92, 26, 0.3);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            color: var(--orange);
            font-family: 'Syne', sans-serif;
            letter-spacing: 1px;
            margin-bottom: 32px;
            animation: fadeUp 0.8s ease 0.2s both;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--orange);
            animation: pulse 2s ease infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.5);
            }
        }

        .hero h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(42px, 6vw, 80px);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -2px;
            margin-bottom: 24px;
            animation: fadeUp 0.8s ease 0.3s both;
        }

        .hero h1 .highlight {
            color: var(--orange);
        }

        .hero h1 .line2 {
            display: block;
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.3);
            color: transparent;
        }

        .hero-desc {
            font-size: 18px;
            color: var(--muted);
            line-height: 1.7;
            max-width: 480px;
            margin-bottom: 40px;
            animation: fadeUp 0.8s ease 0.4s both;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            animation: fadeUp 0.8s ease 0.5s both;
        }

        .btn-primary {
            background: var(--orange);
            color: #fff;
            padding: 16px 36px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.25s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: var(--orange-light);
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(255, 92, 26, 0.35);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text);
            padding: 16px 36px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid var(--border);
            transition: all 0.25s;
        }

        .btn-secondary:hover {
            border-color: var(--orange);
            color: var(--orange);
        }

        /* Floating stats */
        .hero-stats {
            position: absolute;
            right: 48px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 16px;
            z-index: 1;
            animation: fadeLeft 0.8s ease 0.6s both;
        }

        @keyframes fadeLeft {
            from {
                opacity: 0;
                transform: translate(40px, -50%);
            }

            to {
                opacity: 1;
                transform: translate(0, -50%);
            }
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px 28px;
            text-align: center;
            min-width: 160px;
            transition: border-color 0.2s, transform 0.2s;
        }

        .stat-card:hover {
            border-color: var(--orange);
            transform: translateX(-4px);
        }

        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: var(--orange);
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--muted);
        }

        /* Cities ticker */
        .cities-ticker {
            position: absolute;
            bottom: 40px;
            left: 48px;
            right: 48px;
            display: flex;
            align-items: center;
            gap: 16px;
            animation: fadeUp 0.8s ease 0.8s both;
        }

        .ticker-label {
            font-size: 11px;
            color: var(--muted);
            white-space: nowrap;
            letter-spacing: 1px;
        }

        .ticker-track {
            flex: 1;
            overflow: hidden;
            -webkit-mask: linear-gradient(90deg, transparent, black 10%, black 90%, transparent);
        }

        .ticker-inner {
            display: flex;
            gap: 32px;
            animation: ticker 20s linear infinite;
            white-space: nowrap;
        }

        @keyframes ticker {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        .city-pill {
            background: var(--dark3);
            border: 1px solid var(--border);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            color: var(--muted);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .city-pill span {
            color: var(--orange);
            font-size: 10px;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── HOW IT WORKS ── */
        .section {
            padding: 100px 48px;
            position: relative;
        }

        .section-tag {
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 16px;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(32px, 4vw, 52px);
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 16px;
        }

        .section-sub {
            font-size: 16px;
            color: var(--muted);
            max-width: 480px;
            line-height: 1.7;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2px;
            margin-top: 60px;
        }

        .step {
            background: var(--card);
            border: 1px solid var(--border);
            padding: 40px 32px;
            position: relative;
            transition: border-color 0.3s, transform 0.3s;
            cursor: default;
        }

        .step:hover {
            border-color: var(--orange);
            transform: translateY(-4px);
        }

        .step-num {
            font-family: 'Syne', sans-serif;
            font-size: 72px;
            font-weight: 800;
            color: rgba(255, 92, 26, 0.08);
            line-height: 1;
            margin-bottom: 20px;
            transition: color 0.3s;
        }

        .step:hover .step-num {
            color: rgba(255, 92, 26, 0.15);
        }

        .step-icon {
            font-size: 32px;
            margin-bottom: 16px;
            display: block;
        }

        .step-title {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .step-desc {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.7;
        }

        /* ── PRICING ── */
        .pricing-section {
            padding: 100px 48px;
            background: var(--dark2);
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2px;
            margin-top: 60px;
        }

        .price-card {
            background: var(--card);
            border: 1px solid var(--border);
            padding: 36px 28px;
            position: relative;
            transition: all 0.3s;
            cursor: default;
        }

        .price-card:hover {
            border-color: var(--orange);
            transform: translateY(-6px);
        }

        .price-card.featured {
            border-color: var(--orange);
            background: linear-gradient(135deg, #1a0a04, var(--card));
        }

        .featured-tag {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--orange);
            color: #fff;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .price-plan {
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 20px;
        }

        .price-amount {
            font-family: 'Syne', sans-serif;
            font-size: 48px;
            font-weight: 800;
            color: #fff;
            line-height: 1;
            margin-bottom: 4px;
        }

        .price-amount sup {
            font-size: 20px;
            vertical-align: top;
            margin-top: 10px;
        }

        .price-period {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 24px;
        }

        .price-formula {
            background: rgba(255, 92, 26, 0.08);
            border: 1px solid rgba(255, 92, 26, 0.2);
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 12px;
            color: var(--orange);
            margin-bottom: 24px;
            font-family: monospace;
        }

        .price-features {
            list-style: none;
        }

        .price-features li {
            font-size: 13px;
            color: var(--muted);
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .price-features li:last-child {
            border-bottom: none;
        }

        .check {
            color: var(--orange);
            font-size: 14px;
        }

        /* ── CITIES ── */
        .cities-section {
            padding: 100px 48px;
        }

        .cities-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2px;
            margin-top: 60px;
        }

        .city-card {
            background: var(--card);
            border: 1px solid var(--border);
            padding: 32px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s;
            cursor: default;
        }

        .city-card:hover {
            border-color: var(--orange);
        }

        .city-emoji {
            font-size: 36px;
        }

        .city-name {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .city-count {
            font-size: 13px;
            color: var(--muted);
        }

        .city-status {
            margin-left: auto;
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 4px;
        }

        .status-live {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .status-soon {
            background: rgba(255, 92, 26, 0.1);
            color: var(--orange);
        }

        /* ── FOOTER ── */
        footer {
            padding: 60px 48px 40px;
            border-top: 1px solid var(--border);
        }

        .footer-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 24px;
        }

        .footer-logo {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
        }

        .footer-logo span {
            color: var(--orange);
        }

        .footer-links {
            display: flex;
            gap: 24px;
        }

        .footer-links a {
            font-size: 13px;
            color: var(--muted);
            text-decoration: none;
        }

        .footer-links a:hover {
            color: var(--text);
        }

        .footer-copy {
            font-size: 12px;
            color: var(--muted);
            width: 100%;
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }

        /* ── SCROLL REVEAL ── */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            nav {
                padding: 16px 24px;
            }

            .nav-links {
                display: none;
            }

            .hero {
                padding: 100px 24px 80px;
            }

            .hero-stats {
                display: none;
            }

            .section,
            .pricing-section,
            .cities-section {
                padding: 60px 24px;
            }

            .steps,
            .pricing-grid,
            .cities-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- NAV -->
    <nav>
        <div @class(['logo'])>
            <a href="{{ route('index') }}" style="text-decoration: none; color: inherit;">
                Gym<span>Pass</span>.in
            </a>
        </div>
        <div @class(['nav-links'])>
            <a href="search.html">Find a Gym</a>
            <a href="#">For Gym Owners</a>
            <a href="#">Cities</a>
            <a href="{{ route('signup') }}" @class(['nav-cta'])>Login / Signup</a>
        </div>
    </nav>