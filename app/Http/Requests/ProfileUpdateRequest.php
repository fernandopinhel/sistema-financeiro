<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ0-9\s]+$/u'],
            'email'  => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'O nome é obrigatório.',
            'name.max'       => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email'    => 'Informe um e-mail válido.',
            'email.unique'   => 'Este e-mail já está em uso.',
            'avatar.image'   => 'O arquivo deve ser uma imagem.',
            'avatar.mimes'   => 'Formatos aceitos: jpeg, png, jpg, gif, webp.',
            'avatar.max'     => 'O tamanho máximo da imagem é 2 MB.',
            'name.regex' => 'O nome contém caracteres inválidos.',
        ];
    }
}
