<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::latest()->paginate(10);
        return view('admin.zones.index', compact('zones'));
    }

    public function create()
    {
        return view('admin.zones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'coordinates' => 'required|string', // Assuming text format for now (e.g., WKT or JSON)
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        Zone::create($data);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone created successfully.');
    }

    public function edit(Zone $zone)
    {
        return view('admin.zones.edit', compact('zone'));
    }

    public function update(Request $request, Zone $zone)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'coordinates' => 'required|string',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $zone->update($data);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone updated successfully.');
    }

    public function destroy(Zone $zone)
    {
        $zone->delete();
        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone deleted successfully.');
    }
}
