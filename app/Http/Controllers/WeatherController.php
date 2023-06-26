<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index(string $location): array
    {   
        $url = 'http://api.weatherapi.com/v1/forecast.json';
     
        $headers = [
            'key' => env('API_KEY_WEATHER')
        ];

        $params = [
            'q'      => $location,
            'days'   => 1,
            'aqi'    => 'no',
            'alerts' => 'no'
        ];

        $response = Http::acceptJson()
            ->withHeaders($headers)
            ->get($url, $params)
            ->json();
            
        if (!empty($response)) {
            $formattedResponse = [
                'condition' => [
                    'icon' => $response['current']['condition']['icon'],
                    'text' => $response['current']['condition']['text']
                ],
                'celcius' => $response['current']['temp_c']
            ];

            return $formattedResponse;
        }
        
        return [];
    }
}
