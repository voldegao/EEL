<?php

use App\Models\Famille;
use App\Models\Client;
use App\Models\Zone;
use App\Models\Article;
use App\Models\Commande;
use App\Models\Prevision;
use App\Models\Coeffisiont;
use App\Models\Provision;




use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/login', function (Request $request) {
    return response()->json(['status_code' => 500,'message' => 'Unauthorized']);
})->name('login');
Route::post('/login', [App\Http\Controllers\admin\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\admin\AuthController::class, 'register']);

//test auth

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/validate/auth', function () {
        return 1;
    });
});



Route::post('/generation/data', [App\Http\Controllers\GenerationController::class, 'getArticleData']);
Route::post('/prev/generate', [App\Http\Controllers\PrevisionController::class, 'testFunc']);
Route::get('/prev/test', [App\Http\Controllers\PrevisionController::class, 'getPrev']);
Route::get('/prev/test/delete', [App\Http\Controllers\PrevisionController::class, 'deletePrev']);


//test Coefficient e





Route::post('/cv', [App\Http\Controllers\CommandeController::class, 'cv']);
Route::post('/cvChart', [App\Http\Controllers\CommandeController::class, 'cvChart']);
Route::post('/biais', [App\Http\Controllers\CommandeController::class, 'biais']);
Route::post('/biaisFamille', [App\Http\Controllers\CommandeController::class, 'biaisFamille']);
Route::post('/madFamille', [App\Http\Controllers\CommandeController::class, 'madFamille']);
Route::post('/mad', [App\Http\Controllers\CommandeController::class, 'mad']);
Route::get('/bai', [App\Http\Controllers\CommandeController::class, 'bai']);
Route::post('/mMobile', [App\Http\Controllers\CommandeController::class, 'moyenneMobile']);
Route::post('/aMobile', [App\Http\Controllers\CommandeController::class, 'moyenneMobilearticles']);
Route::get('/params', [App\Http\Controllers\Controller::class, 'getAll']);
Route::get('/alpha', [App\Http\Controllers\Controller::class, 'getAlpha']);
Route::post('/alpha/edit', [App\Http\Controllers\Controller::class, 'editAlpha']);
Route::post('/stocks', [App\Http\Controllers\ArticleController::class, 'stocks']);

//Cause et actions

Route::post('/article/causes/create', [App\Http\Controllers\CauseController::class, 'createCauseArticle']);
Route::post('/famille/causes/create', [App\Http\Controllers\CauseController::class, 'createCauseFamille']);
Route::post('/article/causes/update/{id}', [App\Http\Controllers\CauseController::class, 'updateCauseArticle']);
Route::post('/famille/causes/update', [App\Http\Controllers\CauseController::class, 'updateCauseFamille']);
Route::post('/article/causes/delete', [App\Http\Controllers\CauseController::class, 'deleteCauseArticle']);
Route::post('/famille/causes/delete', [App\Http\Controllers\CauseController::class, 'deleteCauseFamille']);
Route::post('/article/causes/{id}', [App\Http\Controllers\CauseController::class, 'listCauseArticle']);
Route::get('/article/cause/info/{id}', [App\Http\Controllers\CauseController::class, 'articleCauseinfo']);
Route::get('/famille/causes', [App\Http\Controllers\CauseController::class, 'listCauseFamille']);


// Familles

Route::get('/familles', [App\Http\Controllers\FamilleController::class, 'getFamilles']); //done tested
Route::get('/famille/info/{id}', [App\Http\Controllers\FamilleController::class, 'familleInfo']); //done  tested
Route::post('/famille/articles', [App\Http\Controllers\FamilleController::class, 'getArticles']); //done tested
Route::post('/famille/add/new', [App\Http\Controllers\FamilleController::class, 'createFamille']); //done tested
Route::post('/famille/edit/{id}', [App\Http\Controllers\FamilleController::class, 'editFamille']); //done tested
Route::post('/famille/delete/{id}', [App\Http\Controllers\FamilleController::class, 'deleteFamille']);//done tested


// Clients

Route::get('/clients', [App\Http\Controllers\ClientController::class, 'getClients']); //done
Route::get('/client/info/{id}', [App\Http\Controllers\ClientController::class, 'clientInfo']); //done tested
Route::post('/client/add/new', [App\Http\Controllers\ClientController::class, 'createClient']); //done tested
Route::post('/client/edit/{id}', [App\Http\Controllers\ClientController::class, 'editClient']); //done tested
Route::post('/client/delete/{id}', [App\Http\Controllers\ClientController::class, 'deleteClient']); //done tested


// Zones

Route::get('/zones', [App\Http\Controllers\ZoneController::class, 'getZones']);  //done tested
Route::get('/zone/info/{id}', [App\Http\Controllers\ZoneController::class, 'zoneInfo']); //done tested
Route::post('/zone/add/new', [App\Http\Controllers\ZoneController::class, 'createZone']); //done tested
Route::post('/zone/edit/{id}', [App\Http\Controllers\ZoneController::class, 'editZone']); //done tested
Route::post('/zone/delete/{id}', [App\Http\Controllers\ZoneController::class, 'deleteZone']); //done tested

// Articles

Route::get('/articles', [App\Http\Controllers\ArticleController::class, 'getArticles']); //done tested
Route::get('/articles/all', [App\Http\Controllers\ArticleController::class, 'getArticlesWithRelations']); //done tested
Route::get('article/info/{id}', [App\Http\Controllers\ArticleController::class, 'articleInfo']);//done tested
Route::post('/article/add/new', [App\Http\Controllers\ArticleController::class, 'createArticle']);//done tested
Route::post('/article/edit/{id}', [App\Http\Controllers\ArticleController::class, 'editArticle']);//done tested
Route::post('/article/delete/{id}', [App\Http\Controllers\ArticleController::class, 'deleteArticle']);//done tested

// Commandes

Route::get('/commandes', [App\Http\Controllers\CommandeController::class, 'getCommandes']);//done tested
Route::get('commande/info/{id}', [App\Http\Controllers\CommandeController::class, 'commandeInfo']);//done tested
Route::post('/commande/add/new', [App\Http\Controllers\CommandeController::class, 'createCommande']);//done tested
Route::post('/commande/edit/{id}', [App\Http\Controllers\CommandeController::class, 'editCommande']);//done tested
Route::post('/commande/delete/{id}', [App\Http\Controllers\CommandeController::class, 'deleteCommande']);//done tested

// Previsions

Route::get('/previsions', [App\Http\Controllers\PrevisionController::class, 'getPrevisions']);
Route::get('prevision/info/{id}', [App\Http\Controllers\PrevisionController::class, 'previsionInfo']);
Route::post('/prevision/add/new', [App\Http\Controllers\PrevisionController::class, 'createPrevision']);
Route::post('/prevision/edit/{id}', [App\Http\Controllers\PrevisionController::class, 'editPrevision']);
Route::post('/prevision/delete/{id}', [App\Http\Controllers\PrevisionController::class, 'deletePrevision']);

// // Coefficient

// Route::get('/coefficients', [App\Http\Controllers\CoefficientController::class, 'getCoefficients']);
// Route::get('coefficient/info/{id}', [App\Http\Controllers\CoefficientController::class, 'coefficientInfo']);
// Route::post('/coefficient/add/new', [App\Http\Controllers\CoefficientController::class, 'createCoefficient']);
// Route::post('/coefficient/edit/{id}', [App\Http\Controllers\CoefficientController::class, 'editCoefficient']);
// Route::post('/coefficient/delete/{id}', [App\Http\Controllers\CoefficientController::class, 'deleteCoefficient']);

// // Biais

// Route::get('/biais', [App\Http\Controllers\BiaisController::class, 'getBiais']);
// Route::get('biais/info/{id}', [App\Http\Controllers\BiaisController::class, 'biaisInfo']);
// Route::post('/biais/add/new', [App\Http\Controllers\BiaisController::class, 'createBiais']);
// Route::post('/biais/edit/{id}', [App\Http\Controllers\BiaisController::class, 'editBiais']);
// Route::post('/biais/delete/{id}', [App\Http\Controllers\BiaisController::class, 'deleteBiais']);

// // MAD

// Route::get('/mads', [App\Http\Controllers\MadController::class, 'getMads']);
// Route::get('mad/info/{id}', [App\Http\Controllers\MadController::class, 'madInfo']);
// Route::post('/mad/add/new', [App\Http\Controllers\MadController::class, 'createMad']);
// Route::post('/mad/edit/{id}', [App\Http\Controllers\MadController::class, 'editMad']);
// Route::post('/mad/delete/{id}', [App\Http\Controllers\MadController::class, 'deleteMad']);




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
