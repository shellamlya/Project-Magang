<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
 * v2: Support upload Thumbnail (wajib), Photo1 & Photo2 (opsional), AI Summary.
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
        $owner = Auth::user()->owner;
        if (!$owner || $owner->verification_status !== 'approved') {
            return redirect()->route('owner.dashboard')->with('error', 'Akun Anda belum disetujui oleh Admin. Anda belum dapat mengajukan tempat usaha.');
        }

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
        if (!$owner || $owner->verification_status !== 'approved') {
            return redirect()->route('owner.dashboard')->with('error', 'Akun Anda belum disetujui oleh Admin. Anda belum dapat mengajukan tempat usaha.');
        }

        $category = $request->input('category', 'penginapan');

        // Rule dasar untuk semua kategori
        $rules = [
            'category'          => ['required', 'in:penginapan,nongkrong,wisata'],
            'name'              => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string'],
            'ai_summary'        => ['nullable', 'string', 'max:500'],
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
            'phone'             => ['required', 'string', 'max:50'],
            'website'           => ['nullable', 'string', 'max:255'],
            'instagram'         => ['nullable', 'string', 'max:255'],
            'facilities'        => ['nullable', 'array'],
            // Media — Thumbnail WAJIB saat create
            'thumbnail'         => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'photo_1'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'photo_2'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if ($category === 'penginapan') {
            $rules['price_start'] = ['required', 'numeric', 'min:0'];
            $rules['price_end']   = ['required', 'numeric', 'gte:price_start'];
            $rules['check_in']    = ['nullable', 'string', 'max:255'];
            $rules['check_out']   = ['nullable', 'string', 'max:255'];
        } elseif ($category === 'wisata') {
            $rules['ticket_price'] = ['nullable', 'string', 'max:255'];
        }

        $request->validate($rules, [
            'name.required'        => 'Nama tempat usaha wajib diisi.',
            'phone.required'       => 'Nomor WhatsApp bisnis / reservasi wajib diisi.',
            'description.required' => 'Deskripsi tempat usaha wajib diisi.',
            'google_maps.required' => 'Link Google Maps wajib diisi.',
            'thumbnail.required'   => 'Foto thumbnail utama wajib diunggah.',
            'thumbnail.image'      => 'File thumbnail harus berupa gambar (JPG, PNG, WEBP).',
            'thumbnail.max'        => 'Ukuran foto thumbnail maksimal 2 MB.',
        ]);

        // Upload foto
        $thumbnailPath = $this->uploadPhoto($request, 'thumbnail', $category);
        $photo1Path    = $this->uploadPhoto($request, 'photo_1', $category);
        $photo2Path    = $this->uploadPhoto($request, 'photo_2', $category);

        $instagramValue = $request->filled('instagram') ? trim($request->instagram) : null;

        if ($category === 'penginapan') {
            $place = Lodging::create([
                'owner_id'          => $owner->id,
                'name'              => $request->name,
                'description'       => $request->description,
                'ai_summary'        => $request->ai_summary,
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
                'instagram'         => $instagramValue,
                'price_start'       => $request->price_start,
                'price_end'         => $request->price_end,
                'thumbnail'         => $thumbnailPath,
                'photo_1'           => $photo1Path,
                'photo_2'           => $photo2Path,
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
                'ai_summary'        => $request->ai_summary,
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
                'instagram'         => $instagramValue,
                'thumbnail'         => $thumbnailPath,
                'photo_1'           => $photo1Path,
                'photo_2'           => $photo2Path,
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
                'ai_summary'        => $request->ai_summary,
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
                'instagram'         => $instagramValue,
                'ticket_price'      => $request->ticket_price ?? 'Gratis',
                'thumbnail'         => $thumbnailPath,
                'photo_1'           => $photo1Path,
                'photo_2'           => $photo2Path,
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
            'ai_summary'        => ['nullable', 'string', 'max:500'],
            'operational_hours' => ['required', 'string', 'max:255'],
            'address'           => ['nullable', 'string'],
            'district'          => ['nullable', 'string', 'max:255'],
            'village'           => ['nullable', 'string', 'max:255'],
            'postal_code'       => ['nullable', 'string', 'max:20'],
            'google_maps'       => ['required', 'string'],
            'manager_name'      => ['required', 'string', 'max:255'],
            'email'             => ['nullable', 'email', 'max:255'],
            'phone'             => ['required', 'string', 'max:50'],
            'website'           => ['nullable', 'string', 'max:255'],
            'instagram'         => ['nullable', 'string', 'max:255'],
            'facilities'        => ['nullable', 'array'],
            // Media — Thumbnail opsional saat update (hanya ganti jika diupload)
            'thumbnail'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'photo_1'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'photo_2'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if ($category === 'penginapan') {
            $rules['price_start'] = ['required', 'numeric', 'min:0'];
            $rules['price_end']   = ['required', 'numeric', 'gte:price_start'];
        } elseif ($category === 'wisata') {
            $rules['ticket_price'] = ['nullable', 'string', 'max:255'];
        }

        $request->validate($rules, [
            'name.required'        => 'Nama tempat usaha wajib diisi.',
            'phone.required'       => 'Nomor WhatsApp bisnis / reservasi wajib diisi.',
            'description.required' => 'Deskripsi tempat usaha wajib diisi.',
            'google_maps.required' => 'Link Google Maps wajib diisi.',
        ]);

        $instagramValue = $request->filled('instagram') ? trim($request->instagram) : null;

        if ($category === 'nongkrong') {
            $place = $owner->hangoutPlaces()->findOrFail($id);
            $updateData = [
                'name'              => $request->name,
                'description'       => $request->description,
                'ai_summary'        => $request->ai_summary,
                'operational_hours' => $request->operational_hours,
                'address'           => $request->address,
                'district'          => $request->district,
                'village'           => $request->village,
                'postal_code'       => $request->postal_code,
                'google_maps'       => $request->google_maps,
                'manager_name'      => $request->manager_name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'instagram'         => $instagramValue,
                'status'            => 'pending',
            ];
            if ($request->hasFile('thumbnail')) $updateData['thumbnail'] = $this->replacePhoto($request, 'thumbnail', $place->thumbnail, $category);
            if ($request->hasFile('photo_1'))   $updateData['photo_1']   = $this->replacePhoto($request, 'photo_1', $place->photo_1, $category);
            if ($request->hasFile('photo_2'))   $updateData['photo_2']   = $this->replacePhoto($request, 'photo_2', $place->photo_2, $category);
            $place->update($updateData);
            if ($request->has('facilities')) {
                $place->facilities()->sync($request->facilities);
            } else {
                $place->facilities()->detach();
            }
        } elseif ($category === 'wisata') {
            $place = $owner->touristPlaces()->findOrFail($id);
            $updateData = [
                'name'              => $request->name,
                'description'       => $request->description,
                'ai_summary'        => $request->ai_summary,
                'operational_hours' => $request->operational_hours,
                'address'           => $request->address,
                'district'          => $request->district,
                'village'           => $request->village,
                'postal_code'       => $request->postal_code,
                'google_maps'       => $request->google_maps,
                'manager_name'      => $request->manager_name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'instagram'         => $instagramValue,
                'ticket_price'      => $request->ticket_price ?? 'Gratis',
                'status'            => 'pending',
            ];
            if ($request->hasFile('thumbnail')) $updateData['thumbnail'] = $this->replacePhoto($request, 'thumbnail', $place->thumbnail, $category);
            if ($request->hasFile('photo_1'))   $updateData['photo_1']   = $this->replacePhoto($request, 'photo_1', $place->photo_1, $category);
            if ($request->hasFile('photo_2'))   $updateData['photo_2']   = $this->replacePhoto($request, 'photo_2', $place->photo_2, $category);
            $place->update($updateData);
            if ($request->has('facilities')) {
                $place->facilities()->sync($request->facilities);
            } else {
                $place->facilities()->detach();
            }
        } else {
            $place = $owner->lodgings()->findOrFail($id);
            $updateData = [
                'name'              => $request->name,
                'description'       => $request->description,
                'ai_summary'        => $request->ai_summary,
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
                'instagram'         => $instagramValue,
                'price_start'       => $request->price_start,
                'price_end'         => $request->price_end,
                'status'            => 'pending',
            ];
            if ($request->hasFile('thumbnail')) $updateData['thumbnail'] = $this->replacePhoto($request, 'thumbnail', $place->thumbnail, $category);
            if ($request->hasFile('photo_1'))   $updateData['photo_1']   = $this->replacePhoto($request, 'photo_1', $place->photo_1, $category);
            if ($request->hasFile('photo_2'))   $updateData['photo_2']   = $this->replacePhoto($request, 'photo_2', $place->photo_2, $category);
            $place->update($updateData);
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

    // ── Private Helpers ───────────────────────────────────────────────────────

    /**
     * Upload foto ke storage dan return path relatif.
     * Digunakan saat CREATE (foto baru).
     *
     * @param  Request $request
     * @param  string  $field   Nama field input ('thumbnail', 'photo_1', 'photo_2')
     * @param  string  $category Kategori tempat ('penginapan', 'nongkrong', 'wisata')
     * @return string|null
     */
    private function uploadPhoto(Request $request, string $field, string $category): ?string
    {
        if (!$request->hasFile($field)) {
            return null;
        }
        // Simpan ke: storage/app/public/places/{category}/
        return $request->file($field)->store('places/' . $category, 'public');
    }

    /**
     * Ganti foto lama dengan foto baru saat UPDATE.
     * Foto lama dihapus dari storage sebelum upload foto baru.
     *
     * @param  Request     $request
     * @param  string      $field       Nama field input
     * @param  string|null $oldPath     Path foto lama yang akan dihapus
     * @param  string      $category
     * @return string|null
     */
    private function replacePhoto(Request $request, string $field, ?string $oldPath, string $category): ?string
    {
        // Hapus file lama jika ada
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
        return $this->uploadPhoto($request, $field, $category);
    }
}
