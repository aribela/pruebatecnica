<?php


use Illuminate\Support\Facades\Route;
use App\Http\Livewire\CategoriesController;

use App\Http\Livewire\PermisosController;

use App\Http\Livewire\RolesController;
use App\Http\Livewire\UsersController;

use App\Http\Livewire\EvidenceController;
use App\Http\Livewire\SliderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('categories', CategoriesController::class);

Route::get('roles', RolesController::class);
Route::get('permisos', PermisosController::class);

Route::get('users', UsersController::class);
Route::get('evidencias', EvidenceController::class);
Route::get('lang/{lang}', function($lang){
    session()->put('locale', $lang);
        return redirect()->back();
});
// Route::get('slider', [EvidenceController::class, 'slider']);
Route::get('slider', SliderController::class);

// Route::get('/evidenciasindex', 'EvidenceController@index');
Route::get('/evidenciasindex', [EvidenceController::class, 'index']);
Route::get('/evidencestore', [EvidenceController::class, 'storeApi']);