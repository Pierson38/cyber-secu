<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsConnected;
use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\AuthController@index')->name('home');

Route::get('/login', 'App\Http\Controllers\AuthController@index')->name('login');
Route::post('/login-in-progress', 'App\Http\Controllers\AuthController@attemptLogin')->name('login-attempt');
Route::get('/logout', 'App\Http\Controllers\AuthController@logout')->name('logout');
Route::post('/access-request', 'App\Http\Controllers\AuthController@access_request')->name('access-request');

Route::middleware(EnsureUserIsConnected::class)->group(function () {
    Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index')->name('dashboard');
    Route::get('/dashboard/users/list', 'App\Http\Controllers\DashboardController@users_list')
        ->name('users.list')->middleware(EnsureUserIsAdmin::class);
    Route::get('/dashboard/users/approve/{id}/{state}', 'App\Http\Controllers\DashboardController@user_approve')
        ->name('users.approve');
    Route::get('/dashboard/users/delete/{id}', 'App\Http\Controllers\DashboardController@user_delete')
        ->name('users.delete');
    Route::post('/dashboard/users/update-me', 'App\Http\Controllers\DashboardController@update_user')
        ->name('users.update');
    Route::get('/dashboard/activate-first-user-azertyuiop123789',
        'App\Http\Controllers\DashboardController@display_first_approval');
    Route::post('/dashboard/activate-first-user-987321poiuytreza',
        'App\Http\Controllers\DashboardController@process_first_approval')->name('approval-process');


    Route::get('/dashboard/clients/list', 'App\Http\Controllers\ClientsController@list')
        ->name('clients.list');
    Route::get('/dashboard/clients/auto-assignee/{userId}', 'App\Http\Controllers\ClientsController@auto_assignee')
        ->name('clients.assign');

    Route::post('/dashboard/clients/create', 'App\Http\Controllers\ClientsController@create_client')
        ->name('clients.create');
});

Route::get('/pictures/{path?}', 'App\Http\Controllers\DashboardController@get_picture_file')
    ->where('path', '.*')
    ->name('picture-get');
