<?php

namespace App\Providers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $notifikasi = Notifikasi::where('id_user', Auth::id())
                    // ->where('is_delete', false)
                    ->orderByDesc('created_at')
                    ->get();

                $unreadCount = $notifikasi->where('is_read', false)->count();

                $view->with([
                    'notifikasi' => $notifikasi,
                    'unreadCount' => $unreadCount
                ]);
            }
        });
    }
}
