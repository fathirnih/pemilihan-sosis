<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Pemilihan OSIS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-primary: #2563eb;
            --color-primary-dark: #1e40af;
            --color-secondary: #64748b;
            --color-success: #10b981;
            --color-danger: #ef4444;
            --color-warning: #f59e0b;
            --color-bg: #f8fafc;
            --color-bg-primary: #ffffff;
            --color-border: #e2e8f0;
            --color-text: #1e293b;
            --color-text-muted: #64748b;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--color-bg);
            color: var(--color-text);
        }

        .container-app {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Smooth transitions */
        * {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }

        /* Remove transition for specific elements */
        img, svg {
            transition: none;
        }
    </style>

    @yield('styles')
</head>
<body>
    @yield('content')

    @yield('scripts')
</body>
</html>
