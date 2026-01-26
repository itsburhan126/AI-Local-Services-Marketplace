<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['category', 'provider'])
            ->latest()
            ->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = Category::all();
        $providers = User::where('role', 'provider')->get();
        return view('admin.services.create', compact('categories', 'providers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'provider_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $data['image'] = Storage::url($path);
        }

        // Handle Gallery
        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('services/gallery', 'public');
                $gallery[] = Storage::url($path);
            }
            $data['gallery'] = $gallery;
        }

        Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        $service->load(['category', 'provider', 'provider.providerProfile', 'reviews.customer']);
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $categories = Category::all();
        $providers = User::where('role', 'provider')->get();
        return view('admin.services.edit', compact('service', 'categories', 'providers'));
    }

    public function update(Request $request, Service $service)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'provider_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $data['image'] = Storage::url($path);
        }

        if ($request->hasFile('gallery')) {
            $gallery = $service->gallery ?? [];
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('services/gallery', 'public');
                $gallery[] = Storage::url($path);
            }
            $data['gallery'] = $gallery;
        }

        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
