<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AiController extends Controller
{
    public function index()
    {
        $settings = Setting::where('group', 'ai')->pluck('value', 'key')->toArray();
        return view('admin.ai.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'openai_api_key' => 'nullable|string',
            'openai_model' => 'required|string',
        ]);

        $data = $request->except(['_token', '_method']);

        // Handle boolean toggles that might not be in the request if unchecked
        $booleans = ['ai_enabled', 'image_generation_enabled', 'chat_completion_enabled'];
        foreach ($booleans as $bool) {
            $data[$bool] = $request->has($bool) ? '1' : '0';
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'group' => 'ai'],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'AI settings updated successfully.');
    }
}
