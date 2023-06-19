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
            'starts_at' => 'nullable|date|required_with:ends_at|before_or_equal:ends_at',
            'ends_at' => 'nullable|date|required_with:starts_at|after_or_equal:starts_at',
            'name' => 'nullable|string',
            'user_email' => 'nullable|email',
        ];
    }
}
