<?php

namespace App\Http\Requests\FeedbackRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
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
            'body' => 'nullable',
            'review_id' => 'required|integer',
            'reviewer_id' => 'required|integer'
        ];
    }
}
