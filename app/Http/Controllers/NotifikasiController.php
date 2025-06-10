<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function markRead(Notifikasi $notifikasi, Request $request)
    {
        $notifikasi->update(['is_read' => true]);
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back();
    }

    public function updateDelete(Notifikasi $notifikasi, Request $request)
    {
        $notifikasi->update(['is_deleted' => true]);
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back();
    }
    
    public function markAllRead(Request $request)
    {
        $userId = Auth::id();
        Notifikasi::where('id_user', $userId)->where('is_read', false)->update(['is_read' => true]);
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back();
    }

    public function getNotifications(Request $request)
    {
        if (Auth::check()) {
            $notifications = Notifikasi::where('id_user', Auth::id())
                ->where('is_deleted', false)
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($notif) {
                    return [
                        'id_notifikasi' => $notif->id_notifikasi,
                        'pesan' => $notif->pesan,
                        'is_read' => $notif->is_read,
                        'waktu_kirim_formatted' => \Carbon\Carbon::parse($notif->waktu_kirim)->format('d/m/Y'),
                        'waktu_kirim_time' => \Carbon\Carbon::parse($notif->waktu_kirim)->format('H:i')
                    ];
                });
            $unreadCount = $notifications->where('is_read', false)->count();
            return response()->json([
                'notifications' => $notifications,
                'unreadCount' => $unreadCount
            ]);
        }
        return response()->json(['notifications' => [], 'unreadCount' => 0]);
    }
}
