<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLocationRequest;
use App\Http\Requests\GetNearestLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function getNearestLocation(GetNearestLocationRequest $request)
    {
        Log::debug('Request Payload:', $request->all());
        $postcode = $request->query('postcode');
    
        // Geocode the user's postcode using postcodes.io
        $client = new Client();
        $response = $client->get("https://api.postcodes.io/postcodes/{$postcode}");
        $data = json_decode($response->getBody(), true);
    
        if ($response->getStatusCode() !== 200 || !isset($data['result'])) {
            return response()->json(['error' => 'Invalid postcode'], 400);
        }
    
        $latitude = $data['result']['latitude'];
        $longitude = $data['result']['longitude'];
    
        // Find the nearest location using the Haversine formula
        $nearestLocation = Location::selectRaw(
            'locations.*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
            [$latitude, $longitude, $latitude]
        )->orderBy('distance')->first();
        
        // Get address for the nearest location's postcode
        $locationResponse = $client->get("https://api.postcodes.io/postcodes/{$nearestLocation->postcode}");
        $locationData = json_decode($locationResponse->getBody(), true);
        
        if ($locationResponse->getStatusCode() === 200 && isset($locationData['result'])) {
            $nearestLocation->address_details = [
                'postcode' => $locationData['result']['postcode'],
                'district' => $locationData['result']['admin_district'],
                'ward' => $locationData['result']['admin_ward'],
                'parish' => $locationData['result']['parish'] ?? null,
                'country' => $locationData['result']['country'],
                'county' => $locationData['result']['admin_county'] ?? null,
            ];
        }
        
        return new LocationResource($nearestLocation);
    }

    public function createNewLocation(CreateLocationRequest $request)
    {
        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $validatedData = $request->validated();
    
        $openingTimes = json_decode($validatedData['opening_times'], true);
        $closingTimes = json_decode($validatedData['closing_times'], true);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON format for opening or closing times'], 400);
        }
    
        $processedOpeningTimes = [];
        $processedClosingTimes = [];
    
        foreach ($daysOfWeek as $day) {
            $openingTime = $openingTimes[$day] ?? null;
            $closingTime = $closingTimes[$day] ?? null;
    
            if (is_null($openingTime) && is_null($closingTime)) {
                $processedOpeningTimes[$day] = 'Closed';
                $processedClosingTimes[$day] = 'Closed';
            }
            elseif (is_null($openingTime) || is_null($closingTime)) {
                return response()->json(['error' => "Incomplete data for $day: both opening and closing times are required"], 400);
            }
            else {
                $processedOpeningTimes[$day] = $openingTime;
                $processedClosingTimes[$day] = $closingTime;
            }
        }
    
        $client = new Client();
        $response = $client->get("https://api.postcodes.io/postcodes/{$validatedData['postcode']}");
        $data = json_decode($response->getBody(), true);
    
        if ($response->getStatusCode() !== 200 || !isset($data['result'])) {
            return response()->json(['error' => 'Invalid postcode'], 400);
        }
    
        $latitude = $data['result']['latitude'];
        $longitude = $data['result']['longitude'];
    
        $location = Location::create([
            'postcode' => $validatedData['postcode'],
            'latitude' => $latitude,
            'longitude' => $longitude,
            'opening_times' => json_encode($processedOpeningTimes),
            'closing_times' => json_encode($processedClosingTimes),
        ]);
    
        return new LocationResource($location);
    }
}
