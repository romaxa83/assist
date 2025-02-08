<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    public function info(): JsonResponse
    {
        return response()->json([
            'version' => '1.0',
        ], Response::HTTP_OK);
    }

    public static function successJsonMessage(
        null|string $msg = null,
        string|int $statusCode = Response::HTTP_OK
    ): JsonResponse
    {
        if (is_null($msg)) {
            $msg = 'Success';
        }

        return response()->json([
            'msg' => $msg,
        ], $statusCode);
    }

    public static function errorJsonMessage(
        null|string $msg = null,
        $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        $stackTrace = []
    ): JsonResponse
    {
        if (is_null($msg)) {
            $msg = 'Error';
        }

        if ($code === 0) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json(['msg' => $msg], $code);
    }

    public static function errorJsonValidation(
        string $msg,
        array $errors = [],
    ): JsonResponse
    {
        return response()->json([
            'msg' => $msg,
            'errors' => $errors,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function noContent(): JsonResponse
    {
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public static function jsonData(
        array $data = [],
        string|int $statusCode = Response::HTTP_OK
    ): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

}
