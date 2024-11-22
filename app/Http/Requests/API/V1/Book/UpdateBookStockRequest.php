<?php

namespace App\Http\Requests\API\V1\Book;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stock' => 'required|integer|min:0',
        ];
    }
}
