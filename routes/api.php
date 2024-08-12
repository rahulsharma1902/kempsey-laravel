<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\BrandsController;
use App\Http\Controllers\Api\FiltersController;


use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('admin')->group(function () {
        Route::post('categories/add', [CategoriesController::class, 'store']);
        Route::any('category/remove/{id}', [CategoriesController::class, 'removeCategory']);

        //  add brands 
        Route::post('brand/add', [BrandsController::class, 'store']);
        Route::any('brand/remove/{id}', [BrandsController::class, 'removeBrand']);

        // add filters
        Route::post('filter/add', [FiltersController::class, 'store']);
        Route::any('filter/remove/{id}', [FiltersController::class, 'removeFilter']);

    });


});


// public get api's

// categries api are here :::::
Route::get('active-parent-categories',[CategoriesController::class,'activeParentCategories']);
Route::get('parent-categories',[CategoriesController::class,'parentCategories']);

Route::get('active-child-categories',[CategoriesController::class,'activeChildCategories']);
Route::get('child-categories',[CategoriesController::class,'childCategories']);

Route::get('get-category/{id}',[CategoriesController::class,'getCategoryById']);

// Brands api are here
Route::get('brands',[BrandsController::class,'getBrands']);
Route::get('get-brand/{id}',[BrandsController::class,'getBrandById']);


// filters api are here

Route::get('filters',[FiltersController::class,'getFilters']);
Route::get('get-filter/{id}',[FiltersController::class,'getFilterById']);
