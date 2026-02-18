<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Inertia\Inertia;

class PageController extends Controller
{
    public function show(Page $page)
    {
        if ($page->status !== 'published') {
            abort(404);
        }

        $latestPosts = [];
        if (is_array($page->layout_data)) {
            $latestPostsBlocks = Page::findBlocksByType($page->layout_data, 'latest_posts');
            if (!empty($latestPostsBlocks)) {
                $maxCount = max(array_map(
                    fn ($b) => (int) ($b['data']['count'] ?? 6),
                    $latestPostsBlocks
                ));
                $latestPosts = Post::published()
                    ->with('author')
                    ->latest('published_at')
                    ->take($maxCount)
                    ->get();
            }
        }

        return Inertia::render('Page/Show', [
            'page' => $page,
            'latestPosts' => $latestPosts,
        ]);
    }
}
