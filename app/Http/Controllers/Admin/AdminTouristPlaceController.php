<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TouristPlace;
use App\Models\TouristFacility;
use App\Models\Owner;

class AdminTouristPlaceController extends Controller
{
    /**
     * Menampilkan daftar Tempat Wisata.
     */
    public function index(Request $request)
    {
        $query = TouristPlace::with(['facilities', 'owner.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('manager_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $touristPlaces = $query->latest()->paginate(10)->withQueryString();

        return view('admin.tourist_places.index', compact('touristPlaces'));
    }

    /**
     * Form Tambah Tempat Wisata Baru.
     */
    public function create()
    {
        $owners     = Owner::with('user')->get();
        $facilities = TouristFacility::orderBy('facility_name', 'asc')->get();

        return view('admin.tourist_places.create', compact('owners', 'facilities'));
    }

    /**
     * Simpan Tempat Wisata Baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string'],
            'operational_hours' => ['required', 'string', 'max:255'],
            'address'           => ['nullable', 'string'],
            'district'          => ['nullable', 'string', 'max:255'],
            'village'           => ['nullable', 'string', 'max:255'],
            'postal_code'       => ['nullable', 'string', 'max:20'],
            'latitude'          => ['nullable', 'string', 'max:50'],
            'longitude'         => ['nullable', 'string', 'max:50'],
            'google_maps'       => ['required', 'string'],
            'manager_name'      => ['required', 'string', 'max:255'],
            'email'             => ['nullable', 'email', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:50'],
            'instagram'         => ['nullable', 'string', 'max:255'],
            'ticket_price'      => ['nullable', 'string', 'max:100'],
            'status'            => ['required', 'in:pending,approved,rejected'],
            'owner_id'          => ['nullable', 'exists:owners,id'],
            'facilities'        => ['nullable', 'array'],
            'facilities.*'      => ['exists:tourist_facilities,id'],
        ]);

        $touristPlace = TouristPlace::create([
            'owner_id'          => $request->owner_id,
            'name'              => $request->name,
            'description'       => $request->description,
            'operational_hours' => $request->operational_hours,
            'address'           => $request->address,
            'district'          => $request->district,
            'village'           => $request->village,
            'postal_code'       => $request->postal_code,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'google_maps'       => $request->google_maps,
            'manager_name'      => $request->manager_name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'instagram'         => $request->filled('instagram') ? trim($request->instagram) : null,
            'ticket_price'      => $request->ticket_price,
            'status'            => $request->status,
        ]);

        if ($request->filled('facilities')) {
            $touristPlace->facilities()->sync($request->facilities);
        }

        return redirect()->route('admin.tourist-places.index')->with('success', 'Data tempat wisata berhasil ditambahkan.');
    }

    /**
     * Form Edit Tempat Wisata.
     */
    public function edit($id)
    {
        $touristPlace = TouristPlace::with('facilities')->findOrFail($id);
        $owners       = Owner::with('user')->get();
        $facilities   = TouristFacility::orderBy('facility_name', 'asc')->get();

        return view('admin.tourist_places.edit', compact('touristPlace', 'owners', 'facilities'));
    }

    /**
     * Update Data Tempat Wisata.
     */
    public function update(Request $request, $id)
    {
        $touristPlace = TouristPlace::findOrFail($id);

        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string'],
            'operational_hours' => ['required', 'string', 'max:255'],
            'address'           => ['nullable', 'string'],
            'district'          => ['nullable', 'string', 'max:255'],
            'village'           => ['nullable', 'string', 'max:255'],
            'postal_code'       => ['nullable', 'string', 'max:20'],
            'latitude'          => ['nullable', 'string', 'max:50'],
            'longitude'         => ['nullable', 'string', 'max:50'],
            'google_maps'       => ['required', 'string'],
            'manager_name'      => ['required', 'string', 'max:255'],
            'email'             => ['nullable', 'email', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:50'],
            'instagram'         => ['nullable', 'string', 'max:255'],
            'ticket_price'      => ['nullable', 'string', 'max:100'],
            'status'            => ['required', 'in:pending,approved,rejected'],
            'owner_id'          => ['nullable', 'exists:owners,id'],
            'facilities'        => ['nullable', 'array'],
            'facilities.*'      => ['exists:tourist_facilities,id'],
        ]);

        $touristPlace->update([
            'owner_id'          => $request->owner_id,
            'name'              => $request->name,
            'description'       => $request->description,
            'operational_hours' => $request->operational_hours,
            'address'           => $request->address,
            'district'          => $request->district,
            'village'           => $request->village,
            'postal_code'       => $request->postal_code,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'google_maps'       => $request->google_maps,
            'manager_name'      => $request->manager_name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'instagram'         => $request->filled('instagram') ? trim($request->instagram) : null,
            'ticket_price'      => $request->ticket_price,
            'status'            => $request->status,
        ]);

        if ($request->has('facilities')) {
            $touristPlace->facilities()->sync($request->facilities);
        } else {
            $touristPlace->facilities()->detach();
        }

        return redirect()->route('admin.tourist-places.index')->with('success', 'Data tempat wisata berhasil diperbarui.');
    }

    /**
     * Hapus Tempat Wisata.
     */
    public function destroy($id)
    {
        $touristPlace = TouristPlace::findOrFail($id);
        $touristPlace->delete();

        return redirect()->route('admin.tourist-places.index')->with('success', 'Data tempat wisata berhasil dihapus.');
    }
}
