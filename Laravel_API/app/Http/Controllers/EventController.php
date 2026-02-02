<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventAttendee;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('is_active', true)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->paginate(9);
            
        return view('pages.community.events.index', compact('events'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)
            ->with(['user', 'category', 'attendees.user'])
            ->firstOrFail();
            
        $isAttending = false;
        if (Auth::guard('web')->check()) {
            $isAttending = EventAttendee::where('event_id', $event->id)
                ->where('user_id', Auth::guard('web')->id())
                ->exists();
        }

        return view('pages.community.events.show', compact('event', 'isAttending'));
    }

    public function attend(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        
        $userId = Auth::guard('web')->id();
        
        $attendee = EventAttendee::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->first();

        if ($attendee) {
            $attendee->delete();
            return back()->with('success', 'You are no longer attending this event.');
        } else {
            EventAttendee::create([
                'event_id' => $event->id,
                'user_id' => $userId,
                'status' => 'going',
            ]);
            return back()->with('success', 'You are now attending this event!');
        }
    }
}
