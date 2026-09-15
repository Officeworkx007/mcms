<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — HCLSC Samadhan</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --ink: #13152D;
            --ink-soft: #4B4D6B;
            --paper: #F5F6FC;
            --paper-raised: #FFFFFF;
            --indigo: #4C3FE0;
            --indigo-deep: #2E22A6;
            --coral: #FF6B4A;
            --coral-deep: #E8542F;
            --mint: #22D3B2;
            --amber: #F5A623;
            --violet: #A855F7;
            --line: #E2E1F5;
            --radius: 14px;
            --shadow: 0 20px 45px -20px rgba(19, 21, 45, 0.25);
            --sidebar-w: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--paper);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        h1,
        h2,
        h3,
        .brand-word {
            font-family: 'Sora', sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        :focus-visible {
            outline: 2.5px solid var(--indigo);
            outline-offset: 3px;
            border-radius: 4px;
        }

        /* ===== LAYOUT SHELL ===== */
        .u-shell {
            display: flex;
            min-height: 100vh;
        }

        .u-main {
            flex: 1;
            margin-left: var(--sidebar-w);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.25s ease;
        }

        .u-content {
            flex: 1;
            padding: 28px clamp(20px, 3vw, 40px) 48px;
            animation: contentIn 0.45s cubic-bezier(.2, .8, .2, 1);
        }

        @keyframes contentIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 900px) {
            .u-main {
                margin-left: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="u-shell">
        @include('user.layouts.sidebar')

        <div class="u-main">
            @include('user.layouts.header')

            <main class="u-content">
                @yield('content')
            </main>

            @include('user.layouts.footer')
        </div>
    </div>

    @stack('scripts')

</body>

</html>
