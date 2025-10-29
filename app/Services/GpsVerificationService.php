<?php

namespace App\Services;

use App\Models\Property;

class GpsVerificationService
{
    /**
     * Calculate distance between two GPS coordinates in meters (Haversine formula)
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Verify if GPS coordinates are within allowed radius of property
     */
    public function verifyLocation(Property $property, float $latitude, float $longitude): array
    {
        if (!$property->latitude || !$property->longitude) {
            return [
                'verified' => false,
                'message' => 'Property location not configured. Please contact administrator.'
            ];
        }

        $distance = $this->calculateDistance(
            $property->latitude,
            $property->longitude,
            $latitude,
            $longitude
        );

        $allowedRadius = $property->gps_radius_meters ?? 50; // Default 50 meters

        $verified = $distance <= $allowedRadius;

        return [
            'verified' => $verified,
            'distance' => round($distance, 2),
            'allowed_radius' => $allowedRadius,
            'message' => $verified
                ? "Location verified. You are within {$allowedRadius}m of the property."
                : "Location verification failed. You are {$distance}m away from the property (allowed: {$allowedRadius}m)."
        ];
    }
}

