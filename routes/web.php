<?php


use App\Http\Controllers\CustomCrawlerController;
use App\Http\Controllers\ExcelImportController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [ExcelImportController::class, 'get']);
Route::post('/', [ExcelImportController::class, 'post']);
Route::get('/temp', [ExcelImportController::class, 'temp']);
Route::get('/temp1', [ExcelImportController::class, 'temp1']);
Route::get('/fetch/{id}', [CustomCrawlerController::class, 'fetchContent']);
