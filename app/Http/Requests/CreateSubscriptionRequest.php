<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubscriptionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cardExist' => 'required',
            'subscriptionType' => 'required',
            'trailPeriod' => 'required',
            'cardNumber' => 'sometimes',
            'expiry' => 'sometimes',
            'cvv' => 'sometimes'
        ];
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
