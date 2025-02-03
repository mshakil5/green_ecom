<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\SpecialOfferController;
use App\Http\Controllers\Admin\FlashSellController;
use App\Http\Controllers\PayPalController;
  

// cache clear
Route::get('/clear', function() {
    Auth::logout();
    session()->flush();
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
 });
//  cache clear
  
  
Auth::routes();

Route::fallback(function () {
    return redirect('/');
});
  

Route::get('/clear-session', [FrontendController::class, 'clearAllSessionData'])->name('clearSessionData');
  
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Frontend
Route::get('/', [FrontendController::class, 'index'])->name('frontend.homepage');
Route::get('/category/{slug}', [FrontendController::class, 'showCategoryProducts'])->name('category.show');
Route::get('/sub-category/{slug}', [FrontendController::class, 'showSubCategoryProducts'])->name('subcategory.show');
Route::get('/product/{slug}', [FrontendController::class, 'showProduct'])->name('product.show');
Route::get('/product/{slug}/{offerId?}', [FrontendController::class, 'showProduct'])->name('product.show.offer');

Route::post('/products/reviews', [FrontendController::class, 'storeReview'])->name('reviews.store');
Route::get('/category-products', [FrontendController::class, 'getCategoryProducts'])->name('getCategoryProducts');


Route::get('/get-products/{ptype?}', [FrontendController::class, 'getDiffTypeProducts'])->name('getDiffTypeProducts');


Route::get('/privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('frontend.privacy-policy');
Route::get('/terms-and-conditions', [FrontendController::class, 'termsAndConditions'])->name('frontend.terms-and-conditions');


//Check Coupon
Route::get('/check-coupon', [FrontendController::class, 'checkCoupon']);
Route::get('/order/success', [OrderController::class, 'orderSuccess'])->name('order.success');
Route::get('/special-offers/{slug}', [SpecialOfferController::class, 'show'])->name('special-offers.show');
Route::get('flash-sells/{slug}', [FlashSellController::class, 'show'])->name('flash-sells.show');
Route::get('/shop', [FrontendController::class, 'shop'])->name('frontend.shop');

Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('frontend.shopdetail');
Route::get('/contact', [FrontendController::class, 'contact'])->name('frontend.contact');
Route::post('/contact-us', [FrontendController::class, 'storeContact'])->name('contact.store');

// Wish list
Route::put('/wishlist/store', [FrontendController::class, 'storeWishlist'])->name('wishlist.store');
Route::get('/wishlist', [FrontendController::class, 'showWishlist'])->name('wishlist.index');

// Search products
Route::get('/search/products', [FrontendController::class, 'search'])->name('search.products');

// Cart list
Route::put('/cart/store', [FrontendController::class, 'storeCart'])->name('cart.store');
Route::get('/cart', [FrontendController::class, 'showCart'])->name('cart.index');
Route::post('/cart/remove', [FrontendController::class, 'removeCartItem'])->name('cart.remove');

Route::post('/checkout', [FrontendController::class, 'checkout'])->name('checkout.store');

Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('place.order');

Route::get('payment/success', [OrderController::class, 'paymentSuccess'])->name('payment.success');
Route::get('payment/cancel', [OrderController::class, 'paymentCancel'])->name('payment.cancel');

Route::get('/order/{encoded_order_id?}', [OrderController::class, 'generatePDF'])->name('generate-pdf');

// Shop

Route::post('/products/filter', [FrontendController::class, 'filter']);
Route::post('/products/type-filter', [FrontendController::class, 'typefilter']);

Route::group(['prefix' =>'user/', 'middleware' => ['auth', 'is_user']], function(){
  
    Route::get('/dashboard', [HomeController::class, 'userHome'])->name('user.dashboard');

    Route::get('/profile', [UserController::class, 'userProfile'])->name('user.profile');

    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('user.profile.update');

    Route::get('/orders', [OrderController::class, 'getOrders'])->name('orders.index');

    Route::get('/orders/{orderId}/details', [OrderController::class, 'showOrderUser'])->name('orders.details');

    Route::post('{orderId}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/orders/details', [OrderController::class, 'getOrderDetailsModal'])->name('orders.details.modal');

    Route::post('/order-return', [OrderController::class, 'returnStore'])->name('orders.return');
});
  

Route::group(['prefix' =>'manager/', 'middleware' => ['auth', 'is_manager']], function(){
  
    Route::get('/dashboard', [HomeController::class, 'managerHome'])->name('manager.dashboard');
});
 