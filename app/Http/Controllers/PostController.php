<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->with('author')
            ->latest('published_at')
            ->paginate(12);

        return Inertia::render('Post/Index', [
            'posts' => $posts,
        ]);
    }

    public function show(Post $post)
    {
        $post->load('author');

        return Inertia::render('Post/Show', [
            'post' => $post,
        ]);
    }
}
