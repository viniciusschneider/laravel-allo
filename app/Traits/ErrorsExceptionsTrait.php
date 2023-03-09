<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;

trait ErrorsExceptionsTrait {

    public function badRequestException($message = 'Bad request', $response = []) {
        throw new HttpResponseException(
            response()->json(array_merge(
                [ 'message' => $message ],
                $response
            ), 400)
        );
    }

    public function unauthorizedRequestException($message = 'Unauthorized', $response = []) {
        throw new HttpResponseException(
            response()->json(array_merge(
                [ 'message' => $message ],
                $response
            ), 401)
        );
    }

    public function forbiddenRequestException() {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Forbidden'
            ], 403)
        );
    }

    public function notFoundRequestException($message = 'Record not found') {
        throw new HttpResponseException(
            response()->json([
                'message' => $message
            ], 404)
        );
    }

    public function tooManyRequestsRequestException() {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Too Many Requests'
            ], 429)
        );
    }

    public function internalServerErrorRequestException() {
        return response()->json([
            'message' => 'Internal server error'
        ], 500);
    }
}
