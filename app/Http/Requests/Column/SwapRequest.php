<?php

namespace App\Http\Requests\Column;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SwapRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'firstColumnId' => ['required', 'int'],
            'secondColumnId' => ['required', 'int'],
        ];
    }
}
