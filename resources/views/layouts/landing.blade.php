<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/phosphor-icons@2.0.2/src/regular/style.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        /* Dark theme dengan orange accent */
        :root {
            --primary: #FF7F00;
            --primary-dark: #E67E00;
            --dark-bg: #0A0E27;
            --dark-card: #1A1F3A;
            --dark-border: #2D3554;
            --text-primary: #FFFFFF;
            --text-secondary: #A0AEC0;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .btn-premium {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            color: white;
            text-decoration: none;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 127, 0, 0.3);
        }

        .soft-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--dark-border);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .soft-card:hover {
            border-color: var(--primary);
            background: rgba(255, 127, 0, 0.05);
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, #FF9F43 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .underline-gradient {
            position: relative;
            display: inline-block;
        }

        .underline-gradient::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, transparent 100%);
            border-radius: 2px;
        }

        .gradient-bg {
            background: linear-gradient(135deg, rgba(255, 127, 0, 0.1) 0%, rgba(255, 127, 0, 0) 100%);
        }

        .card-service {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 16px;
            padding: 32px;
            transition: all 0.3s ease;
        }

        .card-service:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(255, 127, 0, 0.1);
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            aspect-ratio: 1;
            cursor: pointer;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item:hover::after {
            opacity: 1;
        }

        .section-title {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 24px;
        }

        .section-subtitle {
            color: var(--text-secondary);
            font-size: 18px;
            line-height: 1.6;
        }

        .stat-number {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 900;
            color: var(--primary);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 127, 0, 0.1);
            border: 1px solid rgba(255, 127, 0, 0.3);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .card-service:hover .feature-icon {
            background: var(--primary);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }
            
            .section-subtitle {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    @include('components.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
