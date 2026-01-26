<?php

namespace App\Http\Controllers\Admin\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Gig;
use Illuminate\Http\Request;

class GigController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status'); // pending, approved, rejected
        
        $query = Gig::with(['provider', 'category', 'serviceType'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $gigs = $query->paginate(10);
        
        return view('admin.gigs.index', compact('gigs', 'status'));
    }

    public function requests()
    {
        $gigs = Gig::where('status', 'pending')
            ->with(['provider', 'category', 'serviceType'])
            ->latest()
            ->paginate(10);
            
        return view('admin.gigs.requests', compact('gigs'));
    }

    public function show(Gig $gig)
    {
        $gig->load(['provider', 'category', 'serviceType', 'packages', 'extras', 'relatedTags']);
        return view('admin.gigs.show', compact('gig'));
    }

    public function approve(Gig $gig)
    {
        $gig->update(['status' => 'approved', 'is_active' => true]);
        
        // Optional: Send notification to provider
        
        return redirect()->back()->with('success', 'Gig approved successfully.');
    }

    public function reject(Request $request, Gig $gig)
    {
        $request->validate([
            'admin_note' => 'required|string',
        ]);

        $gig->update([
            'status' => 'rejected', 
            'is_active' => false,
            'admin_note' => $request->admin_note
        ]);

        // Optional: Send notification to provider

        return redirect()->back()->with('success', 'Gig rejected.');
    }
}
