<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

/**
 * The messages sent from the public contact form. They used to land in the
 * database and stay there, with nothing in the admin to read them.
 */
class ContactController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('status');

        $contacts = Contact::query()
            ->when($filter === 'open', fn ($query) => $query->open())
            ->when($filter === 'handled', fn ($query) => $query->whereNotNull('handled_at'))
            ->when(trim((string) $request->query('q', '')) !== '', function ($query) use ($request) {
                $like = '%' . trim((string) $request->query('q')) . '%';

                $query->where(function ($inner) use ($like) {
                    $inner->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhere('subject', 'like', $like)
                        ->orWhere('message', 'like', $like);
                });
            })
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.contacts.index', [
            'contacts' => $contacts,
            'openCount' => Contact::open()->count(),
            'filter' => $filter,
        ]);
    }

    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    /** Mark an enquiry as answered, or put it back in the open list. */
    public function toggle(Request $request, Contact $contact)
    {
        $contact->update($contact->handled_at
            ? ['handled_at' => null, 'handled_by' => null]
            : ['handled_at' => now(), 'handled_by' => $request->user()?->name]);

        return back()->with('success', $contact->handled_at
            ? 'ทำเครื่องหมายว่าตอบแล้ว'
            : 'ย้ายกลับไปรายการที่ยังไม่ตอบ');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'ลบข้อความแล้ว');
    }
}
