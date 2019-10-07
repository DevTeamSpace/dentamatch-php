<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateSubscriptionRequest
 * @package App\Http\Requests
 * @property string promoCode
 */
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
            'subscriptionType' => 'required|integer',
            'cardNumber' => 'sometimes',
            'expiry' => 'sometimes',
            'cvv' => 'sometimes',
            'promoCode' => 'sometimes'
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
