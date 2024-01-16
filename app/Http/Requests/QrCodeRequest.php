<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QrCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'prefix' => ['required'],
            'code_number' => ['required', 'integer'],
            'suffix' => ['nullable'],
            'label_number' => ['required', 'integer'],
            'start_number' => ['nullable', 'integer', 'min:1', 'max:999'],
        ];
    }
}
