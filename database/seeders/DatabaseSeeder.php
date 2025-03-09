<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        if (($handle = fopen(database_path('seeders/location_data.csv'), 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $row = array_combine($header, $data);

                // Geocode the postcode
                $response = Http::get('https://api.postcodes.io/postcodes/' . trim($row['postcode']));

                if ($response->ok() && $response->json('result')) {
                    $geodata = $response->json('result');

                    $formatTimes = function ($time) {
                        return empty($time) ? 'Closed' : $time;
                    };

                    Location::create([
                        'postcode' => trim($row['postcode']),
                        'latitude' => $geodata['latitude'],
                        'longitude' => $geodata['longitude'],
                        'opening_times' => json_encode([
                            'monday'    => $formatTimes($row['open_Monday']),
                            'tuesday'   => $formatTimes($row['open_Tuesday']),
                            'wednesday' => $formatTimes($row['open_Wednesday']),
                            'thursday'  => $formatTimes($row['open_Thursday']),
                            'friday'    => $formatTimes($row['open_Friday']),
                            'saturday'  => $formatTimes($row['open_Saturday']),
                            'sunday'    => $formatTimes($row['open_Sunday']),
                        ]),
                        'closing_times' => json_encode([
                            'monday'    => $formatTimes($row['closed_Monday']),
                            'tuesday'   => $formatTimes($row['closed_Tuesday']),
                            'wednesday' => $formatTimes($row['closed_Wednesday']),
                            'thursday'  => $formatTimes($row['closed_Thursday']),
                            'friday'    => $formatTimes($row['closed_Friday']),
                            'saturday'  => $formatTimes($row['closed_Saturday']),
                            'sunday'    => $formatTimes($row['closed_Sunday']),
                        ]),
                    ]);
                }
            }
            fclose($handle);
        }
    }
}
