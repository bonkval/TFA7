<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function create(): View
    {
        return view('customers.form', ['customer' => new Customer, 'title' => 'POS Lab | Create Customer']);
    }

    public function store(Request $request): RedirectResponse
    {
        Customer::create($this->validated($request));

        return to_route('customers')->with('status', 'Customer account created.');
    }

    public function edit(Customer $customer): View
    {
        return view('customers.form', ['customer' => $customer, 'title' => 'POS Lab | Edit Customer']);
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update($this->validated($request));

        return to_route('customers')->with('status', 'Customer account updated.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);
    }

    public function index(): View
    {
        $customers = Customer::orderBy('id')->get();

        return view('customers.index', [
            'title' => 'POS Lab | Customer Accounts',
            'heading' => 'Customer Accounts',
            'customers' => $customers,
        ]);
    }
}
