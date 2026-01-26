<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\FreelancerPortfolio;
use App\Models\FreelancerPortfolioImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'provider') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $user->freelancerPortfolios()->with('images')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'provider') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|string|max:255',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        try {
            DB::beginTransaction();

            // Store the first image as the cover image (for backward compatibility)
            $coverPath = $request->file('images')[0]->store('freelancer/portfolio', 'public');
            $coverUrl = asset('storage/' . $coverPath);

            $portfolio = FreelancerPortfolio::create([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'link' => $validated['link'] ?? null,
                'image_path' => $coverUrl,
            ]);

            // Store all images in the related table
            foreach ($request->file('images') as $image) {
                $path = $image->store('freelancer/portfolio', 'public');
                $url = asset('storage/' . $path);

                FreelancerPortfolioImage::create([
                    'freelancer_portfolio_id' => $portfolio->id,
                    'image_path' => $url,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Portfolio added successfully',
                'data' => $portfolio->load('images'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add portfolio: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, FreelancerPortfolio $portfolio)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'provider') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ((int) $portfolio->user_id !== (int) $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $portfolio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Portfolio deleted successfully',
        ]);
    }
}
