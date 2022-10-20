<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponse;
use BadMethodCallException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Exceptions\AuthExceptions\UserNotLoginable;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Exceptions\UserExceptions\UserNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\UserExceptions\UserNotVerifiedException;
use App\Exceptions\AuthExceptions\BadLoginCredentialException;
use App\Exceptions\AuthExceptions\UserAccountInactiveException;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        // \Illuminate\Auth\AuthenticationException::class,
        // \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) 
        {
            //
        });

        $this->renderable(function(\Exception $e, $request) 
        {
            return $this->handleException($request, $e);
        });
    }

    public function handleException($request, \Exception $exception)
    {
        if ($request->wantsJson() || $this->isApiCall($request)) 
        {
            if ($exception instanceof GeneralException) {
                return $this->createGeneralException($exception);
            }

            if ($exception instanceof UserNotVerifiedException) {
                return $this->createUserNotVerifiedException($exception);
            }

            if ($exception instanceof UserNotFoundException) {
                return $this->createUserNotFoundExceptionResponse($exception);
            }

            if ($exception instanceof UserNotLoginable) {
                return $this->createUserNotLoginable($exception);
            }

            if ($exception instanceof BadLoginCredentialException) {
                return $this->BadLoginCredentialException($exception);
            }

            if ($exception instanceof ModelNotFoundException) {
                return $this->createModelNotFoundException($exception);
            }
            if ($exception instanceof BadMethodCallException) {
                return $this->BadMethodCallException($exception);
            }
            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->MethodNotAllowedHttpException($exception);
            }
            if ($exception instanceof BadLoginCredentialException) {
                return $this->BadLoginCredentialException($exception);
            }
            if ($exception instanceof UnauthorizedException) {
                return $this->UnauthorizedException($exception);
            }
            if ($exception instanceof AuthorizationException) {
                return $this->AuthorizationException($exception);
            }
            if ($exception instanceof QueryException) {
                return $this->QueryException($exception);
            }
            if ($exception instanceof UserAccountInactiveException) {
                return $this->createUserAccountInactiveException($exception);
            }
            if ($exception instanceof AuthenticationException) {
                return $this->createAuthenticationException($exception);
            }
            if ($exception instanceof BadRequestHttpException) {
                return $this->BadRequestHttpException($exception);
            }
            if ($exception instanceof ValidationException) {
                return self::createJson($exception->getMessage(), $exception->status, 'Error', ['errors' => $exception->validator->getMessageBag()]);
            }
            if ($exception instanceof HttpResponseException) {
                return self::createJson($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, 'Error');
            }
            if ($exception instanceof NotFoundHttpException ) {
                return self::createJson(trans('api.PAGE_NOT_FOUND'), Response::HTTP_NOT_FOUND, 'Error', ['base_url' => url("/")."/api/v1/customer"]);
            }











            if ($exception instanceof \Exception) {
                return self::createJson($exception->getMessage() ?? '', Response::HTTP_INTERNAL_SERVER_ERROR, 'Error');
            }
        }
        
        if ($exception instanceof GeneralException) {
            return redirect()
                ->route('home')
                ->withFlashDanger(__('auth.general_error'));
        }
        
        if ($exception instanceof UserNotLoginable) {
            abort($exception->getCode(), $exception->getMessage());
        }
        
        if ($exception instanceof UserNotFoundException) {
            abort($exception->getCode(), $exception->getMessage());
        }

        if ($exception instanceof UserAccountInactiveException) 
        {
            if(isset($exception->getPayload()['email'], $exception->getPayload()['email']) && !is_null($exception->getPayload()['email']))
            {
                $token = $exception->getPayload()['token'];
                $email = $exception->getPayload()['email'];
    
                flash(trans('auth.CHECK_OTP'), 'error');
                return redirect()->route('verifyEmail')->with(compact('token', 'email'));
            }
            else
            {
                flash($exception->getMessage(), 'error');
                return redirect()->route('login');
            }

        }
        

        // return parent::render($request, $exception);
    }



    private function createGeneralException(GeneralException $exception)
    { 
        $payload = $exception->getPayload() ?? [];
        return self::createJson($exception->getMessage(), $exception->getCode(), 'Error', $payload);
    }

    private function createUserNotVerifiedException(UserNotVerifiedException $exception, $payload = null)
    {
        return self::createJson($exception->getMessage(), $exception->getCode(), 'Error', $payload);
    }

    private function createUserNotFoundExceptionResponse(UserNotFoundException $exception, $payload = null)
    {
        return self::createJson($exception->getMessage(), $exception->getCode(), 'Error', $payload);
    }

    private function createUserNotLoginable(UserNotLoginable $exception, $payload = null)
    {
        return self::createJson($exception->getMessage(), $exception->getCode(), 'Error', $payload);
    }

    private function createUserAccountInactiveException(UserAccountInactiveException $exception, $payload = null)
    {
        return self::createJson($exception->getMessage(), $exception->getCode(), 'Error', $payload);
    }

    private function BadLoginCredentialException(BadLoginCredentialException $exception, $payload = null)
    {
        return self::createJson($exception->getMessage(), 401, 'Error', $payload);
    }





    /**
     * Determines if request is an api call.
     *
     * If the request URI contains '/api/v'.
     *
     * @param Request $request
     * @return bool
     */
    private function isApiCall($request)
    {
        return strpos($request->getUri(), '/api/') !== false;
    }
}
