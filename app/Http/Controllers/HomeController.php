<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Settings\HomepageSettings;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(HomepageSettings $settings)
    {
        $page = null;
        $latestPosts = [];

        if ($settings->homepage_page_id) {
            $page = Page::find($settings->homepage_page_id);
        }

        // Gather latest posts if needed by blocks or featured posts setting
        $postsCount = $this->getLatestPostsCount($page, $settings->featured_posts_count);
        if ($postsCount > 0) {
            $latestPosts = Post::published()
                ->with('author')
                ->latest('published_at')
                ->take($postsCount)
                ->get();
        }

        return Inertia::render('Home', [
            'page' => $page,
            'featuredPosts' => $settings->featured_posts_count > 0 ? $latestPosts : [],
            'latestPosts' => $latestPosts,
        ]);
    }

    private function getLatestPostsCount(?Page $page, int $featuredCount): int
    {
        $max = $featuredCount;

        if ($page && is_array($page->layout_data)) {
            $latestPostsBlocks = Page::findBlocksByType($page->layout_data, 'latest_posts');
            foreach ($latestPostsBlocks as $block) {
                $blockCount = (int) ($block['data']['count'] ?? 6);
                $max = max($max, $blockCount);
            }
        }

        return $max;
    }
}
