<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RedirectController;


Route::get('/test/', function () {
    return response()->json(['message' => 'API is working']);
})->name('api.test');


//Rotas de API redirects
Route::resource('redirects', 'App\Http\Controllers\Api\RedirectController')
    ->only(['index', 'store']);
Route::get('/redirects/{code}/stats', [RedirectController::class, 'stats'])
    ->name('redirects.stats');

?>
