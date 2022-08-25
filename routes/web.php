<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;



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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [CategoryController::class, 'index'])->name('index');
Route::post('/category_sub', [CategoryController::class, 'category_sub'])->name('category_sub');
Route::get('/category_data', [CategoryController::class, 'category_data'])->name('category_data');
Route::DELETE('/category_delete/{id}', [CategoryController::class, 'category_delete'])->name('category_delete');
Route::post('/category_edit', [CategoryController::class, 'category_edit'])->name('category_edit');
Route::post('/category_get', [CategoryController::class, 'category_get'])->name('category_get');
Route::post('/category_editsub', [CategoryController::class, 'category_editsub'])->name('category_editsub');

Route::get('/product', [ProductController::class, 'index'])->name('product');
Route::post('/category-search', [ProductController::class, 'category_search'])->name('category-search');
Route::post('/product_sub', [ProductController::class, 'product_sub'])->name('product_sub');
Route::get('/datatable', [ProductController::class, 'datatable'])->name('datatable');
Route::DELETE('/product_delete/{id}', [ProductController::class, 'product_delete'])->name('product_delete');
Route::post('/product_get', [ProductController::class, 'product_get'])->name('product_get');


Route::post('/product_edit', [ProductController::class, 'product_edit'])->name('product_edit');
