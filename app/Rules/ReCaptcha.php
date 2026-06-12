<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Em ambiente de testes, pula a validação
        if (app()->environment('testing')) {
            return;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        if (! ($response->json('success') ?? false)) {
            $fail('A verificação anti-bot falhou. Por favor, tente novamente.');
        }
    }
}