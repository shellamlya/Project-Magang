<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HangoutPlace;
use App\Models\HangoutFacility;
use App\Models\Owner;

class AdminHangoutPlaceController extends Controller
{
    /**
     * Menampilkan daftar Tempat Nongkrong.
     */
    public function index(Request $request)
    {
        $query = HangoutPlace::with(['facilities', 'owner.user']);

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

        $hangoutPlaces = $query->latest()->paginate(10)->withQueryString();

        return view('admin.hangout_places.index', compact('hangoutPlaces'));
    }

    /**
     * Form Tambah Tempat Nongkrong Baru.
     */
    public function create()
    {
        $owners     = Owner::with('user')->get();
        $facilities = HangoutFacility::orderBy('facility_name', 'asc')->get();

        return view('admin.hangout_places.create', compact('owners', 'facilities'));
    }

    /**
     * Simpan Tempat Nongkrong Baru.
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
            'status'            => ['required', 'in:pending,approved,rejected'],
            'owner_id'          => ['nullable', 'exists:owners,id'],
            'facilities'        => ['nullable', 'array'],
            'facilities.*'      => ['exists:hangout_facilities,id'],
        ]);

        $hangoutPlace = HangoutPlace::create([
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
            'status'            => $request->status,
        ]);

        if ($request->filled('facilities')) {
            $hangoutPlace->facilities()->sync($request->facilities);
        }

        return redirect()->route('admin.hangout-places.index')->with('success', 'Data tempat nongkrong berhasil ditambahkan.');
    }

    /**
     * Form Edit Tempat Nongkrong.
     */
    public function edit($id)
    {
        $hangoutPlace = HangoutPlace::with('facilities')->findOrFail($id);
        $owners       = Owner::with('user')->get();
        $facilities   = HangoutFacility::orderBy('facility_name', 'asc')->get();

        return view('admin.hangout_places.edit', compact('hangoutPlace', 'owners', 'facilities'));
    }

    /**
     * Update Data Tempat Nongkrong.
     */
    public function update(Request $request, $id)
    {
        $hangoutPlace = HangoutPlace::findOrFail($id);

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
            'status'            => ['required', 'in:pending,approved,rejected'],
            'owner_id'          => ['nullable', 'exists:owners,id'],
            'facilities'        => ['nullable', 'array'],
            'facilities.*'      => ['exists:hangout_facilities,id'],
        ]);

        $hangoutPlace->update([
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
            'status'            => $request->status,
        ]);

        if ($request->has('facilities')) {
            $hangoutPlace->facilities()->sync($request->facilities);
        } else {
            $hangoutPlace->facilities()->detach();
        }

        return redirect()->route('admin.hangout-places.index')->with('success', 'Data tempat nongkrong berhasil diperbarui.');
    }

    /**
     * Hapus Tempat Nongkrong.
     */
    public function destroy($id)
    {
        $hangoutPlace = HangoutPlace::findOrFail($id);
        $hangoutPlace->delete();

        return redirect()->route('admin.hangout-places.index')->with('success', 'Data tempat nongkrong berhasil dihapus.');
    }
}
