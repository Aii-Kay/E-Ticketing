<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

// Controller untuk manajemen kategori event oleh admin
class CategoryController extends Controller
{
    // List semua kategori
    public function index(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    // Form create kategori
    public function create(): View
    {
        return view('admin.categories.create');
    }

    // Simpan kategori baru
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|string|max:2444',
        ]);

        Category::create($data);

        return redirect()->route('admin.categories.index');
    }

    // Form edit kategori
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Update kategori
    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $category->update($data);

        return redirect()->route('admin.categories.index');
    }

    // Hapus kategori
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index');
    }
}
