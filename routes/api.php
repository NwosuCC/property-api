<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('/', function () {
  return redirect('/api/available-houses');
});


Route::group([], function() {

  Route::namespace('Api')->group(function () {

    Route::middleware(['auth:api'])->group(function () {
      // Houses
      Route::get('/available-houses', 'HouseController@index');
      Route::get('/available-houses/{house}', 'HouseController@show');

      // Apply
      Route::post('/available-houses/{house}/apply', 'HouseController@apply');
    });

    Route::namespace('Auth')->group(function () {

      Route::post('/register', 'RegisterController@register');
      Route::post('/login', 'LoginController@login');

    });
  });

});
