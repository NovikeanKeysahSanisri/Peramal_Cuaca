@extends('layouts.app')

@section('title', 'Weather Forecast')

@section('content')
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <header class="bg-blue-500 dark:bg-blue-800 p-4">
        <div class="flex justify-center items-center">
            <form method="GET" action="/" class="flex relative" id="weatherForm">
                <input type="text" name="city" id="city" placeholder="Enter city name" value="{{ $defaultCity }}" class="px-4 py-2 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <i class="fas fa-search"></i>
                </button>
                <div id="searchHistory" class="absolute top-full left-0 w-full bg-white shadow-md rounded-b-lg hidden">
                    
                </div>
            </form>
            <button onclick="refreshWeather()" class="bg-green-500 text-white px-4 py-2 rounded-r-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 ml-1">
                <i class="fas fa-sync-alt"></i>
            </button>
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
            document.getElementById('city').value = '{{ $defaultCity }}';
            document.getElementById('weatherForm').submit();
        }
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

        // theme
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            html.classList.add('dark');
        }

         // Search history functionality
         const cityInput = document.getElementById('city');
        const searchHistoryContainer = document.getElementById('searchHistory');
        let searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];

        function updateSearchHistory() {
            searchHistoryContainer.innerHTML = '';
            searchHistory.forEach((city, index) => {
                const item = document.createElement('div');
                item.className = 'p-2 hover:bg-gray-100 cursor-pointer flex justify-between items-center';
                item.innerHTML = `
                    <span onclick="selectCity('${city}')">${city}</span>
                    <button onclick="deleteHistoryItem(${index})" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                searchHistoryContainer.appendChild(item);
            });
        }

        function addToSearchHistory(city) {
            if (!searchHistory.includes(city)) {
                searchHistory.unshift(city);
                if (searchHistory.length > 5) {
                    searchHistory.pop();
                }
                localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
                updateSearchHistory();
            }
        }

        function selectCity(city) {
            cityInput.value = city;
            searchHistoryContainer.classList.add('hidden');
            document.getElementById('weatherForm').submit();
        }

        function deleteHistoryItem(index) {
            searchHistory.splice(index, 1);
            localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
            updateSearchHistory();

            // Send delete request to server
            fetch('/delete-search-history', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ city: searchHistory[index] })
            });
        }

        cityInput.addEventListener('focus', () => {
            updateSearchHistory();
            searchHistoryContainer.classList.remove('hidden');
        });

        cityInput.addEventListener('blur', () => {
            setTimeout(() => {
                searchHistoryContainer.classList.add('hidden');
            }, 200);
        });

        document.getElementById('weatherForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const city = cityInput.value.trim();
            if (city) {
                addToSearchHistory(city);
                e.target.submit();
            }
        });

        // Initialize search history
        updateSearchHistory();
    </script>
</body>
@endsection