<!doctype html>
<html class="min-h-screen">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body class="min-h-screen bg-base-200">
    <header class="fixed w-full top-0 z-50 bg-base-100">
        <x-navbar />
    </header>

    <main class="container mx-auto pt-20 px-4">
        {{ $slot }}
    </main>

    <footer class="footer footer-center p-4 bg-base-300 text-base-content mt-8">
        <div>
            <p>Copyright © 2024 - All rights reserved by Blog</p>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
