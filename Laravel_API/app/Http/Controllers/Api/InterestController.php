<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\FreelancerInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InterestController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'local_service'); // 'local_service' or 'freelancer'

        if ($type === 'freelancer') {
            $query = FreelancerInterest::where('is_active', true)->orderBy('order', 'asc');
            $relation = 'freelancerInterests';
            $table = 'freelancer_interests';
            $pivotTable = 'freelancer_interest_user';
            $pivotCol = 'freelancer_interest_id';
        } else {
            $query = Interest::where('is_active', true)->orderBy('order', 'asc');
            $relation = 'interests';
            $table = 'interests';
            $pivotTable = 'user_interests';
            $pivotCol = 'interest_id';
        }

        $interests = $query->get();

        // If user is logged in, apply "Smart" logic
        $user = $request->user('sanctum');
        if ($user) {
            // 1. Cooldown Logic: Check if user added any interest in the last 24 hours
            $lastInteraction = $user->{$relation}()
                ->withPivot('created_at')
                ->orderBy($pivotTable . '.created_at', 'desc')
                ->first();

            if ($lastInteraction && $lastInteraction->pivot->created_at > now()->subHours(24)) {
                // User interacted recently. Hide the section entirely for a "Professional" feel.
                return response()->json([
                    'status' => 'success',
                    'data' => [] 
                ]);
            }

            // 2. Filter Logic: Exclude already selected interests
            $selectedIds = $user->{$relation}()->pluck($table . '.id')->toArray();
            
            $interests = $interests->reject(function ($interest) use ($selectedIds) {
                return in_array($interest->id, $selectedIds);
            })->values(); // Reset keys
        }

        $data = $interests->map(function ($interest) {
            return [
                'id' => $interest->id,
                'name' => $interest->name,
                'slug' => $interest->slug,
                'icon' => $interest->icon && Str::startsWith($interest->icon, ['http', 'https']) 
                    ? $interest->icon 
                    : ($interest->icon ? url($interest->icon) : null),
                'is_selected' => false, // Always false since we filtered out selected ones
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'interest_id' => 'required|integer',
            'type' => 'nullable|string|in:local_service,freelancer',
        ]);

        $type = $request->input('type', 'local_service');
        $user = $request->user();
        $interestId = $request->interest_id;

        if ($type === 'freelancer') {
            $relation = $user->freelancerInterests();
            $exists = $relation->where('freelancer_interest_id', $interestId)->exists();
        } else {
            $relation = $user->interests();
            $exists = $relation->where('interest_id', $interestId)->exists();
        }

        if ($exists) {
            $relation->detach($interestId);
            $message = 'Interest removed';
            $isSelected = false;
        } else {
            $relation->attach($interestId);
            $message = 'Interest added';
            $isSelected = true;
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'is_selected' => $isSelected
        ]);
    }
}
