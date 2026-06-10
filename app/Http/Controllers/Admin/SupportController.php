<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with('user')
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->when(request('type'), fn($q) => $q->where('type', request('type')))
            ->latest()->paginate(15);
        return view('admin.support.index', compact('tickets'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate(['admin_reply' => 'required|string']);

        // Warn if already closed — only update if explicitly acknowledged or re-replying
        if ($ticket->status === 'closed' && !$request->has('force_reply')) {
            return back()->withErrors(['admin_reply' => 'এই টিকেটটি ইতিমধ্যে বন্ধ করা হয়েছে। আপনি কি নিশ্চিত যে পুনরায় রিপ্লাই দিতে চান?']);
        }

        $ticket->update([
            'admin_reply' => $request->admin_reply,
            'replied_by'  => auth()->id(),
            'replied_at'  => now(),
            'status'      => 'closed',
        ]);
        return back()->with('success', 'Reply পাঠানো হয়েছে এবং Ticket বন্ধ করা হয়েছে।');
    }
}
