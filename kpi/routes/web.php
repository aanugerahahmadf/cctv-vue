<?php

use App\Http\Controllers\Admin\BuildingController as AdminBuildingController;
use App\Http\Controllers\Admin\CameraController as AdminCameraController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Auth\CodeController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Google OAuth
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

// Email code flows
Route::get('/auth/code/request', [CodeController::class, 'showRequest'])->name('code.request');
Route::post('/auth/code/send', [CodeController::class, 'send'])->name('code.send');
Route::get('/auth/code/verify', [CodeController::class, 'showVerify'])->name('code.verify.show');
Route::post('/auth/code/verify', [CodeController::class, 'verify'])->name('code.verify');

// Maps (user)
Route::get('/maps', [MapController::class, 'index'])->middleware(['auth', 'verified'])->name('maps');

// Locations
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/locations', [LocationController::class, 'buildings'])->name('locations.buildings');
    Route::get('/locations/{building}/rooms', [LocationController::class, 'rooms'])->name('locations.rooms');
    Route::get('/locations/rooms/{room}/cameras', [LocationController::class, 'cameras'])->name('locations.cameras');
});

// Contact
Route::get('/contact', ContactController::class)->name('contact');

// Chat
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
});

// Cameras
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cameras/{camera}/live', [CameraController::class, 'live'])->name('cameras.live');
    Route::get('/cameras/{camera}/snapshot', [CameraController::class, 'snapshot'])->name('cameras.snapshot');
    Route::get('/cameras/{camera}/export', [CameraController::class, 'export'])->name('cameras.export');
});

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('buildings/export', [AdminBuildingController::class, 'export'])->name('buildings.export');
    Route::resource('buildings', AdminBuildingController::class)->except(['show']);

    Route::get('rooms/export', [AdminRoomController::class, 'export'])->name('rooms.export');
    Route::resource('rooms', AdminRoomController::class)->except(['show']);

    Route::get('cameras/export', [AdminCameraController::class, 'export'])->name('cameras.export');
    Route::resource('cameras', AdminCameraController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
