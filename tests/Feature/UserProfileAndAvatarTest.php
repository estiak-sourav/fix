<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Division;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserProfileAndAvatarTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        /** @var User $user */
        $user = User::factory()->create([
            'email' => 'customer.test@example.com',
            'password' => Hash::make('secret123'),
            'name' => 'Tanvir Customer',
            'phone' => '01711000000',
        ]);
        $this->user = $user;

        /** @var Admin $admin */
        $admin = Admin::where('role', 'super_admin')->first();
        $this->admin = $admin;
    }

    /**
     * Test client user can access their profile page.
     */
    public function test_customer_user_can_view_profile_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('customer.profile'));

        $response->assertStatus(200);
        $response->assertSee('Tanvir Customer');
        $response->assertSee('Profile Picture');
        $response->assertSee('Address');
    }

    /**
     * Test client user can view profile page when divisions and districts are populated.
     */
    public function test_customer_user_can_view_profile_page_with_seeded_divisions(): void
    {
        $division = Division::create(['name' => 'Dhaka']);
        $district = $division->districts()->create(['name' => 'Dhaka']);
        $district->thanas()->create(['name' => 'Gulshan']);

        $response = $this->actingAs($this->user)
            ->get(route('customer.profile'));

        $response->assertStatus(200);
        $response->assertSee('Dhaka');
    }

    /**
     * Test client user can update profile details and upload display picture (avatar).
     */
    public function test_customer_user_can_update_profile_and_upload_avatar(): void
    {
        Storage::fake('public');

        $fakeAvatar = UploadedFile::fake()->image('tanvir_avatar.jpg', 300, 300);

        $response = $this->actingAs($this->user)
            ->put(route('customer.profile.update'), [
                'name' => 'Tanvir Ahmed Updated',
                'phone' => '01888999111',
                'company_name' => 'Acme Corporation BD',
                'property_type' => 'Commercial Office',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'area' => 'Gulshan-2',
                'default_address' => 'Road 11, Plot 45, Banani/Gulshan',
                'avatar' => $fakeAvatar,
            ]);

        $response->assertRedirect(route('customer.profile'));
        $response->assertSessionHas('success');

        $this->user->refresh();

        $this->assertSame('Tanvir Ahmed Updated', $this->user->name);
        $this->assertSame('01888999111', $this->user->phone);
        $this->assertSame('Acme Corporation BD', $this->user->company_name);
        $this->assertSame('Gulshan-2', $this->user->area);
        $this->assertNotNull($this->user->avatar);
        $this->assertStringStartsWith('avatars/users/', $this->user->avatar);

        Storage::disk('public')->assertExists($this->user->avatar);

        // Verify the saved image is square cropped
        $savedPath = Storage::disk('public')->path($this->user->avatar);
        if (file_exists($savedPath)) {
            [$w, $h] = getimagesize($savedPath);
            $this->assertSame($w, $h, 'Avatar image must be cropped to a 1:1 square');
        }
    }

    /**
     * Test uploaded rectangular avatar is center-cropped to a square.
     */
    public function test_uploaded_rectangular_avatar_is_center_cropped_to_square(): void
    {
        Storage::fake('public');

        // Rectangular image (800x400)
        $fakeAvatar = UploadedFile::fake()->image('rectangle.jpg', 800, 400);

        $response = $this->actingAs($this->user)
            ->post(route('customer.profile.avatar'), [
                'avatar' => $fakeAvatar,
            ]);

        $response->assertRedirect(route('customer.profile'));

        $this->user->refresh();
        $this->assertNotNull($this->user->avatar);
        Storage::disk('public')->assertExists($this->user->avatar);

        $savedPath = Storage::disk('public')->path($this->user->avatar);
        $this->assertFileExists($savedPath);
        [$w, $h] = getimagesize($savedPath);
        $this->assertSame($w, $h, 'Rectangular upload must be center-cropped to a 1:1 square');
    }

    /**
     * Test profile page renders cascading division, district, thana dropdowns and clean UI.
     */
    public function test_customer_profile_renders_location_dropdowns_and_clean_ui(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('customer.profile'));

        $response->assertStatus(200);
        $response->assertSee('name="division"', false);
        $response->assertSee('name="district"', false);
        $response->assertSee('name="area"', false);
        $response->assertSee('Address');
        $response->assertDontSee('Default Facility Address (Optional)');
        $response->assertDontSee('(DP)');
        $response->assertDontSee('Upload your profile photo independently');
        $response->assertDontSee('Recommended: 400×400 square');
        $response->assertDontSee('Optional — if saved, automatically pre-fills during service bookings');
    }

    /**
     * Test client user can remove their uploaded avatar.
     */
    public function test_customer_user_can_remove_avatar(): void
    {
        Storage::fake('public');

        // Put an avatar first
        $storedPath = 'avatars/users/sample_avatar.jpg';
        Storage::disk('public')->put($storedPath, 'dummy-image-content');
        $this->user->update(['avatar' => $storedPath]);

        $response = $this->actingAs($this->user)
            ->put(route('customer.profile.update'), [
                'name' => $this->user->name,
                'phone' => $this->user->phone,
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'area' => 'Gulshan-2',
                'default_address' => 'House 10, Road 4',
                'remove_avatar' => '1',
            ]);

        $response->assertRedirect(route('customer.profile'));

        $this->user->refresh();
        $this->assertNull($this->user->avatar);
        Storage::disk('public')->assertMissing($storedPath);
    }

    /**
     * Test client user can optionally update password from profile.
     */
    public function test_customer_user_can_update_password_from_profile(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('customer.profile.update'), [
                'name' => $this->user->name,
                'phone' => $this->user->phone,
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'area' => 'Gulshan-2',
                'default_address' => 'House 10, Road 4',
                'password' => 'new_strong_password123',
                'password_confirmation' => 'new_strong_password123',
            ]);

        $response->assertRedirect(route('customer.profile'));

        $this->user->refresh();
        $this->assertTrue(Hash::check('new_strong_password123', $this->user->password));
    }

    /**
     * Test admin can view self-service profile page.
     */
    public function test_admin_can_view_profile_page(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.profile'));

        $response->assertStatus(200);
        $response->assertSee('My Profile & Display Picture', false);
        $response->assertSee($this->admin->name);
        $response->assertSee('Display Picture (Avatar / DP)', false);
    }

    /**
     * Test admin can update profile details and upload display picture (avatar).
     */
    public function test_admin_can_update_profile_and_upload_avatar(): void
    {
        Storage::fake('public');

        $fakeAvatar = UploadedFile::fake()->image('admin_dp.png', 400, 400);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => 'Chief Operations Commander',
                'email' => $this->admin->email,
                'phone' => '01999888777',
                'avatar' => $fakeAvatar,
            ]);

        $response->assertRedirect(route('admin.profile'));
        $response->assertSessionHas('success');

        $this->admin->refresh();
        $this->assertSame('Chief Operations Commander', $this->admin->name);
        $this->assertSame('01999888777', $this->admin->phone);
        $this->assertNotNull($this->admin->avatar);
        $this->assertStringStartsWith('avatars/admins/', $this->admin->avatar);

        Storage::disk('public')->assertExists($this->admin->avatar);

        // Activity log should have recorded the profile update
        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_id' => $this->admin->id,
            'action' => 'profile.update',
        ]);
    }

    /**
     * Test admin can change security password with valid current password.
     */
    public function test_admin_can_update_password_with_current_password_verification(): void
    {
        $this->admin->password = Hash::make('CurrentAdminPass123!');
        $this->admin->save();

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => $this->admin->name,
                'email' => $this->admin->email,
                'current_password' => 'CurrentAdminPass123!',
                'password' => 'BrandNewAdminPass999!',
                'password_confirmation' => 'BrandNewAdminPass999!',
            ]);

        $response->assertRedirect(route('admin.profile'));
        $response->assertSessionHas('success');

        $this->admin->refresh();
        $this->assertTrue(Hash::check('BrandNewAdminPass999!', $this->admin->password));
    }

    /**
     * Test admin cannot update password if current password is incorrect.
     */
    public function test_admin_cannot_update_password_with_wrong_current_password(): void
    {
        $this->admin->password = Hash::make('CurrentAdminPass123!');
        $this->admin->save();

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => $this->admin->name,
                'email' => $this->admin->email,
                'current_password' => 'WrongPasswordOops',
                'password' => 'BrandNewAdminPass999!',
                'password_confirmation' => 'BrandNewAdminPass999!',
            ]);

        $response->assertSessionHasErrors('current_password');

        $this->admin->refresh();
        $this->assertTrue(Hash::check('CurrentAdminPass123!', $this->admin->password));
    }

    /**
     * Test admin can remove display picture.
     */
    public function test_admin_can_remove_avatar(): void
    {
        Storage::fake('public');

        $storedPath = 'avatars/admins/head_admin.png';
        Storage::disk('public')->put($storedPath, 'admin-image-binary');
        $this->admin->update(['avatar' => $storedPath]);

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => $this->admin->name,
                'email' => $this->admin->email,
                'remove_avatar' => '1',
            ]);

        $response->assertRedirect(route('admin.profile'));

        $this->admin->refresh();
        $this->assertNull($this->admin->avatar);
        Storage::disk('public')->assertMissing($storedPath);
    }

    /**
     * Test client user can upload avatar independently without providing address or other fields.
     */
    public function test_customer_can_upload_avatar_standalone_without_providing_address(): void
    {
        Storage::fake('public');

        $fakeAvatar = UploadedFile::fake()->image('tanvir_dp.png', 400, 400);

        // User currently has no address in profile
        $this->assertNull($this->user->default_address);
        $this->assertNull($this->user->division);

        $response = $this->actingAs($this->user)
            ->post(route('customer.profile.avatar'), [
                'avatar' => $fakeAvatar,
            ]);

        $response->assertRedirect(route('customer.profile'));
        $response->assertSessionHas('success');

        $this->user->refresh();
        $this->assertNotNull($this->user->avatar);
        $this->assertStringStartsWith('avatars/users/', $this->user->avatar);
        // Address remains null
        $this->assertNull($this->user->default_address);
        Storage::disk('public')->assertExists($this->user->avatar);
    }

    /**
     * Test client user can remove avatar via dedicated DELETE endpoint.
     */
    public function test_customer_can_remove_avatar_via_delete_endpoint(): void
    {
        Storage::fake('public');

        $storedPath = 'avatars/users/existing_user_dp.jpg';
        Storage::disk('public')->put($storedPath, 'user-image-binary');
        $this->user->update(['avatar' => $storedPath]);

        $response = $this->actingAs($this->user)
            ->delete(route('customer.profile.avatar.remove'));

        $response->assertRedirect(route('customer.profile'));
        $response->assertSessionHas('success');

        $this->user->refresh();
        $this->assertNull($this->user->avatar);
        Storage::disk('public')->assertMissing($storedPath);
    }

    /**
     * Test service booking does NOT overwrite or set customer's profile address.
     */
    public function test_service_booking_does_not_overwrite_customer_profile_address(): void
    {
        $service = Service::first();

        // Customer has NO address in profile
        $this->assertNull($this->user->default_address);
        $this->assertNull($this->user->division);

        $bookingData = [
            'service_type' => 'on_demand',
            'service_id' => $service->id,
            'customer_name' => $this->user->name,
            'customer_phone' => '01711223344',
            'company_name' => 'Custom Office BD',
            'property_type' => 'commercial',
            'division' => 'Chattogram',
            'district' => 'Chattogram',
            'area' => 'Agrabad',
            'street_address' => 'Plot #42, Commercial Area, Agrabad C/A',
            'preferred_date' => now()->addDays(2)->format('Y-m-d'),
            'preferred_time_slot' => '10:00 AM - 01:00 PM',
            'urgency' => 'standard',
            'problem_description' => 'Test emergency AC system check in Agrabad office',
        ];

        $response = $this->actingAs($this->user)
            ->post('/booking', $bookingData);

        $response->assertRedirect();

        // Verify booking has the booking address
        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->user->id,
            'street_address' => 'Plot #42, Commercial Area, Agrabad C/A',
            'division' => 'Chattogram',
        ]);

        // Verify customer profile address was NOT updated with booking address
        $this->user->refresh();
        $this->assertNull($this->user->default_address);
        $this->assertNull($this->user->division);
        $this->assertNull($this->user->district);
        $this->assertNull($this->user->area);
    }
}
