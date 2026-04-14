<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(function () {
            // Kita turunkan aturan sementara agar mudah diubah (misal min 8) atau pertahankan jika ingin ketat
            return Password::min(8)
            ->mixedCase()
            ->numbers();
        });

        // Pindahkan registrasi enum DB keluar dari closure Password::defaults
        DB::connection()->getDoctrineSchemaManager()
            ->getDatabasePlatform()
            ->registerDoctrineTypeMapping('enum', 'string');
    }
}
