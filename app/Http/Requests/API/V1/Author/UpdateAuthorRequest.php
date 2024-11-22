<?php

namespace App\Http\Requests\API\V1\Author;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:authors,name,' . $this->route('author')->id,
        ];
    }
}
