<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// Rotas OAuth (Google)
Route::middleware('guest')->group(function () {
    Route::get('auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->where('provider', 'google')
        ->name('social.redirect');
});

Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback'])
    ->where('provider', 'google')
    ->name('social.callback');

Route::middleware('guest')->group(function () {
    Route::get('criar-conta', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('criar-conta', [RegisteredUserController::class, 'store']);

    Route::get('acesso', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('acesso', [AuthenticatedSessionController::class, 'store']);

    Route::get('recuperar-acesso', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('recuperar-acesso', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('nova-senha/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('nova-senha', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {

    Route::get('verificar-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verificar-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/reenviar-verificacao', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirmar-senha', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirmar-senha', [ConfirmablePasswordController::class, 'store']);

    Route::put('atualizar-senha', [PasswordController::class, 'update'])->name('password.update');

    Route::post('sair', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});