<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventSearchRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start' => 'nullable|date|required_with:end|before_or_equal:end',
            'end' => 'nullable|date|required_with:start|after_or_equal:start',
            'name' => 'nullable|string',
            'user_email' => 'nullable|email',
        ];
    }
}
