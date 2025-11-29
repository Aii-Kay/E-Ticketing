<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Organizer\BookingController as OrganizerBookingController;


// route default, bebas
Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

// GROUP ADMIN
Route::middleware(['auth', 'role.admin']) // middleware admin yang sudah kamu buat
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard admin sederhana
        Route::get('/', function () {
            return 'Admin dashboard';
        })->name('dashboard');

        // USER MANAGEMENT

        // GET /admin/users
        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        // POST /admin/users/{id}/approve
        Route::post('/users/{id}/approve', [AdminUserController::class, 'approveOrganizer'])
            ->name('users.approve');

        // POST /admin/users/{id}/reject
        Route::post('/users/{id}/reject', [AdminUserController::class, 'rejectOrganizer'])
            ->name('users.reject');

        // DELETE /admin/users/{id}
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])
            ->name('users.destroy');
    });

Route::middleware(['auth', 'role.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ... route admin yang sudah kamu punya (dashboard, user management, dll)

        // EVENTS (ADMIN)
        Route::get('/events', [EventController::class, 'index'])
            ->name('events.index');

        Route::get('/events/create', [EventController::class, 'create'])
            ->name('events.create');

        Route::post('/events', [EventController::class, 'store'])
            ->name('events.store');

        Route::get('/events/{event}/edit', [EventController::class, 'edit'])
            ->name('events.edit');

        Route::put('/events/{event}', [EventController::class, 'update'])
            ->name('events.update');

        Route::delete('/events/{event}', [EventController::class, 'destroy'])
            ->name('events.destroy');
    });

Route::middleware(['auth', 'role.organizer'])
    ->prefix('organizer')
    ->name('organizer.')
    ->group(function () {

        // ... route organizer dashboard yang sudah ada

        // EVENTS (ORGANIZER)
        Route::get('/events', [EventController::class, 'index'])
            ->name('events.index');

        Route::get('/events/create', [EventController::class, 'create'])
            ->name('events.create');

        Route::post('/events', [EventController::class, 'store'])
            ->name('events.store');

        Route::get('/events/{event}/edit', [EventController::class, 'edit'])
            ->name('events.edit');

        Route::put('/events/{event}', [EventController::class, 'update'])
            ->name('events.update');

        Route::delete('/events/{event}', [EventController::class, 'destroy'])
            ->name('events.destroy');
    });

Route::middleware(['auth', 'role.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ... route admin lain (dashboard, users, events, dll)

        // ROUTE CATEGORY (ADMIN)
        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        Route::get('/categories/create', [CategoryController::class, 'create'])
            ->name('categories.create');

        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('categories.store');

        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
            ->name('categories.edit');

        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->name('categories.update');

        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');
    });
Route::middleware(['auth', 'role.user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/', function () {
            return 'User dashboard';
        })->name('dashboard');

        Route::prefix('bookings')
            ->name('bookings.')
            ->group(function () {
                Route::get('/', [UserBookingController::class, 'index'])
                    ->name('index');   // GET /user/bookings
                Route::post('/', [UserBookingController::class, 'store'])
                    ->name('store');   // POST /user/bookings
                Route::get('/{booking}', [UserBookingController::class, 'show'])
                    ->name('show');    // GET /user/bookings/{booking}
                Route::post('/{booking}/cancel', [UserBookingController::class, 'cancel'])
                    ->name('cancel');  // POST /user/bookings/{booking}/cancel
            });
    });

Route::middleware(['auth', 'role.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ... route admin lain (dashboard, users, events, categories, dll)

        Route::prefix('bookings')
            ->name('bookings.')
            ->group(function () {
                Route::get('/', [AdminBookingController::class, 'index'])
                    ->name('index');   // GET /admin/bookings
                Route::get('/{booking}', [AdminBookingController::class, 'show'])
                    ->name('show');    // GET /admin/bookings/{booking}
                Route::post('/{booking}/approve', [AdminBookingController::class, 'approve'])
                    ->name('approve'); // POST /admin/bookings/{booking}/approve
                Route::post('/{booking}/cancel', [AdminBookingController::class, 'cancel'])
                    ->name('cancel');  // POST /admin/bookings/{booking}/cancel
            });
    });

Route::middleware(['auth', 'role.organizer'])
    ->prefix('organizer')
    ->name('organizer.')
    ->group(function () {

        // ... route organizer lain (dashboard, events, dll)

        Route::prefix('bookings')
            ->name('bookings.')
            ->group(function () {
                Route::get('/', [OrganizerBookingController::class, 'index'])
                    ->name('index');   // GET /organizer/bookings
                Route::get('/{booking}', [OrganizerBookingController::class, 'show'])
                    ->name('show');    // GET /organizer/bookings/{booking}
                Route::post('/{booking}/approve', [OrganizerBookingController::class, 'approve'])
                    ->name('approve'); // POST /organizer/bookings/{booking}/approve
                Route::post('/{booking}/cancel', [OrganizerBookingController::class, 'cancel'])
                    ->name('cancel');  // POST /organizer/bookings/{booking}/cancel
            });
    });
