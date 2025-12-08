<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ADMIN
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;

// ORGANIZER
use App\Http\Controllers\Organizer\BookingController as OrganizerBookingController;
use App\Http\Controllers\Organizer\NotificationController as OrganizerNotificationController;

// SHARED & PUBLIC
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketTypeController;
use App\Http\Controllers\AnalyticsController;

// USER
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\User\FavoriteController as UserFavoriteController;
use App\Http\Controllers\User\NotificationController as UserNotificationController;
use App\Http\Controllers\User\ReviewController as UserReviewController;

// MIDDLEWARE ROLE
use App\Http\Middleware\RoleAdmin;
use App\Http\Middleware\RoleOrganizer;
use App\Http\Middleware\RoleUser;

/*
|--------------------------------------------------------------------------
| ROUTE GUEST / PUBLIC
|--------------------------------------------------------------------------
*/

// Halaman awal (Home). Menggunakan method search() karena logic-nya sama:
// Menampilkan view 'welcome', mengirim data $events dan $categories.
Route::get('/', [EventController::class, 'search'])->name('home');

// Route khusus untuk aksi Search pada form
Route::get('/search', [EventController::class, 'search'])->name('events.search');

// Auth routes (Breeze)
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| PROFILE (BREEZE)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT (SEMUA ROLE)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = Auth::user();

    if (! $user) {
        return redirect()->route('login');
    }

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'organizer') {
        return redirect()->route('organizer.dashboard');
    }

    // default registered_user
    return redirect()->route('user.dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard admin
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // USER MANAGEMENT
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{id}/approve', [AdminUserController::class, 'approveOrganizer'])->name('users.approve');
        Route::post('/users/{id}/reject', [AdminUserController::class, 'rejectOrganizer'])->name('users.reject');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // CATEGORY MANAGEMENT
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        // EVENT MANAGEMENT (ADMIN)
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('index');
            Route::get('/create', [EventController::class, 'create'])->name('create');
            Route::post('/', [EventController::class, 'store'])->name('store');
            Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
            Route::put('/{event}', [EventController::class, 'update'])->name('update');
            Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
        });

        // BOOKING MANAGEMENT (ADMIN)
        Route::prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/', [AdminBookingController::class, 'index'])->name('index');
            Route::get('/{booking}', [AdminBookingController::class, 'show'])->name('show');
            Route::post('/{booking}/approve', [AdminBookingController::class, 'approve'])->name('approve');
            Route::post('/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('cancel');
        });

        // NOTIFICATIONS (ADMIN)
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
            Route::post('/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('read');
        });

        // TICKET TYPES (ADMIN)
        Route::prefix('ticket-types')->name('ticket-types.')->group(function () {
            Route::post('/', [TicketTypeController::class, 'store'])->name('store');
        });

        // ADMIN ANALYTICS JSON
        Route::get('/analytics/json', [AnalyticsController::class, 'adminStats'])
            ->name('analytics.json');

        // Analytics Page
        Route::get('/analytics', function () {
            return view('admin.analytics');
        })->name('analytics.index');
    });

/*
|--------------------------------------------------------------------------
| ORGANIZER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleOrganizer::class])
    ->prefix('organizer')
    ->name('organizer.')
    ->group(function () {

        // Dashboard organizer
        Route::get('/', function () {
            return view('organizer.dashboard');
        })->name('dashboard');

        // EVENT MANAGEMENT (ORGANIZER)
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('index');
            Route::get('/create', [EventController::class, 'create'])->name('create');
            Route::post('/', [EventController::class, 'store'])->name('store');
            Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
            Route::put('/{event}', [EventController::class, 'update'])->name('update');
            Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
        });

        // BOOKING MANAGEMENT (ORGANIZER)
        Route::prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/', [OrganizerBookingController::class, 'index'])->name('index');
            Route::get('/{booking}', [OrganizerBookingController::class, 'show'])->name('show');
            Route::post('/{booking}/approve', [OrganizerBookingController::class, 'approve'])->name('approve');
            Route::post('/{booking}/cancel', [OrganizerBookingController::class, 'cancel'])->name('cancel');
        });

        // NOTIFICATIONS (ORGANIZER)
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [OrganizerNotificationController::class, 'index'])->name('index');
            Route::post('/{id}/read', [OrganizerNotificationController::class, 'markAsRead'])->name('read');
        });

        // TICKET TYPES (ORGANIZER)
        Route::prefix('ticket-types')->name('ticket-types.')->group(function () {
            Route::post('/', [TicketTypeController::class, 'store'])->name('store');
        });

        // ORGANIZER ANALYTICS JSON
        Route::get('/analytics/json', [AnalyticsController::class, 'organizerStats'])
            ->name('analytics.json');

        // Analytics Page
        Route::get('/analytics', function () {
            return view('organizer.analytics');
        })->name('analytics.index');
    });

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleUser::class])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        // DASHBOARD USER (list + search event)
        Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');

        // BOOKING USER
        Route::prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/', [UserBookingController::class, 'index'])->name('index');
            Route::get('/create', [UserBookingController::class, 'create'])->name('create');
            Route::post('/', [UserBookingController::class, 'store'])->name('store');
            Route::get('/{booking}', [UserBookingController::class, 'show'])->name('show');
            Route::post('/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('cancel');

            Route::get('/{booking}/ticket/download', [TicketController::class, 'downloadPDF'])
                ->name('ticket.download'); // user.bookings.ticket.download
        });

        // FAVORITE EVENTS
        Route::prefix('favorites')->name('favorites.')->group(function () {
            Route::get('/', [UserFavoriteController::class, 'index'])->name('index');
            Route::post('/', [UserFavoriteController::class, 'store'])->name('store');
            Route::delete('/{id}', [UserFavoriteController::class, 'destroy'])->name('destroy');
        });

        // NOTIFICATIONS (USER)
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [UserNotificationController::class, 'index'])->name('index');
            Route::post('/{id}/read', [UserNotificationController::class, 'markAsRead'])->name('read');
        });

        // EVENT REVIEWS (USER – create review)
        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::post('/', [UserReviewController::class, 'store'])->name('store');
        });
    });

/*
|--------------------------------------------------------------------------
| PUBLIC EVENT REVIEWS
|--------------------------------------------------------------------------
*/
Route::get('/event/{event}/reviews', [UserReviewController::class, 'index'])
    ->name('events.reviews.index');

/*
|--------------------------------------------------------------------------
| DEBUG / UTIL
|--------------------------------------------------------------------------
*/

// Cek user yang sedang login
Route::get('/whoami', function () {
    return Auth::user();
})->middleware('auth');

// Force logout (untuk testing)
Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| GLOBAL E-TICKET DOWNLOAD (Fallback)
|--------------------------------------------------------------------------
*/
Route::get('/booking/{booking}/ticket/download', [TicketController::class, 'downloadPDF'])
    ->middleware('auth')
    ->name('booking.ticket.download');
