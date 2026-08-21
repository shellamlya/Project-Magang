<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

/**
 * Class AdminCategoryController
 * @package App\Http\Controllers\Admin
 * Pengendali Kelola Master Data Kategori (Penginapan, Wisata, Nongkrong).
 */
class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $serviceType = $request->get('service_type');

        $query = Category::query();
        if ($request->filled('service_type')) {
            if ($serviceType === 'penginapan') {
                $query->whereIn('service_type', ['penginapan', 'shella']);
            } else {
                $query->where('service_type', $serviceType);
            }
        }

        $categories = $query->latest()->get();

        $countPenginapan = Category::whereIn('service_type', ['penginapan', 'shella'])->count();
        $countWisata     = Category::where('service_type', 'wisata')->count();
        $countNongkrong  = Category::where('service_type', 'nongkrong')->count();

        return view('admin.categories.index', compact(
            'categories',
            'serviceType',
            'countPenginapan',
            'countWisata',
            'countNongkrong'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'service_type' => ['required', 'string', 'in:penginapan,wisata,nongkrong'],
            'icon'         => ['nullable', 'string'],
        ]);

        Category::create([
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'service_type' => $request->service_type,
            'icon'         => $request->icon ?? 'fa-layer-group',
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'service_type' => ['required', 'string', 'in:penginapan,wisata,nongkrong'],
            'icon'         => ['nullable', 'string'],
        ]);

        $category->update([
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'service_type' => $request->service_type,
            'icon'         => $request->icon ?? 'fa-layer-group',
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
