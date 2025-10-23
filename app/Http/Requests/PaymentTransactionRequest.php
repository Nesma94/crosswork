<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentTransactionRequest extends FormRequest
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
            'order_id' => 'required|exists:orders,id',
            'payment_method_id' => 'nullable|string',
            'method' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,successful,failed',
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors(),
                ],
                422,
            ),
        );
    }
}
