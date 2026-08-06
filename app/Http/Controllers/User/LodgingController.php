<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lodging;
use App\Models\Review;

/**
 * Class LodgingController
 * @package App\Http\Controllers\User
 * Pengendali halaman detail penginapan publik dan pengiriman ulasan (Review).
 */
class LodgingController extends Controller
{
    /**
     * Menampilkan detail informasi penginapan.
     */
    public function show($slug)
    {
        $lodging = Lodging::with(['category', 'district', 'village', 'facilities', 'owner.user', 'reviews' => function($q) {
            $q->latest();
        }])->where('slug', $slug)->firstOrFail();

        // Rekomendasi penginapan serupa di kecamatan yang sama
        $relatedLodgings = Lodging::approved()
            ->where('id', '!=', $lodging->id)
            ->where('district_id', $lodging->district_id)
            ->take(3)
            ->get();

        return view('user.lodging-detail', compact('lodging', 'relatedLodgings'));
    }

    /**
     * Memproses submit review pengunjung untuk penginapan ini.
     */
    public function storeReview(Request $request, $id)
    {
        $lodging = Lodging::findOrFail($id);

        $request->validate([
            'reviewer_name'  => ['required', 'string', 'max:255'],
            'reviewer_email' => ['nullable', 'email', 'max:255'],
            'rating'         => ['required', 'integer', 'min:1', 'max:5'],
            'comment'        => ['required', 'string', 'min:5'],
        ], [
            'reviewer_name.required' => 'Nama Anda wajib diisi.',
            'rating.required' => 'Rating bintang wajib dipilih (1-5).',
            'comment.required' => 'Ulasan wajib diisi.',
            'comment.min' => 'Ulasan minimal 5 karakter.',
        ]);

        // Simpan review baru
        Review::create([
            'lodging_id'     => $lodging->id,
            'reviewer_name'  => $request->reviewer_name,
            'reviewer_email' => $request->reviewer_email,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
        ]);

        // Hitung ulang rata-rata rating penginapan
        $avgRating = $lodging->reviews()->avg('rating');
        $reviewCount = $lodging->reviews()->count();

        $lodging->update([
            'rating' => round($avgRating, 2),
            'review_count' => $reviewCount,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil ditambahkan.');
    }
}
