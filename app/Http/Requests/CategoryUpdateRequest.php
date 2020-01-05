<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'published_at' => 'nullable|date_format:d/m/Y',
        ];
    }
}
