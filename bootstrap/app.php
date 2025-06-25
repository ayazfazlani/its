<?php

use App\Http\Middleware\Localization;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web([
            Localization::class,
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role-or-permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            // 'department' => \App\Http\Middleware\DepartmentAccess::class,
            'check.department.or.role' => \App\Http\Middleware\CheckDepartmentOrRole::class,


        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();