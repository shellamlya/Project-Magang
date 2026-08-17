<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LodgingFacility;
use App\Models\TouristFacility;
use App\Models\HangoutFacility;
use App\Models\Facility;

/**
 * Class AdminFacilityController
 * @package App\Http\Controllers\Admin
 * Pengendali Kelola Master Data Fasilitas (Penginapan, Wisata, Nongkrong).
 */
class AdminFacilityController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('type', 'penginapan');

        $lodgingFacilities = LodgingFacility::orderBy('facility_name', 'asc')->get();
        $touristFacilities = TouristFacility::orderBy('facility_name', 'asc')->get();
        $hangoutFacilities = HangoutFacility::orderBy('facility_name', 'asc')->get();

        return view('admin.facilities.index', compact(
            'activeTab',
            'lodgingFacilities',
            'touristFacilities',
            'hangoutFacilities'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_name' => ['required', 'string', 'max:255'],
            'service_type'  => ['required', 'string', 'in:penginapan,wisata,nongkrong,all'],
            'icon'          => ['nullable', 'string'],
        ]);

        $name = trim($request->facility_name);
        $type = $request->service_type;
        $icon = $request->icon ?? 'fa-check';

        if ($type === 'penginapan' || $type === 'all') {
            LodgingFacility::firstOrCreate(['facility_name' => $name]);
        }
        if ($type === 'wisata' || $type === 'all') {
            TouristFacility::firstOrCreate(['facility_name' => $name]);
        }
        if ($type === 'nongkrong' || $type === 'all') {
            HangoutFacility::firstOrCreate(['facility_name' => $name]);
        }

        // Master facility sync
        Facility::firstOrCreate(['name' => $name], ['icon' => $icon]);

        return back()->with('success', "Fasilitas '$name' berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $type = $request->get('facility_type', 'penginapan');

        $request->validate([
            'facility_name' => ['required', 'string', 'max:255'],
        ]);

        $newName = trim($request->facility_name);

        if ($type === 'penginapan') {
            $fac = LodgingFacility::findOrFail($id);
            $fac->update(['facility_name' => $newName]);
        } elseif ($type === 'wisata') {
            $fac = TouristFacility::findOrFail($id);
            $fac->update(['facility_name' => $newName]);
        } elseif ($type === 'nongkrong') {
            $fac = HangoutFacility::findOrFail($id);
            $fac->update(['facility_name' => $newName]);
        }

        return back()->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->get('facility_type', 'penginapan');

        if ($type === 'penginapan') {
            $fac = LodgingFacility::findOrFail($id);
            $fac->delete();
        } elseif ($type === 'wisata') {
            $fac = TouristFacility::findOrFail($id);
            $fac->delete();
        } elseif ($type === 'nongkrong') {
            $fac = HangoutFacility::findOrFail($id);
            $fac->delete();
        }

        return back()->with('success', 'Fasilitas berhasil dihapus.');
    }
}
