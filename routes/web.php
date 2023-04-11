<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

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
Route::prefix('users')->group(function () {
    Route::get('/index', [UserController::class, 'index'])->name('user.index');
    Route::get('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/update/{user}', [UserController::class, 'update'])->name('user.update');
    Route::get('/destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');
});

Route::prefix('projects')->group(function () {
    Route::get('/index', [ProjectController::class, 'index'])->name('project.index');
    Route::get('/create', [ProjectController::class, 'create'])->name('project.create');
    Route::post('/store', [ProjectController::class, 'store'])->name('project.store');
    Route::get('/edit/{project}', [ProjectController::class, 'edit'])->name('project.edit');
    Route::post('/update/{project}', [ProjectController::class, 'update'])->name('project.update');
    Route::get('/destroy/{project}', [ProjectController::class, 'destroy'])->name('project.destroy');
});

Route::prefix('tasks')->group(function () {
    Route::get('/index', [TaskController::class, 'index'])->name('task.index');
    Route::get('/create', [TaskController::class, 'create'])->name('task.create');
    Route::post('/store', [TaskController::class, 'store'])->name('task.store');
    Route::get('/edit/{task}', [TaskController::class, 'edit'])->name('task.edit');
    Route::post('/update/{task}', [TaskController::class, 'update'])->name('task.update');
    Route::get('/destroy/{task}', [TaskController::class, 'destroy'])->name('task.destroy');
});




require __DIR__.'/auth.php';
