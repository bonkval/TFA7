<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccountManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_create_edit_and_validation(): void
    {
        $this->get('/customers/create')->assertOk();
        $this->post('/customers', [])->assertSessionHasErrors(['full_name', 'email']);
        $this->post('/customers', ['full_name' => 'Ada Customer', 'email' => 'invalid'])->assertSessionHasErrors('email');
        $this->assertDatabaseCount('customers', 0);
        $this->post('/customers', ['full_name' => 'Ada Customer', 'email' => 'ada@example.com'])->assertRedirect('/customers');
        $customer = Customer::firstOrFail();
        $this->get("/customers/{$customer->id}/edit")->assertOk()->assertSee('Ada Customer')->assertSee('ada@example.com');
        $this->put("/customers/{$customer->id}", ['full_name' => 'Ada Updated', 'email' => 'new@example.com', 'phone' => '09123456789'])->assertRedirect('/customers');
        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'full_name' => 'Ada Updated', 'email' => 'new@example.com']);
        $this->put("/customers/{$customer->id}", ['full_name' => '', 'email' => 'invalid'])->assertSessionHasErrors(['full_name', 'email']);
        $this->assertSame('Ada Updated', $customer->fresh()->full_name);
    }

    public function test_user_create_edit_unique_username_and_missing_records(): void
    {
        $this->get('/users/create')->assertOk();
        $this->post('/users', [])->assertSessionHasErrors(['username', 'full_name']);
        $this->assertDatabaseCount('users', 0);
        $this->post('/users', ['username' => 'ada', 'full_name' => 'Ada Staff'])->assertRedirect('/users');
        $user = User::firstOrFail();
        $other = User::create(['username' => 'grace', 'full_name' => 'Grace Staff']);
        $this->post('/users', ['username' => 'ada', 'full_name' => 'Duplicate'])->assertSessionHasErrors('username');
        $this->get("/users/{$user->id}/edit")->assertOk()->assertSee('ada')->assertSee('Ada Staff');
        $this->put("/users/{$user->id}", ['username' => 'ada', 'full_name' => 'Ada Updated'])->assertRedirect('/users');
        $this->assertSame('Ada Updated', $user->fresh()->full_name);
        $this->put("/users/{$user->id}", ['username' => $other->username, 'full_name' => 'Wrong'])->assertSessionHasErrors('username');
        $this->assertSame('ada', $user->fresh()->username);
        $this->get('/users/999/edit')->assertNotFound();
        $this->put('/users/999', [])->assertNotFound();
        $this->get('/customers/999/edit')->assertNotFound();
        $this->put('/customers/999', [])->assertNotFound();
    }

    public function test_avatar_is_prepared_replaced_and_retained_without_an_upload(): void
    {
        Storage::fake('public');
        $user = User::create(['username' => 'ada', 'full_name' => 'Ada Staff']);
        $this->get('/users')->assertOk()->assertSee('images/avatar-placeholder.svg');
        $data = ['username' => 'ada', 'full_name' => 'Ada Staff'];
        $this->put("/users/{$user->id}", $data + ['avatar' => UploadedFile::fake()->image('portrait.png', 640, 480)])->assertRedirect('/users');
        $path = $user->fresh()->avatar;
        $this->assertMatchesRegularExpression('~^avatars/[a-f0-9-]+\.jpg$~', $path);
        Storage::disk('public')->assertExists($path);
        $size = getimagesizefromstring(Storage::disk('public')->get($path));
        $this->assertSame([256, 256], [$size[0], $size[1]]);
        $this->assertSame('image/jpeg', $size['mime']);
        $this->get('/users')->assertOk()->assertSee(Storage::disk('public')->url($path));
        $this->put("/users/{$user->id}", $data)->assertRedirect('/users');
        $this->assertSame($path, $user->fresh()->avatar);
        $this->put("/users/{$user->id}", $data + ['avatar' => UploadedFile::fake()->image('new.jpg', 400, 600)])->assertRedirect('/users');
        Storage::disk('public')->assertMissing($path);
        Storage::disk('public')->assertExists($user->fresh()->avatar);
        $this->assertCount(1, Storage::disk('public')->files('avatars'));
    }

    public function test_invalid_avatars_do_not_change_the_account_or_store_files(): void
    {
        Storage::fake('public');
        $user = User::create(['username' => 'ada', 'full_name' => 'Ada Staff']);
        $files = [
            UploadedFile::fake()->create('notes.txt', 10, 'text/plain'),
            UploadedFile::fake()->image('large.jpg')->size(2049),
            UploadedFile::fake()->image('wide.png', 4097, 10),
            UploadedFile::fake()->createWithContent('fake.jpg', 'not an image'),
            UploadedFile::fake()->createWithContent('unsafe.svg', '<svg xmlns="http://www.w3.org/2000/svg"></svg>'),
        ];
        foreach ($files as $file) {
            $this->put("/users/{$user->id}", ['username' => 'ada', 'full_name' => 'Changed', 'avatar' => $file])->assertSessionHasErrors('avatar');
        }
        $this->assertSame('Ada Staff', $user->fresh()->full_name);
        $this->assertNull($user->fresh()->avatar);
        $this->assertCount(0, Storage::disk('public')->allFiles());
    }
}
