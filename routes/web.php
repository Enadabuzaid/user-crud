<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::group(['middleware' => ['auth' , 'verified']], function () {
    /*---- option 1 : ----*/
    //Route::resource('users' , UserController::class);

    /*---- option 2 : ----*/
//    Route::prefix('users')->group(function () {
//        Route::get('/' , [UserController::class , 'index'])->name('users.index');
//        Route::get('/create' , [UserController::class , 'create'])->name('users.create');
//        Route::get('/trashed', [UserController::class , 'trashed'])->name('users.trashed');
//        Route::post('/store' , [UserController::class , 'store'])->name('users.store');
//        Route::get('/edit/{user}' , [UserController::class , 'edit'])->name('users.edit');
//        Route::get('/show/{id}' , [UserController::class , 'show'])->name('users.show');
//        Route::put('/update/{user}' , [UserController::class , 'update'])->name('users.update');
//        Route::delete('/destroy/{id}' , [UserController::class , 'destroy'])->name('users.destroy');
//        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
//    });

    /*---- option 3 : after define macro ----*/
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
        Route::get('/show/{id}', [UserController::class, 'show'])->name('users.show');
        Route::put('/update/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::softDeletes('users', 'App\Http\Controllers\UserController');
    });
});



require __DIR__.'/auth.php';
