<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
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
                <button id="themeToggle" class="bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-full hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors duration-200">
                    <i class="fas fa-sun dark:hidden"></i>
                    <i class="fas fa-moon hidden dark:inline"></i>
                </button>
            </div>
            <div class="flex justify-center items-center">
                <form method="GET" action="/" class="flex" id="weatherForm">
                    <input type="text" name="city" id="city" placeholder="Enter city name" class="px-4 py-2 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <button onclick="refreshWeather()" class="bg-green-500 text-white px-4 py-2 rounded-r-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 ml-1">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
    </header>

    <main class="container mx-auto mt-8">
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ $errors->first('message') }}</span>
            </div>
        @endif

        <div id="weatherData" class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4">Weather in <span id="cityName">-</span></h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="font-semibold">Temperature</h3>
                    <p id="temperature">-</p>
                </div>
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="font-semibold">Weather</h3>
                    <p id="weatherDescription">-</p>
                </div>
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="font-semibold">Humidity</h3>
                    <p id="humidity">-</p>
                </div>
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="font-semibold">Wind Speed</h3>
                    <p id="windSpeed">-</p>
                </div>
            </div>
        </div>

        <div class="carousel mx-auto mb-8">
            <div class="carousel-inner">
                @foreach($carouselData as $data)
                <div class="carousel-item rounded-lg shadow-md p-4 {{ $data['background'] }} ">
                <div class="weather-icon text-gray-700">
                    @switch($data['weather'][0]['main'])
                        @case('Clear')
                            ☀️
                            @break
                        @case('Clouds')
                            ☁️
                            @break
                        @case('Rain')
                            🌧️
                            @break
                        @case('Snow')
                            ❄️
                            @break
                        @case('Thunderstorm')
                            ⛈️
                            @break
                        @case('Drizzle')
                            🌦️
                            @break
                        @case('Mist')
                        @case('Fog')
                            🌫️
                            @break
                        @default
                            🌫️
                    @endswitch
                </div>
                        <h3 class="text-lg font-semibold mb-2 text-white">{{ $data['name'] }}</h3>
                        <p class="text-sm text-white">{{ $data['main']['temp'] }}°C</p>
                        <p class="text-sm text-white">{{ $data['weather'][0]['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="calendar-container">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4">
                    Weather Calendar for 1 Week in {{ $currentDate ? $currentDate->format('F Y') : 'Current Month' }}
                    @if($timezone)
                        <span class="text-sm text-gray-500">(Timezone: {{ $timezone }})</span>
                    @endif
                </h2>
                <table class="calendar">
                    <thead class="text-gray-700">
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($currentDate && $monthlyForecast)
                            @php
                                $daysInMonth = $currentDate->daysInMonth;
                                $firstDayOfWeek = $currentDate->copy()->startOfMonth()->dayOfWeek;
                                $day = 1;
                            @endphp
                            @for ($i = 0; $i < 6; $i++)
                                <tr>
                                    @for ($j = 0; $j < 7; $j++)
                                        <td class="bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                            @if (($i == 0 && $j >= $firstDayOfWeek) || ($i > 0 && $day <= $daysInMonth))
                                                <div class="font-semibold">{{ $day }}</div>
                                                @php
                                                    $date = $currentDate->copy()->startOfMonth()->addDays($day - 1)->format('Y-m-d');
                                                    $forecast = $monthlyForecast[$date] ?? null;
                                                @endphp
                                                @if($forecast)
                                                    <div class="weather-icon">{{ $forecast['icon'] }}</div>
                                                    <div class="temperature">{{ round($forecast['temp']) }}°C</div>
                                                @endif
                                                @php $day++; @endphp
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                                @if ($day > $daysInMonth)
                                    @break
                                @endif
                            @endfor
                        @else
                            <tr>
                                <td colspan="7" class="text-center py-4">Please select a city to view the calendar</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        function refreshWeather() {
        document.getElementById('city').value = '';
        document.getElementById('cityName').textContent = '....';
        document.getElementById('temperature').textContent = '-';
        document.getElementById('weatherDescription').textContent = '-';
        document.getElementById('humidity').textContent = '-';
        document.getElementById('windSpeed').textContent = '-';
        
        // Optionally, you can also clear the error message if it exists
        var errorDiv = document.querySelector('[role="alert"]');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    @if(isset($weather))
    document.getElementById('cityName').textContent = '{{ $weather['name'] }}';
    document.getElementById('temperature').textContent = '{{ $weather['main']['temp'] }}°C';
    document.getElementById('weatherDescription').textContent = '{{ $weather['weather'][0]['description'] }}';
    document.getElementById('humidity').textContent = '{{ $weather['main']['humidity'] }}%';
    document.getElementById('windSpeed').textContent = '{{ $weather['wind']['speed'] }} m/s';
    @endif

    const carousel = document.querySelector('.carousel-inner');
    const items = document.querySelectorAll('.carousel-item');
    let currentPosition = 0;
    const itemWidth = items[1].offsetWidth + 0; // Width + margin
    const speed = 0.5; // Kecepatan pergerakan (dalam pixel per frame)

    function moveCarousel() {
        currentPosition -= speed;
        if (currentPosition <= -itemWidth) {
            // Pindahkan item pertama ke akhir
            carousel.appendChild(carousel.firstElementChild);
            currentPosition += itemWidth;
        }
        carousel.style.transform = `translateX(${currentPosition}px)`;
        requestAnimationFrame(moveCarousel);
    }

    requestAnimationFrame(moveCarousel);

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
    </script>
</body>
</html>