<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'nullable|string|max:255',
        ]);

        $image = $request->file('image');
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

        // Store the image in the public disk
        $path = $image->storeAs('images', $filename, 'public');

        // Create image record
        $imageRecord = Image::create([
            'uri' => $path,
            'name' => $request->name ?? $image->getClientOriginalName(),
        ]);

        return response()->json([
            'success' => true,
            'image' => $imageRecord,
            'url' => Storage::url($path)
        ]);
    }

    public function delete(Image $image)
    {
        // Delete the file from storage
        if (Storage::disk('public')->exists($image->uri)) {
            Storage::disk('public')->delete($image->uri);
        }

        // Delete the database record
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully'
        ]);
    }

    public function show(Image $image)
    {
        return response()->json([
            'success' => true,
            'image' => $image,
            'url' => Storage::url($image->uri)
        ]);
    }
}
