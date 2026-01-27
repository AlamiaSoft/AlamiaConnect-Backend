<?php

namespace Alamia\RestApi\Exceptions;

use App\Exceptions\Handler as AppExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Alamia\RestApi\Traits\JsonApiResponse;
use PDOException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends AppExceptionHandler
{
    use JsonApiResponse;

    /**
     * Create handler instance.
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // Force JSON response for API routes
        if ($request->is('api/*')) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Handle API exceptions and return JSON:API response
     */
    protected function handleApiException($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof ValidationException) {
            return response()->json(
                $this->jsonApiValidationErrors($exception->errors()),
                422,
                ['Content-Type' => 'application/vnd.api+json']
            );
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json(
                $this->jsonApiError('Resource Not Found', 'The requested resource was not found.', 404),
                404,
                ['Content-Type' => 'application/vnd.api+json']
            );
        }

        $statusCode = 500;
        $title = 'Internal Server Error';
        $detail = $exception->getMessage();

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $title = $this->getHttpTitle($statusCode);
        }

        if (!config('app.debug') && $statusCode == 500) {
            $detail = 'An unexpected error occurred.';
        }

        return response()->json(
            $this->jsonApiError($title, $detail, $statusCode),
            $statusCode,
            ['Content-Type' => 'application/vnd.api+json']
        );
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(
            $this->jsonApiError('Unauthenticated', 'You are not authenticated. Please log in to continue.', 401),
            401,
            ['Content-Type' => 'application/vnd.api+json']
        );
    }

    /**
     * Get title for HTTP status code
     */
    protected function getHttpTitle($statusCode)
    {
        $titles = [
            400 => 'Bad Request',
            401 => 'Unauthenticated',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            422 => 'Unprocessable Entity',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable',
        ];

        return $titles[$statusCode] ?? 'Error';
    }
}
