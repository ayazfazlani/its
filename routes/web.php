<?php



use App\Livewire\Pages\Staff;



use App\Livewire\Layouts\Index;
use App\Livewire\Pages\Branches;
use App\Livewire\Admin\RolesCrud;
use App\Livewire\Admin\UsersCrud;
use App\Livewire\Pages\AdDetails;
use App\Livewire\Settings\Locale;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Pages\Ads\PauseAds;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Pages\AdInformation;
use App\Livewire\Pages\WebDevelopers;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Ads\ClientLeft;
use App\Livewire\Pages\Ads\InActiveAd;
use App\Livewire\Admin\PermissionsCrud;
use App\Livewire\Pages\EmployeeProfile;
use App\Livewire\Pages\ManagerDashboard;
use App\Livewire\Pages\Web\DelayedSites;
use App\Livewire\Pages\EpmloyeeDashboard;
use App\Livewire\Pages\Notices\NoticeList;
use App\Livewire\Pages\Web\CancelledSites;
use App\Livewire\Pages\Web\DeliveredSites;
use App\Livewire\Pages\Notices\NoticeForAll;
use App\Livewire\Pages\Employee\EmployeesList;
use App\Livewire\Pages\Employee\SeoSpecialist;
use App\Livewire\Pages\Employee\CustomerSupprt;
use App\Livewire\Pages\CstmrSprt\PaymentCleared;
use App\Livewire\Pages\CstmrSprt\PaymentUnclear;
use App\Http\Controllers\ImpersonationController;
use App\Livewire\Pages\Employee\DigitalMarketers;
use App\Livewire\Pages\Notices\NoticeForSpecific;
use App\Livewire\Pages\CstmrSprt\PaymentHalfCleared;


// Route::get('/', \App\Livewire\Home::class)->name('home');

Route::get('/dashboard', ManagerDashboard::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function (): void {
    // 🧭 Root Redirect Based on Role
    Route::get('', function () {
        $user = Auth::user();

        if ($user->hasRole(['Admin','Manager'])) {
            return redirect()->route('home');
        } else {
            return redirect()->route('dashboard');
        }

        return redirect('/login'); // fallback
    });
    // Impersonations
    Route::post('/impersonate/{user}', [ImpersonationController::class, 'store'])->name('impersonate.store');
    Route::delete('/impersonate/stop', [ImpersonationController::class, 'destroy'])->name('impersonate.destroy');

    // Settings
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/locale', \App\Livewire\Settings\Locale::class)->name('settings.locale');

    // routes



    // 🔹 Admin Dashboard
    Route::get('/home', Index::class)->name('home');

    // 🔹 Employee Dashboard
    Route::get('/employee-dashboard', EpmloyeeDashboard::class)->name('employee.dashboard');

    // 🔹 Staff
    Route::get('/staff', Staff::class)->name('staff');

    // 🔹 Employees
    Route::get('/employees/list', EmployeesList::class)->name('employees.list');
    Route::get('/employees', EmployeeProfile::class)->name('employees');
    Route::get('/employees/marketers', DigitalMarketers::class)->name('emp.marketers');
    Route::get('/employees/seo', SeoSpecialist::class)->name('emp.seo');
    Route::get('/employees/customersupport', CustomerSupprt::class)->name('emp.customersupport');

    // 🔸 Digital Marketing Routes
    Route::middleware(['check.department.or.role:digital marketing,Admin,Employee,Manager,Customer Support'])->group(function () {
        Route::get('/adds', AdInformation::class)->name('ads');
        Route::get('/pauseAds', PauseAds::class)->name('ads.paused');
        Route::get('/overdueAds', InActiveAd::class)->name('ads.inActive');
        Route::get('/clientleft', ClientLeft::class)->name('ads.clientleft');
        Route::get('/details/{id}', AdDetails::class)->name('ad.details');
    });

    // 🔸 Web Design Routes
    Route::middleware(['check.department.or.role:web design,Admin,Website Developer,Manager'])->group(function () {
        Route::get('/webdevs', WebDevelopers::class)->name('web.active');
        Route::get('/pausesites', DelayedSites::class)->name('web.paused');
        Route::get('/inactivesites', CancelledSites::class)->name('web.cancelled');
        Route::get('/completedsites', DeliveredSites::class)->name('web.completed');
    });
    Route::middleware(['check.department.or.role:customer support,Admin,Manager'])->group(function () {
        Route::get('/cleared', PaymentCleared::class)->name('ads.pymtclrd');
        Route::get('/payment/halfcleared', PaymentHalfCleared::class)->name('ads.pymthalfclrd');
        Route::get('/payment/uncleared', PaymentUnclear::class)->name('ads.pymtuncleared');
    });
    // 🔐 Admin-only routes
    Route::middleware(['role:Super Admin|Admin'])->group(function () {
        Route::get('/branches', Branches::class)->name('branches');
        //     // Route::get('/roles', CreateRoles::class)->name('roles.index');
        Route::get('/permissions', PermissionsCrud::class)->name('permissions.index');
        Route::get('/roles', RolesCrud::class)->name('roles.index');
        Route::get('/assign', UsersCrud::class)->name('assignRole');
    });

    // 🔚 Logout
    Route::get('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    Route::get('/notices/all', NoticeForAll::class)->name('notices.all');
    Route::get('/notices/specific', NoticeForSpecific::class)->name('notices.specific');
    Route::get('/notices', NoticeList::class)->name('notices.list');

    // 🔹 Manager Dashboard
    // Route::middleware(['role:Manager|Admin'])->group(function () {
        Route::get('/dashboard', ManagerDashboard::class)->name('dashboard');
    // });
    // endroutes

    // Admin
    Route::prefix('admin')->as('admin.')->group(function (): void {
        Route::get('/', \App\Livewire\Admin\Index::class)->middleware(['auth', 'verified'])->name('index');
        // Route::get('/users', \App\Livewire\Admin\Users::class)->name('users.index');
        // Route::get('/users/create', \App\Livewire\Admin\Users\CreateUser::class)->name('users.create');
        // Route::get('/users/{user}', \App\Livewire\Admin\Users\ViewUser::class)->name('users.show');
        // Route::get('/users/{user}/edit', \App\Livewire\Admin\Users\EditUser::class)->name('users.edit');
        // Route::get('/roles', \App\Livewire\Admin\Roles::class)->name('roles.index');
        // Route::get('/roles/create', \App\Livewire\Admin\Roles\CreateRole::class)->name('roles.create');
        // Route::get('/roles/{role}/edit', \App\Livewire\Admin\Roles\EditRole::class)->name('roles.edit');
        // Route::get('/permissions', \App\Livewire\Admin\Permissions::class)->name('permissions.index');
        // Route::get('/permissions/create', \App\Livewire\Admin\Permissions\CreatePermission::class)->name('permissions.create');
        // Route::get('/permissions/{permission}/edit', \App\Livewire\Admin\Permissions\EditPermission::class)->name('permissions.edit');

        // New single-component CRUDs
        Route::get('/roles-crud', RolesCrud::class)->name('roles.index');
        Route::get('/permissions-crud', \App\Livewire\Admin\PermissionsCrud::class)->name('permissions.index');
        Route::get('/users-crud', \App\Livewire\Admin\UsersCrud::class)->name('users-crud');
    });
});

require __DIR__ . '/auth.php';