<?php

namespace App\Http\Controllers\Provider\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Gig;
use App\Models\Category;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class GigController extends Controller
{
    public function index()
    {
        $gigs = Gig::where('provider_id', Auth::guard('web')->id())
            ->with(['category', 'serviceType', 'packages'])
            ->latest()
            ->paginate(10);

        return view('Provider.Freelancer.gigs.index', compact('gigs'));
    }

    public function create()
    {
        $categories = Category::with('children')->whereNull('parent_id')->where('is_active', true)->get();
        $serviceTypes = ServiceType::where('is_active', true)->get();
        return view('Provider.Freelancer.gigs.create', compact('categories', 'serviceTypes'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'service_type_id' => 'required|exists:service_types,id',
            'description' => 'required|string',
            'thumbnail' => 'required|image|max:2048', // 2MB Max
            'images.*' => 'image|max:2048',
            // Add other validations as needed
        ]);

        try {
            DB::beginTransaction();

            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('gigs/thumbnails', 'public');
            }

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = $image->store('gigs/images', 'public');
                }
            }

            // Handle arrays (tags, etc.)
            // Assuming tags come as a comma-separated string or array
            $tags = $request->tags ? explode(',', $request->tags) : [];

            $gig = Gig::create([
                'provider_id' => Auth::guard('web')->id(),
                'category_id' => $request->sub_category_id ?? $request->category_id, // Use sub_category if selected, else main
                'service_type_id' => $request->service_type_id,
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . uniqid(),
                'description' => $request->description,
                'thumbnail_image' => $thumbnailPath,
                'images' => $imagePaths,
                'tags' => $tags,
                'is_active' => true,
                'status' => 'pending',
                'view_count' => 0,
            ]);

            // Handle Packages
            if ($request->has('packages')) {
                foreach ($request->packages as $pkg) {
                    // Check if package has minimal required data
                    if (!empty($pkg['name']) && !empty($pkg['price'])) {
                        $gig->packages()->create([
                            'tier' => $pkg['tier'] ?? null,
                            'name' => $pkg['name'],
                            'description' => $pkg['description'] ?? '',
                            'price' => $pkg['price'],
                            'delivery_days' => $pkg['delivery_days'] ?? 1,
                            'revisions' => $pkg['revisions'] ?? 0,
                            'features' => isset($pkg['features']) ? explode(',', $pkg['features']) : [],
                        ]);
                    }
                }
            }

            // Handle Extras
            if ($request->has('extras')) {
                foreach ($request->extras as $extra) {
                    if (!empty($extra['title']) && !empty($extra['price'])) {
                        $gig->extras()->create([
                            'title' => $extra['title'],
                            'price' => $extra['price'],
                            'additional_days' => $extra['additional_days'] ?? 0,
                        ]);
                    }
                }
            }

            // Handle FAQs
            if ($request->has('faqs')) {
                foreach ($request->faqs as $faq) {
                    if (!empty($faq['question']) && !empty($faq['answer'])) {
                        $gig->faqs()->create([
                            'question' => $faq['question'],
                            'answer' => $faq['answer'],
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('provider.freelancer.gigs.index')->with('success', 'Gig created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gig Creation Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to create gig: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $gig = Gig::where('provider_id', Auth::guard('web')->id())->findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        $serviceTypes = ServiceType::where('is_active', true)->get();
        return view('Provider.Freelancer.gigs.edit', compact('gig', 'categories', 'serviceTypes'));
    }

    public function update(Request $request, $id)
    {
        // Implementation similar to store but updating
        // For now, let's focus on create/index as per request
    }

    public function destroy($id)
    {
        $gig = Gig::where('provider_id', Auth::guard('web')->id())->findOrFail($id);
        $gig->delete();
        return back()->with('success', 'Gig deleted successfully!');
    }
}
