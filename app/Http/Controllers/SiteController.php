<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\GalleryImage;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Video;
use App\Support\Schema;
use App\Support\Seo;
use Illuminate\Contracts\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $faqs = Faq::active()->limit(5)->get();

        return view('pages.home', [
            'seo' => Seo::make()->schema(Schema::faq($faqs) ?? []),
            'services' => Service::active()->get(),
            'videos' => Video::active()->where('is_featured', true)->limit(4)->get(),
            'testimonials' => Testimonial::active()->limit(6)->get(),
            'faqs' => $faqs,
            'posts' => Post::published()->with('category')->limit(3)->get(),
        ]);
    }

    public function about(): View
    {
        return view('pages.about', [
            'seo' => Seo::make('من نحن', setting('about_lead'))
                ->image(setting('lawyer_photo'))
                ->breadcrumbs([['من نحن', route('about')]])
                ->schema(Schema::lawyer()),
            'services' => Service::active()->get(),
        ]);
    }

    public function services(): View
    {
        return view('pages.services.index', [
            'seo' => Seo::make('الخدمات القانونية', 'خدمات قانونية شاملة: الأحوال الشخصية، الجنايات والجنح، القضايا المدنية والعمالية، التحكيم الدولي، العقود، وتأسيس الشركات في مصر والسعودية.')
                ->breadcrumbs([['الخدمات', route('services.index')]]),
            'services' => Service::active()->get(),
        ]);
    }

    public function service(Service $service): View
    {
        abort_unless($service->is_active, 404);

        return view('pages.services.show', [
            'seo' => Seo::make($service->meta_title ?: $service->title, $service->meta_description ?: $service->excerpt)
                ->image($service->image)
                ->breadcrumbs([['الخدمات', route('services.index')], [$service->title, $service->url]])
                ->schema(Schema::service($service)),
            'service' => $service,
            'others' => Service::active()->whereKeyNot($service->id)->get(),
        ]);
    }

    public function blog(?PostCategory $category = null): View
    {
        $posts = Post::published()->with('category')
            ->when($category, fn ($query) => $query->where('post_category_id', $category->id))
            ->paginate(9);

        $title = $category ? 'مقالات '.$category->name : 'المدونة القانونية';
        $trail = [['المدونة', route('blog.index')]];

        if ($category) {
            $trail[] = [$category->name, route('blog.category', $category)];
        }

        return view('pages.blog.index', [
            'seo' => Seo::make($title, 'مقالات وتوعية قانونية مبسّطة من مؤسسة المستشار محمد رجب للمحاماة.')
                ->breadcrumbs($trail)
                ->noindex($posts->isEmpty()),
            'posts' => $posts,
            'category' => $category,
            'categories' => PostCategory::whereHas('posts', fn ($query) => $query->published())->orderBy('sort_order')->get(),
        ]);
    }

    public function post(Post $post): View
    {
        abort_unless($post->is_published && $post->published_at?->isPast(), 404);

        return view('pages.blog.show', [
            'seo' => Seo::make($post->meta_title ?: $post->title, $post->meta_description ?: $post->excerpt)
                ->image($post->image)
                ->type('article')
                ->breadcrumbs([['المدونة', route('blog.index')], [$post->title, $post->url]])
                ->schema(Schema::article($post)),
            'post' => $post,
            'related' => Post::published()->whereKeyNot($post->id)->limit(3)->get(),
        ]);
    }

    public function videos(): View
    {
        $videos = Video::active()->get();

        return view('pages.videos', [
            'seo' => Seo::make('الفيديوهات', 'مقاطع توعية قانونية ونصائح عملية من المستشار محمد رجب المحامي.')
                ->breadcrumbs([['الفيديوهات', route('videos')]])
                ->schema(...$videos->map(fn (Video $video) => Schema::video($video))->filter()->values()->all()),
            'videos' => $videos,
        ]);
    }

    public function gallery(): View
    {
        return view('pages.gallery', [
            'seo' => Seo::make('معرض الصور', 'صور من محاضرات وفعاليات ومكتب المستشار محمد رجب المحامي.')
                ->breadcrumbs([['معرض الصور', route('gallery')]]),
            'images' => GalleryImage::active()->get(),
        ]);
    }

    public function faq(): View
    {
        $faqs = Faq::active()->get();

        return view('pages.faq', [
            'seo' => Seo::make('الأسئلة الشائعة', 'إجابات عن أكثر الأسئلة التي تصلنا: حجز الاستشارة، المستندات المطلوبة، التوكيل من خارج مصر، وسرية البيانات.')
                ->breadcrumbs([['الأسئلة الشائعة', route('faq')]])
                ->schema(Schema::faq($faqs) ?? []),
            'faqs' => $faqs,
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', [
            'seo' => Seo::make('اتصل بنا', 'تواصل مع مؤسسة المستشار محمد رجب للمحاماة: هاتف، واتساب، أو نموذج طلب استشارة. العنوان: مدينة نصر، القاهرة.')
                ->breadcrumbs([['الاتصال', route('contact')]]),
            'services' => Service::active()->get(['id', 'title']),
        ]);
    }

    public function page(Page $page): View
    {
        abort_unless($page->is_active, 404);

        return view('pages.page', [
            'seo' => Seo::make($page->meta_title ?: $page->title, $page->meta_description)
                ->breadcrumbs([[$page->title, $page->url]]),
            'page' => $page,
        ]);
    }
}
