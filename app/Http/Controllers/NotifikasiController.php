<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function markRead(Notifikasi $notifikasi)
    {
        $notifikasi->update(['is_read' => true]);
        return redirect()->back();
    }

    public function updateDelete(Notifikasi $notifikasi)
    {
        $notifikasi->update(['is_delete' => true]);
        return redirect()->back();
    }
    
    public function markAllRead()
    {
        $userId = auth()->id();
        Notifikasi::where('id_user', $userId)->where('is_read', false)->update(['is_read' => true]);
        return redirect()->back();
    }
}
