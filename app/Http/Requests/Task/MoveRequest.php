<?php

namespace App\Http\Requests\Task;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MoveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'oldColumnId' => ['required', 'int'],
            'newColumnId' => ['required', 'int'],
            'oldIndex' => ['required', 'int'],
            'newIndex' => ['required', 'int'],
        ];
    }
}
