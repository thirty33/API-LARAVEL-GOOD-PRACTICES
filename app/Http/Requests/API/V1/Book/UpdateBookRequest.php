<?php

namespace App\Http\Requests\API\V1\Book;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author_id' => 'required|exists:authors,id',
            'genre_id' => 'required|exists:genres,id',
            'title' => 'required|string|max:100|unique:books,title,' . $this->route('book')->id,
            'isbn' => 'required|string|max:13|unique:books,isbn,' . $this->route('book')->id,
            'pages' => 'required|integer|min:1',
            'stock' => 'required|integer|min:1',
            'published_at' => 'required|date',
        ];
    }
}
