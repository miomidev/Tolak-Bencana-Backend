<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\AppInfo;
use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_successful_response(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_about_page_returns_successful_response(): void
    {
        $this->get(route('about'))->assertOk();
    }

    public function test_faq_page_lists_active_faqs(): void
    {
        Faq::create([
            'question' => 'Bagaimana cara melapor banjir?',
            'answer' => 'Gunakan fitur laporan di aplikasi.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->get(route('faq'))
            ->assertOk()
            ->assertSee('Bagaimana cara melapor banjir?');
    }

    public function test_home_page_shows_active_announcements(): void
    {
        Announcement::create([
            'key' => 'peringatan',
            'message' => 'Waspada cuaca ekstrem.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Waspada cuaca ekstrem.');
    }

    public function test_download_apk_returns_404_when_not_configured(): void
    {
        $this->get(route('download.apk'))->assertNotFound();
    }

    public function test_download_apk_redirects_to_external_link(): void
    {
        AppInfo::create([
            'app_name' => 'Kaliwedi',
            'developer' => 'KALIWEDI PKM-KC',
            'apk_path' => 'https://kaliwedi.example.com/kaliwedi.apk',
        ]);

        $this->get(route('download.apk'))
            ->assertRedirect('https://kaliwedi.example.com/kaliwedi.apk');
    }

    public function test_download_apk_serves_local_file(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('apks/kaliwedi.apk', 'dummy-content');

        AppInfo::create([
            'app_name' => 'Kaliwedi',
            'developer' => 'KALIWEDI PKM-KC',
            'apk_path' => 'apks/kaliwedi.apk',
            'apk_filename' => 'kaliwedi-release.apk',
        ]);

        $this->get(route('download.apk'))
            ->assertOk()
            ->assertHeaderContains('content-disposition', 'kaliwedi-release.apk');
    }
}
