<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Authorization already implemented in \App\Policies\HousePolicy::class

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $house = $this->route('house');

        return [
            'category' => [
                'required',
                Rule::exists('categories', 'id')->whereNull('deleted_at')
            ],
            'title' => [
                'required', 'min:3', 'max:30',
                Rule::unique('houses')->ignore($house ? $house->id : ''),
            ],
            'description'  => [
              'required', 'min:3'
            ]
        ];
    }
}
