<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('about'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('services.index'), 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => route('videos'), 'priority' => '0.6', 'changefreq' => 'weekly'],
            ['loc' => route('gallery'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => route('faq'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('contact'), 'priority' => '0.8', 'changefreq' => 'yearly'],
        ]);

        foreach (Service::active()->get() as $service) {
            $urls->push(['loc' => $service->url, 'lastmod' => $service->updated_at, 'priority' => '0.9', 'changefreq' => 'monthly']);
        }

        $posts = Post::published()->get();

        if ($posts->isNotEmpty()) {
            $urls->push(['loc' => route('blog.index'), 'lastmod' => $posts->max('updated_at'), 'priority' => '0.7', 'changefreq' => 'weekly']);

            foreach (PostCategory::whereHas('posts', fn ($query) => $query->published())->get() as $category) {
                $urls->push(['loc' => route('blog.category', $category), 'priority' => '0.5', 'changefreq' => 'weekly']);
            }

            foreach ($posts as $post) {
                $urls->push(['loc' => $post->url, 'lastmod' => $post->updated_at, 'priority' => '0.7', 'changefreq' => 'monthly']);
            }
        }

        foreach (Page::where('is_active', true)->get() as $page) {
            $urls->push(['loc' => $page->url, 'lastmod' => $page->updated_at, 'priority' => '0.3', 'changefreq' => 'yearly']);
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = setting('noindex', false)
            ? ['User-agent: *', 'Disallow: /']
            : ['User-agent: *', 'Allow: /', 'Disallow: /admin', 'Disallow: /livewire', '', 'Sitemap: '.route('sitemap')];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
