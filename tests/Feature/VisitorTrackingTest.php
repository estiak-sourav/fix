<?php

namespace Tests\Feature;

use App\Models\DailyAnalytics;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class VisitorTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_human_visitor_is_tracked_and_marked_as_new(): void
    {
        $today = today()->toDateString();

        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml',
        ])->get('/');

        $response->assertStatus(200);
        $response->assertCookie('fix_returning_user', '1');

        $record = DailyAnalytics::forDate($today);
        $this->assertEquals(1, $record->total_visitors);
        $this->assertEquals(1, $record->new_visitors);
    }

    public function test_refreshing_page_or_multi_tab_does_not_inflate_visitor_count(): void
    {
        $today = today()->toDateString();
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml',
        ];

        // First visit
        $this->withHeaders($headers)->get('/');

        // Second visit (same session & cookies)
        $this->withHeaders($headers)->get('/about');

        // Third visit (refreshing homepage)
        $this->withHeaders($headers)->get('/');

        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(1, $record->total_visitors);
        $this->assertEquals(1, $record->new_visitors);
    }

    public function test_bots_and_crawlers_are_not_tracked(): void
    {
        $today = today()->toDateString();

        // 1. Googlebot
        $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            'Accept' => 'text/html',
        ])->get('/');

        // 2. Python Requests / Scanner
        $this->withHeaders([
            'User-Agent' => 'python-requests/2.28.1',
            'Accept' => '*/*',
        ])->get('/');

        // 3. Curl
        $this->withHeaders([
            'User-Agent' => 'curl/7.88.1',
            'Accept' => '*/*',
        ])->get('/');

        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(0, $record->total_visitors);
        $this->assertEquals(0, $record->new_visitors);
    }

    public function test_assets_and_non_html_requests_are_not_tracked(): void
    {
        $today = today()->toDateString();

        // Favicon
        $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 Chrome/120.0.0.0',
            'Accept' => 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
        ])->get('/favicon.ico');

        // Robots.txt
        $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 Chrome/120.0.0.0',
            'Accept' => 'text/plain',
        ])->get('/robots.txt');

        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(0, $record->total_visitors);
        $this->assertEquals(0, $record->new_visitors);
    }

    public function test_admin_routes_are_never_tracked_as_public_visitors(): void
    {
        $today = today()->toDateString();

        $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 Chrome/120.0.0.0',
            'Accept' => 'text/html',
        ])->get('/admin/login');

        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(0, $record->total_visitors);
        $this->assertEquals(0, $record->new_visitors);
    }
}
