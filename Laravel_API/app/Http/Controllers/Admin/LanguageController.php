<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('name', 'asc')->paginate(20);
        return view('admin.languages.index', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:languages',
        ]);

        Language::create($request->all());

        return redirect()->route('admin.languages.index')->with('success', 'Language created successfully.');
    }

    public function update(Request $request, Language $language)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:5|unique:languages,code,' . $language->id,
        ]);

        $language->update($request->all());

        return redirect()->route('admin.languages.index')->with('success', 'Language updated successfully.');
    }

    public function destroy(Language $language)
    {
        $language->delete();
        return redirect()->route('admin.languages.index')->with('success', 'Language deleted successfully.');
    }
}
