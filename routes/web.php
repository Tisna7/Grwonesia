<?php

use App\Http\Controllers\Admin\AiCenterController;
use App\Http\Controllers\Admin\BusinessVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Business\CoachController;
use App\Http\Controllers\Business\DashboardController;
use App\Http\Controllers\Business\FinanceController;
use App\Http\Controllers\Business\InstagramController;
use App\Http\Controllers\Business\InventoryController;
use App\Http\Controllers\Business\MarketingController;
use App\Http\Controllers\Business\OrderController;
use App\Http\Controllers\Business\ProductAiController;
use App\Http\Controllers\Business\ProductController;
use App\Http\Controllers\Business\ProgramSignupController;
use App\Http\Controllers\Business\WhatsAppController;
use App\Http\Controllers\Government\PolicyController;
use App\Http\Controllers\Government\ProgramController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrownesiaController;
use App\Http\Controllers\AuthController;

// Public Landing Page (with Navbar)
Route::get('/', [GrownesiaController::class, 'landing'])->name('landing');
// Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
  //     Route::get('/register', [RegisterController::class, 'create'])->name('register');
//     Route::post('/register', [RegisterController::class, 'store']);
//     Route::get('/login', [LoginController::class, 'create'])->name('login');
//     Route::post('/login', [LoginController::class, 'store']);


  // Dedicated Authentication Pages (Split 2-Column Screen Layout)
  Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [AuthController::class, 'login']);
  Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
  Route::post('/register', [AuthController::class, 'register']);

  // Email OTP Verification
  Route::get('/verify-email', [AuthController::class, 'showVerifyForm'])->name('verification.notice');
  Route::post('/verify-email', [AuthController::class, 'verifyOtp'])->name('verification.verify');
  Route::post('/verify-email/resend', [AuthController::class, 'resendOtp'])->name('verification.resend');

  // Google OAuth
  Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
  Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

// Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], '/switch-role/{role}', [AuthController::class, 'switchRole'])->name('switch-role');

// Logged-in User Dashboard & Navigation Routes
Route::middleware('auth')->group(function () {
  Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.dashboard');
  Route::get('/produk', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.produk');
  Route::get('/produk/{id}', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.produk.detail');
  Route::get('/checkout', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.checkout');
  Route::post('/checkout', [\App\Http\Controllers\User\CheckoutController::class, 'store'])->name('checkout.store');
  Route::get('/cart', [\App\Http\Controllers\User\CartController::class, 'index'])->name('cart.index');
  Route::post('/cart/sync', [\App\Http\Controllers\User\CartController::class, 'sync'])->name('cart.sync');
  Route::get('/orders', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.orders');
  Route::get('/tracking', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.tracking');
  Route::get('/profile', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.profile');
  Route::get('/ai-assistant', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.ai-assistant');
  Route::get('/ai-gift', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.ai-gift');
  Route::get('/ai-compare', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.ai-compare');
  Route::get('/favorites', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.favorites');

  Route::get('/user/shipping/{order}', [\App\Http\Controllers\User\DashboardController::class, 'getShippingStatus'])->name('user.shipping.status');
  Route::post('/user/profile/update', [\App\Http\Controllers\User\DashboardController::class, 'updateProfile'])->name('user.profile.update');
  Route::post('/user/ai/chat', [\App\Http\Controllers\User\AiAssistantController::class, 'chat'])->name('user.ai.chat');
  Route::post('/user/ai/gift-recommend', [\App\Http\Controllers\User\AiAssistantController::class, 'recommendGift'])->name('user.ai.gift-recommend');
  Route::post('/user/ai/compare', [\App\Http\Controllers\User\AiAssistantController::class, 'compare'])->name('user.ai.compare');
  Route::post('/favorites/toggle', [\App\Http\Controllers\User\FavoriteController::class, 'toggle'])->name('user.favorites.toggle');
  Route::post('/orders/confirm-received', [\App\Http\Controllers\User\OrderController::class, 'confirmReceived'])->name('user.orders.confirm-received');
  Route::post('/user/reviews', [\App\Http\Controllers\User\ReviewController::class, 'store'])->name('user.reviews.store');
});

Route::middleware(['auth', 'role:business'])->prefix('business')->name('business.')->group(function () {
  Route::get('/dashboard', DashboardController::class)->name('dashboard');

  Route::resource('products', ProductController::class)->except('show');
  Route::post('products/{product}/ai/photo', [ProductAiController::class, 'photo'])->name('products.ai.photo');
  Route::post('products/{product}/ai/seo', [ProductAiController::class, 'seo'])->name('products.ai.seo');
  Route::post('products/{product}/ai/description', [ProductAiController::class, 'description'])->name('products.ai.description');

  Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show']);
  Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

  Route::get('/shipping', [\App\Http\Controllers\Business\ShippingController::class, 'index'])->name('shipping.index');
  Route::patch('/shipping/{order}', [\App\Http\Controllers\Business\ShippingController::class, 'update'])->name('shipping.update');

  Route::get('/inventory', InventoryController::class)->name('inventory');

  Route::get('/coach', [CoachController::class, 'index'])->name('coach');
  Route::post('/coach/{chat?}', [CoachController::class, 'send'])->name('coach.send');

  Route::get('/marketing', [MarketingController::class, 'index'])->name('marketing');
  Route::post('/marketing/generate', [MarketingController::class, 'generate'])->name('marketing.generate');

  Route::get('/whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp');
  Route::post('/whatsapp/update', [WhatsAppController::class, 'updatePhone'])->name('whatsapp.update');
  Route::post('/whatsapp/broadcast', [WhatsAppController::class, 'broadcast'])->name('whatsapp.broadcast');

  Route::get('/instagram', [InstagramController::class, 'index'])->name('instagram');
  Route::post('/instagram/publish', [InstagramController::class, 'publish'])->name('instagram.publish');

  Route::post('/programs/{program}/register', [ProgramSignupController::class, 'store'])->name('programs.register');

  Route::get('/finance', FinanceController::class)->name('finance');
  Route::post('/finance/fixed-cost', function (Request $request) {
    $request->validate(['monthly_fixed_cost' => ['required', 'numeric', 'min:0']]);
    $request->user()->business->update(['monthly_fixed_cost' => $request->monthly_fixed_cost]);

    return back()->with('success', 'Biaya tetap bulanan diperbarui.');
  })->name('finance.fixed-cost');
});

Route::middleware(['auth', 'role:government'])->prefix('government')->name('government.')->group(function () {
  Route::get('/dashboard', App\Http\Controllers\Government\DashboardController::class)->name('dashboard');

  Route::get('/programs', [ProgramController::class, 'index'])->name('programs');
  Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
  Route::patch('/programs/{program}', [ProgramController::class, 'update'])->name('programs.update');
  Route::delete('/programs/{program}', [ProgramController::class, 'destroy'])->name('programs.destroy');
  Route::post('/programs/recommend', [ProgramController::class, 'recommend'])->name('programs.recommend');

  Route::get('/policy', [PolicyController::class, 'index'])->name('policy');
  Route::post('/policy/simulate', [PolicyController::class, 'simulate'])->name('policy.simulate');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::get('/dashboard', App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');
  Route::get('/businesses', [BusinessVerificationController::class, 'index'])->name('businesses');
  Route::patch('/businesses/{business}', [BusinessVerificationController::class, 'update'])->name('businesses.update');
  Route::get('/ai-center', AiCenterController::class)->name('ai-center');
});

// WhatsApp gateway webhook (public, exempt from CSRF)
Route::post('/whatsapp/webhook', [\App\Http\Controllers\WhatsAppWebhookController::class, 'handle']);

