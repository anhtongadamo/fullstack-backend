<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function successResponse($responseTitle, $message, $data, $code = 200)
    {
        $response = [
            'success' => true,
            'code' => $code,
            'title' => $responseTitle,
            'message' => $message,

        ];
        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function errorResponse($errorTitle, $error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'code' => $code,
            'title' => $errorTitle,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}
