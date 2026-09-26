<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PublicPostController extends Controller
{
    // Menampilkan daftar semua berita (Paginasi & Pencarian)
    public function index(Request $request)
    {
        // Hanya ambil berita yang is_published = true (1)
        $query = Post::where('is_published', true)->with('author');

        // Fitur Pencarian Judul
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Menggunakan paginate(9) agar dalam 1 halaman maksimal 9 berita (3 kolom x 3 baris)
        $posts = $query->latest()->paginate(9);

        // Ambil semua kategori unik yang ada di database untuk menu dropdown filter
        $categories = Post::where('is_published', true)->distinct()->pluck('category');

        return view('public.posts.index', compact('posts', 'categories'));
    }

    // Menampilkan halaman detail baca berita
    public function show($slug)
    {
        // Cari berita berdasarkan slug yang statusnya publish
        $post = Post::where('slug', $slug)
                    ->where('is_published', true)
                    ->with('author')
                    ->firstOrFail();

        // Ambil 3 berita lain dengan kategori yang sama untuk "Berita Terkait"
        $relatedPosts = Post::where('category', $post->category)
                            ->where('id', '!=', $post->id)
                            ->where('is_published', true)
                            ->latest()
                            ->take(3)
                            ->get();

        return view('public.posts.show', compact('post', 'relatedPosts'));
    }
}
