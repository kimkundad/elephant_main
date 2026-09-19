<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Booking;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::query()
            ->orderByDesc('id')
            ->get()
            ->map(function (Agent $agent) {
                $agent->bookings_count = Booking::where('agent_id', $agent->id)->count();
                $agent->sales_total = Booking::where('agent_id', $agent->id)->sum('grand_total');
                return $agent;
            });

        return view('admin.agents.index', compact('agents'));
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'phone_country' => ['nullable', 'string', 'size:2'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Agent::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => PhoneNumber::normalize($data['phone'] ?? null),
            'phone_country' => PhoneNumber::country($data['phone_country'] ?? null),
            'notes' => $data['notes'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.agents.index')
            ->with('success', 'เพิ่มพนักงานขายเรียบร้อยแล้ว');
    }

    public function edit(Agent $agent)
    {
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'phone_country' => ['nullable', 'string', 'size:2'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $agent->update([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => PhoneNumber::normalize($data['phone'] ?? null),
            'phone_country' => PhoneNumber::country($data['phone_country'] ?? null),
            'notes' => $data['notes'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.agents.index')
            ->with('success', 'อัปเดตพนักงานขายเรียบร้อยแล้ว');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();

        return back()->with('success', 'ลบพนักงานขายเรียบร้อยแล้ว');
    }
}
