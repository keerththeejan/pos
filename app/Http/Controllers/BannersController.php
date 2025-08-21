<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class BannersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::orderByDesc('id')->paginate(12);

        if (view()->exists('banners.index')) {
            return view('banners.index', compact('banners'));
        }

        return response()->json(['data' => $banners]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banner = null;
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
        try {
            $validated = $request->validate([
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'is_active' => ['nullable', 'boolean'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            ]);

            $banner = new Banner();
            $banner->title = $validated['title'] ?? null;
            $banner->description = $validated['description'] ?? null;
            $banner->is_active = $request->boolean('is_active', true);

            $new = $this->saveImage($request, 'image');
            if ($new) {
                $banner->image = $new;
            }

            $banner->save();

            return redirect()->route('banners.index')
                ->with('status', 'Banner created successfully');
        } catch (QueryException $e) {
            Log::error('Banner store failed (DB)', ['message' => $e->getMessage()]);
            $msg = app()->environment('local') ? $e->getMessage() : 'Save failed due to database error.';
            return back()->withErrors(['error' => $msg])->withInput();
        } catch (\Throwable $e) {
            Log::error('Banner store failed', ['message' => $e->getMessage()]);
            $msg = app()->environment('local') ? $e->getMessage() : 'Save failed. Please check fields and try again.';
            return back()->withErrors(['error' => $msg])->withInput();
        }
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
        try {
            $validated = $request->validate([
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'is_active' => ['nullable', 'boolean'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            ]);

            $updates = [
                'title' => $validated['title'] ?? $banner->title,
                'description' => $validated['description'] ?? $banner->description,
                'is_active' => $request->boolean('is_active', $banner->is_active),
            ];

            $new = $this->saveImage($request, 'image');
            if ($new) {
                $this->deleteImageQuietly($banner->image);
                $updates['image'] = $new;
            }

            $banner->update($updates);

            return back()->with('status', 'Banners updated');
        } catch (QueryException $e) {
            Log::error('Banner update failed (DB)', ['message' => $e->getMessage()]);
            $msg = app()->environment('local') ? $e->getMessage() : 'Update failed due to database error.';
            return back()->withErrors(['error' => $msg])->withInput();
        } catch (\Throwable $e) {
            Log::error('Banner update failed', ['message' => $e->getMessage()]);
            $msg = app()->environment('local') ? $e->getMessage() : 'Update failed. Please check fields and try again.';
            return back()->withErrors(['error' => $msg])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        $this->deleteImageQuietly($banner->image);
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
        if (! is_dir($dir) || ! is_writable($dir)) {
            throw new \RuntimeException('Upload directory is not writable: ' . $dir);
        }

        $name = uniqid('banner_') . '.' . $file->getClientOriginalExtension();
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
