@extends('layouts.app')
@section('content')
<section class="hero"><div class="hero-copy"><div class="eyebrow">Laravel · MVC foundation</div><h1>{{ $heading }}</h1><p>Manage customer contacts and staff accounts in one place. Create records, update details, and personalize user profiles with prepared avatars.</p><a class="button" href="{{ route('customers') }}">View customer accounts</a></div></section>
<section class="grid" aria-label="POS modules"><article class="feature"><h3>Customer Accounts</h3><p>Create and edit customer names, email addresses, and phone numbers.</p></article><article class="feature"><h3>User Accounts</h3><p>Manage unique usernames, staff details, and profile pictures.</p></article><article class="feature"><h3>About the Lab</h3><p>See how routing, controllers, and views work together in Laravel.</p></article></section>
@endsection
