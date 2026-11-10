@extends('layouts.app')
@section('content')
<section class="page-intro"><div><div class="eyebrow">Staff directory</div><h1>{{ $heading }}</h1></div><p>Manage staff details and give every account a familiar face.</p><div><a class="button" href="{{ route('users.create') }}">+ Create user</a></div></section>
<div class="table-wrap"><table><thead><tr><th>Profile</th><th>Username</th><th>Full name</th><th>Actions</th></tr></thead><tbody>
@forelse ($users as $user)
<tr><td><img class="avatar" width="56" height="56" src="{{ $user->avatar ? Storage::disk('public')->url($user->avatar) : asset('images/avatar-placeholder.svg') }}" alt="{{ $user->full_name }}'s profile picture"></td><td>{{ $user->username }}</td><td>{{ $user->full_name }}</td><td><a class="edit-link" href="{{ route('users.edit', $user) }}" aria-label="Edit {{ $user->full_name }}">Edit ↗</a></td></tr>
@empty
<tr><td colspan="4">No user accounts yet. Create your first user above.</td></tr>
@endforelse
</tbody></table></div>
@endsection
