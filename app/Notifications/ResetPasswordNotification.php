<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notificação de redefinição de senha com branding Finanças FP.
 *
 * A view usada é emails/auth/reset-password.blade.php (template de e-mail HTML).
 * NÃO confundir com auth/reset-password.blade.php (formulário web de nova senha).
 */
class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Redefinição de Senha — ' . config('app.name'))
            ->view('emails.auth.reset-password', [
                'actionUrl'        => $url,
                'user'             => $notifiable,
                'expireTimeString' => config('auth.passwords.users.expire', 60) . ' minutos',
            ]);
    }
}