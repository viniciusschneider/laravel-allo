<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use App\Traits\ErrorsExceptionsTrait;

trait RequestValidationTrait {
    use ErrorsExceptionsTrait;

    public function failedValidation(Validator $validator) {
        $this->badRequestException('Validation errors', [
            'data' => $validator->errors(),
        ]);
    }

    public function failedAuthorization()
    {
        $this->forbiddenRequestException();
    }
}
