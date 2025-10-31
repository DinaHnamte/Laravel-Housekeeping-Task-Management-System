<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    /**
     * Geocode an address to get latitude and longitude
     *
     * @param string $address
     * @return array|null ['latitude' => float, 'longitude' => float]
     */
    public function geocode(string $address): ?array
    {
        if (empty(trim($address))) {
            return null;
        }

        // Try primary provider first, then fallback
        $result = $this->geocodeWithOpenCage($address) ?? $this->geocodeWithNominatim($address);

        if (!$result) {
            Log::warning('Geocoding failed: No results found for address', ['address' => $address]);
        }

        return $result;
    }

    /**
     * Geocode an address and return structured address components
     *
     * @param string $address
     * @return array|null ['latitude' => float, 'longitude' => float, 'address_components' => array]
     */
    public function geocodeWithDetails(string $address): ?array
    {
        if (empty(trim($address))) {
            return null;
        }

        // Try primary provider first, then fallback
        $result = $this->geocodeWithOpenCage($address, true) ?? $this->geocodeWithNominatim($address, true);

        if (!$result) {
            Log::warning('Geocoding with details failed: No results found for address', ['address' => $address]);
        }

        return $result;
    }

    /**
     * Geocode using OpenCage API
     *
     * @param string $address
     * @param bool $withDetails
     * @return array|null
     */
    protected function geocodeWithOpenCage(string $address, bool $withDetails = false): ?array
    {
        $apiKey = config('services.geocoding.opencage.api_key');

        // Skip OpenCage if no API key is configured
        if (empty($apiKey)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get('https://api.opencagedata.com/geocode/v1/json', [
                'key' => $apiKey,
                'q' => $address,
                'limit' => 1,
                'no_annotations' => 1,
            ]);

            if ($response->successful() && $response->json()) {
                $data = $response->json();

                if (isset($data['results']) && !empty($data['results'])) {
                    $result = $data['results'][0];
                    $geometry = $result['geometry'] ?? [];

                    $responseData = [
                        'latitude' => (float) ($geometry['lat'] ?? 0),
                        'longitude' => (float) ($geometry['lng'] ?? 0),
                    ];

                    if ($withDetails) {
                        $components = $this->extractOpenCageComponents($result['components'] ?? []);
                        $responseData['address_components'] = $components;
                    }

                    return $responseData;
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::debug('OpenCage geocoding error: ' . $e->getMessage(), [
                'address' => $address,
                'exception' => $e
            ]);
            return null;
        }
    }

    /**
     * Geocode using Nominatim API (fallback)
     *
     * @param string $address
     * @param bool $withDetails
     * @return array|null
     */
    protected function geocodeWithNominatim(string $address, bool $withDetails = false): ?array
    {
        try {
            $response = Http::timeout(10)->get('https://nominatim.openstreetmap.org/search', [
                'q' => $address,
                'format' => 'json',
                'limit' => 1,
                'addressdetails' => $withDetails ? 1 : 0,
            ]);

            if ($response->successful() && $response->json()) {
                $results = $response->json();

                if (!empty($results)) {
                    $result = $results[0];

                    $responseData = [
                        'latitude' => (float) $result['lat'],
                        'longitude' => (float) $result['lon'],
                    ];

                    if ($withDetails && isset($result['address'])) {
                        $addressComponents = $this->extractNominatimComponents($result['address']);
                        $responseData['address_components'] = $addressComponents;
                    }

                    return $responseData;
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::debug('Nominatim geocoding error: ' . $e->getMessage(), [
                'address' => $address,
                'exception' => $e
            ]);
            return null;
        }
    }

    /**
     * Extract structured address components from OpenCage data
     *
     * @param array $components
     * @return array
     */
    protected function extractOpenCageComponents(array $components): array
    {
        return [
            'street' => $components['road'] ?? $components['street'] ?? null,
            'house_number' => $components['house_number'] ?? null,
            'neighborhood' => $components['neighbourhood'] ?? $components['suburb'] ?? null,
            'suburb' => $components['suburb'] ?? null,
            'city' => $components['city'] ?? $components['town'] ?? $components['village'] ?? null,
            'state' => $components['state'] ?? $components['region'] ?? null,
            'postcode' => $components['postcode'] ?? null,
            'country' => $components['country'] ?? null,
        ];
    }

    /**
     * Extract structured address components from Nominatim address data
     *
     * @param array $addressData
     * @return array
     */
    protected function extractNominatimComponents(array $addressData): array
    {
        $components = [
            'street' => null,
            'house_number' => null,
            'neighborhood' => null,
            'suburb' => null,
            'city' => null,
            'state' => null,
            'postcode' => null,
            'country' => null,
        ];

        // Map Nominatim fields to our components
        $mapping = [
            'house_number' => 'house_number',
            'road' => 'street',
            'neighbourhood' => 'neighborhood',
            'suburb' => 'suburb',
            'city' => 'city',
            'state' => 'state',
            'postcode' => 'postcode',
            'country' => 'country',
        ];

        foreach ($mapping as $nominatimKey => $ourKey) {
            $components[$ourKey] = $addressData[$nominatimKey] ?? null;

            // Fallback for city
            if ($ourKey === 'city' && empty($components[$ourKey])) {
                $components['city'] = $addressData['town'] ?? $addressData['city_district'] ?? null;
            }

            // Fallback for state
            if ($ourKey === 'state' && empty($components[$ourKey])) {
                $components['state'] = $addressData['state_district'] ?? null;
            }
        }

        return $components;
    }

    /**
     * Reverse geocode coordinates to get an address
     *
     * @param float $latitude
     * @param float $longitude
     * @return string|null
     */
    public function reverseGeocode(float $latitude, float $longitude): ?string
    {
        // Try OpenCage first, then Nominatim
        $result = $this->reverseGeocodeWithOpenCage($latitude, $longitude)
                  ?? $this->reverseGeocodeWithNominatim($latitude, $longitude);

        if (!$result) {
            Log::warning('Reverse geocoding failed: No results found for coordinates', [
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
        }

        return $result;
    }

    /**
     * Reverse geocode using OpenCage
     *
     * @param float $latitude
     * @param float $longitude
     * @return string|null
     */
    protected function reverseGeocodeWithOpenCage(float $latitude, float $longitude): ?string
    {
        $apiKey = config('services.geocoding.opencage.api_key');

        if (empty($apiKey)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get('https://api.opencagedata.com/geocode/v1/json', [
                'key' => $apiKey,
                'q' => "{$latitude},{$longitude}",
                'limit' => 1,
            ]);

            if ($response->successful() && $response->json()) {
                $data = $response->json();

                if (isset($data['results']) && !empty($data['results'])) {
                    return $data['results'][0]['formatted'] ?? null;
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::debug('OpenCage reverse geocoding error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Reverse geocode using Nominatim (fallback)
     *
     * @param float $latitude
     * @param float $longitude
     * @return string|null
     */
    protected function reverseGeocodeWithNominatim(float $latitude, float $longitude): ?string
    {
        try {
            $response = Http::timeout(10)->get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $latitude,
                'lon' => $longitude,
                'format' => 'json',
                'addressdetails' => 1,
            ]);

            if ($response->successful() && $response->json()) {
                $result = $response->json();

                if (isset($result['display_name'])) {
                    return $result['display_name'];
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::debug('Nominatim reverse geocoding error: ' . $e->getMessage());
            return null;
        }
    }
}
