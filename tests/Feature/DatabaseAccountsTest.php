<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseAccountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_accounts_render_and_seeding_is_repeatable(): void
    {
        $this->seed();
        $this->seed();
        $this->assertDatabaseCount('customers', 5);
        $this->assertDatabaseCount('users', 5);

        $customers = $this->get('/customers')->assertOk()->assertViewHas('customers');
        foreach (Customer::all() as $customer) {
            $customers->assertSeeText($customer->full_name)
                ->assertSeeText($customer->email)->assertSeeText($customer->phone);
        }

        $users = $this->get('/users')->assertOk()->assertViewHas('users');
        foreach (User::all() as $user) {
            $users->assertSeeText($user->username)->assertSeeText($user->full_name);
        }
    }

    public function test_pages_display_changes_saved_through_models(): void
    {
        $customer = Customer::create([
            'full_name' => 'Database Customer',
            'email' => 'database@example.com',
            'phone' => null,
        ]);
        $user = User::create(['username' => 'database_staff', 'full_name' => 'Database Staff']);
        $this->assertNotNull($customer->fresh()->created_at);
        $this->assertNotNull($user->fresh()->created_at);
        $customer->update(['full_name' => 'Updated Customer']);
        $user->update(['full_name' => 'Updated Staff']);
        $this->get('/customers')->assertOk()->assertSeeText('Updated Customer')->assertSeeText('database@example.com');
        $this->get('/users')->assertOk()->assertSeeText('database_staff')->assertSeeText('Updated Staff');
    }

    public function test_username_is_unique(): void
    {
        User::create(['username' => 'duplicate', 'full_name' => 'First User']);
        $this->expectException(QueryException::class);
        User::create(['username' => 'duplicate', 'full_name' => 'Second User']);
    }

    public function test_account_schema_matches_tfa2(): void
    {
        $this->assertSame(['id', 'full_name', 'email', 'phone', 'created_at'], Schema::getColumnListing('customers'));
        $this->assertSame(['id', 'username', 'full_name', 'created_at', 'avatar'], Schema::getColumnListing('users'));
        Artisan::call('migrate:rollback');
        $this->assertFalse(Schema::hasTable('customers'));
        $this->assertFalse(Schema::hasTable('users'));
        Artisan::call('migrate');
        $this->seed();
        $this->assertDatabaseCount('customers', 5);
        $this->assertDatabaseCount('users', 5);
    }
}
