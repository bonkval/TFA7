@extends('layouts.app')
@section('content')
<section class="page-intro"><div><div class="eyebrow">Customer directory</div><h1>{{ $heading }}</h1></div><p>Keep customer contact details together and up to date.</p><div><a class="button" href="{{ route('customers.create') }}">+ Create customer</a></div></section>
<div class="table-wrap"><table><thead><tr><th>Full name</th><th>Email</th><th>Phone</th><th>Actions</th></tr></thead><tbody>
@forelse ($customers as $customer)
<tr><td>{{ $customer->full_name }}</td><td>{{ $customer->email }}</td><td>{{ $customer->phone ?? '—' }}</td><td><a class="edit-link" href="{{ route('customers.edit', $customer) }}" aria-label="Edit {{ $customer->full_name }}">Edit ↗</a></td></tr>
@empty
<tr><td colspan="4">No customer accounts yet. Create your first customer above.</td></tr>
@endforelse
</tbody></table></div>
@endsection
