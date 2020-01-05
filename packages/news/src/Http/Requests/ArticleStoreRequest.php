<?php

namespace Laravelha\News\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleStoreRequest extends FormRequest
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
        ];
    }
}
