<?php

namespace App\Http\Requests;

use App\Traits\RequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    use RequestValidationTrait;

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
    public function rules(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => [
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ]
        ]);

        if ($validator->fails())
            $this->badRequestException('Password too weak', [
                'status' => 'PASSWORD_TOO_WEAK'
            ]);

        return [
            'name' => 'required|string|min:8|max:50',
            'email' => 'required|email|min:8|max:50',
            'password' => [
                'required',
                'confirmed',
                'max:50',
            ],
        ];
    }
}
