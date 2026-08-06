<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;

/**
 * Class AdminFacilityController
 * @package App\Http\Controllers\Admin
 * Pengendali Kelola Master Data Fasilitas.
 */
class AdminFacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::withCount('lodgings')->latest()->get();
        return view('admin.facilities.index', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:facilities,name'],
            'icon' => ['nullable', 'string'],
        ]);

        Facility::create([
            'name' => $request->name,
            'icon' => $request->icon ?? 'fa-check',
        ]);

        return back()->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:facilities,name,' . $id],
            'icon' => ['nullable', 'string'],
        ]);

        $facility->update([
            'name' => $request->name,
            'icon' => $request->icon,
        ]);

        return back()->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);
        $facility->delete();

        return back()->with('success', 'Fasilitas berhasil dihapus.');
    }
}
