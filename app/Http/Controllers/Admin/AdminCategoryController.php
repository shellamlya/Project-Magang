<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

/**
 * Class AdminCategoryController
 * @package App\Http\Controllers\Admin
 * Pengendali Kelola Master Data Kategori.
 */
class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('lodgings')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'service_type' => ['required', 'string'],
            'icon'         => ['nullable', 'string'],
        ]);

        Category::create([
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'service_type' => $request->service_type,
            'icon'         => $request->icon ?? 'fa-building',
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'service_type' => ['required', 'string'],
            'icon'         => ['nullable', 'string'],
        ]);

        $category->update([
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'service_type' => $request->service_type,
            'icon'         => $request->icon,
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
