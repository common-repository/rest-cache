<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Tables\RecordsTable;
use App\Tables\RulesTable;

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

Route::get('install', function (Request $request) {
	
	Artisan::call('migrate');
	
	return [
		"success" => true
	];
	
});

Route::resource('records', 'RecordController')->only([
	'index', 'store', 'update', 'destroy'
]);

Route::delete('records', 'RecordController@destroyAll');

Route::resource('rules', 'RuleController')->only([
	'index', 'store', 'update', 'destroy'
]);
