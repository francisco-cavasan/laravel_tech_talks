<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
            'type' => 'required|in:public,private',
        ];
    }
}
