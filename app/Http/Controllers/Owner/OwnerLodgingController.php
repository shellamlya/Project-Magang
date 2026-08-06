<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lodging;
use App\Models\LodgingFacility;

/**
 * Class OwnerLodgingController
 * @package App\Http\Controllers\Owner
 * Pengendali CRUD pengajuan data penginapan oleh Pemilik Penginapan (Owner).
 */
class OwnerLodgingController extends Controller
{
    /**
     * Menampilkan daftar penginapan milik Owner.
     */
    public function index()
    {
        $owner = Auth::user()->owner;
        $lodgings = $owner ? $owner->lodgings()->with('facilities')->latest()->paginate(10) : collect();

        return view('owner.lodgings.index', compact('lodgings'));
    }

    /**
     * Menampilkan form penambahan penginapan baru.
     */
    public function create()
    {
        $facilities = LodgingFacility::orderBy('facility_name', 'asc')->get();

        return view('owner.lodgings.create', compact('facilities'));
    }

    /**
     * Menyimpan pengajuan data penginapan baru.
     */
    public function store(Request $request)
    {
        $owner = Auth::user()->owner;

        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string'],
            'operational_hours' => ['required', 'string', 'max:255'],
            'check_in'          => ['nullable', 'string', 'max:255'],
            'check_out'         => ['nullable', 'string', 'max:255'],
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
            'price_start'       => ['required', 'numeric', 'min:0'],
            'price_end'         => ['required', 'numeric', 'gte:price_start'],
            'facilities'        => ['nullable', 'array'],
            'facilities.*'      => ['exists:lodging_facilities,id'],
        ]);

        $lodging = Lodging::create([
            'owner_id'          => $owner ? $owner->id : null,
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
            'status'            => 'pending', // default pending verifikasi admin
        ]);

        if ($request->filled('facilities')) {
            $lodging->facilities()->sync($request->facilities);
        }

        return redirect()->route('owner.lodgings.index')->with('success', 'Pengajuan penginapan baru berhasil disimpan dan saat ini berstatus Pending untuk diverifikasi oleh Admin.');
    }

    /**
     * Menampilkan form edit penginapan.
     */
    public function edit($id)
    {
        $owner = Auth::user()->owner;
        $lodging = $owner->lodgings()->with('facilities')->findOrFail($id);
        $facilities = LodgingFacility::orderBy('facility_name', 'asc')->get();

        return view('owner.lodgings.edit', compact('lodging', 'facilities'));
    }

    /**
     * Memperbarui pengajuan data penginapan.
     */
    public function update(Request $request, $id)
    {
        $owner = Auth::user()->owner;
        $lodging = $owner->lodgings()->findOrFail($id);

        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string'],
            'operational_hours' => ['required', 'string', 'max:255'],
            'check_in'          => ['nullable', 'string', 'max:255'],
            'check_out'         => ['nullable', 'string', 'max:255'],
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
            'price_start'       => ['required', 'numeric', 'min:0'],
            'price_end'         => ['required', 'numeric', 'gte:price_start'],
            'facilities'        => ['nullable', 'array'],
            'facilities.*'      => ['exists:lodging_facilities,id'],
        ]);

        $lodging->update([
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
            'status'            => 'pending', // Kembali ke status pending
        ]);

        if ($request->has('facilities')) {
            $lodging->facilities()->sync($request->facilities);
        } else {
            $lodging->facilities()->detach();
        }

        return redirect()->route('owner.lodgings.index')->with('success', 'Data penginapan berhasil diperbarui. Status diset ke Pending untuk diverifikasi kembali oleh Admin.');
    }

    /**
     * Menghapus data penginapan milik Owner.
     */
    public function destroy($id)
    {
        $owner = Auth::user()->owner;
        $lodging = $owner->lodgings()->findOrFail($id);
        $lodging->delete();

        return redirect()->route('owner.lodgings.index')->with('success', 'Data penginapan berhasil dihapus.');
    }
}
