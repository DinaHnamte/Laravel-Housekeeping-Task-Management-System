<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Room;
use App\Models\Task;
use App\Models\Image;
use App\Services\GeocodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    protected $geocodingService;

    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $perPage = (int) ($request->get('per_page') ?? 10);

        // Filter properties based on user role
        if ($user->hasRole('Admin')) {
            // Admins can see all properties
            $properties = Property::with('rooms', 'user', 'headerImage')->paginate($perPage);
        } elseif ($user->hasRole('Owner')) {
            // Owners can only see their own properties
            $properties = Property::with('rooms', 'headerImage')->where('user_id', $user->id)->paginate($perPage);
        } else {
            // Other roles see no properties
            $properties = collect()->paginate($perPage);
        }

        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
            'gps_radius_meters' => 'nullable|integer|min:10|max:1000',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Assign property to the current user (Owner)
        $validated['user_id'] = auth()->id();

        // Build address from individual fields if address is not provided
        if (empty($validated['address'])) {
            $parts = [
                $validated['house_number'] ?? null,
                $validated['street'] ?? null,
                $validated['city'] ?? null,
                $validated['state'] ?? null,
                $validated['postcode'] ?? null,
                $validated['country'] ?? null,
            ];
            $validated['address'] = implode(', ', array_filter($parts));
        }

        // Build address for geocoding (without house number for better results)
        $addressForGeocoding = $validated['address'];
        if (!empty($validated['house_number'])) {
            // Remove house number from the beginning of the address for geocoding
            $addressForGeocoding = preg_replace('/^' . preg_quote($validated['house_number'], '/') . ',\s*/', '', $validated['address']);
        }

        // Geocode the address to get latitude and longitude (without house number)
        if (!empty($addressForGeocoding)) {
            $geocodeResult = $this->geocodingService->geocodeWithDetails($addressForGeocoding);
            if ($geocodeResult) {
                $validated['latitude'] = $geocodeResult['latitude'];
                $validated['longitude'] = $geocodeResult['longitude'];

                // Only override address components if they weren't provided by the user
                if (isset($geocodeResult['address_components'])) {
                    $components = $geocodeResult['address_components'];
                    $validated['street'] = $validated['street'] ?? $components['street'];
                    $validated['house_number'] = $validated['house_number'] ?? $components['house_number'];
                    $validated['neighborhood'] = $components['neighborhood'];
                    $validated['suburb'] = $components['suburb'];
                    $validated['city'] = $validated['city'] ?? $components['city'];
                    $validated['state'] = $validated['state'] ?? $components['state'];
                    $validated['postcode'] = $validated['postcode'] ?? $components['postcode'];
                    $validated['country'] = $validated['country'] ?? $components['country'];
                }
            }
        }

        // Set default GPS radius if not provided
        if (!isset($validated['gps_radius_meters'])) {
            $validated['gps_radius_meters'] = 50;
        }

        // Handle header image upload
        if ($request->hasFile('header_image')) {
            $image = $request->file('header_image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images/properties', $filename, 'public');

            $imageRecord = Image::create([
                'uri' => $path,
                'name' => $image->getClientOriginalName(),
            ]);

            $validated['header_image_id'] = $imageRecord->id;
        }

        $property = Property::create($validated);

        // Automatically assign default rooms and tasks to new property
        $this->assignDefaultRoomsAndTasks($property);

        return redirect()
            ->route('properties.index')
            ->with('success', 'Property created successfully!');
    }

    public function edit(Property $property)
    {
        $property->load('headerImage');
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
            'gps_radius_meters' => 'nullable|integer|min:10|max:1000',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Build address from individual fields if address is not provided
        if (empty($validated['address'])) {
            $parts = [
                $validated['house_number'] ?? null,
                $validated['street'] ?? null,
                $validated['city'] ?? null,
                $validated['state'] ?? null,
                $validated['postcode'] ?? null,
                $validated['country'] ?? null,
            ];
            $validated['address'] = implode(', ', array_filter($parts));
        }

        // Build address for geocoding (without house number for better results)
        $addressForGeocoding = $validated['address'];
        if (!empty($validated['house_number'])) {
            // Remove house number from the beginning of the address for geocoding
            $addressForGeocoding = preg_replace('/^' . preg_quote($validated['house_number'], '/') . ',\s*/', '', $validated['address']);
        }

        // Geocode the address if any address fields have changed (without house number)
        if (!empty($addressForGeocoding)) {
            $geocodeResult = $this->geocodingService->geocodeWithDetails($addressForGeocoding);
            if ($geocodeResult) {
                $validated['latitude'] = $geocodeResult['latitude'];
                $validated['longitude'] = $geocodeResult['longitude'];

                // Only override address components if they weren't provided by the user
                if (isset($geocodeResult['address_components'])) {
                    $components = $geocodeResult['address_components'];
                    $validated['street'] = $validated['street'] ?? $components['street'];
                    $validated['house_number'] = $validated['house_number'] ?? $components['house_number'];
                    $validated['neighborhood'] = $components['neighborhood'];
                    $validated['suburb'] = $components['suburb'];
                    $validated['city'] = $validated['city'] ?? $components['city'];
                    $validated['state'] = $validated['state'] ?? $components['state'];
                    $validated['postcode'] = $validated['postcode'] ?? $components['postcode'];
                    $validated['country'] = $validated['country'] ?? $components['country'];
                }
            }
        }

        // Set default GPS radius if not provided
        if (!isset($validated['gps_radius_meters'])) {
            $validated['gps_radius_meters'] = 50;
        }

        // Handle header image upload
        if ($request->hasFile('header_image')) {
            // Delete old image if exists
            if ($property->header_image_id) {
                $oldImage = Image::find($property->header_image_id);
                if ($oldImage && Storage::disk('public')->exists($oldImage->uri)) {
                    Storage::disk('public')->delete($oldImage->uri);
                    $oldImage->delete();
                }
            }

            $image = $request->file('header_image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images/properties', $filename, 'public');

            $imageRecord = Image::create([
                'uri' => $path,
                'name' => $image->getClientOriginalName(),
            ]);

            $validated['header_image_id'] = $imageRecord->id;
        }

        $property->update($validated);

        return redirect()
            ->route('properties.index')
            ->with('success', 'Property updated successfully!');
    }

    public function show(Property $property)
    {
        $property->load('headerImage');
        return view('properties.show', compact('property'));
    }

    /**
     * Assign default rooms and tasks to a property
     */
    protected function assignDefaultRoomsAndTasks(Property $property): void
    {
        // Find a property with default rooms to copy from, or use common defaults
        $sourceProperty = Property::whereHas('rooms', function($query) {
            $query->where('is_default', true);
        })->first();

        if ($sourceProperty) {
            // Copy default rooms and tasks from source property
            $defaultRooms = $sourceProperty->rooms()->where('is_default', true)->get();

            foreach ($defaultRooms as $defaultRoom) {
                $room = Room::create([
                    'name' => $defaultRoom->name,
                    'property_id' => $property->id,
                    'is_default' => true,
                ]);

                // Copy default tasks for this room
                $defaultTasks = $defaultRoom->tasks()->where('is_default', true)->get();
                foreach ($defaultTasks as $defaultTask) {
                    Task::create([
                        'property_id' => $property->id,
                        'room_id' => $room->id,
                        'task' => $defaultTask->task,
                        'is_default' => true,
                    ]);
                }
            }
        } else {
            // Create common default rooms and tasks if no defaults exist
            $commonRooms = [
                'Living Room' => ['Vacuum floor', 'Dust furniture', 'Clean windows'],
                'Bedroom' => ['Make bed', 'Vacuum floor', 'Dust surfaces'],
                'Bathroom' => ['Clean toilet', 'Clean shower', 'Clean sink', 'Restock supplies'],
                'Kitchen' => ['Clean countertops', 'Clean sink', 'Clean appliances'],
                'Dining Room' => ['Vacuum floor', 'Dust furniture', 'Clean table'],
            ];

            foreach ($commonRooms as $roomName => $tasks) {
                $room = Room::create([
                    'name' => $roomName,
                    'property_id' => $property->id,
                    'is_default' => true,
                ]);

                foreach ($tasks as $taskName) {
                    Task::create([
                        'property_id' => $property->id,
                        'room_id' => $room->id,
                        'task' => $taskName,
                        'is_default' => true,
                    ]);
                }
            }
        }
    }
}
