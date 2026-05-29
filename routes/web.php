<?php

use App\Http\Controllers\AtsRouteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InteractiveMapController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NavigationalAidController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WaypointController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('home');

Route::redirect('/network', '/interactive-map');
Route::redirect('/slots', '/ats-routes');
Route::redirect('/live-flights', '/dashboard');
Route::redirect('/weather', '/reports');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('waypoints', WaypointController::class)->except(['show']);
    Route::resource('navigational-aids', NavigationalAidController::class)->except(['show']);
    Route::resource('ats-routes', AtsRouteController::class)->except(['show']);
    Route::get('/interactive-map', [InteractiveMapController::class, 'index'])->name('interactive-map');
    Route::get('/api/map-data', [InteractiveMapController::class, 'data'])->name('api.map-data');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API data routes kept for compatibility with the existing project.
Route::get('/api/flights', function () {
    return response()->json([]);
})->name('api.flights');
Route::get('/api/weather', function () {
    return response()->json([]);
})->name('api.weather');
Route::get('/api/slots', function () {
    return response()->json([]);
})->name('api.slots');
Route::post('/api/slots/generate', function () {
    return response()->json(['message' => 'Slot generation is available in the dashboard module.']);
})->name('api.slots.generate');
Route::patch('/api/slots/{id}', function () {
    return response()->json(['message' => 'Slot status update is not used in the Blade version.']);
})->name('api.slots.update');

require __DIR__.'/auth.php';
