<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WeatherController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $city = $request->input('city', $user ? $user->location : null);
        $apiKey = env('OPENWEATHER_API_KEY');

        // Fetch weather data for the searched city
        $weatherData = $this->getWeatherData($city, $apiKey);

        // Fetch weather data for 6 random cities
        $randomCities = ['Jakarta', 'Bandung', 'Bogor', 'Depok', 'Tanggerang', 'Bekasi', 'Rangkas', 'Karawang', 'Surabaya', 'Solo', 'Yogyakarta', 'lampung'];
        $carouselData = [];

        for ($i = 0; $i < 10; $i++) {
            $randomCity = $randomCities[array_rand($randomCities)];
            $cityData = $this->getWeatherData($randomCity, $apiKey);
            if ($cityData) {
                $cityData['background'] = $this->getWeatherBackground($cityData['weather'][0]['main']);
                $carouselData[] = $cityData;
            }
        }

        // Get current date and time for the searched city
        $timezone = null;
        $currentDate = null;
        $monthlyForecast = null;
        if ($weatherData) {
            $timezoneOffset = $weatherData['timezone'];
            $timezone = $this->getTimezoneString($timezoneOffset);
            $currentDate = Carbon::now($timezone);
            $monthlyForecast = $this->getMonthlyForecast($weatherData['coord']['lat'], $weatherData['coord']['lon'], $apiKey);
        }

        return view('weather', [
            'weather' => $weatherData,
            'carouselData' => $carouselData,
            'currentDate' => $currentDate,
            'timezone' => $timezone,
            'monthlyForecast' => $monthlyForecast,
            'defaultCity' => $city
        ]);
    }


    private function getWeatherData($city, $apiKey)
    {
        if (empty($city)) {
            return null;
        }

        $response = Http::get("http://api.openweathermap.org/data/2.5/weather", [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    private function getMonthlyForecast($lat, $lon, $apiKey)
    {
        $response = Http::get("http://api.openweathermap.org/data/2.5/forecast", [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);

        if ($response->failed()) {
            return null;
        }

        $forecast = $response->json()['list'];
        $dailyForecast = [];

        foreach ($forecast as $weatherData) {
            $date = Carbon::createFromTimestamp($weatherData['dt'])->format('Y-m-d');
            if (!isset($dailyForecast[$date])) {
                $dailyForecast[$date] = [
                    'temp' => $weatherData['main']['temp'],
                    'weather' => $weatherData['weather'][0]['main'],
                    'icon' => $this->getWeatherIcon($weatherData['weather'][0]['main'])
                ];
            }
        }

        return $dailyForecast;
    }

    private function getWeatherBackground($weatherMain)
    {
        $backgrounds = [
            'Clear' => 'bg-gradient-to-r from-blue-400 to-blue-200',
            'Clouds' => 'bg-gradient-to-r from-gray-400 to-gray-200',
            'Rain' => 'bg-gradient-to-r from-blue-500 to-blue-400',
            'Snow' => 'bg-gradient-to-r from-gray-200 to-white',
            'Thunderstorm' => 'bg-gradient-to-r from-gray-700 to-gray-500',
            'Drizzle' => 'bg-gradient-to-r from-blue-300 to-blue-100',
            'Mist' => 'bg-gradient-to-r from-gray-500 to-gray-100',
            'Fog' => 'bg-gradient-to-r from-gray-400 to gray-100s'
        ];

        return $backgrounds[$weatherMain] ?? 'bg-gradient-to-r from-gray-700 to-white';
    }

    private function getTimezoneString($offsetSeconds)
    {
        $hours = floor($offsetSeconds / 3600);
        $minutes = floor(($offsetSeconds % 3600) / 60);

        $sign = $hours >= 0 ? '+' : '-';
        $hours = abs($hours);
        $minutes = abs($minutes);

        return sprintf("GMT%s%02d:%02d", $sign, $hours, $minutes);
    }

    private function getWeatherIcon($weatherMain)
    {
        $icons = [
            'Clear' => '☀️',
            'Clouds' => '☁️',
            'Rain' => '🌧️',
            'Snow' => '❄️',
            'Thunderstorm' => '⛈️',
            'Drizzle' => '🌦️',
            'Mist' => '🌫️',
            'Fog' => '🌫️'
        ];

        return $icons[$weatherMain] ?? '🌈';
    }
    
    public function deleteSearchHistory(Request $request)
    {
        $city = $request->input('city');
        // In a real application, you might want to store the search history in the database
        // and delete it there. For now, we'll just return a success response.
        return response()->json(['success' => true]);
    }
}