<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'station_id' => ['required', 'exists:stations,id'],
            'data_file' => 'file',
            'observation_file' => ['file', 'sometimes', 'nullable'],
            'selectedUnitTemp' => ['nullable', 'string'],
            'selectedUnitPres' => ['nullable', 'string'],
            'selectedUnitWind' => ['nullable', 'string'],
            'selectedUnitRain' => ['nullable', 'string'],

        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
