<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Gig;
use App\Models\GigPackage;
use App\Models\GigExtra;
use App\Models\GigFaq;
use App\Models\GigOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GigController extends Controller
{
    public function index()
    {
        $gigs = Gig::where('provider_id', Auth::id())
            ->with(['packages', 'extras', 'serviceType', 'category', 'faqs'])
            ->withCount('bookings')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $gigs
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'service_type_id' => 'nullable|exists:service_types,id',
            'description' => 'required|string',
            'packages' => 'required', // JSON string or array
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimetypes:video/mp4,video/quicktime|max:51200', // 50MB
            'documents.*' => 'mimes:pdf|max:5120',
            'faqs' => 'nullable', // JSON string or array
        ]);

        try {
            DB::beginTransaction();

            // Handle Metadata & Tags
            $metadata = $request->metadata ? (is_string($request->metadata) ? json_decode($request->metadata, true) : $request->metadata) : [];
            $tags = $request->tags ? (is_string($request->tags) ? json_decode($request->tags, true) : $request->tags) : [];

            // Handle Files
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('gigs/thumbnails', 'public');
                $thumbnailPath = asset('storage/' . $path);
            }

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('gigs/images', 'public');
                    $imagePaths[] = asset('storage/' . $path);
                }
            }

            $videoPath = null;
            if ($request->hasFile('video')) {
                $path = $request->file('video')->store('gigs/videos', 'public');
                $videoPath = asset('storage/' . $path);
            }

            $documentPaths = [];
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $doc) {
                    $path = $doc->store('gigs/documents', 'public');
                    $documentPaths[] = asset('storage/' . $path);
                }
            }

            // Create Gig
            $gig = Gig::create([
                'provider_id' => Auth::id(),
                'category_id' => $request->category_id,
                'service_type_id' => $request->service_type_id,
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . Str::random(6),
                'description' => $request->description,
                'thumbnail_image' => $thumbnailPath,
                'images' => $imagePaths,
                'video' => $videoPath,
                'documents' => $documentPaths,
                'tags' => $tags,
                'metadata' => $metadata,
                'is_active' => true,
                'status' => 'pending',
            ]);

            // Sync Tags
            if (!empty($tags)) {
                $tagIds = [];
                foreach ($tags as $tagName) {
                    $tag = \App\Models\Tag::firstOrCreate(
                        ['name' => $tagName],
                        [
                            'slug' => Str::slug($tagName),
                            'created_by' => Auth::id()
                        ]
                    );
                    $tagIds[] = $tag->id;
                }
                $gig->relatedTags()->sync($tagIds);
            }

            // Create Packages
            $packages = is_string($request->packages) ? json_decode($request->packages, true) : $request->packages;
            if (is_array($packages)) {
                foreach ($packages as $pkg) {
                    $gig->packages()->create([
                        'tier' => $pkg['tier'],
                        'name' => $pkg['name'],
                        'description' => $pkg['description'],
                        'price' => $pkg['price'],
                        'delivery_days' => $pkg['delivery_days'],
                        'revisions' => $pkg['revisions'] ?? 0,
                        'source_code' => $pkg['source_code'] ?? false,
                        'features' => $pkg['features'] ?? [],
                    ]);
                }
            }

            // Create Extras
            if ($request->has('extras')) {
                $extras = is_string($request->extras) ? json_decode($request->extras, true) : $request->extras;
                if (is_array($extras)) {
                    foreach ($extras as $extra) {
                        $gig->extras()->create([
                            'title' => $extra['title'],
                            'description' => $extra['description'] ?? null,
                            'price' => $extra['price'],
                            'additional_days' => $extra['additional_days'] ?? 0,
                        ]);
                    }
                }
            }

            // Create FAQs
            if ($request->has('faqs')) {
                $faqs = is_string($request->faqs) ? json_decode($request->faqs, true) : $request->faqs;
                if (is_array($faqs)) {
                    foreach ($faqs as $faq) {
                        if (!empty($faq['question']) && !empty($faq['answer'])) {
                            $gig->faqs()->create([
                                'question' => $faq['question'],
                                'answer' => $faq['answer'],
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gig created successfully',
                'data' => $gig->load(['packages', 'extras', 'faqs'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create gig: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $gig = Gig::where('provider_id', Auth::id())
            ->with(['packages', 'extras', 'serviceType', 'category', 'faqs'])
            ->withCount('bookings')
            ->findOrFail($id);
            
        return response()->json([
            'success' => true,
            'data' => $gig
        ]);
    }

    public function update(Request $request, $id)
    {
        $gig = Gig::where('provider_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'service_type_id' => 'nullable|exists:service_types,id',
            'description' => 'required|string',
            'packages' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimetypes:video/mp4,video/quicktime|max:51200',
            'documents.*' => 'mimes:pdf|max:5120',
            'faqs' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $metadata = $request->metadata ? (is_string($request->metadata) ? json_decode($request->metadata, true) : $request->metadata) : $gig->metadata;
            $tags = $request->tags ? (is_string($request->tags) ? json_decode($request->tags, true) : $request->tags) : [];

            // Handle Files
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($gig->thumbnail_image) {
                     $oldPath = str_replace(asset('storage/'), '', $gig->thumbnail_image);
                     Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file('thumbnail')->store('gigs/thumbnails', 'public');
                $gig->thumbnail_image = asset('storage/' . $path);
            }

            $imagePaths = $gig->images ?? [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('gigs/images', 'public');
                    $imagePaths[] = asset('storage/' . $path);
                }
            }
            // Handle image deletion if needed (not implemented in basic update, usually separate endpoint or logic)

            if ($request->hasFile('video')) {
                 if ($gig->video) {
                     $oldPath = str_replace(asset('storage/'), '', $gig->video);
                     Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file('video')->store('gigs/videos', 'public');
                $gig->video = asset('storage/' . $path);
            }

            $documentPaths = $gig->documents ?? [];
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $doc) {
                    $path = $doc->store('gigs/documents', 'public');
                    $documentPaths[] = asset('storage/' . $path);
                }
            }

            $gig->update([
                'category_id' => $request->category_id,
                'service_type_id' => $request->service_type_id,
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . Str::random(6),
                'description' => $request->description,
                'images' => $imagePaths,
                'documents' => $documentPaths,
                'tags' => $tags,
                'metadata' => $metadata,
            ]);

             // Sync Tags
            if (!empty($tags)) {
                $tagIds = [];
                foreach ($tags as $tagName) {
                    $tag = \App\Models\Tag::firstOrCreate(
                        ['name' => $tagName],
                        [
                            'slug' => Str::slug($tagName),
                            'created_by' => Auth::id()
                        ]
                    );
                    $tagIds[] = $tag->id;
                }
                $gig->relatedTags()->sync($tagIds);
            }

            // Update Packages (Delete old and create new for simplicity, or update existing)
            // For simplicity, we'll delete and recreate as packages are tightly coupled
            $gig->packages()->delete();
            $packages = is_string($request->packages) ? json_decode($request->packages, true) : $request->packages;
            if (is_array($packages)) {
                foreach ($packages as $pkg) {
                    $gig->packages()->create([
                        'tier' => $pkg['tier'],
                        'name' => $pkg['name'],
                        'description' => $pkg['description'],
                        'price' => $pkg['price'],
                        'delivery_days' => $pkg['delivery_days'],
                        'revisions' => $pkg['revisions'] ?? 0,
                        'source_code' => $pkg['source_code'] ?? false,
                        'features' => $pkg['features'] ?? [],
                    ]);
                }
            }

            // Update Extras
            $gig->extras()->delete();
            if ($request->has('extras')) {
                $extras = is_string($request->extras) ? json_decode($request->extras, true) : $request->extras;
                if (is_array($extras)) {
                    foreach ($extras as $extra) {
                        $gig->extras()->create([
                            'title' => $extra['title'],
                            'description' => $extra['description'] ?? null,
                            'price' => $extra['price'],
                            'additional_days' => $extra['additional_days'] ?? 0,
                        ]);
                    }
                }
            }

            // Update FAQs
            $gig->faqs()->delete();
            if ($request->has('faqs')) {
                $faqs = is_string($request->faqs) ? json_decode($request->faqs, true) : $request->faqs;
                if (is_array($faqs)) {
                    foreach ($faqs as $faq) {
                        if (!empty($faq['question']) && !empty($faq['answer'])) {
                            $gig->faqs()->create([
                                'question' => $faq['question'],
                                'answer' => $faq['answer'],
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gig updated successfully',
                'data' => $gig->load(['packages', 'extras', 'faqs'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update gig: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $gig = Gig::where('provider_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:active,paused',
        ]);

        $gig->is_active = $request->status === 'active';
        $gig->status = $request->status === 'active' ? 'active' : 'paused'; // or keep original status if it was 'published' vs 'active'?
        // The frontend uses 'is_active' for pause/activate toggle.
        // Let's assume 'active' means visible, 'paused' means hidden.
        
        $gig->save();

        return response()->json([
            'success' => true,
            'message' => 'Gig status updated successfully',
            'data' => $gig
        ]);
    }

    public function destroy(Gig $gig)
    {
        if ($gig->provider_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $gig->delete();

        return response()->json(['success' => true, 'message' => 'Gig deleted successfully']);
    }

    public function analytics($id)
    {
        $gig = Gig::where('provider_id', Auth::id())->findOrFail($id);

        // 1. Day Sell Analytics (Last 7 Days)
        $endDate = now();
        $startDate = now()->subDays(6);
        
        $dailySales = GigOrder::where('gig_id', $id)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(provider_amount) as amount')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $salesChart = [];
        $ordersChart = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $data = $dailySales->get($date);
            
            $salesChart[] = [
                'date' => $date,
                'value' => $data ? (float)$data->amount : 0.0
            ];
            
             $ordersChart[] = [
                'date' => $date,
                'value' => $data ? (int)$data->count : 0
            ];
        }

        // 2. Total Stats
        $totalEarnings = GigOrder::where('gig_id', $id)
            ->where('status', 'completed')
            ->sum('provider_amount');
            
        $todayEarnings = GigOrder::where('gig_id', $id)
            ->where('status', 'completed')
            ->whereDate('created_at', now())
            ->sum('provider_amount');
            
        // Calculate percentage changes
        $yesterdayEarnings = GigOrder::where('gig_id', $id)
            ->where('status', 'completed')
            ->whereDate('created_at', now()->subDay())
            ->sum('provider_amount');
            
        $earningsChange = $yesterdayEarnings > 0 
            ? (($todayEarnings - $yesterdayEarnings) / $yesterdayEarnings) * 100 
            : ($todayEarnings > 0 ? 100 : 0);

        $activeOrders = GigOrder::where('gig_id', $id)->where('status', 'active')->count();
        $completedOrders = GigOrder::where('gig_id', $id)->where('status', 'completed')->count();
        $totalOrders = GigOrder::where('gig_id', $id)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_earnings' => $totalEarnings,
                'today_earnings' => $todayEarnings,
                'earnings_change' => round($earningsChange, 1),
                'active_orders' => $activeOrders,
                'completed_orders' => $completedOrders,
                'sales_chart' => $salesChart,
                'orders_chart' => $ordersChart,
                'recent_orders' => GigOrder::where('gig_id', $id)->with('user')->latest()->take(5)->get(),
                'view_count' => $gig->view_count,
                'bookings_count' => $totalOrders,
            ]
        ]);
    }
}
