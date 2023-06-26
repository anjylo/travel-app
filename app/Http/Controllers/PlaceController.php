<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

use App\Services\LocationService;

class PlaceController extends Controller
{
    public function index(LocationService $location): array
    {     
        $url = 'https://api.foursquare.com/v3/places/nearby';
     
        $headers = [
            'accept' => 'application/json',
            'Authorization' => env('API_KEY_FOURSQUARE')
        ];

        $params = [
            'll' => $location->getCoordinates(),
            'limit' => 5
        ];

        $response = Http::acceptJson()
            ->withHeaders($headers)
            ->get($url, $params)
            ->json();

        if (isset($response['results']) && !empty($response['results'])) {
            $filteredResponse = array_map(function ($data) {
                return [
                    'id'         => $data['fsq_id'],
                    'name'       => $data['name'],
                    'categories' => $this->extractCategories($data['categories']),
                    'img'        => $this->getPicture($data['fsq_id']),
                    'address'    => $data['location']['formatted_address'],
                    'coordinates' => [
                        'latitude'   => $data['geocodes']['main']['latitude'],
                        'longitude'  => $data['geocodes']['main']['longitude']
                    ]
                ];
            }, $response['results']);

            return $filteredResponse;
        }

        return [];
    }

    public function getReviews(string $id): array
    {
        $url = "https://api.foursquare.com/v3/places/$id/tips";

        $headers = [
            'Authorization' => env('API_KEY_FOURSQUARE')
        ];

        $params = [
            'sort' => 'POPULAR',
            'limit' => 3
        ];

        $response = Http::acceptJson()
            ->withHeaders($headers)
            ->get($url, $params)
            ->json();

        if (!empty($response)) {
            $tips = array_map(function ($tip) {
                return [
                    'created_at' => date('F j, Y', strtotime($tip['created_at'])),
                    'text' => $tip['text']
                ];
            }, $response);

            return $tips;
        }

        return [];
    }
    
    private function getPicture(string $id): string
    {   
        $url = "https://api.foursquare.com/v3/places/$id/photos";
     
        $headers = [
            'accept' => 'application/json',
            'Authorization' => env('API_KEY_FOURSQUARE')
        ];

        $params = [
            'sort' => 'POPULAR',
            'limit' => 1
        ];

        $response = Http::acceptJson()
            ->withHeaders($headers)
            ->get($url, $params)
            ->json();

        $picture = 'https://placehold.co/600x400?text=No+image+available';

        if (!empty($response)) {
            $picture = $response[0]['prefix'] . '600x400' . $response[0]['suffix'];
        }

        return $picture;
    }

    private function extractCategories(array $categories): array
    {
        if (!empty($categories)) {
            $response = array_map(function ($category) {
                return $category['name'];
            }, $categories);

            return $response;
        }

        return [];
    }
}
