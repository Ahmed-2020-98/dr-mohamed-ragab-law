<?php

namespace Tests\Feature;

use App\Models\ConsultationRequest;
use App\Models\Post;
use App\Models\Service;
use App\Models\User;
use App\Support\Settings;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ContentSeeder::class);
    }

    public function test_public_pages_render(): void
    {
        foreach (['/', '/about', '/services', '/blogs', '/videos', '/gallery', '/faq', '/contact', '/privacy'] as $uri) {
            $this->get($uri)->assertOk()->assertSee('lang="ar" dir="rtl"', false);
        }
    }

    public function test_home_has_seo_tags_and_structured_data(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<meta name="description"', false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('og:image', false)
            ->assertSee('"LegalService"', false)
            ->assertSee('"FAQPage"', false)
            ->assertSee('المستشار محمد رجب المحامي');
    }

    public function test_every_page_has_a_single_h1(): void
    {
        $service = Service::first();

        foreach (['/', '/about', '/services', $service->url, '/contact', '/faq'] as $uri) {
            $html = $this->get($uri)->getContent();
            $this->assertSame(1, substr_count($html, '<h1'), "Expected one <h1> on {$uri}");
        }
    }

    public function test_service_page_shows_details(): void
    {
        $service = Service::where('slug', 'الأحوال-الشخصية')->firstOrFail();

        $this->get($service->url)
            ->assertOk()
            ->assertSee($service->title)
            ->assertSee($service->highlights[0])
            ->assertSee('"Service"', false)
            ->assertSee('"BreadcrumbList"', false);
    }

    public function test_hidden_service_returns_404(): void
    {
        $service = Service::first();
        $service->update(['is_active' => false]);

        $this->get($service->url)->assertNotFound();
    }

    public function test_draft_posts_are_hidden_and_published_posts_show(): void
    {
        $post = Post::first();

        $this->get($post->url)->assertNotFound();
        $this->get('/blogs')->assertDontSee($post->title);

        $post->update(['is_published' => true]);

        $this->assertNotNull($post->fresh()->published_at);
        $this->get($post->url)->assertOk()->assertSee($post->title)->assertSee('"Article"', false);
        $this->get('/blogs')->assertSee($post->title);
    }

    public function test_sitemap_and_robots(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(Service::first()->url, false);

        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap: ')->assertSee('Disallow: /admin');

        app(Settings::class)->put(['noindex' => true]);

        $this->get('/robots.txt')->assertSee('Disallow: /');
        $this->get('/')->assertSee('noindex, nofollow', false);
    }

    public function test_old_wordpress_addresses(): void
    {
        $this->get('/works')->assertRedirect('/');
        $this->get('/imported')->assertRedirect('/');
        $this->get('/wyplacalne-kasyna-internetowe-jak-dzialaja-limity-i-wymagania-wyplat')->assertStatus(410);
        $this->get('/author/someone')->assertStatus(410);
        $this->get('/portfolio-item/flowers-poster')->assertStatus(410);
        $this->get('/not-a-real-page')->assertNotFound();
    }

    public function test_consultation_request_is_saved(): void
    {
        $service = Service::first();

        $this->post('/contact', [
            'name' => 'أحمد علي',
            'phone' => '01004312938',
            'service_id' => $service->id,
            'message' => 'أريد استشارة',
        ])->assertRedirect(route('contact').'#consultation-form');

        $this->assertDatabaseHas('consultation_requests', [
            'name' => 'أحمد علي',
            'phone' => '01004312938',
            'service_id' => $service->id,
            'status' => 'new',
        ]);
    }

    public function test_consultation_request_validation(): void
    {
        $this->from('/contact')
            ->post('/contact', ['name' => '', 'phone' => 'abc'])
            ->assertRedirect('/contact')
            ->assertSessionHasErrors(['name', 'phone']);

        $this->assertSame(0, ConsultationRequest::count());
    }

    public function test_honeypot_drops_bots_silently(): void
    {
        $this->post('/contact', ['name' => 'Bot', 'phone' => '01000000000', 'website' => 'http://spam.test'])
            ->assertRedirect();

        $this->assertSame(0, ConsultationRequest::count());
    }

    public function test_admin_requires_login_and_works_for_users(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');

        $this->actingAs(User::factory()->create());

        foreach (['/admin', '/admin/services', '/admin/posts', '/admin/consultation-requests', '/admin/settings/general', '/admin/settings/content', '/admin/settings/contact', '/admin/settings/seo', '/admin/videos', '/admin/gallery-images'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_settings_changes_appear_on_the_site(): void
    {
        app(Settings::class)->put(['hero_title' => 'عنوان تجريبي جديد']);

        $this->get('/')->assertSee('عنوان تجريبي جديد');
    }

    public function test_helpers(): void
    {
        $this->assertSame('201004312938', phone_intl('01004312938'));
        $this->assertSame('966503554199', phone_intl('+966503554199'));
        $this->assertSame('201004312938', phone_intl('٠١٠٠٤٣١٢٩٣٨'));
        $this->assertSame('تأسيس-الشركات-في-مصر', ar_slug('تأسيس الشركات في مصر'));
        $this->assertSame('٠٧', ar_num(7, 2));
    }
}
