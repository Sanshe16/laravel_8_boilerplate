<?php

namespace App\Traits;

use BadMethodCallException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success(string $message = null, $payload = null, int $responseCode = 200)
    {
        return response()->json([
            'responseCode' => $responseCode,
            'status' => 'Success',
            'message' => $message,
            'payload' => $payload
        ], $responseCode);
    }

    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(string $message = null, int $responseCode, $payload = null)
    {
        return response()->json([
            'responseCode' => $responseCode,
            'status' => 'Error',
            'message' => $message,
            'payload' => $payload
        ], $responseCode);
    }


    public static function createJson(string $message = null,  int $responseCode = 200, $status = 'Success', $payload = null)
    {
        return response()->json([
            'responseCode' => $responseCode,
            'status' => $status,
            'message' => $message,
            'payload' => $payload
        ], $responseCode);
    }

    function verifyRequiredParams($required_fields, $request_params)
    {
        $error = false;
        $error_fields = array();

        foreach ($required_fields as $field) {
            if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
                $error = true;
                $error_fields[] = $field;
            }
        }
        if ($error) {
            // Required field(s) are missing or empty
            return array(
                'success' => false,
                'message' => 'Required field(s) ' . implode(', ', $error_fields) . ' missing or empty'
            );
        }

        // return appropriate response when successful?
        return array(
            'success' => true,
            'message' => ''
        );
    }

    function createInternalErrorResponse($message = 'Internal server error', $payload = null)
    {
        return self::createJson($message, Response::HTTP_INTERNAL_SERVER_ERROR, "Error", $payload);
    }

    function createBadResponse($message = 'Bad request', $payload = null)
    {
        return self::createJson($message, Response::HTTP_BAD_REQUEST, "Error", $payload);
    }

    function createValidResponseNotFound($message = 'Not Found', $payload = null)
    {
        return self::createJson($message, Response::HTTP_NOT_FOUND, "Error", $payload);
    }
    function createValidResponseAlreadyExists($message = 'Already Exists', $payload = null)
    {
        return self::createJson($message, Response::HTTP_CONFLICT, "Error", $payload);
    }


    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    function createAuthenticationException(AuthenticationException $exception, $payload = null)
    {
        return self::createJson('You are not authenticated, Please login with valid credentials', Response::HTTP_UNAUTHORIZED, 'Error', $payload);
    }

    /**
     * @param \Illuminate\Database\Eloquent\ModelNotFoundException $exception
     *
     * @return \Illuminate\Http\Response
     */
    private function createModelNotFoundException(ModelNotFoundException $exception, $payload = null)
    {
        return self::createJson('Model resource for ' . $exception->getModel() . ' not found' . ' ' . $exception->getMessage(), Response::HTTP_NOT_FOUND, 'Error', $payload);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    private function BadMethodCallException(BadMethodCallException $exception, $payload = null)
    {
        return self::createJson($exception->getMessage(), Response::HTTP_NOT_FOUND, 'Error', $payload);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $exception
     *
     * @return \Illuminate\Http\Response
     */
    private function MethodNotAllowedHttpException(MethodNotAllowedHttpException $exception, $payload = null)
    {
        return self::createJson($exception->getMessage(), Response::HTTP_METHOD_NOT_ALLOWED, 'Error', $payload);
    }

    private function UnauthorizedException(UnauthorizedException $exception, $payload = null)
    {
        return self::createJson('You do not have proper permission to perform this task' . ' ' . $exception->getMessage(),  Response::HTTP_FORBIDDEN, 'Error', $payload);
    }

    private function AuthorizationException(AuthorizationException $exception, $payload = null)
    {
        return self::createJson('You do not have proper permission to perform this task' . ' ' . $exception->getMessage(), Response::HTTP_FORBIDDEN, 'Error', $payload);
    }
    private function AccessDeniedHttpException(AccessDeniedHttpException $exception, $payload = null)
    {
        return self::createJson('You do not have proper permission to perform this task' . ' ' . $exception->getMessage(), Response::HTTP_FORBIDDEN, 'Error', $payload);
    }

    private function QueryException(QueryException $exception, $payload = ['success' => false, 'data' => null])
    {
        return self::createJson($exception->getMessage() . ' ' . 'Please check your payload data again', 409, 'Error', $payload);
    }

    private function BadRequestHttpException(BadRequestHttpException $exception, $payload = null)
    {
        return self::createJson($exception->getMessage() . ' ' . 'Please check your payload data again', Response::HTTP_BAD_REQUEST, 'Error', $payload);
    }
    private function NotFoundHttpException($exception, $payload = null)
    {
        return self::createJson($exception->getMessage() . 'Not Found', Response::HTTP_NOT_FOUND, 'Error', $payload);
    }
}
