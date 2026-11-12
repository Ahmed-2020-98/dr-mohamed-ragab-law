<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/about', [SiteController::class, 'about'])->name('about');

Route::get('/services', [SiteController::class, 'services'])->name('services.index');
Route::get('/services/{service}', [SiteController::class, 'service'])->name('services.show');

// "/blogs" keeps the address the previous website used for its blog.
Route::get('/blogs', [SiteController::class, 'blog'])->name('blog.index');
Route::get('/blogs/category/{category}', [SiteController::class, 'blog'])->name('blog.category');
Route::get('/blogs/{post}', [SiteController::class, 'post'])->name('blog.show');

Route::get('/videos', [SiteController::class, 'videos'])->name('videos');
Route::get('/gallery', [SiteController::class, 'gallery'])->name('gallery');
Route::get('/faq', [SiteController::class, 'faq'])->name('faq');

Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:6,10')->name('contact.store');

Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

/*
| Addresses from the previous WordPress site.
| Template leftovers go home; the spam posts injected into the old blog (and the
| WordPress archives around them) answer 410 Gone so search engines drop them.
*/
Route::permanentRedirect('/works', '/');
Route::permanentRedirect('/imported', '/');
Route::permanentRedirect('/blog', '/blogs');
Route::permanentRedirect('/wp-sitemap.xml', '/sitemap.xml');

$gone = fn () => abort(410);

foreach ([
    '/on-top-of-all-that-its-price-mentioning-that-the-arcwave-voy',
    '/how-to-evaluate-the-reliability-of-online-information',
    '/wyplacalne-kasyna-internetowe-na-co-zwrocic-uwage-przy-wyborze-platformy',
    '/wyplacalne-kasyna-internetowe-jak-dzialaja-limity-i-wymagania-wyplat',
] as $spamPath) {
    Route::get($spamPath, $gone);
}

Route::get('/{legacy}/{any?}', $gone)
    ->where('legacy', 'author|portfolio-item|portfolio-types|offer-types|slide-types|category|tag|wp-content|wp-includes')
    ->where('any', '.*');

// Free-form pages managed from the admin panel (privacy policy, ...). Keep last.
Route::get('/{page}', [SiteController::class, 'page'])
    ->where('page', '(?!admin$|up$|livewire|storage)[^/]+')
    ->name('pages.show');
