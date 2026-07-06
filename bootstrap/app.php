<?php

use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\ResolveTenant;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // routes/superadmin.php pakai grup 'web' juga (supaya dapat session,
            // CSRF, dll bawaan), TAPI secara eksplisit MENGECUALIKAN ResolveTenant
            // lewat withoutMiddleware() -- karena ResolveTenant sekarang di-prepend
            // ke grup 'web' secara global (lihat withMiddleware di bawah), yang
            // otomatis kena ke SEMUA request lewat grup 'web' TERMASUK route
            // internal Livewire (/livewire/update) yang tidak terdaftar lewat
            // routes/web.php kita sendiri.
            Route::middleware('web')
                ->withoutMiddleware(ResolveTenant::class)
                ->group(base_path('routes/superadmin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // PENTING: prepend ke grup 'web' GLOBAL (bukan cuma alias yang dipakai
        // manual di routes/web.php). Alasannya: Livewire mendaftarkan route
        // internalnya sendiri (/livewire/update, dipakai tiap interaksi
        // komponen seperti PageManager & SectionManager) langsung lewat
        // service provider-nya, BUKAN lewat routes/web.php kita -- jadi kalau
        // ResolveTenant cuma dibungkus manual di routes/web.php, route
        // Livewire itu tidak akan pernah kena resolusi tenant, dan search_path
        // tetap default (public) saat Livewire coba query ulang data komponen.
        $middleware->web(prepend: [
            ResolveTenant::class,
        ]);

        $middleware->alias([
            'role' => EnsureRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
