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
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\CustomerServiceContentController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\ShippingMethodController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;

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


        // Customer service CustomerServiceContentController
        Route::post('customer-service-content/add', [CustomerServiceContentController::class, 'store']);

        // Faqs Routes FaqController
        Route::post('faq-category/add', [FaqController::class, 'AddFaqCategory']);
        Route::any('faq-category/remove/{id}', [FaqController::class, 'removeFaqCategory']);

        Route::post('faq-content/add', [FaqController::class, 'store']);

        Route::post('faq/add', [FaqController::class, 'AddFaq']);
        Route::any('faq/remove/{id}', [FaqController::class, 'removeFaq']);

        Route::post('store-content/add', [SiteContentController::class, 'store']);

        Route::post('contact-us-content/add', [SiteContentController::class, 'storeContactData']);
        Route::post('workshop-content/add', [SiteContentController::class, 'storeWorkshopData']);


        // service ServiceController 
        Route::post('service/add', [ServiceController::class, 'addService']);
        Route::any('service/remove/{id}', [ServiceController::class, 'removeService']);

        Route::post('service-type/add', [ServiceController::class, 'addServiceType']);
        Route::any('service-type/remove/{id}', [ServiceController::class, 'removeServiceType']);

        Route::post('service-option/add', [ServiceController::class, 'addServiceOption']);
        Route::any('service-option/remove/{id}', [ServiceController::class, 'removeServiceOption']);

        //StoreController
        Route::post('store/add', [StoreController::class, 'store']);
        
        Route::any('complete-booking/{id}', [BookingController::class, 'BookingStatus']);
        Route::any('remove-booking/{id}', [BookingController::class, 'RemoveBooking']);


        // add CouponController
        Route::post('coupon/add', [CouponController::class, 'store']);
        Route::any('coupon/remove/{id}', [CouponController::class, 'removeCoupon']);

        // 
        Route::post('shipping-method/add', [ShippingMethodController::class, 'store']);
        Route::any('shipping-method/remove/{id}', [ShippingMethodController::class, 'removeShippingMethod']);
    });


    Route::post('checkout', [CheckoutController::class, 'checkout']);

});
Route::get('shipping-methods',[ShippingMethodController::class,'getShippingMethods']);
Route::get('shipping-method',[ShippingMethodController::class,'getShippingMethod']);
Route::get('get-shipping-method/{id}',[ShippingMethodController::class,'getShippingMethodById']);


Route::get('coupons',[CouponController::class,'getCoupons']);


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
Route::get('get-filter-by-categroy/{slug}',[FiltersController::class,'getFilterByCategory']);



//  Products api are here

Route::get('products',[ProductsController::class,'products']);
Route::get('get-product/{id}',[ProductsController::class,'getProductById']);
Route::get('get-products-by-category/{slug}',[ProductsController::class,'getProductByCategory']);


// CarouselController api are here

Route::get('carousels',[CarouselController::class,'carousel']);

// HomeContentController api are here
Route::get('home-content',[HomeContentController::class,'homecontent']);

// CustomerServiceContentController api are here
Route::get('customer-service-content',[CustomerServiceContentController::class,'CustomerServiceContent']);

// AboutUsController
Route::get('about-us-content',[AboutUsController::class,'aboutuscontent']);

// SiteContentController

Route::get('site-content',[SiteContentController::class,'sitecontent']);
Route::get('workshop-content',[SiteContentController::class,'WorkshopContent']);
Route::get('contact-us-content',[SiteContentController::class,'ContactUsContent']);

// Faqs and FaqCategory
Route::get('faq-categories',[FaqController::class,'Faqcategories']);   
Route::get('get-faq-category/{id}',[FaqController::class,'getCategoryById']);

Route::get('faq-content',[FaqController::class,'FaqContent']);

Route::get('faqs',[FaqController::class,'Faqs']);
Route::get('get-faq/{id}',[FaqController::class,'getFaqId']);

//  Servicing 
Route::get('services',[ServiceController::class,'getServices']);
Route::get('get-service/{id}',[ServiceController::class,'getServiceById']);

Route::get('get-service-type/{id}',[ServiceController::class,'getServiceTypeById']);
Route::get('get-service-type',[ServiceController::class,'getServiceType']);

Route::get('get-service-type-by-service/{id}',[ServiceController::class,'getServiceTypeByServiceId']);
Route::post('/service-types-by-ids', [ServiceController::class, 'getServiceTypesByArray']);

Route::get('get-service-option/{id}',[ServiceController::class,'getServiceOptionById']);

// StoreController
Route::get('stores',[StoreController::class,'getStores']);

Route::get('get-store/{id}',[StoreController::class,'getStoreById']);




////  Add service BookingController 
Route::post('service-booking/add', [BookingController::class, 'store']);
Route::get('bookings', [BookingController::class, 'getBookings']);


// Route::middleware(['web'])->group(function () {
    Route::get('/generate-temp-id', [CartController::class, 'generateTempId']);
    Route::get('/get-cart', [CartController::class, 'getCart']);
    Route::post('cart/add', [CartController::class, 'AddCart']);
    Route::get('cart/remove/{id}', [CartController::class, 'RemoveCart']);
// });

Route::post('coupon/apply', [CouponController::class, 'applyCoupon']);
Route::get('cart/count', [CartController::class, 'CartCount']);


// // Add cart CartController
// Route::post('cart/add', [CartController::class, 'AddCart']);
// Route::get('get-cart', [CartController::class, 'getCart']);

// // generte temp id
// Route::get('generate-temp-id', [CartController::class, 'generateTempId']);
// // Route::middleware(['web'])->get('/generate-temp-id', [CartController::class, 'generateTempId']);



Route::get('orders',[OrderController::class,'orders']);