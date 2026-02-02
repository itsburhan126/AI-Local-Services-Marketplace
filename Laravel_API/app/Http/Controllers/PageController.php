<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\HowItWorksStep;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function howItWorks()
    {
        $clientSteps = HowItWorksStep::where('type', 'client')
            ->where('is_active', true)
            ->orderBy('step_order')
            ->get();

        $freelancerSteps = HowItWorksStep::where('type', 'freelancer')
            ->where('is_active', true)
            ->orderBy('step_order')
            ->get();

        return view('pages.how-it-works', compact('clientSteps', 'freelancerSteps'));
    }

    public function show($slug)
    {
        if ($slug === 'forum') {
            return redirect()->route('community.forum.index');
        }
        if ($slug === 'events') {
            return redirect()->route('community.events.index');
        }
        if ($slug === 'community-hub') {
            return redirect()->route('community.index');
        }
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('page', compact('page'));
    }
}
