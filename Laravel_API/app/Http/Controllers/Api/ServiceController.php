<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProviderProfile;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'local_service');
        $popular = $request->boolean('popular');
        $recommended = $request->boolean('recommended');

        $query = Service::query()
            ->where('is_active', true)
            ->where('type', $type)
            ->with(['provider', 'category']);

        if ($popular) {
            $query->where('is_featured', true);
        } elseif ($recommended) {
            $query->inRandomOrder();
        }

        return response()->json([
            'status' => true,
            'data' => $query->paginate(20),
        ]);
    }

    public function providerIndex(Request $request)
    {
        $user = $request->user();
        $type = $request->query('type');

        $query = Service::query()
            ->where('provider_id', $user->id)
            ->latest();

        if ($type) {
            $query->where('type', $type);
        }

        return response()->json([
            'status' => true,
            'data' => $query->get(),
        ]);
    }

    public function providerStore(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'provider') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'type' => ['required', Rule::in(['local_service', 'freelancer'])],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'location_type' => ['nullable', Rule::in(['provider', 'customer'])],
            'image' => ['nullable', 'string'],
            'gallery' => ['nullable', 'array'],
        ]);

        $profile = ProviderProfile::firstOrCreate(['user_id' => $user->id]);
        if ($profile->mode && $profile->mode !== $validated['type']) {
            return response()->json([
                'status' => false,
                'message' => 'Provider mode mismatch',
            ], 422);
        }

        $category = Category::find($validated['category_id']);
        if ($category && $category->type && $category->type !== $validated['type']) {
            return response()->json([
                'status' => false,
                'message' => 'Category type mismatch',
            ], 422);
        }

        $service = Service::create([
            'provider_id' => $user->id,
            'category_id' => $validated['category_id'],
            'type' => $validated['type'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(6),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? 60,
            'image' => $validated['image'] ?? null,
            'gallery' => $validated['gallery'] ?? null,
            'metadata' => $validated['metadata'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'location_type' => $validated['location_type'] ?? 'provider',
            'is_active' => true,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Created successfully',
            'data' => $service->load(['category']),
        ], 201);
    }
}

