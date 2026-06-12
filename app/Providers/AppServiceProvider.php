<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

/**
 * AppServiceProvider
 *
 * CORREÇÃO: removido o ResetPassword::toMailUsing() que estava aqui.
 *
 * O motivo: o User.php já sobrescreve sendPasswordResetNotification()
 * apontando para App\Notifications\ResetPasswordNotification.
 * Ter os dois ao mesmo tempo causava conflito — o AppServiceProvider
 * sobrescrevia a notificação personalizada, voltando para o padrão do Laravel.
 *
 * Com apenas o User.php definindo a notificação, o fluxo fica:
 *   Password::sendResetLink()
 *     → User::sendPasswordResetNotification($token)
 *       → ResetPasswordNotification($token)
 *         → view('emails.auth.reset-password') com $actionUrl, $user, $expireTimeString
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Registra o provider Microsoft Azure no Socialite
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('azure', \SocialiteProviders\Azure\Provider::class);
        });
    }
}