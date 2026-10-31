
<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SalesHistoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\PreOrderController;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Customer shopping page
Route::get('/shop', function () {
    $products = Product::where('is_available', true)
        ->where('stock', '>', 0)
        ->latest()
        ->get();

    return view('shop.index', compact('products'));
})->name('shop.index');

// Login and registration
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))
        ->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return Auth::user()->is_admin
                ? redirect()->route('admin.dashboard')
                : redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The email or password is incorrect.',
        ])->onlyInput('email');
    })->name('login.submit');

    Route::get('/register', fn () => view('auth.register'))
        ->name('register');

    Route::post('/register', function (Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('login')
            ->with('status', 'Account created successfully! You can now log in.');
    })->name('register.submit');
});

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Customer account
    Route::get('/account', [AccountController::class, 'index'])
        ->name('account.index');

    Route::put('/account', [AccountController::class, 'update'])
        ->name('account.update');

    // Customer pre-orders
    Route::get('/preorders', [PreOrderController::class, 'index'])
        ->name('preorders.index');

    Route::post('/preorders', [PreOrderController::class, 'store'])
        ->name('preorders.store');

    Route::post('/preorders/{preorder}/cancel-request',
        [PreOrderController::class, 'requestCancellation']
    )->name('preorders.cancel-request');

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');

    // Admin dashboard
    Route::get('/admin', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Admin products
    Route::resource('admin/products', ProductController::class)
        ->names('admin.products')
        ->except(['show']);

    // Admin orders
    Route::get('/admin/orders', [OrderController::class, 'index'])
        ->name('admin.orders.index');

    Route::put('/admin/orders/{order}', [OrderController::class, 'update'])
        ->name('admin.orders.update');

    // Admin cancellation request review
    Route::post('/admin/orders/{order}/cancellation-review',
        [OrderController::class, 'reviewCancellation']
    )->name('admin.orders.cancellation-review');

    // Delete cancelled orders
    Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy'])
        ->name('admin.orders.destroy');

    // Admin Sales History
    Route::get('/admin/sales-history', [SalesHistoryController::class, 'index'])
        ->name('admin.sales-history.index');

    // Reset Current Sales
    Route::post('/admin/sales/reset', [SalesHistoryController::class, 'reset'])
        ->name('admin.sales.reset');

    // User Management
    Route::get('/admin/users', [UserController::class, 'index'])
        ->name('admin.users.index');

    Route::patch('/admin/users/{user}/promote', [UserController::class, 'promote'])
        ->name('admin.users.promote');

    Route::patch('/admin/users/{user}/demote', [UserController::class, 'demote'])
        ->name('admin.users.demote');

    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
        ->name('admin.users.destroy');
});
