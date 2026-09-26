<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        // Menampilkan berita terbaru di atas, include nama penulis (author)
        $posts = Post::with('author')->latest()->get();
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'is_published' => 'required|boolean',
            'content'      => 'required|string',
            'image'        => 'required|image|mimes:jpeg,png,jpg,webp|max:3072', // Maks 3MB, support webp
        ]);

        // Simpan gambar dengan struktur folder: posts/images/Tahun/Bulan
        $folderPath = 'posts/images/' . date('Y/m');
        $validated['image']   = $request->file('image')->store($folderPath, 'public');
        $validated['user_id'] = auth()->id() ?? 1;

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil dipublikasikan!');
    }

    public function show(Post $post)
    {
        // Opsional: Untuk preview berita
        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'is_published' => 'required|boolean',
            'content'      => 'required|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        // Jika judul berubah, kita BISA perbarui slug-nya (opsional, tapi baik untuk SEO jika belum terindeks lama)
        if ($request->title !== $post->title) {
            $validated['slug'] = Str::slug($request->title . '-' . now()->timestamp);
        }

        // Jika admin mengganti gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $folderPath = 'posts/images/' . date('Y/m');
            $validated['image'] = $request->file('image')->store($folderPath, 'public');
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(Post $post)
    {
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil dihapus secara permanen.');
    }
}
