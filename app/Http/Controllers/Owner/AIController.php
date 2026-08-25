<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\AIService;
use Illuminate\Http\Request;

class AIController extends Controller
{
    /**
     * Handle AJAX request for AI description generation.
     */
    public function generateDescription(Request $request)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'category'   => ['nullable', 'string'],
            'district'   => ['nullable', 'string'],
            'facilities' => ['nullable', 'array'],
        ]);

        $name = $request->name;
        $category = $request->category ?? 'penginapan';
        $district = $request->district;
        $facilities = $request->facilities ?? [];

        $description = AIService::generateDescription($name, $category, $district, $facilities);

        return response()->json([
            'success'     => true,
            'description' => $description,
        ]);
    }
}
