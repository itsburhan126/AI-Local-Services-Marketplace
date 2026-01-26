<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceTypeController extends Controller
{
    public function index()
    {
        $serviceTypes = ServiceType::where('type', 'local')->latest()->paginate(10);
        return view('admin.service_types.index', compact('serviceTypes'));
    }

    public function create()
    {
        return view('admin.service_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:service_types,name',
            'is_active' => 'boolean',
            'type' => 'nullable|in:local,freelancer',
        ]);

        ServiceType::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => $request->has('is_active'),
            'type' => $request->type ?? 'local',
        ]);

        if ($request->type === 'freelancer') {
            return redirect()->route('admin.freelancers.index', ['tab' => 'service_types'])
                ->with('success', 'Freelancer Service Type created successfully.');
        }

        return redirect()->route('admin.service-types.index')
            ->with('success', 'Service Type created successfully.');
    }

    public function edit(ServiceType $serviceType)
    {
        return view('admin.service_types.edit', compact('serviceType'));
    }

    public function update(Request $request, ServiceType $serviceType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:service_types,name,' . $serviceType->id,
            'is_active' => 'boolean',
        ]);

        $serviceType->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => $request->has('is_active'),
        ]);

        if ($serviceType->type === 'freelancer') {
            return redirect()->route('admin.freelancers.index', ['tab' => 'service_types'])
                ->with('success', 'Freelancer Service Type updated successfully.');
        }

        return redirect()->route('admin.service-types.index')
            ->with('success', 'Service Type updated successfully.');
    }

    public function destroy(ServiceType $serviceType)
    {
        $type = $serviceType->type;
        $serviceType->delete();

        if ($type === 'freelancer') {
            return redirect()->route('admin.freelancers.index', ['tab' => 'service_types'])
                ->with('success', 'Freelancer Service Type deleted successfully.');
        }

        return redirect()->route('admin.service-types.index')
            ->with('success', 'Service Type deleted successfully.');
    }
}
