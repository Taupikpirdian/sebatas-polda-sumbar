<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'api'], function () {
	// manipulasi data
	Route::post('/update-satker', ['as' => 'update-satker', 'uses' => 'PerkaraController@update']);
	Route::get('/update-param-id', ['as' => 'update-param-id', 'uses' => 'PerkaraController@updateParamKorban']);
	
	// Data Laporan
	Route::get('/count-data-polda', ['as' => 'count-data-polda', 'uses' => 'PerkaraController@polda']);
	Route::get('/data-perkara', ['as' => 'data-perkara', 'uses' => 'PerkaraController@data']);

	// Cek no rangka di aplikasi BB
	Route::get('/check-no-rangka', ['as' => 'check-no-rangka', 'uses' => 'PerkaraController@checkNoRangka']);

	Route::post('/perkara-curanmor', ['as' => 'perkara-curanmor', 'uses' => 'PerkaraController@perkaraCuranmor']);
	Route::post('/formater', ['as' => 'formater', 'uses' => 'PerkaraController@formater']);
 });