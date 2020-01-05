<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:150',
            'content' => 'required|string',
            'published_at' => 'nullable|date_format:d/m/Y',
            'category_id' => 'required|integer',
        ];
    }
}
