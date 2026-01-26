<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::withCount(['bookings as total_bookings', 'bookings as completed_bookings_count' => function($q) {
            $q->where('status', 'completed');
        }])->findOrFail($id);

        $recent_bookings = Booking::where('user_id', $id)
            ->with(['service', 'provider'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.users.show', compact('user', 'recent_bookings'));
    }
}
