<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\ImageManager;
use Throwable;

class UserController extends Controller
{
    public function create(): View
    {
        return view('users.form', ['user' => new User, 'title' => 'POS Lab | Create User']);
    }

    public function store(Request $request): RedirectResponse
    {
        User::create($this->validated($request));

        return to_route('users')->with('status', 'User account created. Add a profile picture from Edit.');
    }

    public function edit(User $user): View
    {
        return view('users.form', ['user' => $user, 'title' => 'POS Lab | Edit User']);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, $user);
        $oldAvatar = $user->avatar;
        $newAvatar = null;

        if ($request->hasFile('avatar')) {
            try {
                $image = ImageManager::gd(decodeAnimation: false)
                    ->read($request->file('avatar')->getPathname())
                    ->cover(256, 256)
                    ->toJpeg(quality: 85);
            } catch (DecoderException $exception) {
                throw ValidationException::withMessages(['avatar' => 'This image could not be read. Please choose another JPEG, PNG, or WebP image.']);
            }

            $newAvatar = 'avatars/'.Str::uuid().'.jpg';
            Storage::disk('public')->put($newAvatar, (string) $image);
            $data['avatar'] = $newAvatar;
        }

        try {
            $user->update($data);
        } catch (Throwable $exception) {
            if ($newAvatar) {
                Storage::disk('public')->delete($newAvatar);
            }
            throw $exception;
        }

        if ($newAvatar && $oldAvatar) {
            Storage::disk('public')->delete($oldAvatar);
        }

        return to_route('users')->with('status', 'User account updated.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user)],
            'full_name' => ['required', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=4096,max_height=4096'],
        ]);

        // Only a prepared image path may be written to the avatar column.
        unset($data['avatar']);

        return $data;
    }

    public function index(): View
    {
        $users = User::orderBy('id')->get();

        return view('users.index', [
            'title' => 'POS Lab | User Accounts',
            'heading' => 'User Accounts',
            'users' => $users,
        ]);
    }
}
