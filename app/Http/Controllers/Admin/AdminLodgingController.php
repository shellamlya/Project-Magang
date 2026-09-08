<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lodging;
use App\Models\LodgingFacility;
use App\Models\Owner;

/**
 * Class AdminLodgingController
 * @package App\Http\Controllers\Admin
 * Pengendali Kelola Data Penginapan penuh dari sisi Admin.
 */
class AdminLodgingController extends Controller
{
    public function index(Request $request)
    {
        $query = Lodging::with(['owner.user', 'facilities']);

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

        $lodgings = $query->latest()->paginate(10)->withQueryString();

        return view('admin.lodgings.index', compact('lodgings'));
    }

    public function create()
    {
        $owners     = Owner::with('user')->get();
        $facilities = LodgingFacility::orderBy('facility_name', 'asc')->get();

        return view('admin.lodgings.create', compact('owners', 'facilities'));
    }

    public function store(Request $request)
    {
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
            'instagram'         => ['nullable', 'string', 'max:255'],
            'price_start'       => ['required', 'numeric', 'min:0'],
            'price_end'         => ['required', 'numeric', 'gte:price_start'],
            'status'            => ['required', 'in:pending,approved,rejected'],
            'owner_id'          => ['nullable', 'exists:owners,id'],
            'facilities'        => ['nullable', 'array'],
            'facilities.*'      => ['exists:lodging_facilities,id'],
        ]);

        $lodging = Lodging::create([
            'owner_id'          => $request->owner_id,
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
            'instagram'         => $request->filled('instagram') ? trim($request->instagram) : null,
            'price_start'       => $request->price_start,
            'price_end'         => $request->price_end,
            'status'            => $request->status,
        ]);

        if ($request->filled('facilities')) {
            $lodging->facilities()->sync($request->facilities);
        }

        return redirect()->route('admin.lodgings.index')->with('success', 'Data penginapan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $lodging    = Lodging::with('facilities')->findOrFail($id);
        $owners     = Owner::with('user')->get();
        $facilities = LodgingFacility::orderBy('facility_name', 'asc')->get();

        return view('admin.lodgings.edit', compact('lodging', 'owners', 'facilities'));
    }

    public function update(Request $request, $id)
    {
        $lodging = Lodging::findOrFail($id);

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
            'instagram'         => ['nullable', 'string', 'max:255'],
            'price_start'       => ['required', 'numeric', 'min:0'],
            'price_end'         => ['required', 'numeric', 'gte:price_start'],
            'status'            => ['required', 'in:pending,approved,rejected'],
            'owner_id'          => ['nullable', 'exists:owners,id'],
            'facilities'        => ['nullable', 'array'],
            'facilities.*'      => ['exists:lodging_facilities,id'],
        ]);

        $lodging->update([
            'owner_id'          => $request->owner_id,
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
            'instagram'         => $request->filled('instagram') ? trim($request->instagram) : null,
            'price_start'       => $request->price_start,
            'price_end'         => $request->price_end,
            'status'            => $request->status,
        ]);

        if ($request->has('facilities')) {
            $lodging->facilities()->sync($request->facilities);
        } else {
            $lodging->facilities()->detach();
        }

        return redirect()->route('admin.lodgings.index')->with('success', 'Data penginapan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lodging = Lodging::findOrFail($id);
        $lodging->delete();

        return redirect()->route('admin.lodgings.index')->with('success', 'Data penginapan berhasil dihapus.');
    }
}
