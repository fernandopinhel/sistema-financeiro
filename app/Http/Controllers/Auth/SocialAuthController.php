<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private const PROVIDERS = ['google'];

    public function redirect(string $provider)
    {
        abort_unless(in_array($provider, self::PROVIDERS, true), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        abort_unless(in_array($provider, self::PROVIDERS, true), 404);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception) {
            return redirect()->route('login')
                ->withErrors(['social' => 'Não foi possível autenticar. Verifique as permissões e tente novamente.']);
        }

        $email = filter_var($socialUser->getEmail(), FILTER_VALIDATE_EMAIL);

        if (! $email) {
            return redirect()->route('login')
                ->withErrors(['social' => 'Não foi possível obter o e-mail da conta Google. Verifique as permissões e tente novamente.']);
        }

        $user = User::where('google_id', $socialUser->getId())
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            if (! $user->google_id) {
                $user->update(['google_id' => $socialUser->getId()]);
            }
        } else {
            $user = User::create([
                'name'              => strip_tags($socialUser->getName() ?? $socialUser->getNickname() ?? 'Usuário'),
                'email'             => $email,
                'google_id'         => $socialUser->getId(),
                'avatar'            => $socialUser->getAvatar(),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, remember: true);

        // Previne session fixation após login social
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}