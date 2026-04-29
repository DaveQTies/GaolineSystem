<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClerkController;
use App\Http\Controllers\CustomerController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes - Krude Gas Pre-Order System
|--------------------------------------------------------------------------
*/

// 1. AUTH / LOGIN ROUTES
Route::get('/', function () { 
    return view('auth.login'); 
})->name('login');

Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'role' => 'required|in:admin,clerk,customer',
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $email = strtolower($validated['email']);
    $requestedRole = ucfirst($validated['role']);

    if ($email === 'admin15@gmail.com' && $validated['password'] !== 'admin15') {
        return back()->withErrors(['password' => 'Invalid admin password.'])->withInput();
    }

    $user = User::firstOrCreate(
        ['email' => $email],
        [
            'name' => Str::headline(Str::before($email, '@')),
            'password' => Hash::make($validated['password']),
            'role' => $email === 'admin15@gmail.com' ? 'Admin' : 'Customer',
            'status' => 'Active',
        ]
    );

    if ($email === 'admin15@gmail.com' && $user->role !== 'Admin') {
        $user->role = 'Admin';
    }

    if ($email === 'admin15@gmail.com') {
        $user->password = Hash::make('admin15');
    } elseif (!$user->wasRecentlyCreated && !Hash::check($validated['password'], $user->password)) {
        return back()->withErrors(['password' => 'Invalid password for this account.'])->withInput();
    }

    if ($user->status !== 'Active') {
        return back()->withErrors(['email' => 'This account is inactive. Please contact the admin.'])->withInput();
    }

    if ($user->role !== 'Admin' && $requestedRole !== $user->role) {
        return back()
            ->withErrors(['role' => 'Your account is only allowed to sign in as ' . $user->role . '.'])
            ->withInput();
    }

    $user->forceFill([
        'last_login_at' => now(),
    ])->save();

    $request->session()->put('login_email', $email);
    $request->session()->put('login_role', $validated['role']);
    $request->session()->put('account_role', $user->role);

    return redirect()->to('/' . $validated['role'] . '.php');
})->name('login.redirect');

Route::get('/admin.php', [AdminController::class, 'index'])->middleware('role:Admin')->name('admin.php');

Route::get('/clerk.php', [ClerkController::class, 'index'])->middleware('role:Clerk')->name('clerk.php');

Route::get('/customer.php', [CustomerController::class, 'index'])->middleware('role:Customer')->name('customer.php');


// 2. ADMIN DASHBOARD ROUTES (Prefix: /admin)
Route::prefix('admin')->name('admin.')->middleware('role:Admin')->group(function () {
    
    // Main Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('users');
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store');
    Route::patch('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::patch('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggleStatus');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Fuel Prices & Inventory
    Route::get('/prices', [AdminController::class, 'managePrices'])->name('prices');
    Route::post('/update-stock', [AdminController::class, 'updateStock'])->name('updateStock');
});


// 3. CLERK DASHBOARD ROUTES (Prefix: /clerk)
Route::prefix('clerk')->name('clerk.')->middleware('role:Clerk')->group(function () {
    Route::get('/', [ClerkController::class, 'index'])->name('dashboard');
    Route::post('/orders', [ClerkController::class, 'store'])->name('orders.store');
    
    // KINI ANG USBA: Gikan sa updateStatus, himoang update
    Route::patch('/orders/{id}/status', [ClerkController::class, 'updateStatus'])->name('orders.update');
    
    Route::get('/orders/search', [ClerkController::class, 'search'])->name('orders.search');
});


// 4. CUSTOMER DASHBOARD ROUTES (Prefix: /customer)
Route::prefix('customer')->name('customer.')->middleware('role:Customer')->group(function () {
    
    // Main Dashboard para sa customer
    Route::get('/', [CustomerController::class, 'index'])->name('dashboard');

    Route::post('/orders', [CustomerController::class, 'store'])->name('orders.store');
});

/*
|--------------------------------------------------------------------------
| Dev Note: Siguroha nga naa kay ClerkController ug AdminController dol 
| para dili mo-error og "Target class does not exist".
|--------------------------------------------------------------------------
*/
