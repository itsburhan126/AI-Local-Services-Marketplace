<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $layout = 'layouts.customer'; // Default layout

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->role === 'provider' && $user->service_rule === 'freelancer') {
                $layout = 'layouts.freelancer';
            }
        }

        return view('support.index', compact('layout'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'email' => 'required|email',
        ]);

        // Logic to store ticket or send email would go here.
        // For now, just return success.

        return back()->with('success', 'Your support request has been received. We will get back to you shortly.');
    }
}
