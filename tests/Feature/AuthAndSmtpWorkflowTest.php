<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\EmailOtp;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthAndSmtpWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        Mail::fake();
    }

    /**
     * Test registration requires valid Bangladesh mobile number.
     */
    public function test_registration_requires_bangladesh_phone_number(): void
    {
        // 1. Missing phone number fails
        $response = $this->postJson('/auth/send-otp', [
            'purpose' => 'register',
            'email' => 'newuser@fix.com.bd',
            'name' => 'Kazi Nazrul',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone']);

        // 2. Non-Bangladesh foreign number fails
        $responseForeign = $this->postJson('/auth/send-otp', [
            'purpose' => 'register',
            'email' => 'foreign@fix.com.bd',
            'name' => 'John Doe',
            'phone' => '+14155552671', // US number
        ]);
        $responseForeign->assertStatus(422);
        $responseForeign->assertJsonValidationErrors(['phone']);

        // 3. Invalid prefix fails (e.g. 012 is invalid in Bangladesh)
        $responseInvalidPrefix = $this->postJson('/auth/send-otp', [
            'purpose' => 'register',
            'email' => 'invalid@fix.com.bd',
            'name' => 'Invalid Prefix',
            'phone' => '01234567890',
        ]);
        $responseInvalidPrefix->assertStatus(422);
        $responseInvalidPrefix->assertJsonValidationErrors(['phone']);

        // 4. Valid Bangladesh mobile numbers succeed
        $responseValid = $this->postJson('/auth/send-otp', [
            'purpose' => 'register',
            'email' => 'bangladeshi.user@fix.com.bd',
            'name' => 'Habibur Rahman',
            'phone' => '01712345678',
        ]);
        $responseValid->assertStatus(200);
        $responseValid->assertJson(['success' => true]);

        // Verify OTP dispatched
        Mail::assertSent(\App\Mail\AuthOtpMail::class);
    }

    /**
     * Test user registration and login OTP lifecycle with session establishment.
     */
    public function test_user_can_register_and_then_login_with_email_otp(): void
    {
        $email = 'corporate.facility@dhaka.bd';
        $phone = '01898765432';
        $name = 'Dhaka Commercial Hub';

        // 1. Register OTP
        $regResponse = $this->postJson('/auth/send-otp', [
            'purpose' => 'register',
            'email' => $email,
            'name' => $name,
            'phone' => $phone,
        ]);
        $regResponse->assertStatus(200);

        $otpRecord = EmailOtp::where('email', $email)->latest()->first();
        $this->assertNotNull($otpRecord);
        $this->assertEquals('+8801898765432', $otpRecord->payload['phone']);

        // Set known code
        $code = '654321';
        $otpRecord->update(['otp_code_hash' => Hash::make($code)]);

        // 2. Verify Registration OTP
        $verifyResponse = $this->postJson('/auth/verify-otp', [
            'email' => $email,
            'otp_code' => $code,
        ]);
        $verifyResponse->assertStatus(200);
        $this->assertAuthenticated();

        // Ensure user was persisted with normalized BD phone
        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals('+8801898765432', $user->phone);

        // Logout
        $this->post('/auth/logout');
        $this->assertGuest();

        // 3. Login with email
        $loginOtpResponse = $this->postJson('/auth/send-otp', [
            'purpose' => 'login',
            'email' => $email,
        ]);
        $loginOtpResponse->assertStatus(200);

        $loginOtp = EmailOtp::where('email', $email)->where('is_consumed', false)->latest()->first();
        $loginOtp->update(['otp_code_hash' => Hash::make('112233')]);

        // 4. Verify Login
        $loginVerifyResponse = $this->postJson('/auth/verify-otp', [
            'email' => $email,
            'otp_code' => '112233',
        ]);
        $loginVerifyResponse->assertStatus(200);
        $this->assertAuthenticated();
    }

    /**
     * Test login fails when email does not exist.
     */
    public function test_login_fails_for_non_existent_email(): void
    {
        $response = $this->postJson('/auth/send-otp', [
            'purpose' => 'login',
            'email' => 'nonexistent.user@random.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Test admin can update SMTP settings and trigger test email.
     */
    public function test_admin_can_update_smtp_settings_and_test_connection(): void
    {
        $admin = Admin::first();

        // 1. Admin updates SMTP settings
        $response = $this->actingAs($admin, 'admin')->post('/admin/cms/settings', [
            'mail_mailer' => 'smtp',
            'mail_host' => 'smtp.mailtrap.io',
            'mail_port' => 2525,
            'mail_username' => 'testuser',
            'mail_password' => 'secretpass',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'dispatch@fix.com.bd',
            'mail_from_name' => 'FIX Dispatch Operations',
            'emergency_phone' => '+880 1700-000000',
            'support_email' => 'support@fix.com.bd',
            'head_office_address' => 'Banani, Dhaka',
        ]);

        $response->assertRedirect();
        $this->assertEquals('smtp.mailtrap.io', SiteSetting::get('mail_host'));
        $this->assertEquals(2525, SiteSetting::get('mail_port'));
        $this->assertEquals('dispatch@fix.com.bd', SiteSetting::get('mail_from_address'));

        // 2. Admin triggers test SMTP email
        $testResponse = $this->actingAs($admin, 'admin')->post('/admin/cms/settings/test-smtp', [
            'test_email' => 'operations.lead@fix.com.bd',
        ]);

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('success');
        Mail::assertSent(\App\Mail\TestSmtpMail::class);
    }

    /**
     * Test admin can access all Web Control Hub pages and content modules.
     */
    public function test_admin_can_access_web_control_hub_and_sub_modules(): void
    {
        $admin = Admin::first();

        // 1. Web Control Hub
        $hubRes = $this->actingAs($admin, 'admin')->get('/admin/web-control');
        $hubRes->assertStatus(200);
        $hubRes->assertSee('Web Control Center');

        // 2. AMC Packages
        $amcRes = $this->actingAs($admin, 'admin')->get('/admin/web-control/amc-plans');
        $amcRes->assertStatus(200);
        $amcRes->assertSee('Annual Maintenance Contracts');

        // 3. Section Contents
        $contentRes = $this->actingAs($admin, 'admin')->get('/admin/web-control/contents');
        $contentRes->assertStatus(200);
        $contentRes->assertSee('Homepage Sections');

        // 4. FAQs
        $faqRes = $this->actingAs($admin, 'admin')->get('/admin/web-control/faqs');
        $faqRes->assertStatus(200);
        $faqRes->assertSee('FAQs Knowledgebase');

        // 5. Testimonials
        $testimRes = $this->actingAs($admin, 'admin')->get('/admin/web-control/testimonials');
        $testimRes->assertStatus(200);
        $testimRes->assertSee('Client Testimonials');
    }
}
