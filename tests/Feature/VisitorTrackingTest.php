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

    public function test_public_pages_render_with_visitor_tracker_script_and_no_synchronous_db_writes(): void
    {
        $today = today()->toDateString();

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('fix_vst_uid'); // Confirms tracker script is embedded

        // Confirms page load did NOT synchronously write to daily analytics
        $record = DailyAnalytics::where('date', $today)->first();
        $this->assertNull($record);
    }

    public function test_human_visitor_beacon_records_new_visitor(): void
    {
        $today = today()->toDateString();

        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/120.0.0.0 Safari/537.36',
        ])->postJson('/analytics/ping', [
            'is_new_visit' => true,
            'is_new_user' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'recorded']);

        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(1, $record->total_visitors);
        $this->assertEquals(1, $record->new_visitors);
    }

    public function test_ongoing_navigation_does_not_inflate_visitor_count(): void
    {
        $today = today()->toDateString();
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/120.0.0.0 Safari/537.36',
        ];

        // 1. First visit (new visit + new user)
        $this->withHeaders($headers)->postJson('/analytics/ping', [
            'is_new_visit' => true,
            'is_new_user' => true,
        ]);

        // 2. Ongoing navigation within session (is_new_visit = false)
        $this->withHeaders($headers)->postJson('/analytics/ping', [
            'is_new_visit' => false,
            'is_new_user' => false,
        ]);

        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(1, $record->total_visitors);
        $this->assertEquals(1, $record->new_visitors);
    }

    public function test_user_visiting_multiple_times_a_day_increments_total_visitors_each_time(): void
    {
        $today = today()->toDateString();
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/120.0.0.0 Safari/537.36',
        ];

        // 1st Visit (Morning): First time ever
        $this->withHeaders($headers)->postJson('/analytics/ping', [
            'is_new_visit' => true,
            'is_new_user' => true,
        ]);
        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(1, $record->total_visitors);
        $this->assertEquals(1, $record->new_visitors);

        // 2nd Visit (Noon): Returning user (new visit = true, new user = false)
        $this->withHeaders($headers)->postJson('/analytics/ping', [
            'is_new_visit' => true,
            'is_new_user' => false,
        ]);
        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(2, $record->total_visitors);
        $this->assertEquals(1, $record->new_visitors);

        // 3rd Visit (Afternoon)
        $this->withHeaders($headers)->postJson('/analytics/ping', [
            'is_new_visit' => true,
            'is_new_user' => false,
        ]);
        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(3, $record->total_visitors);
        $this->assertEquals(1, $record->new_visitors);

        // 4th Visit (Evening)
        $this->withHeaders($headers)->postJson('/analytics/ping', [
            'is_new_visit' => true,
            'is_new_user' => false,
        ]);
        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(4, $record->total_visitors);
        $this->assertEquals(1, $record->new_visitors);

        // 5th Visit (Night)
        $this->withHeaders($headers)->postJson('/analytics/ping', [
            'is_new_visit' => true,
            'is_new_user' => false,
        ]);
        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(5, $record->total_visitors);
        $this->assertEquals(1, $record->new_visitors);
    }

    public function test_service_and_amc_visitors_tracked_properly(): void
    {
        $today = today()->toDateString();
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Chrome/120.0.0.0 Safari/537.36',
        ];

        // 1. Visit starts on home page
        $this->withHeaders($headers)->postJson('/analytics/ping', [
            'is_new_visit' => true,
            'is_new_user' => true,
        ]);

        // 2. User explores service page for the first time in this session
        $this->withHeaders($headers)->postJson('/analytics/ping', [
            'is_new_visit' => false,
            'is_service' => true,
        ]);

        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(1, $record->total_visitors);
        $this->assertEquals(1, $record->service_visitors);
        $this->assertEquals(0, $record->amc_visitors);

        // 3. User explores AMC for the first time in this session
        $this->withHeaders($headers)->postJson('/analytics/ping', [
            'is_new_visit' => false,
            'is_amc' => true,
        ]);

        $record = DailyAnalytics::forDate($today)->fresh();
        $this->assertEquals(1, $record->total_visitors);
        $this->assertEquals(1, $record->service_visitors);
        $this->assertEquals(1, $record->amc_visitors);
    }

    public function test_bots_and_crawlers_are_rejected(): void
    {
        $today = today()->toDateString();

        // 1. Googlebot
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        ])->postJson('/analytics/ping', [
            'is_new_visit' => true,
            'is_new_user' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'ignored']);

        // 2. Python Requests / Scanner
        $response = $this->withHeaders([
            'User-Agent' => 'python-requests/2.28.1',
        ])->postJson('/analytics/ping', [
            'is_new_visit' => true,
            'is_new_user' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'ignored']);

        // 3. Automated Webdriver test runner
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 Chrome/120.0.0.0',
        ])->postJson('/analytics/ping', [
            'is_new_visit' => true,
            'is_new_user' => true,
            'is_webdriver' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'ignored']);

        $record = DailyAnalytics::where('date', $today)->first();
        $this->assertNull($record);
    }
}
