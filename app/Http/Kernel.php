<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\LogUserAction::class
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'access.query' => \App\Http\Middleware\AccessQuery::class,
        'access.career' => \App\Http\Middleware\AccessCareer::class,
        'access.catering' => \App\Http\Middleware\AccessCatering::class,
        'access.order' => \App\Http\Middleware\AccessOrder::class,
        'access.dashboard' => \App\Http\Middleware\AccessDashboard::class,
        'access.user' => \App\Http\Middleware\AccessUser::class,
        'access.meal' => \App\Http\Middleware\AccessMeal::class,
        'access.report' => \App\Http\Middleware\AccessReport::class,
        'access.coupons' => \App\Http\Middleware\AccessCoupon::class,
        'access.location' => \App\Http\Middleware\AccessLocation::class,
        'access.banner' => \App\Http\Middleware\AccessBanner::class,
        'access.adText' => \App\Http\Middleware\AccessAdText::class,
        'access.testimonials' => \App\Http\Middleware\AccessTestimonial::class,
        'access.pages' => \App\Http\Middleware\AccessPage::class,
        'access.slider' => \App\Http\Middleware\AccessSlider::class,
        'access.deliveryBoy' => \App\Http\Middleware\AccessDeliveryBoy::class,
        'access.setting' => \App\Http\Middleware\AccessSetting::class,
        'access.ngo' => \App\Http\Middleware\AccessNgo::class,
        'access.role' => \App\Http\Middleware\AccessRole::class,
        'access.group' => \App\Http\Middleware\AccessGroup::class,
        'access.kitchen' => \App\Http\Middleware\AccessKitchen::class,
        'access.stock' => \App\Http\Middleware\AccessStock::class,
        'area' => \App\Http\Middleware\CheckIfAreaIsSelected::class,
        'access.membership' => \App\Http\Middleware\AccessMembership::class,
        'verify'  => \App\Http\Middleware\VerifyUser::class,
        'jwt.auth' => \App\Http\Middleware\JWTAuth::class,
        'jwt.refresh' => \Tymon\JWTAuth\Middleware\RefreshToken::class,
    ];
}
