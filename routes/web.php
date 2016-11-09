<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'LoginUserController@login');

Route::get('login', 'LoginUserController@login');

Route::post('logout', 'LoginUserController@logout');

Route::post('login', 'LoginUserController@loginPost');

Route::get('register', 'RegisterUserController@register');

Route::post('register', 'RegisterUserController@registerPost');

Route::get('verify/{email}/{hash}', 'RegisterUserController@verifyEmail');

Route::get('dashboard', 'HomeController@index');

Route::get('email', 'RegisterUserController@email');