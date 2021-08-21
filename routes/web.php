<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
    return "lol";
});



Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});


Route::get('/familles',[App\Http\Controllers\Controller::class, 'ListeFamilles']);
Route::get('/articles',[App\Http\Controllers\Controller::class, 'ListeArticles']);
Route::get('/zones',[App\Http\Controllers\Controller::class, 'ListeZones']);
Route::get('/ventes',[App\Http\Controllers\Controller::class, 'ListeVentes']);
Route::get('/stocks',[App\Http\Controllers\Controller::class, 'ListeStocks']);


Route::get('/transfert/familles',[App\Http\Controllers\Controller::class, 'TransfertFamille']);
Route::get('/transfert/zones',[App\Http\Controllers\Controller::class, 'TransfertZone']);
Route::get('/transfert/articles',[App\Http\Controllers\Controller::class, 'TransfertArticle']);
Route::get('/transfert/ventes',[App\Http\Controllers\Controller::class, 'TransfertCommande']);
Route::get('/transfert/stocks',[App\Http\Controllers\Controller::class, 'TransfertStock']);