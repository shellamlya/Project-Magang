<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lodging;
use App\Models\HangoutPlace;
use App\Models\TouristPlace;
use App\Models\LodgingFacility;
use App\Models\HangoutFacility;
use App\Models\TouristFacility;

/**
 * Class OwnerLodgingController
 * @package App\Http\Controllers\Owner
 * Pengendali CRUD pengajuan data tempat usaha (Penginapan, Nongkrong, Wisata) oleh Owner.
 */
class OwnerLodgingController extends Controller
{
    /**
     * Menampilkan daftar seluruh tempat usaha milik Owner.
     */
    public function index(Request $request)
    {
        $owner = Auth::user()->owner;
        if (!$owner) {
            return redirect()->route('home')->with('error', 'Profil Owner tidak ditemukan.');
        }

        $category = $request->query('category');
        $allPlaces = $owner->allPlacesCollection();

        if ($category && in_array($category, ['penginapan', 'nongkrong', 'wisata'])) {
            $allPlaces = $allPlaces->where('place_category', $category)->values();
        }

        return view('owner.lodgings.index', compact('allPlaces', 'category'));
    }

    /**
     * Menampilkan form penambahan tempat usaha baru.
     */
    public function create(Request $request)
    {
        $category = $request->query('category', 'penginapan');
        if (!in_array($category, ['penginapan', 'nongkrong', 'wisata'])) {
            $category = 'penginapan';
        }

        $lodgingFacilities = LodgingFacility::orderBy('facility_name', 'asc')->get();
        $hangoutFacilities = HangoutFacility::orderBy('facility_name', 'asc')->get();
        $touristFacilities = TouristFacility::orderBy('facility_name', 'asc')->get();

        return view('owner.lodgings.create', compact('category', 'lodgingFacilities', 'hangoutFacilities', 'touristFacilities'));
    }

    /**
     * Menyimpan pengajuan data tempat usaha baru.
     */
    public function store(Request $request)
    {
        $owner = Auth::user()->owner;
        if (!$owner) {
            return redirect()->route('home')->with('error', 'Profil Owner tidak ditemukan.');
        }

        $category = $request->input('category', 'penginapan');

        // Rule dasar untuk semua kategori
        $rules = [
            'category'          => ['required', 'in:penginapan,nongkrong,wisata'],
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
            'website'           => ['nullable', 'string', 'max:255'],
            'facilities'        => ['nullable', 'array'],
        ];

        if ($category === 'penginapan') {
            $rules['price_start'] = ['required', 'numeric', 'min:0'];
            $rules['price_end']   = ['required', 'numeric', 'gte:price_start'];
            $rules['check_in']    = ['nullable', 'string', 'max:255'];
            $rules['check_out']   = ['nullable', 'string', 'max:255'];
        } elseif ($category === 'wisata') {
            $rules['ticket_price'] = ['nullable', 'string', 'max:255'];
        }

        $request->validate($rules);

        if ($category === 'penginapan') {
            $place = Lodging::create([
                'owner_id'          => $owner->id,
                'name'              => $request->name,
                'description'       => $request->description,
                'operational_hours' => $request->operational_hours ?? '24 Jam',
                'check_in'          => $request->check_in ?? '14.00 WIB',
                'check_out'         => $request->check_out ?? '12.00 WIB',
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
                'website'           => $request->website,
                'price_start'       => $request->price_start,
                'price_end'         => $request->price_end,
                'status'            => 'pending',
                'status_claim'      => 'claimed',
            ]);
            if ($request->filled('facilities')) {
                $place->facilities()->sync($request->facilities);
            }
        } elseif ($category === 'nongkrong') {
            $place = HangoutPlace::create([
                'owner_id'          => $owner->id,
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
                'status'            => 'pending',
                'status_claim'      => 'claimed',
            ]);
            if ($request->filled('facilities')) {
                $place->facilities()->sync($request->facilities);
            }
        } elseif ($category === 'wisata') {
            $place = TouristPlace::create([
                'owner_id'          => $owner->id,
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
                'ticket_price'      => $request->ticket_price ?? 'Gratis',
                'status'            => 'pending',
                'status_claim'      => 'claimed',
            ]);
            if ($request->filled('facilities')) {
                $place->facilities()->sync($request->facilities);
            }
        }

        return redirect()->route('owner.lodgings.index')->with('success', 'Pengajuan tempat usaha baru berhasil disimpan dan berstatus Pending untuk diverifikasi oleh Admin.');
    }

    /**
     * Menampilkan form edit tempat usaha.
     */
    public function edit(Request $request, $id)
    {
        $owner = Auth::user()->owner;
        $category = $request->query('category', 'penginapan');

        if ($category === 'nongkrong') {
            $place = $owner->hangoutPlaces()->with('facilities')->findOrFail($id);
            $facilities = HangoutFacility::orderBy('facility_name', 'asc')->get();
        } elseif ($category === 'wisata') {
            $place = $owner->touristPlaces()->with('facilities')->findOrFail($id);
            $facilities = TouristFacility::orderBy('facility_name', 'asc')->get();
        } else {
            $category = 'penginapan';
            $place = $owner->lodgings()->with('facilities')->findOrFail($id);
            $facilities = LodgingFacility::orderBy('facility_name', 'asc')->get();
        }

        $lodgingFacilities = LodgingFacility::orderBy('facility_name', 'asc')->get();
        $hangoutFacilities = HangoutFacility::orderBy('facility_name', 'asc')->get();
        $touristFacilities = TouristFacility::orderBy('facility_name', 'asc')->get();

        return view('owner.lodgings.edit', compact('place', 'category', 'facilities', 'lodgingFacilities', 'hangoutFacilities', 'touristFacilities'));
    }

    /**
     * Memperbarui pengajuan data tempat usaha.
     */
    public function update(Request $request, $id)
    {
        $owner = Auth::user()->owner;
        $category = $request->input('category', 'penginapan');

        $rules = [
            'category'          => ['required', 'in:penginapan,nongkrong,wisata'],
            'name'              => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string'],
            'operational_hours' => ['required', 'string', 'max:255'],
            'address'           => ['nullable', 'string'],
            'district'          => ['nullable', 'string', 'max:255'],
            'village'           => ['nullable', 'string', 'max:255'],
            'postal_code'       => ['nullable', 'string', 'max:20'],
            'google_maps'       => ['required', 'string'],
            'manager_name'      => ['required', 'string', 'max:255'],
            'email'             => ['nullable', 'email', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:50'],
            'facilities'        => ['nullable', 'array'],
        ];

        if ($category === 'penginapan') {
            $rules['price_start'] = ['required', 'numeric', 'min:0'];
            $rules['price_end']   = ['required', 'numeric', 'gte:price_start'];
        }

        $request->validate($rules);

        if ($category === 'nongkrong') {
            $place = $owner->hangoutPlaces()->findOrFail($id);
            $place->update([
                'name'              => $request->name,
                'description'       => $request->description,
                'operational_hours' => $request->operational_hours,
                'address'           => $request->address,
                'district'          => $request->district,
                'village'           => $request->village,
                'postal_code'       => $request->postal_code,
                'google_maps'       => $request->google_maps,
                'manager_name'      => $request->manager_name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'status'            => 'pending',
            ]);
            if ($request->has('facilities')) {
                $place->facilities()->sync($request->facilities);
            } else {
                $place->facilities()->detach();
            }
        } elseif ($category === 'wisata') {
            $place = $owner->touristPlaces()->findOrFail($id);
            $place->update([
                'name'              => $request->name,
                'description'       => $request->description,
                'operational_hours' => $request->operational_hours,
                'address'           => $request->address,
                'district'          => $request->district,
                'village'           => $request->village,
                'postal_code'       => $request->postal_code,
                'google_maps'       => $request->google_maps,
                'manager_name'      => $request->manager_name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'ticket_price'      => $request->ticket_price ?? 'Gratis',
                'status'            => 'pending',
            ]);
            if ($request->has('facilities')) {
                $place->facilities()->sync($request->facilities);
            } else {
                $place->facilities()->detach();
            }
        } else {
            $place = $owner->lodgings()->findOrFail($id);
            $place->update([
                'name'              => $request->name,
                'description'       => $request->description,
                'operational_hours' => $request->operational_hours ?? '24 Jam',
                'check_in'          => $request->check_in ?? '14.00 WIB',
                'check_out'         => $request->check_out ?? '12.00 WIB',
                'address'           => $request->address,
                'district'          => $request->district,
                'village'           => $request->village,
                'postal_code'       => $request->postal_code,
                'google_maps'       => $request->google_maps,
                'manager_name'      => $request->manager_name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'website'           => $request->website,
                'price_start'       => $request->price_start,
                'price_end'         => $request->price_end,
                'status'            => 'pending',
            ]);
            if ($request->has('facilities')) {
                $place->facilities()->sync($request->facilities);
            } else {
                $place->facilities()->detach();
            }
        }

        return redirect()->route('owner.lodgings.index')->with('success', 'Data tempat usaha berhasil diperbarui dan diset ke Pending untuk verifikasi admin.');
    }

    /**
     * Menghapus data tempat usaha milik Owner.
     */
    public function destroy(Request $request, $id)
    {
        $owner = Auth::user()->owner;
        $category = $request->query('category', 'penginapan');

        if ($category === 'nongkrong') {
            $place = $owner->hangoutPlaces()->findOrFail($id);
        } elseif ($category === 'wisata') {
            $place = $owner->touristPlaces()->findOrFail($id);
        } else {
            $place = $owner->lodgings()->findOrFail($id);
        }

        $place->delete();

        return redirect()->route('owner.lodgings.index')->with('success', 'Data tempat usaha berhasil dihapus.');
    }
}
