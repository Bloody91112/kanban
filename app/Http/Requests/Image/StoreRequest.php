<?php

namespace App\Http\Requests\Image;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
           'image' => ['required', 'file'],
           'class' => ['required', 'string'],
           'id' => ['required', 'string'],
        ];
    }
}
