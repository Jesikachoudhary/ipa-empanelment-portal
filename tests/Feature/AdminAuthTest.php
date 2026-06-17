<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(array $overrides = []): Admin
    {
        return Admin::create(array_merge([
            'name'              => 'Test Admin',
            'email'             => 'admin@example.com',
            'password'          => Hash::make('password123'),
            'email_verified_at' => now(),
            'is_super'          => false,
        ], $overrides));
    }

    public function test_login_page_loads(): void
    {
        $this->get(route('admin.login'))->assertStatus(200);
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
             ->assertRedirect(route('admin.login'));
    }

    public function test_verified_admin_can_login(): void
    {
        $admin = $this->makeAdmin();

        $this->post(route('admin.login.post'), [
            'email'    => 'admin@example.com',
            'password' => 'password123',
        ])->assertRedirect();

        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_unverified_admin_cannot_login(): void
    {
        $this->makeAdmin([
            'email'             => 'unverified@example.com',
            'email_verified_at' => null,
        ]);

        $this->post(route('admin.login.post'), [
            'email'    => 'unverified@example.com',
            'password' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('admin');
    }

    public function test_non_super_admin_cannot_access_applicants_index(): void
    {
        $admin = $this->makeAdmin(['email' => 'regular@example.com', 'is_super' => false]);

        $this->actingAs($admin, 'admin')
             ->get(route('admin.applicants.index'))
             ->assertStatus(403);
    }

    public function test_super_admin_can_access_applicants_index(): void
    {
        $admin = $this->makeAdmin(['email' => 'super@example.com', 'is_super' => true]);

        $this->actingAs($admin, 'admin')
             ->get(route('admin.applicants.index'))
             ->assertStatus(200);
    }
}
