<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'color'    => 'nullable|string|max:30',
            'bg_color' => 'nullable|string|max:60',
        ]);

        $base = Str::slug($request->input('name'));
        $slug = $base;
        $i    = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $category = Category::create([
            'slug'     => $slug,
            'name'     => $request->input('name'),
            'color'    => $request->input('color', '#00CFFF'),
            'bg_color' => $request->input('bg_color', 'rgba(0,207,255,0.08)'),
        ]);

        return response()->json(['success' => true, 'data' => $category], 201);
    }

    public function update(string $id, Request $request): JsonResponse
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'     => 'sometimes|string|max:100',
            'color'    => 'nullable|string|max:30',
            'bg_color' => 'nullable|string|max:60',
        ]);

        $category->update($request->only('name', 'color', 'bg_color'));

        return response()->json(['success' => true, 'data' => $category]);
    }

    public function destroy(string $id): JsonResponse
    {
        Category::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
