<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberNotificationController extends Controller
{
    public function index(Request $request)
    {
        return view('member.notifications.index', [
            'notifications' => $request->user()->notifications()->latest()->paginate(15),
            'unreadCount' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    public function markRead(Request $request, string $notification)
    {
        $request->user()->notifications()->whereKey($notification)->firstOrFail()->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }
}
