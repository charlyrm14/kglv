<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateNoticeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:5|max:120',
            'content' => 'required',
            'cover_image' => 'nullable',
            'active' => 'nullable|in:0,1'
        ];
    }


    /**
     * This function throws an exception with a JSON response containing validation errors.
     * 
     * @param Validator validator The  parameter is an instance of the Validator class, which
     * is responsible for validating input data against a set of rules. It contains the validation
     * errors that occurred during the validation process.
     */
    public function failedValidation(Validator $validator)
    {
        $response = array(
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        );

        throw new HttpResponseException(response()->json($response, 422));
    }
}
