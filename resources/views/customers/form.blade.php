@extends('layouts.app')
@section('content')
<section class="page-intro form-intro"><div><div class="eyebrow">Customer accounts / {{ $customer->exists ? 'Edit' : 'New account' }}</div><h1>{{ $customer->exists ? 'Edit Customer' : 'Create Customer' }}</h1></div><p>Fields marked * are required.</p></section>
<form class="account-form card" method="POST"  action="{{ $customer->exists ? route('customers.update', $customer) : route('customers.store') }}">
@csrf
@if($customer->exists) @method('PUT') @endif

<div class="field"><label for="full_name">Full name *</label><input id="full_name" name="full_name" value="{{ old('full_name', $customer->full_name) }}" required maxlength="100" autocomplete="name" @error('full_name') aria-invalid="true" aria-describedby="full_name-error" @enderror>@error('full_name')<p class="field-error" id="full_name-error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="email">Email address *</label><input id="email" name="email" value="{{ old('email', $customer->email) }}" type="email" required maxlength="100" autocomplete="email" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>@error('email')<p class="field-error" id="email-error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="phone">Phone <span class="optional">Optional</span></label><input id="phone" type="tel" name="phone" value="{{ old('phone', $customer->phone) }}" maxlength="20" autocomplete="tel" @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror>@error('phone')<p class="field-error" id="phone-error">{{ $message }}</p>@enderror</div>
<div class="form-actions"><button class="button" type="submit">{{ $customer->exists ? 'Save changes' : 'Create customer' }}</button><a href="{{ route('customers') }}">Cancel</a></div>
</form>
@endsection
