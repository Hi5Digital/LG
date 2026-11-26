<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
Route::put('/account', [AccountController::class, 'update'])->name('account.update');

Route::get('/models', [HomeController::class, 'models'])->name('models');
Route::get('/models/create', [HomeController::class, 'model_create'])->name('models.create');
Route::post('/models', [HomeController::class, 'model_store'])->name('models.store');
Route::get('/models/{id}', [HomeController::class, 'model_details'])->name('models.details');
Route::put('/models/{id}', [HomeController::class, 'model_update'])->name('models.update');
Route::post('/models/{id}/listing', [HomeController::class, 'model_listing'])->name('models.listing');
Route::post('/models/{id}/payment', [HomeController::class, 'payment_store'])->name('models.payment.store');
Route::post('/models/{id}/photo', [HomeController::class, 'model_photo_update'])->name('models.photo.update')->middleware('throttle:20,1');
Route::get('/models/{id}/photo/{pid}', [HomeController::class, 'show_photo'])->name('models.photo.show');
Route::post('/models/{mid}/photo/{pid}/delete', [HomeController::class, 'delete_photo'])->name('models.photo.delete');

Route::get('/body', [HomeController::class, 'body_types'])->name('body');
Route::post('/body', [HomeController::class, 'body_type_store'])->name('body.store');
Route::put('/body/{id}', [HomeController::class, 'body_type_update'])->name('body.update');
Route::delete('/body/{id}', [HomeController::class, 'body_type_delete'])->name('body.delete');

Route::get('/bust', [HomeController::class, 'bust_sizes'])->name('bust');
Route::post('/bust', [HomeController::class, 'bust_size_store'])->name('bust.store');
Route::put('/bust/{id}', [HomeController::class, 'bust_size_update'])->name('bust.update');
Route::delete('/bust/{id}', [HomeController::class, 'bust_size_delete'])->name('bust.delete');

Route::get('/ethnics', [HomeController::class, 'ethnics'])->name('ethnics');
Route::post('/ethnics', [HomeController::class, 'ethnic_store'])->name('ethnics.store');
Route::put('/ethnics/{id}', [HomeController::class, 'ethnic_update'])->name('ethnics.update');
Route::delete('/ethnics/{id}', [HomeController::class, 'ethnic_delete'])->name('ethnics.delete');

Route::get('/orientations', [HomeController::class, 'orientations'])->name('orientations');
Route::post('/orientations', [HomeController::class, 'orientation_store'])->name('orientations.store');
Route::put('/orientations/{id}', [HomeController::class, 'orientation_update'])->name('orientations.update');
Route::delete('/orientations/{id}', [HomeController::class, 'orientation_delete'])->name('orientations.delete');

Route::get('/towns', [HomeController::class, 'towns'])->name('towns');
Route::post('/towns', [HomeController::class, 'town_store'])->name('towns.store');
Route::put('/towns/{id}', [HomeController::class, 'town_update'])->name('towns.update');
Route::delete('/towns/{id}', [HomeController::class, 'town_delete'])->name('towns.delete');

Route::get('/others', [HomeController::class, 'others'])->name('others');
