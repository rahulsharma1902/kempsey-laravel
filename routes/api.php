<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\BrandsController;
use App\Http\Controllers\Api\FiltersController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\CarouselController;
use App\Http\Controllers\Api\AboutUsController;
use App\Http\Controllers\Api\HomeContentController;
use App\Http\Controllers\Api\SiteContentController;
use App\Http\Controllers\Api\ServiceController;


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
        Route::post('filter/update', [FiltersController::class, 'update']);
        Route::any('filter/remove/{id}', [FiltersController::class, 'removeFilter']);


        // add Products 
        Route::post('product/add', [ProductsController::class, 'store']);
        Route::post('product/update', [ProductsController::class, 'update']);
        Route::any('product/remove/{id}', [ProductsController::class, 'removeProduct']);

        // Add home crousel CarouselController
        Route::post('carousel/add', [CarouselController::class, 'store']);

        // Home content HomeContentController
        Route::post('home-content/add', [HomeContentController::class, 'store']);
        //AboutUsController 
        Route::post('about-us-content/add', [AboutUsController::class, 'store']);


        Route::post('store-content/add', [SiteContentController::class, 'store']);


        // service ServiceController 
        Route::post('service/add', [ServiceController::class, 'addService']);
        Route::any('service/remove/{id}', [ServiceController::class, 'removeService']);

        Route::post('service-type/add', [ServiceController::class, 'addServiceType']);
        Route::any('service-type/remove/{id}', [ServiceController::class, 'removeServiceType']);

        Route::post('service-option/add', [ServiceController::class, 'addServiceOption']);
        Route::any('service-option/remove/{id}', [ServiceController::class, 'removeServiceOption']);


    });


});


// public get api's

// categries api are here :::::
Route::get('active-parent-categories',[CategoriesController::class,'activeParentCategories']);
Route::get('parent-categories',[CategoriesController::class,'parentCategories']);

Route::get('active-child-categories',[CategoriesController::class,'activeChildCategories']);
Route::get('child-categories',[CategoriesController::class,'childCategories']);

Route::get('get-category/{id}',[CategoriesController::class,'getCategoryById']);
Route::get('categories',[CategoriesController::class,'categories']);

// Brands api are here
Route::get('brands',[BrandsController::class,'getBrands']);
Route::get('get-brand/{id}',[BrandsController::class,'getBrandById']);


// filters api are here

Route::get('filters',[FiltersController::class,'getFilters']);
Route::get('get-filter/{id}',[FiltersController::class,'getFilterById']);



//  Products api are here

Route::get('products',[ProductsController::class,'products']);
Route::get('get-product/{id}',[ProductsController::class,'getProductById']);


// CarouselController api are here

Route::get('carousels',[CarouselController::class,'carousel']);

// HomeContentController api are here
Route::get('home-content',[HomeContentController::class,'homecontent']);


// AboutUsController
Route::get('about-us-content',[AboutUsController::class,'aboutuscontent']);

// SiteContentController

Route::get('site-content',[SiteContentController::class,'sitecontent']);


//  Servicing 
Route::get('services',[ServiceController::class,'getServices']);
Route::get('get-service/{id}',[ServiceController::class,'getServiceById']);

Route::get('get-service-type/{id}',[ServiceController::class,'getServiceTypeById']);

Route::get('get-service-type-by-service/{id}',[ServiceController::class,'getServiceTypeByServiceId']);

Route::get('get-service-option/{id}',[ServiceController::class,'getServiceOptionById']);
