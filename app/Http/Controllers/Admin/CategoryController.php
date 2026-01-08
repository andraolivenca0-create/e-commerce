<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori.
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menyimpan kategori baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);

        /* =========================
           HANDLE CHECKBOX
        ========================= */
        $validated['is_active'] = $request->boolean('is_active');

        /* =========================
           UPLOAD GAMBAR
        ========================= */
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('categories', 'public');
        }

        /* =========================
           SLUG UNIK & AMAN
        ========================= */
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        Category::create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Update kategori.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);

        /* =========================
           HANDLE CHECKBOX
        ========================= */
        $validated['is_active'] = $request->boolean('is_active');

        /* =========================
           GANTI GAMBAR
        ========================= */
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $validated['image'] = $request->file('image')
                ->store('categories', 'public');
        }

        /* =========================
           UPDATE SLUG
        ========================= */
        $validated['slug'] = $this->generateUniqueSlug(
            $validated['name'],
            $category->id
        );

        $category->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Hapus kategori.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'Kategori tidak dapat dihapus karena masih memiliki produk.'
            );
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    /**
     * Generate slug unik.
     */
    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}
