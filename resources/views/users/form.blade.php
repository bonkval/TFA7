@extends('layouts.app')
@section('content')
<section class="page-intro form-intro"><div><div class="eyebrow">User accounts / {{ $user->exists ? 'Edit' : 'New account' }}</div><h1>{{ $user->exists ? 'Edit User' : 'Create User' }}</h1></div><p>Fields marked * are required.</p></section>
<form class="account-form card" method="POST" enctype="multipart/form-data" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}">
@csrf
@if($user->exists) @method('PUT') @endif
<div class="field"><label for="username">Username *</label><input id="username" name="username" value="{{ old('username', $user->username) }}" required maxlength="50" autocomplete="username" @error('username') aria-invalid="true" aria-describedby="username-error" @enderror>@error('username')<p class="field-error" id="username-error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="full_name">Full name *</label><input id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required maxlength="100" autocomplete="name" @error('full_name') aria-invalid="true" aria-describedby="full_name-error" @enderror>@error('full_name')<p class="field-error" id="full_name-error">{{ $message }}</p>@enderror</div>

@if($user->exists)
<div class="field"><label for="avatar">Profile picture <span class="optional">Optional</span></label><div class="avatar-upload"><img class="avatar avatar-large" width="88" height="88" src="{{ $user->avatar ? Storage::disk('public')->url($user->avatar) : asset('images/avatar-placeholder.svg') }}" alt="Current profile picture for {{ $user->full_name }}"><input id="avatar" type="file" name="avatar" accept="image/jpeg,image/png,image/webp" aria-describedby="avatar-help @error('avatar') avatar-error @enderror" @error('avatar') aria-invalid="true" @enderror></div><p class="field-help" id="avatar-help">JPEG, PNG or WebP · Up to 2 MB and 4096 × 4096 pixels. Saved as a 256 × 256 thumbnail. Leave empty to keep the current picture.</p>@error('avatar')<p class="field-error" id="avatar-error">{{ $message }}</p>@enderror</div>
@else
<p class="field-help">You can add a profile picture after creating the account.</p>
@endif
<div class="form-actions"><button class="button" type="submit">{{ $user->exists ? 'Save changes' : 'Create user' }}</button><a href="{{ route('users') }}">Cancel</a></div>
</form>
@endsection
