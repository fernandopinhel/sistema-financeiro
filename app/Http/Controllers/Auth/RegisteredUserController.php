<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\ReCaptcha;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                 => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s]+$/u'],
            'email'                => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'             => ['required', 'confirmed', Rules\Password::defaults()->mixedCase()->numbers()->symbols()],
            'g-recaptcha-response' => ['required', new ReCaptcha],
        ], [
            'name.required'                  => 'Este campo é obrigatório.',
            'name.max'                       => 'O nome deve ter no máximo 255 caracteres.',
            'name.regex'                     => 'Use apenas letras e espaços no seu nome.',
            'email.required'                 => 'Este campo é obrigatório.',
            'email.email'                    => 'Insira um endereço de e-mail válido.',
            'email.unique'                   => 'Este e-mail já está cadastrado. Tente fazer login.',
            'email.max'                      => 'O e-mail é muito longo.',
            'password.required'              => 'Este campo é obrigatório.',
            'password.confirmed'             => 'As senhas não são iguais. Verifique e tente novamente.',
            'g-recaptcha-response.required'  => 'Por favor, confirme que você não é um robô.',
        ]);

        $user = User::create([
            'name'     => strip_tags($request->name),
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // CORREÇÃO: redireciona direto para o dashboard pois MustVerifyEmail está desativado.
        // Quando reativar o e-mail, troque de volta para: redirect()->route('verification.notice')
        return redirect()->route('dashboard');
    }
}