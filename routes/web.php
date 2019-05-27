<?php

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

Route::redirect('/', '/properties');

Auth::routes();

// Overrides Auth::routes '/register'. Registration via API only
Route::get('/register', function (){
  abort(404);
});


Route::namespace('Auth')->group(function () {

  Route::get('/login', 'LoginController@showLoginForm')->name('login');
  Route::post('/login', 'LoginController@login');

  Route::match(['get', 'post'], '/logout', 'LoginController@logout');

});


Route::middleware(['auth', 'admin'])->group(function () {

  Route::name('tenant.')->group(function () {

    Route::get('/tenants', 'TenantController@index')->name('index');
    Route::get('/tenants/{user}', 'TenantController@show')->name('show');

  });


  Route::name('applicant.')->group(function () {

    Route::get('/applicants', 'ApplicantController@index')->name('index');
    Route::get('/applicants/{user}', 'ApplicantController@show')->name('show');
    Route::put('/applicants/{user}', 'ApplicantController@update')->name('update');

  });


  Route::name('category.')->group(function () {

    Route::get('/categories', 'CategoryController@index')->name('index');
    Route::post('/categories', 'CategoryController@store')->name('store');
    Route::put('/categories/{category}', 'CategoryController@update')->name('update');
    Route::delete('/categories/{category}', 'CategoryController@destroy')->name('delete');

  });


  Route::name('house.')->group(function () {

    Route::get('/properties/applications', 'HouseController@applied')->name('applied');
    Route::put('/properties/assign/{user}/{house}', 'HouseController@assign')->name('assign');
    Route::put('/properties/release/{user}/{house}', 'HouseController@release')->name('release');

    Route::get('/properties/occupied', 'HouseController@rented')->name('rented');

    Route::get('/properties', 'HouseController@index')->name('index');
    Route::get('/properties/in/{category}', 'HouseController@index')->name('category');
    Route::get('/properties/create', 'HouseController@create')->name('create');
    Route::post('/properties', 'HouseController@store')->name('store');
    Route::get('/properties/{house}/edit', 'HouseController@edit')->name('edit');
    Route::get('/properties/{house}', 'HouseController@show')->name('show');
    Route::put('/properties/{house}', 'HouseController@update')->name('update');
    Route::delete('/properties/{house}', 'HouseController@destroy')->name('delete');

  });

});

