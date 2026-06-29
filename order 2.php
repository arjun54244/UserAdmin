<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <?php
    session_start();
    include 'include/head.php'; ?>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght=500;700&family=Plus+Jakarta+Sans:wght=300;400;600&family=Syne:wght=700;800&display=swap');

        html,
        body {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #050507;
            margin: 0;
            padding: 0;
        }

        .heading-font {
            font-family: 'Syne', sans-serif;
        }

        .brand-font {
            font-family: 'Cinzel', serif;
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.01);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* 💎 REAL 3D MODEL DEPTH ENGINE */
        .image-viewport {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            perspective: 1500px;
            /* Strong perspective depth */
        }

        .img-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            /* Ultra-smooth cubic bezier curve for 3D model movement */
            transition: transform 0.9s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.8s ease-in-out;
        }

        /* Front Image Initial 3D State */
        .front-image {
            opacity: 1;
            transform: scale(1) rotateX(0deg) rotateY(0deg) translateZ(0px);
            z-index: 2;
        }

        /* Back Image Initial 3D State (Hidden Deep inside the 3D grid) */
        .back-image {
            opacity: 0;
            transform: scale(0.8) rotateX(-15deg) rotateY(20deg) translateZ(-150px);
            z-index: 1;
        }

        /* ⚡ TRIGGER STAGE: 3D MODEL ZOOM & FLIP EFFECT ON TAP */
        .premium-card.active .front-image {
            opacity: 0;
            transform: scale(1.2) rotateX(15deg) rotateY(-20deg) translateZ(150px);
            /* Aage ki taraf zoom aur tilt hoke fly-out hogi */
        }

        .premium-card.active .back-image {
            opacity: 1;
            transform: scale(1) rotateX(0deg) rotateY(0deg) translateZ(0px);
            /* Deep perspective se nikal kar seamlessly samne fix hogi */
            z-index: 3;
        }
    </style>
</head>

<body class="text-gray-100 min-h-screen antialiased">

    <?php include 'include/header.php'; ?>
    <style>
        /* ── Reset & Base ─────────────────────────────────────────────────── */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        :root {
            --gold: #C9933A;
            --gold-2: #e0b060;
            --gold-3: #f0cc88;
            --gold-bg: rgba(201, 147, 58, .10);
            --gold-bg2: rgba(201, 147, 58, .18);
            --bg: #0d0c0b;
            --bg-card: #181614;
            --bg-lift: #201e1b;
            --bg-input: rgba(255, 255, 255, 0.045);
            --border: rgba(255, 255, 255, 0.08);
            --border-md: rgba(255, 255, 255, 0.13);
            --border-gold: rgba(201, 147, 58, .32);
            --text: #f2ede7;
            --text-2: #a89f95;
            --text-3: #6b635c;
            --green: #3ecf8e;
            --red: #f06565;
            --r: 7px;
            --r-lg: 14px;
            --r-xl: 22px;
            --font-serif: 'Playfair Display', Georgia, serif;
            --font-sans: 'Inter', system-ui, sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font-sans);
            min-height: 100vh;
            padding: 0
        }

        /* ── Page Shell ─────────────────────────────────────────────────── */
        .page-wrap {
            max-width: 1160px;
            margin: 0 auto;
            padding: 56px 24px 80px
        }

        .page-grid {
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            gap: 72px;
            align-items: start
        }

        /* ── Left — Intro ───────────────────────────────────────────────── */
        .intro-sticky {
            position: sticky;
            top: 40px
        }

        .eyebrow {
            font-size: 11px;
            letter-spacing: .35em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .eyebrow::before {
            content: '';
            width: 28px;
            height: 1px;
            background: var(--gold);
            opacity: .55
        }

        .intro h1 {
            font-family: var(--font-serif);
            font-size: clamp(28px, 3.6vw, 46px);
            font-weight: 600;
            line-height: 1.18;
            margin-bottom: 18px
        }

        .intro h1 em {
            color: var(--gold);
            font-style: italic
        }

        .intro .sub {
            font-size: 15px;
            color: var(--text-2);
            line-height: 1.75;
            margin-bottom: 36px
        }

        .perks {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 40px
        }

        .perk {
            display: flex;
            align-items: flex-start;
            gap: 14px
        }

        .perk-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--gold-bg);
            border: 1px solid var(--border-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px
        }

        .perk-icon svg {
            width: 14px;
            height: 14px;
            stroke: var(--gold);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round
        }

        .perk-text strong {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            display: block;
            margin-bottom: 2px
        }

        .perk-text span {
            font-size: 12px;
            color: var(--text-3)
        }

        .trust-strip {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 18px 20px;
            background: var(--bg-lift);
            border: 1px solid var(--border);
            border-radius: var(--r-lg)
        }

        .trust-strip svg {
            width: 20px;
            height: 20px;
            stroke: var(--gold);
            fill: none;
            stroke-width: 1.8;
            flex-shrink: 0
        }

        .trust-strip p {
            font-size: 12px;
            color: var(--text-2);
            line-height: 1.5
        }

        .trust-strip p strong {
            color: var(--text);
            font-weight: 500
        }

        /* ── Right — Card Form ──────────────────────────────────────────── */
        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--r-xl);
            overflow: hidden
        }

        /* Step progress bar */
        .step-bar {
            display: flex;
            border-bottom: 1px solid var(--border);
            background: rgba(0, 0, 0, .2)
        }

        .step-item {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 16px 12px;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-3);
            cursor: pointer;
            transition: color .2s;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px
        }

        .step-item.active {
            color: var(--gold);
            border-bottom-color: var(--gold)
        }

        .step-item.done {
            color: var(--text-2)
        }

        .step-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 1.5px solid currentColor;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            flex-shrink: 0
        }

        .step-item.done .step-dot {
            background: var(--gold-bg);
            border-color: var(--gold);
            color: var(--gold)
        }

        .form-body {
            padding: 36px 36px 32px
        }

        /* Section heading */
        .sec-head {
            font-size: 11px;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 20px;
            margin-top: 28px;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .sec-head:first-child {
            margin-top: 0
        }

        .sec-head::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-gold);
            opacity: .4
        }

        /* Field */
        .field {
            margin-bottom: 16px
        }

        .field label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-2);
            margin-bottom: 7px;
            letter-spacing: .01em
        }

        .field label .opt {
            color: var(--text-3);
            font-weight: 400
        }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
            padding: 13px 15px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: var(--r);
            color: var(--text);
            font-size: 14px;
            font-family: var(--font-sans);
            outline: none;
            transition: border-color .18s, box-shadow .18s;
        }

        .field select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b635c' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 44px
        }

        .field select option {
            background: #201e1b;
            color: var(--text)
        }

        .field textarea {
            resize: vertical;
            line-height: 1.65;
            min-height: 80px
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201, 147, 58, .1)
        }

        .field input.error,
        .field select.error,
        .field textarea.error {
            border-color: var(--red)
        }

        .field .hint {
            font-size: 11px;
            color: var(--text-3);
            margin-top: 5px
        }

        .field .err-msg {
            font-size: 11px;
            color: var(--red);
            margin-top: 5px
        }

        .row2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px
        }

        /* ── Frame Size Cards ──────────────────────────────────────────── */
        .size-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 4px
        }

        .size-card {
            border: 1.5px solid var(--border);
            border-radius: var(--r-lg);
            padding: 16px 12px;
            text-align: center;
            cursor: pointer;
            transition: border-color .18s, background .18s;
            position: relative;
            user-select: none;
        }

        .size-card:hover {
            border-color: var(--border-gold);
            background: var(--gold-bg)
        }

        .size-card.selected {
            border-color: var(--gold);
            background: var(--gold-bg2)
        }

        .size-card .badge {
            position: absolute;
            top: -1px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--gold);
            color: #111;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 0 0 8px 8px;
            white-space: nowrap;
            letter-spacing: .03em;
        }

        .size-card .sz-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 3px
        }

        .size-card .sz-dim {
            font-size: 11px;
            color: var(--text-3);
            margin-bottom: 8px
        }

        .size-card .sz-type {
            font-size: 10px;
            color: var(--text-2);
            margin-bottom: 10px;
            background: var(--bg-lift);
            border-radius: 20px;
            padding: 2px 10px;
            display: inline-block
        }

        .size-card .sz-price {
            font-size: 18px;
            font-weight: 600;
            color: var(--gold)
        }

        .size-card .sz-price small {
            font-size: 12px;
            font-weight: 400;
            color: var(--text-3)
        }

        input[type="radio"].size-radio {
            display: none
        }

        /* ── Upload Zone ─────────────────────────────────────────────── */
        .upload-zone {
            border: 1.5px dashed var(--border-gold);
            border-radius: var(--r-lg);
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color .18s, background .18s;
            background: rgba(201, 147, 58, .015);
        }

        .upload-zone:hover,
        .upload-zone.drag {
            border-color: var(--gold);
            background: var(--gold-bg)
        }

        .upload-zone input {
            display: none
        }

        .up-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 10px;
            opacity: .45
        }

        .up-icon svg {
            width: 40px;
            height: 40px;
            stroke: var(--gold);
            fill: none;
            stroke-width: 1.4;
            stroke-linecap: round;
            stroke-linejoin: round
        }

        .upload-zone p {
            font-size: 13px;
            color: var(--text-2);
            margin-bottom: 4px
        }

        .upload-zone small {
            font-size: 11px;
            color: var(--text-3)
        }

        .preview-wrap {
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 10px
        }

        .preview-wrap img {
            max-height: 110px;
            max-width: 100%;
            border-radius: var(--r);
            border: 1px solid var(--border-gold)
        }

        .preview-info {
            display: flex;
            align-items: center;
            gap: 8px
        }

        .preview-info span {
            font-size: 12px;
            color: var(--gold)
        }

        .preview-info button {
            font-size: 11px;
            color: var(--text-3);
            background: none;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 3px 10px;
            cursor: pointer;
            font-family: var(--font-sans)
        }

        .preview-info button:hover {
            border-color: var(--red);
            color: var(--red)
        }

        /* ── Order Summary ──────────────────────────────────────────── */
        .summary-box {
            background: rgba(255, 255, 255, .025);
            border: 1px solid var(--border);
            border-radius: var(--r-lg);
            padding: 20px;
            margin-top: 8px
        }

        .sum-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: var(--text-2);
            padding: 6px 0
        }

        .sum-row+.sum-row {
            border-top: 1px solid var(--border)
        }

        .sum-row .label {
            display: flex;
            align-items: center;
            gap: 6px
        }

        .sum-row .label svg {
            width: 13px;
            height: 13px;
            stroke: var(--text-3);
            fill: none;
            stroke-width: 2
        }

        .sum-row.total {
            padding-top: 14px;
            margin-top: 4px;
            border-top: 1px solid var(--border-md)
        }

        .sum-row.total .label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text)
        }

        .sum-row.total .val {
            font-size: 20px;
            font-weight: 600;
            color: var(--gold)
        }

        .sum-row .val {
            color: var(--text)
        }

        /* ── Buttons ─────────────────────────────────────────────────── */
        .btn-row {
            display: flex;
            gap: 12px;
            margin-top: 24px
        }

        .btn {
            padding: 14px 24px;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 600;
            font-family: var(--font-sans);
            cursor: pointer;
            transition: transform .14s, opacity .14s;
            border: none;
            flex: 1
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border-md);
            color: var(--text-2)
        }

        .btn-outline:hover {
            border-color: var(--gold);
            color: var(--gold)
        }

        .btn-primary {
            background: #b252e2;
            color: #0d0c0b;
            font-size: 15px
        }

        .btn-primary:hover {
            opacity: .88;
            transform: translateY(-1px)
        }

        .btn-primary:active {
            transform: translateY(0)
        }

        .btn-primary:disabled {
            opacity: .4;
            cursor: not-allowed;
            transform: none
        }

        .btn-full {
            width: 100%
        }

        /* ── Alerts ──────────────────────────────────────────────────── */
        .alert {
            padding: 14px 16px;
            border-radius: var(--r);
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            font-size: 13px;
            align-items: flex-start
        }

        .alert svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            margin-top: 1px
        }

        .alert-error {
            background: rgba(240, 101, 101, .1);
            border: 1px solid rgba(240, 101, 101, .3);
            color: #f5a0a0
        }

        .alert-error svg {
            stroke: #f06565
        }

        .alert-success {
            background: rgba(62, 207, 142, .1);
            border: 1px solid rgba(62, 207, 142, .3);
            color: #7ae8b4
        }

        .alert-success svg {
            stroke: #3ecf8e
        }

        .alert ul {
            margin-top: 6px;
            padding-left: 16px
        }

        .alert ul li {
            margin-top: 3px
        }

        /* ── Steps (JS-driven, post-stream) ─────────────────────────── */
        .step-panel {
            display: none
        }

        .step-panel.active {
            display: block
        }

        /* ── Footer note ─────────────────────────────────────────────── */
        .foot-note {
            text-align: center;
            font-size: 11px;
            color: var(--text-3);
            margin-top: 16px;
            line-height: 1.8
        }

        .foot-note a {
            color: var(--text-3);
            text-decoration: underline
        }

        /* ── Responsive ──────────────────────────────────────────────── */
        @media(max-width:780px) {
            .page-grid {
                grid-template-columns: 1fr;
                gap: 40px
            }

            .intro-sticky {
                position: static
            }

            .size-grid {
                grid-template-columns: 1fr 1fr
            }

            .row2 {
                grid-template-columns: 1fr
            }

            .form-body {
                padding: 24px 20px
            }

            .btn-row {
                flex-direction: column
            }
        }

        @media(max-width:480px) {
            .size-grid {
                grid-template-columns: 1fr
            }

            .step-item span {
                display: none
            }
        }
    </style>

    <?php

    $sizes_result = mysqli_query(
        $conn,
        "SELECT id, size_name, portrait_type, price FROM frame_sizes
     WHERE status = 1 ORDER BY sort_order ASC"
    );
    $frame_sizes = [];
    while ($row = mysqli_fetch_assoc($sizes_result)) {
        $frame_sizes[] = $row;
    }

    // Flash errors from session (set by submit_order.php on validation fail)
    
    $errors = $_SESSION['order_errors'] ?? [];
    $old = $_SESSION['order_old'] ?? [];
    unset($_SESSION['order_errors'], $_SESSION['order_old']);
    ?>

    <div class="page-wrap">
        <div class="page-grid  mt-20">

            <!-- ── LEFT INTRO ───────────────────────────────────────── -->
            <div class="intro-sticky">
                <div class="eyebrow">Start Creating</div>
                <div class="intro">
                    <h1>Transform Your<br><em><span
                                class="block text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-purple-500 to-rose-500">

                                Precious Moments into

                            </span></em></h1>
                    <p class="sub">Upload any photo — a wedding, a birthday, a face you love — and our artisans
                        hand-craft it into a museum-quality illuminated portrait you'll treasure forever.</p>
                </div>

                <div class="perks">
                    <div class="perk">
                        <div class="perk-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </div>
                        <div class="perk-text">
                            <strong>Free design preview</strong>
                            <span>See your art before we start crafting</span>
                        </div>
                    </div>
                    <div class="perk">
                        <div class="perk-icon">
                            <svg viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="perk-text">
                            <strong>100% satisfaction guarantee</strong>
                            <span>Not happy? We'll redo it, free of charge</span>
                        </div>
                    </div>
                    <div class="perk">
                        <div class="perk-icon">
                            <svg viewBox="0 0 24 24">
                                <rect x="1" y="3" width="15" height="13" />
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>
                        </div>
                        <div class="perk-text">
                            <strong>Pan-India secure shipping</strong>
                            <span>Tracked & insured delivery to your door</span>
                        </div>
                    </div>
                    <div class="perk">
                        <div class="perk-icon">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                            </svg>
                        </div>
                        <div class="perk-text">
                            <strong>Handwritten gift note</strong>
                            <span>Add a personal message at no extra cost</span>
                        </div>
                    </div>
                    <div class="perk">
                        <div class="perk-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <div class="perk-text">
                            <strong>Ready in 7–14 business days</strong>
                            <span>Rush options available on request</span>
                        </div>
                    </div>
                </div>

                <div class="trust-strip">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0110 0v4" />
                    </svg>
                    <p><strong>Safe & private</strong><br>Your photos are stored securely and never shared with third
                        parties. Orders processed via encrypted connection.</p>
                </div>
            </div>

            <!-- ── RIGHT FORM ────────────────────────────────────────── -->
            <div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error" style="margin-bottom:20px">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <div>
                            <strong>Please fix the following:</strong>
                            <ul><?php foreach ($errors as $e): ?>
                                    <li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-card">

                    <!-- Step bar -->
                    <div class="step-bar" id="stepBar">
                        <div class="step-item active" data-step="1">
                            <div class="step-dot">1</div>
                            <span>Your details</span>
                        </div>
                        <div class="step-item" data-step="2">
                            <div class="step-dot">2</div>
                            <span>Size &amp; photo</span>
                        </div>
                        <div class="step-item" data-step="3">
                            <div class="step-dot">3</div>
                            <span>Delivery</span>
                        </div>
                    </div>

                    <div class="form-body">
                        <form id="orderForm" method="POST" action="#" enctype="multipart/form-data"
                            novalidate>

                            <!-- <input type="hidden" name="product_id" value="1"> -->
                            <input type="hidden" name="order_type" value="custom_photo">
                            <input type="hidden" name="frame_size_id" id="frameSizeId"
                                value="<?= htmlspecialchars($old['frame_size_id'] ?? '') ?>">

                            <!-- ════ STEP 1 — Your Details ════ -->
                            <div class="step-panel active" id="step1">

                                <div class="sec-head">Your details</div>

                                <div class="field">
                                    <label>Full name</label>
                                    <input type="text" name="customer_name" placeholder="Full Name"
                                        value="<?= htmlspecialchars($old['customer_name'] ?? '') ?>" autocomplete="name"
                                        required>
                                </div>

                                <div class="field">
                                    <label>
                                        WhatsApp number
                                        <span class="opt">+91 India</span>
                                    </label>
                                    <input type="tel" name="whatsapp_number" placeholder="WhatsApp Number"
                                        value="<?= htmlspecialchars($old['whatsapp_number'] ?? '') ?>"
                                        pattern="[6-9][0-9]{9}" maxlength="10" inputmode="numeric" required>
                                    <div class="hint">10-digit mobile number. We'll send order updates here.</div>
                                </div>

                                <div class="field">
                                    <label>Occasion <span class="opt">(optional)</span></label>
                                    <select id="occasionSel">
                                        <option value="">— Select occasion —</option>
                                        <option value="Wedding">Wedding</option>
                                        <option value="Anniversary">Anniversary</option>
                                        <option value="Birthday">Birthday</option>
                                        <option value="Diwali / Festive">Diwali / Festive</option>
                                        <option value="Valentine's Day">Valentine's Day</option>
                                        <option value="Memorial">Memorial</option>
                                        <option value="Housewarming">Housewarming</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="btn-row">
                                    <button type="button" class="btn btn-primary btn-full" onclick="goStep(2)">
                                        Continue — choose size &amp; photo &rarr;
                                    </button>
                                </div>
                            </div>

                            <!-- ════ STEP 2 — Size & Photo ════ -->
                            <div class="step-panel" id="step2">

                                <div class="sec-head">Frame size</div>

                                <?php if (empty($frame_sizes)): ?>
                                    <p style="color:var(--text-3);font-size:13px;margin-bottom:20px">No frame sizes
                                        available right now. Please try again later.</p>
                                <?php else: ?>
                                    <div class="size-grid" id="sizeGrid">
                                        <?php foreach ($frame_sizes as $i => $fs):
                                            $popular = ($i === 1); // mark 2nd option as popular
                                            $sel = (isset($old['frame_size_id']) && $old['frame_size_id'] == $fs['id']);
                                            ?>
                                            <label class="size-card <?= $sel ? 'selected' : '' ?>" data-id="<?= $fs['id'] ?>"
                                                data-price="<?= $fs['price'] ?>"
                                                data-name="<?= htmlspecialchars($fs['size_name']) ?>">
                                                <?php if ($popular): ?>
                                                    <div class="badge">Most popular</div><?php endif; ?>
                                                <input type="radio" class="size-radio" name="_size_display"
                                                    value="<?= $fs['id'] ?>" <?= $sel ? 'checked' : '' ?>>
                                                <div class="sz-name"><?= htmlspecialchars($fs['size_name']) ?></div>
                                                <div class="sz-dim"><?= htmlspecialchars($fs['portrait_type'] ?? '') ?></div>
                                                <div class="sz-price">
                                                    <small>₹</small><?= number_format($fs['price']) ?>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="sec-head" style="margin-top:28px">Upload photo</div>

                                <div class="field">
                                    <label for="photoInput">Your photo</label>
                                    <label class="upload-zone" id="uploadZone">
                                        <input type="file" name="client_image" id="photoInput"
                                            accept="image/jpeg,image/png,image/webp">
                                        <div id="uploadDefault">
                                            <div class="up-icon">
                                                <svg viewBox="0 0 24 24">
                                                    <polyline points="16 16 12 12 8 16" />
                                                    <line x1="12" y1="12" x2="12" y2="21" />
                                                    <path d="M20.39 18.39A5 5 0 0018 9h-1.26A8 8 0 103 16.3" />
                                                </svg>
                                            </div>
                                            <p>Click to upload or drag &amp; drop</p>
                                            <small>JPG, PNG, WebP — max 5 MB · High resolution recommended</small>
                                        </div>
                                        <div class="preview-wrap" id="uploadPreview">
                                            <img id="previewImg" src="" alt="Preview">
                                            <div class="preview-info">
                                                <span id="previewName"></span>
                                                <button type="button" onclick="clearUpload(event)">Remove</button>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="btn-row">
                                    <button type="button" class="btn btn-outline" onclick="goStep(1)">&larr;
                                        Back</button>
                                    <button type="button" class="btn btn-primary" onclick="goStep(3)">Continue —
                                        delivery &rarr;</button>
                                </div>
                            </div>

                            <!-- ════ STEP 3 — Delivery & Summary ════ -->
                            <div class="step-panel" id="step3">

                                <div class="sec-head">Delivery address</div>

                                <div class="field">
                                    <label>Full delivery address</label>
                                    <textarea name="delivery_address" rows="3"
                                        placeholder="Flat no., Building, Street, Area, City, State, PIN code"
                                        required><?= htmlspecialchars($old['delivery_address'] ?? '') ?></textarea>
                                </div>

                                <div class="sec-head">Gift note</div>

                                <div class="field">
                                    <label>Personal message <span class="opt">(optional)</span></label>
                                    <textarea name="notes" id="notesField" rows="2"
                                        placeholder="Write something heartfelt to include with the gift…"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                                    <div class="hint">We'll hand-write this on premium card stock.</div>
                                </div>

                                <!-- Order summary -->
                                <div class="sec-head">Order summary</div>

                                <div class="summary-box">
                                    <div class="sum-row">
                                        <span class="label">
                                            <svg viewBox="0 0 24 24">
                                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                                <circle cx="8.5" cy="8.5" r="1.5" />
                                                <polyline points="21 15 16 10 5 21" />
                                            </svg>
                                            <span id="sumSize">— select a size —</span>
                                        </span>
                                        <span class="val" id="sumPrice">₹0</span>
                                    </div>
                                    <div class="sum-row">
                                        <span class="label">
                                            <svg viewBox="0 0 24 24">
                                                <rect x="1" y="3" width="15" height="13" />
                                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                                <circle cx="5.5" cy="18.5" r="2.5" />
                                                <circle cx="18.5" cy="18.5" r="2.5" />
                                            </svg>
                                            Shipping
                                        </span>
                                        <span class="val" style="color:var(--green)">Free</span>
                                    </div>
                                    <div class="sum-row">
                                        <span class="label">
                                            <svg viewBox="0 0 24 24">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg>
                                            Design preview
                                        </span>
                                        <span class="val" style="color:var(--green)">Included</span>
                                    </div>
                                    <div class="sum-row total">
                                        <span class="label">Total payable</span>
                                        <span class="val" id="sumTotal">₹0</span>
                                    </div>
                                </div>

                                <div class="btn-row">
                                    <button type="button" class="btn btn-outline" onclick="goStep(2)">&larr;
                                        Back</button>
                                    <button type="button" class="btn btn-primary" id="submitBtn" onclick="initiatePayment()" disabled>
                                        <span id="btnLabel">Place order — <span id="btnAmt">₹0</span></span>
                                        <span id="btnSpinner" style="display:none">Processing…</span>
                                    </button>
                                </div>

                                <p class="foot-note">
                                    By placing an order you agree to our <a href="#">terms</a> &amp; <a href="#">privacy
                                        policy</a>.<br>
                                    Payment collected after design approval · 100% secure
                                </p>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        (function () {
            /* ── Frame sizes from PHP ─────────────── */
            var SIZES = <?= json_encode(array_values($frame_sizes)) ?>;

            var currentStep = 1;
            var selectedSize = null;

            /* ── Step navigation ──────────────────── */
            function goStep(n) {
                if (n > currentStep && !validateStep(currentStep)) return;
                currentStep = n;
                document.querySelectorAll('.step-panel').forEach(function (p) { p.classList.remove('active') });
                document.getElementById('step' + n).classList.add('active');
                document.querySelectorAll('.step-item').forEach(function (el) {
                    var sn = parseInt(el.dataset.step);
                    el.classList.toggle('active', sn === n);
                    el.classList.toggle('done', sn < n);
                    if (sn < n) el.querySelector('.step-dot').textContent = '✓';
                    else if (sn === n) el.querySelector('.step-dot').textContent = sn;
                    else el.querySelector('.step-dot').textContent = sn;
                });
            }
            window.goStep = goStep;

            /* ── Validation per step ─────────────── */
            function validateStep(n) {
                var ok = true;
                if (n === 1) {
                    var name = document.querySelector('[name="customer_name"]');
                    var wa = document.querySelector('[name="whatsapp_number"]');
                    if (!name.value.trim()) { shake(name); ok = false; }
                    if (!wa.value.trim() || !/^[6-9][0-9]{9}$/.test(wa.value.trim())) { shake(wa); ok = false; }
                }
                if (n === 2) {
                    if (!selectedSize) { shake(document.getElementById('sizeGrid')); showToast('Please select a frame size.'); ok = false; }
                    if (!document.getElementById('photoInput').files.length) { shake(document.getElementById('uploadZone')); showToast('Please upload your photo.'); ok = false; }
                }
                return ok;
            }

            function shake(el) {
                el.style.animation = 'none';
                el.offsetHeight;
                el.style.animation = 'shake .35s ease';
            }

            /* ── Size card selection ─────────────── */
            document.querySelectorAll('.size-card').forEach(function (card) {
                card.addEventListener('click', function () {
                    document.querySelectorAll('.size-card').forEach(function (c) { c.classList.remove('selected') });
                    card.classList.add('selected');
                    selectedSize = { id: card.dataset.id, price: parseFloat(card.dataset.price), name: card.dataset.name };
                    document.getElementById('frameSizeId').value = selectedSize.id;
                    updateSummary();
                });
            });

            /* Auto-select first if only one or if restored from session */
            var savedId = document.getElementById('frameSizeId').value;
            document.querySelectorAll('.size-card').forEach(function (card) {
                if (card.dataset.id === savedId || (!savedId && card.classList.contains('selected'))) {
                    selectedSize = { id: card.dataset.id, price: parseFloat(card.dataset.price), name: card.dataset.name };
                    updateSummary();
                }
            });
            if (!selectedSize && document.querySelectorAll('.size-card').length) {
                var first = document.querySelectorAll('.size-card')[1] || document.querySelectorAll('.size-card')[0];
                if (first) { first.click(); }
            }

            /* ── Summary update ──────────────────── */
            function fmt(n) { return '₹' + Math.round(n).toLocaleString('en-IN'); }
            function updateSummary() {
                if (!selectedSize) return;
                document.getElementById('sumSize').textContent = selectedSize.name;
                document.getElementById('sumPrice').textContent = fmt(selectedSize.price);
                document.getElementById('sumTotal').textContent = fmt(selectedSize.price);
                document.getElementById('btnAmt').textContent = fmt(selectedSize.price);
                document.getElementById('submitBtn').disabled = false;
            }

            /* ── Upload handling ─────────────────── */
            var zone = document.getElementById('uploadZone');
            var input = document.getElementById('photoInput');

            zone.addEventListener('dragover', function (e) { e.preventDefault(); zone.classList.add('drag') });
            zone.addEventListener('dragleave', function () { zone.classList.remove('drag') });
            zone.addEventListener('drop', function (e) {
                e.preventDefault(); zone.classList.remove('drag');
                if (e.dataTransfer.files.length) {
                    setFiles(e.dataTransfer.files);
                }
            });
            input.addEventListener('change', function () { if (input.files.length) setFiles(input.files) });

            function setFiles(files) {
                var file = files[0];
                if (file.size > 5 * 1024 * 1024) { showToast('File must be under 5 MB.'); return; }
                var dt = new DataTransfer(); dt.items.add(file);
                input.files = dt.files;
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('previewName').textContent = file.name;
                    document.getElementById('uploadDefault').style.display = 'none';
                    document.getElementById('uploadPreview').style.display = 'flex';
                };
                reader.readAsDataURL(file);
            }

            window.clearUpload = function (e) {
                e.preventDefault(); e.stopPropagation();
                input.value = '';
                document.getElementById('uploadDefault').style.display = 'block';
                document.getElementById('uploadPreview').style.display = 'none';
            };

            /* ── Append occasion to notes ─────────── */
            function appendOccasion() {
                var occ = document.getElementById('occasionSel').value;
                var notes = document.getElementById('notesField');
                if (occ && notes.value.indexOf('[Occasion:') === -1) {
                    var existing = notes.value.trim();
                    notes.value = (existing ? existing + '\n' : '') + '[Occasion: ' + occ + ']';
                }
            }

            /* ── Show inline payment error ────────── */
            function showPaymentError(msg) {
                var existing = document.getElementById('paymentErrorBox');
                if (existing) existing.remove();
                var box = document.createElement('div');
                box.id = 'paymentErrorBox';
                box.className = 'alert alert-error';
                box.style.marginTop = '16px';
                box.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><div>' + msg + '</div>';
                document.querySelector('.btn-row:last-of-type').before(box);
                setBtnIdle();
            }

            function setBtnLoading() {
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('btnLabel').style.display = 'none';
                document.getElementById('btnSpinner').style.display = 'inline';
            }

            function setBtnIdle() {
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('btnLabel').style.display = 'inline';
                document.getElementById('btnSpinner').style.display = 'none';
            }

            /* ── Main Razorpay flow ───────────────── */
            window.initiatePayment = function () {
                if (!validateStep(1) || !validateStep(2)) { goStep(1); return; }

                // validate delivery address
                var addr = document.querySelector('[name="delivery_address"]');
                if (!addr || !addr.value.trim()) {
                    shake(addr);
                    showToast('Please enter your delivery address.');
                    return;
                }

                appendOccasion();
                setBtnLoading();

                // Remove any old error box
                var old = document.getElementById('paymentErrorBox');
                if (old) old.remove();

                // Build FormData (includes file upload)
                var form = document.getElementById('orderForm');
                var fd = new FormData(form);

                // Step 1: Create Razorpay order on server
                fetch('create_order.php', {
                    method: 'POST',
                    body: fd
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.status !== 'success') {
                        var errMsg = (res.errors && res.errors.length) ? res.errors.join('<br>') : (res.message || 'Failed to create order. Please try again.');
                        showPaymentError(errMsg);
                        return;
                    }

                    // Step 2: Open Razorpay checkout popup
                    var options = {
                        key:               res.key_id,
                        amount:            res.amount,
                        currency:          res.currency,
                        name:              'Artistnik',
                        description:       res.product_title || 'Custom Glass Carved Portrait',
                        order_id:          res.razorpay_order_id,
                        prefill: {
                            name:    res.customer_name,
                            contact: res.whatsapp_number
                        },
                        theme: { color: '#C9933A' },
                        modal: {
                            ondismiss: function () {
                                // User closed popup — mark order as Cancelled
                                fetch('cancel_order.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ order_no: res.order_no })
                                }).catch(function(){});
                                showPaymentError('Payment was cancelled. You can try again below.');
                            }
                        },
                        handler: function (paymentData) {
                            // Step 3: Verify payment signature on server
                            setBtnLoading();
                            fetch('verify_payment.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    razorpay_payment_id: paymentData.razorpay_payment_id,
                                    razorpay_order_id:   paymentData.razorpay_order_id,
                                    razorpay_signature:  paymentData.razorpay_signature,
                                    order_no:            res.order_no
                                })
                            })
                            .then(function (r) { return r.json(); })
                            .then(function (vRes) {
                                if (vRes.status === 'success') {
                                    // Redirect to success page
                                    window.location.href = 'order_success.php?order=' + encodeURIComponent(res.order_no);
                                } else {
                                    showPaymentError('Payment received but verification failed: ' + (vRes.message || 'Unknown error.') + ' Please contact support with Order No: ' + res.order_no);
                                }
                            })
                            .catch(function () {
                                showPaymentError('Network error during verification. Please contact support with Order No: ' + res.order_no);
                            });
                        }
                    };

                    var rzp = new Razorpay(options);
                    rzp.on('payment.failed', function (resp) {
                        // Update order as Failed in DB
                        fetch('cancel_order.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ order_no: res.order_no, reason: 'failed' })
                        }).catch(function(){});
                        showPaymentError('Payment failed: ' + (resp.error.description || 'Unknown error.') + ' Please try again.');
                    });
                    rzp.open();
                })
                .catch(function () {
                    showPaymentError('Network error. Please check your connection and try again.');
                });
            };

            /* ── Toast ───────────────────────────── */
            function showToast(msg) {
                var t = document.createElement('div');
                t.textContent = msg;
                Object.assign(t.style, {
                    position: 'fixed', bottom: '24px', left: '50%', transform: 'translateX(-50%)',
                    background: '#201e1b', color: '#f2ede7', padding: '12px 20px',
                    borderRadius: '40px', fontSize: '13px', fontFamily: 'Inter,sans-serif',
                    border: '1px solid rgba(255,255,255,.1)', zIndex: '9999',
                    boxShadow: '0 4px 24px rgba(0,0,0,.4)', whiteSpace: 'nowrap'
                });
                document.body.appendChild(t);
                setTimeout(function () { t.remove() }, 3000);
            }

            /* ── Step bar clicks ─────────────────── */
            document.querySelectorAll('.step-item').forEach(function (el) {
                el.addEventListener('click', function () {
                    var n = parseInt(el.dataset.step);
                    if (n < currentStep) goStep(n);
                });
            });

        })();
    </script>

    <!-- Razorpay Checkout SDK -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <style>
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0)
            }

            20% {
                transform: translateX(-6px)
            }

            40% {
                transform: translateX(6px)
            }

            60% {
                transform: translateX(-4px)
            }

            80% {
                transform: translateX(4px)
            }
        }
    </style>


    <?php include('include/footer.php'); ?>
    <?php include('include/foot.php'); ?>


</body>

</html>