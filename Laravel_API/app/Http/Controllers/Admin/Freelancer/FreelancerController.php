<?php

namespace App\Http\Controllers\Admin\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Gig;
use App\Models\ProviderProfile;
use App\Models\Tag;
use App\Models\ServiceType;
use App\Models\FreelancerBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FreelancerController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'overview');

        // Stats
        $stats = [
            'total_freelancers' => User::where('role', 'provider')
                ->whereHas('providerProfile', function($q) {
                    $q->where('mode', 'freelancer');
                })->count(),
            'total_gigs' => Gig::count(),
            'active_gigs' => Gig::where('is_active', true)->count(),
            'pending_freelancers' => User::where('role', 'provider')
                ->where('status', 'pending') // Assuming status on User
                ->whereHas('providerProfile', function($q) {
                    $q->where('mode', 'freelancer');
                })->count(),
            'total_tags' => Tag::whereHas('gigs.provider.providerProfile', function($q) {
                $q->where('mode', 'freelancer');
            })->count(),
            'total_service_types' => ServiceType::where('type', 'freelancer')->count(),
        ];

        // Freelancers List
        $freelancers = User::where('role', 'provider')
            ->whereHas('providerProfile', function($q) {
                $q->where('mode', 'freelancer');
            })
            ->with('providerProfile')
            ->latest()
            ->paginate(10, ['*'], 'freelancers_page');

        // Gigs List
        $gigsQuery = Gig::with(['provider', 'category', 'serviceType']);

        if ($request->has('search') && $tab === 'gigs') {
            $search = $request->search;
            $gigsQuery->where('title', 'like', "%{$search}%");
        }

        if ($request->has('status')) {
            if ($request->status === 'active') {
                $gigsQuery->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $gigsQuery->where('is_active', false);
            }
        }

        $gigs = $gigsQuery->latest()->paginate(10, ['*'], 'gigs_page');

        // Service Types List
        $serviceTypes = ServiceType::where('type', 'freelancer')
            ->latest()
            ->paginate(10, ['*'], 'service_types_page');

        // Tags List
        $tagsQuery = Tag::whereHas('gigs.provider.providerProfile', function($q) {
                $q->where('mode', 'freelancer');
            })
            ->withCount(['gigs' => function($q) {
                $q->whereHas('provider.providerProfile', function($q2) {
                    $q2->where('mode', 'freelancer');
                });
            }])
            ->with(['gigs' => function($q) {
                $q->whereHas('provider.providerProfile', function($q2) {
                    $q2->where('mode', 'freelancer');
                })->with('provider');
            }]);
        
        if ($request->has('search') && $tab === 'tags') {
            $tagsQuery->where('name', 'like', "%{$request->search}%");
        }
        
        $tags = $tagsQuery->latest()->paginate(20, ['*'], 'tags_page');

        return view('admin.freelancers.index', compact('stats', 'freelancers', 'gigs', 'serviceTypes', 'tags', 'tab'));
    }

    public function banners(Request $request)
    {
        $banners = FreelancerBanner::orderBy('order', 'asc')->get();
        return view('admin.freelancers.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('freelancer_banners', 'public');
            
            FreelancerBanner::create([
                'image_path' => $path,
                'title' => $request->title,
                'order' => $request->order ?? 0,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->back()->with('success', 'Banner added successfully');
        }

        return redirect()->back()->with('error', 'Image is required');
    }

    public function destroyBanner(FreelancerBanner $banner)
    {
        if (Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }
        
        $banner->delete();

        return redirect()->back()->with('success', 'Banner deleted successfully');
    }

    public function tags(Request $request)
    {
        return redirect()->route('admin.freelancers.index', ['tab' => 'tags']);
    }

    public function storeTag(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'is_active' => 'boolean'
        ]);

        Tag::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'is_active' => $request->has('is_active'),
            'created_by' => auth()->id(),
            'source' => 'admin'
        ]);

        return redirect()->back()->with('success', 'Tag created successfully');
    }

    public function updateTag(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'is_active' => 'boolean'
        ]);

        $tag->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->back()->with('success', 'Tag updated successfully');
    }

    public function destroyTag($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        
        return redirect()->back()->with('success', 'Tag deleted successfully');
    }
}
