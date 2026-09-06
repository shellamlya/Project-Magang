<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $owner = $user->owner;

        if (!$owner) {
            return redirect()->route('home')->with('error', 'Profil Owner tidak ditemukan.');
        }

        // Ambil semua tempat usaha milik owner
        $allPlaces = $owner->allPlacesCollection();

        // Ambil filter kategori dari URL (jika ada)
        $category = $request->get('category');
        if ($category) {
            $allPlaces = $allPlaces->filter(function ($place) use ($category) {
                $cat = strtolower($place->category ?? class_basename($place));
                if ($category === 'lodging') {
                    return str_contains($cat, 'lodging') || str_contains($cat, 'hotel') || str_contains($cat, 'homestay');
                } elseif ($category === 'culinary') {
                    return str_contains($cat, 'culinary') || str_contains($cat, 'cafe') || str_contains($cat, 'restaurant');
                } elseif ($category === 'attraction') {
                    return str_contains($cat, 'attraction') || str_contains($cat, 'wisata');
                }
                return true;
            });
        }

        $totalViews = $allPlaces->sum('views_count');
        $totalMapClicks = $allPlaces->sum('maps_clicks_count');

        return view('owner.reports.index', compact(
            'owner',
            'allPlaces',
            'totalViews',
            'totalMapClicks'
        ));
    }
}