<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_admin_who_can_reach_the_dashboard(): void
    {
        $this->artisan('admin:user', [
            'email' => 'Koi@Example.com',
            '--password' => 'first-password',
        ])->assertSuccessful();

        $user = User::firstWhere('email', 'koi@example.com');

        $this->assertNotNull($user, 'The address is stored in lower case.');
        $this->assertSame('Koi', $user->name, 'The name comes from the address when none is given.');
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue(Hash::check('first-password', $user->password));

        $this->actingAs($user)->get(url('admin/dashboard'))->assertOk();
    }

    public function test_it_can_create_a_super_admin_with_a_name(): void
    {
        $this->artisan('admin:user', [
            'email' => 'sirirat@example.com',
            '--name' => 'Sirirat',
            '--role' => 'superAdmin',
        ])->assertSuccessful();

        $user = User::firstWhere('email', 'sirirat@example.com');

        $this->assertSame('Sirirat', $user->name);
        $this->assertTrue($user->hasRole('superAdmin'));
    }

    public function test_running_it_again_replaces_the_password_and_the_role(): void
    {
        $this->artisan('admin:user', ['email' => 'koi@example.com', '--password' => 'first-password'])->assertSuccessful();
        $this->artisan('admin:user', [
            'email' => 'koi@example.com',
            '--password' => 'second-password',
            '--role' => 'superAdmin',
        ])->assertSuccessful();

        $this->assertSame(1, User::where('email', 'koi@example.com')->count());

        $user = User::firstWhere('email', 'koi@example.com');

        $this->assertTrue(Hash::check('second-password', $user->password));
        $this->assertTrue($user->hasRole('superAdmin'));
        $this->assertFalse($user->hasRole('admin'), 'The old role is dropped, not kept alongside.');
    }

    public function test_it_refuses_an_unknown_role(): void
    {
        $this->artisan('admin:user', ['email' => 'koi@example.com', '--role' => 'customer'])->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }

    public function test_it_refuses_an_address_that_is_not_an_email(): void
    {
        $this->artisan('admin:user', ['email' => 'not-an-email'])->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }
}
