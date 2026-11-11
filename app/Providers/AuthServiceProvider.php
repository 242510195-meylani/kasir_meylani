<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // <--- Tambahkan ini di atas
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Daftarkan Gate admin di sini
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}