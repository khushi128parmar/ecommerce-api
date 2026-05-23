<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\AuthController;

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\BrandController;
use App\Http\Controllers\Api\Admin\ProductController;

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\Admin\CouponController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\OrderStatusController;
use App\Http\Controllers\Api\Admin\ProductImageController;
use App\Http\Controllers\Api\Admin\ProductVariantController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\CouponApplyController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WishlistController;

// AUTH ROUTES
Route::prefix('auth')->group(function () {

    Route::post(
        'register',
        [AuthController::class, 'register']
    );

    Route::post(
        'login',
        [AuthController::class, 'login']
    )->middleware('throttle:login');
});

Route::post(
    'stripe/webhook',
    [PaymentController::class, 'webhook']
);
// AUTHENTICATED ROUTES
Route::middleware('auth:sanctum')->group(function () {

    // PROFILE
    Route::prefix('auth')->group(function () {

        Route::get(
            'profile',
            [AuthController::class, 'profile']
        );

        Route::post(
            'logout',
            [AuthController::class, 'logout']
        );
    });


    Route::prefix('admin')->group(function () {

        // CATEGORY ROUTES
        Route::apiResource(
            'categories',
            CategoryController::class
        );

        // BRAND ROUTES
        Route::apiResource(
            'brands',
            BrandController::class
        );

        // PRODUCT ROUTES
        Route::apiResource(
            'products',
            ProductController::class
        );

        // PRODUCT IMAGES
        Route::prefix('product-images')->group(function () {

            Route::get(
                '/{productId}',
                [ProductImageController::class, 'index']
            );

            Route::post(
                '/',
                [ProductImageController::class, 'store']
            );

            Route::delete(
                '/{productImage}',
                [ProductImageController::class, 'destroy']
            );
        });


        // PRODUCT VARIANTS
        Route::prefix('product-variants')->group(function () {

            Route::get(
                '/{productId}',
                [ProductVariantController::class, 'index']
            );

            Route::post(
                '/',
                [ProductVariantController::class, 'store']
            );

            Route::put(
                '/{productVariant}',
                [ProductVariantController::class, 'update']
            );

            Route::delete(
                '/{productVariant}',
                [ProductVariantController::class, 'destroy']
            );
        });

        Route::apiResource(
            'coupons',
            CouponController::class
        );
        Route::get(
            'dashboard',
            [DashboardController::class, 'index']
        );
    });

    // CART
    Route::prefix('cart')->group(function () {

        Route::get(
            '/',
            [CartController::class, 'index']
        );

        Route::post(
            '/',
            [CartController::class, 'store']
        );

        Route::put(
            '/{cart}',
            [CartController::class, 'update']
        );

        Route::delete(
            '/{cart}',
            [CartController::class, 'destroy']
        );
    });


    // WISHLIST
    Route::prefix('wishlist')->group(function () {

        Route::get(
            '/',
            [WishlistController::class, 'index']
        );

        Route::post(
            '/',
            [WishlistController::class, 'store']
        );

        Route::delete(
            '/{wishlist}',
            [WishlistController::class, 'destroy']
        );
    });


    // ADDRESS
    Route::apiResource(
        'addresses',
        AddressController::class
    );


    // CHECKOUT
    Route::post(
        'checkout',
        [CheckoutController::class, 'checkout']
    );


    // ORDERS
    Route::prefix('orders')->group(function () {

        Route::get(
            '/',
            [OrderController::class, 'index']
        );

        Route::get(
            '/{order}',
            [OrderController::class, 'show']
        );

        Route::delete(
            '/{order}',
            [OrderController::class, 'destroy']
        );

        Route::put(
            '/{order}/status',
            [OrderController::class, 'updateStatus']
        );
    });

    Route::prefix('payments')->group(function () {

        // CASH ON DELIVERY
        Route::post(
            '/{order}/cod',
            [PaymentController::class, 'cod']
        );

        // PAYMENT SUCCESS
        Route::post(
            '/{order}/success',
            [PaymentController::class, 'paymentSuccess']
        );

        // PAYMENT FAILED
        Route::post(
            '/{order}/failed',
            [PaymentController::class, 'paymentFailed']
        );
        Route::post(
            '/{order}/stripe',
            [PaymentController::class, 'stripeCheckout']
        );
    });

    Route::post(
        'apply-coupon',
        [CouponApplyController::class, 'apply']
    );

    Route::prefix('reviews')->group(function () {

        Route::get(
            '/{productId}',
            [ReviewController::class, 'index']
        );

        Route::post(
            '/',
            [ReviewController::class, 'store']
        );

        Route::delete(
            '/{review}',
            [ReviewController::class, 'destroy']
        );
    });

    Route::put(
        'orders/{order}/status',
        [OrderStatusController::class, 'updateStatus']
    );

    Route::get(
        'notifications',
        [NotificationController::class, 'index']
    );
});
