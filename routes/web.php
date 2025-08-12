<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ExportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Building; // Added

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Google OAuth
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

// Maps (user)
Route::get('/maps', [MapController::class, 'index'])->middleware(['auth', 'verified'])->name('maps');

// Public routes
Route::get('/contacts', [ContactController::class, 'getPublicContacts'])->name('contacts.public');

// User routes (authenticated)
Route::middleware(['auth', 'verified'])->group(function () {
    // Settings
    Route::get('/settings', function () {
        return Inertia::render('Settings');
    })->name('settings');

    // Location routes
    Route::get('/location', function () {
        $buildings = Building::withCount(['cameras as online_cameras_count' => function($q){$q->where('status','online');}, 'cameras as offline_cameras_count' => function($q){$q->where('status','offline');}, 'cameras as maintenance_cameras_count' => function($q){$q->where('status','maintenance');}])->get(['id','name','description']);
        return Inertia::render('Location/Index', [
            'buildings' => $buildings,
        ]);
    })->name('location.index');
    
    Route::get('/location/{building}', [BuildingController::class, 'show'])->name('location.building');
    Route::get('/location/{building}/{room}', [RoomController::class, 'show'])->name('location.room');
    
    // Camera routes
    Route::get('/cameras/{camera}', [CameraController::class, 'show'])->name('cameras.show');
    Route::get('/cameras/{camera}/stream', [CameraController::class, 'getStreamUrl'])->name('cameras.stream');
    
    // Message routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/unread/count', [MessageController::class, 'getUnreadCount'])->name('messages.unread.count');
    Route::get('/messages/conversations', [MessageController::class, 'getConversations'])->name('messages.conversations');
});

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    
    // Users
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');

    // Building management
    Route::resource('buildings', BuildingController::class);
    Route::get('buildings/{building}/map-data', [BuildingController::class, 'getMapData'])->name('buildings.map-data');
    
    // Room management
    Route::resource('rooms', RoomController::class);
    Route::get('buildings/{building}/rooms', [RoomController::class, 'getByBuilding'])->name('buildings.rooms');
    
    // Camera management
    Route::get('cameras', [CameraController::class, 'index'])->name('cameras.index');
    Route::resource('cameras', CameraController::class)->except(['index', 'show']);
    Route::patch('cameras/{camera}/status', [CameraController::class, 'updateStatus'])->name('cameras.status');
    Route::get('buildings/{building}/cameras', [CameraController::class, 'getByBuilding'])->name('buildings.cameras');
    Route::get('rooms/{room}/cameras', [CameraController::class, 'getByRoom'])->name('rooms.cameras');

    // Camera control & media
    Route::post('cameras/{camera}/start', [CameraController::class, 'startStream'])->name('cameras.start');
    Route::post('cameras/{camera}/stop', [CameraController::class, 'stopStream'])->name('cameras.stop');
    Route::post('cameras/{camera}/snapshot', [CameraController::class, 'snapshot'])->name('cameras.snapshot');
    Route::post('cameras/{camera}/record', [CameraController::class, 'record'])->name('cameras.record');

    // Export
    Route::get('export/cameras.csv', [ExportController::class, 'camerasCsv'])->name('export.cameras');
    Route::get('export/users.csv', [ExportController::class, 'usersCsv'])->name('export.users');
    
    // Contact management
    Route::resource('contacts', ContactController::class);
    
    // Message management
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('messages', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::patch('messages/{message}', [MessageController::class, 'markAsRead'])->name('messages.read');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
