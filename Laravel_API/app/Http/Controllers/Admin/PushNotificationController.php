<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PushNotificationController extends Controller
{
    public function index()
    {
        $notifications = PushNotification::latest()->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'target_audience' => 'required|in:all,customer,provider',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('notifications', 'public');
            $data['image'] = Storage::url($path);
        }

        // If not scheduled, mark as sent immediately (simulation)
        if (empty($data['scheduled_at'])) {
            $data['is_sent'] = true;
            $data['sent_at'] = now();
            // Here you would trigger the actual Firebase/OneSignal logic
        } else {
            $data['is_sent'] = false;
        }

        PushNotification::create($data);

        return redirect()->route('admin.push-notifications.index')
            ->with('success', 'Notification created successfully.');
    }

    public function destroy(PushNotification $pushNotification)
    {
        $pushNotification->delete();
        return redirect()->route('admin.push-notifications.index')
            ->with('success', 'Notification deleted successfully.');
    }
}
