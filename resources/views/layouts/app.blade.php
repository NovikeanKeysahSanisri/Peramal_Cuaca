<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App - @yield('title', 'Home')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .carousel {
            overflow: hidden;
            width: 975px;
            height: 300px;
            position: relative;
        }
        .carousel-inner {
            display: flex;
            transition: transform 5
             ease-in-out;
        }
        .carousel-item {
            min-width: 80%;
            height: 200px;
            margin: 10px;
        }
        .weather-icon {
            font-size: 24px;
        }
        .weather-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .weather-info {
            flex: 1;
            min-width: 300px;
            margin-right: 20px;
        }
        .calendar-container {
            flex: 1;
            min-width: 300px;
        }
        .calendar {
            width: 100%;
            border-collapse: separate;
            border-spacing: 2px;
        }
        .calendar th, .calendar td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: center;
        }
        .calendar th {
            background-color: #edf2f7;
            font-weight: bold;
        }
        .calendar td {
            height: 80px;
            vertical-align: top;
        }
        .weather-icon {
            font-size: 24px;
        }
        .temperature {
            font-size: 12px;
        }
        /* Dark mode styles */
        .dark body { background-color: #1a202c; color: #e2e8f0; }
        .dark .bg-white { background-color: #2d3748; }
        .dark .bg-gray-100 { background-color: #4a5568; }
        .dark .text-gray-500 { color: #000000; }
        .dark .bg-gray-50 { background-color: #2d3748; }
        .dark .hover\:bg-gray-100:hover { background-color: #000000; }
        .dark .border-gray-200 { border-color: #4a5568; }
        .\ { color: #000000; }
        .dark .text-gray-800 { color: #000000; }
        .dark .bg-blue-500 { background-color: #2b6cb0; }
        .dark .bg-blue-600 { background-color: #2c5282; }
        .dark .hover\:bg-blue-700:hover { background-color: #2a4365; }
        .dark .bg-green-500 { background-color: #48bb78; }
        .dark .hover\:bg-green-600:hover { background-color: #38a169; }
        .weather-icon {
            font-size: 36px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <header class="bg-blue-500 dark:bg-blue-800 p-4">
        <div class="container mx-auto">
            <div class="flex justify-between items-center">
                <h1 class="text-white text-3xl font-bold mb-4">Weather App</h1>
                <div class="flex items-center">
                    <button id="themeToggle" class="bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-full hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors duration-200 mr-2">
                        <i class="fas fa-sun dark:hidden"></i>
                        <i class="fas fa-moon hidden dark:inline"></i>
                    </button>
                    @auth
                    @php
                        // Mengambil inisial dari username yang login
                        $initials = collect(explode(' ', Auth::user()->name))->map(function ($word) {
                            return strtoupper($word[0]); // Ambil huruf pertama dari setiap kata dan jadikan huruf besar
                        })->join('');
                    @endphp

                    <!-- Tampilkan inisial dari username -->
                    <a href="{{ route('profile') }}" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 transition-colors duration-200">
                        {{ $initials }} <!-- Menampilkan inisial -->
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 transition-colors duration-200">
                        Login
                    </a>
                @endauth

                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto mt-8">
        @yield('content')
    </main>

    <script>
        // Script untuk toggle tema gelap/terang
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        });

        // Check for saved theme preference or prefer-color-scheme
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            html.classList.add('dark');
        }

        // ... tambahkan script lainnya jika diperlukan ...
    </script>
</body>
</html>