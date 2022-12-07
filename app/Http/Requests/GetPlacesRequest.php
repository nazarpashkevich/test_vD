<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetPlacesRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'date' => ['string', 'regex:/(\d){4}-(\d){2}-(\d){2}/'],
            'time' => ['string', 'regex:/(\d){2}:(\d){2}/']
        ];
    }
}
