<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BannersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Treat banners as a singleton configuration entry
        $banner = Banner::first();

        // If you have a view, return it; otherwise return JSON for now
        if (view()->exists('banners.index')) {
            return view('banners.index', compact('banner'));
        }

        return response()->json([
            'data' => $banner,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banner = Banner::first();
        if (view()->exists('banners.create')) {
            return view('banners.create', compact('banner'));
        }
        return response()->json(['message' => 'Create view not found'], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'image1' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'image2' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'image3' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'image4' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        $banner = Banner::firstOrCreate([], [
            'title' => $validated['title'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Upload images and update paths
        $updates = [
            'title' => $validated['title'] ?? $banner->title,
            'is_active' => $request->boolean('is_active', $banner->is_active ?? true),
        ];

        foreach (['image1', 'image2', 'image3', 'image4'] as $key) {
            $new = $this->saveImage($request, $key);
            if ($new) {
                // delete old if exists
                $this->deleteImageQuietly($banner->{$key});
                $updates[$key] = $new;
            }
        }

        $banner->update($updates);

        return back()->with('status', 'Banners saved');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        if (view()->exists('banners.show')) {
            return view('banners.show', compact('banner'));
        }
        return response()->json(['data' => $banner]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        if (view()->exists('banners.edit')) {
            return view('banners.edit', compact('banner'));
        }
        return response()->json(['message' => 'Edit view not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'image1' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'image2' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'image3' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'image4' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        $updates = [
            'title' => $validated['title'] ?? $banner->title,
            'is_active' => $request->boolean('is_active', $banner->is_active),
        ];

        foreach (['image1', 'image2', 'image3', 'image4'] as $key) {
            $new = $this->saveImage($request, $key);
            if ($new) {
                $this->deleteImageQuietly($banner->{$key});
                $updates[$key] = $new;
            }
        }

        $banner->update($updates);

        return back()->with('status', 'Banners updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        foreach (['image1', 'image2', 'image3', 'image4'] as $key) {
            $this->deleteImageQuietly($banner->{$key});
        }
        $banner->delete();
        return back()->with('status', 'Banners deleted');
    }

    /**
     * Persist uploaded image file and return stored filename or null.
     */
    private function saveImage(Request $request, string $field): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }
        $file = $request->file($field);
        if (! $file->isValid()) {
            return null;
        }

        $dir = public_path(Banner::uploadDir());
        if (! File::exists($dir)) {
            File::makeDirectory($dir, 0775, true, true);
        }

        $name = uniqid($field . '_') . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);
        return $name;
    }

    /**
     * Quietly delete an image file from disk if present.
     */
    private function deleteImageQuietly(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }
        $path = public_path(Banner::uploadDir() . DIRECTORY_SEPARATOR . $filename);
        try {
            if (File::exists($path)) {
                File::delete($path);
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }

}
