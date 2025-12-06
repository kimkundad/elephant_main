<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    //

    public function index()
    {
        $customers = Customer::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:customers,email',
            'phone'     => 'required|string|unique:customers,phone',
        ]);

        Customer::create([
            'full_name'   => $request->full_name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'เพิ่มลูกค้าสำเร็จ');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:customers,email,' . $customer->id,
            'phone'     => 'required|string|unique:customers,phone,' . $customer->id,
        ]);

        $customer->update($request->only('full_name', 'email', 'phone'));

        return redirect()->route('admin.customers.index')->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
}
