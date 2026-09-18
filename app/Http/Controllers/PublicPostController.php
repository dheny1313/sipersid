<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PublicPostController extends Controller
{
    //
    public function index()
    {
        // Mengambil daftar berita yang berstatus 'is_published' = true
        $posts = Post::where('is_published', true)
                     ->orderBy('created_at', 'desc')
                     ->paginate(9); // 9 berita per halaman

        // Catatan: Anda perlu membuat view 'public.posts.index' nanti
        return view('public.posts.index', compact('posts'));
    }

    public function show($slug)
    {
        // Mengambil detail berita berdasarkan slug yang dipublish
        $post = Post::where('slug', $slug)
                    ->where('is_published', true)
                    ->firstOrFail();

        // Catatan: Anda perlu membuat view 'public.posts.show' nanti
        return view('public.posts.show', compact('post'));
    }
}
