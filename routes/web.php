<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LiveFlightController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\SlotController;
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
})->name('home');

Route::get('/network', function () {
    return Inertia::render('Network', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('network');

Route::get('/slots', function () {
    return Inertia::render('Slots', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('slots');

Route::get('/live-flights', function () {
    return Inertia::render('LiveFlights', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('live-flights');

Route::get('/weather', function () {
    return Inertia::render('Weather', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('weather');

// API Data Routes
Route::get('/api/flights', [LiveFlightController::class, 'index'])->name('api.flights');
Route::get('/api/weather', [WeatherController::class, 'index'])->name('api.weather');
Route::get('/api/slots', [SlotController::class, 'index'])->name('api.slots');
Route::post('/api/slots/generate', [SlotController::class, 'generateLiveSlots'])->name('api.slots.generate');
Route::patch('/api/slots/{id}', [SlotController::class, 'updateStatus'])->name('api.slots.update');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
