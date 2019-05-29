<?php

namespace App\Http\Requests;

use App\House;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HouseActionsRequest extends FormRequest
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
    if($this->routeIs('house.release')){
      return [
        'action' => [
          'required',
          Rule::in([House::ACTION_RELEASE])
        ]
      ];
    }
    else {
      return [
        'action' => [
          'required',
          Rule::in([House::ACTION_APPROVE, House::ACTION_DECLINE])
        ],
        'expires_at' => [
          'after:now', 'nullable',
          'required_if:action,'. House::ACTION_APPROVE,
        ],
      ];
    }
  }
}
