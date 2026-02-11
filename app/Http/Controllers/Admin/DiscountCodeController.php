<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index()
    {
        $codes = DiscountCode::with('agent')
            ->orderByDesc('id')
            ->get();

        return view('admin.discount_codes.index', compact('codes'));
    }

    public function create()
    {
        $agents = Agent::orderBy('name')->get();
        return view('admin.discount_codes.create', compact('agents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'agent_id' => ['nullable', 'integer', 'exists:agents,id'],
            'code' => ['required', 'string', 'max:50', 'unique:discount_codes,code'],
            'amount' => ['required', 'numeric', 'min:0'],
            'max_uses' => ['required', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DiscountCode::create([
            'agent_id' => $data['agent_id'] ?? null,
            'code' => strtoupper(trim($data['code'])),
            'amount' => $data['amount'],
            'max_uses' => $data['max_uses'],
            'used_count' => 0,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', 'เพิ่มโค้ดส่วนลดเรียบร้อยแล้ว');
    }

    public function edit(DiscountCode $discountCode)
    {
        $agents = Agent::orderBy('name')->get();
        return view('admin.discount_codes.edit', compact('discountCode', 'agents'));
    }

    public function update(Request $request, DiscountCode $discountCode)
    {
        $data = $request->validate([
            'agent_id' => ['nullable', 'integer', 'exists:agents,id'],
            'code' => ['required', 'string', 'max:50', 'unique:discount_codes,code,' . $discountCode->id],
            'amount' => ['required', 'numeric', 'min:0'],
            'max_uses' => ['required', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $discountCode->update([
            'agent_id' => $data['agent_id'] ?? null,
            'code' => strtoupper(trim($data['code'])),
            'amount' => $data['amount'],
            'max_uses' => $data['max_uses'],
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', 'อัปเดตโค้ดส่วนลดเรียบร้อยแล้ว');
    }

    public function destroy(DiscountCode $discountCode)
    {
        $discountCode->delete();
        return back()->with('success', 'ลบโค้ดส่วนลดเรียบร้อยแล้ว');
    }
}
