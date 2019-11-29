<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response as IlluminateResponse;
use Response;

/**
 * Base API Controller.
 */
class APIController extends Controller
{
    /**
     * Default status code.
     *
     * @var int
     */
    protected $statusCode = 200;

    /**
     * Get the status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the status code.
     *
     * @param int $statusCode
     *
     * @return APIController
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Respond.
     *
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Respond with pagination.
     *
     * @param Paginator $items
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithPagination($items, $data)
    {
        $data = array_merge($data, [
            'paginator' => [
                'total_count' => $items->total(),
                'total_pages' => ceil($items->total() / $items->perPage()),
                'current_page' => $items->currentPage(),
                'limit' => $items->perPage(),
            ],
        ]);

        return $this->respond($data);
    }

    /**
     * Respond Created.
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondCreated($data)
    {
        return $this->setStatusCode(201)->respond([
            'data' => $data,
        ]);
    }

    /**
     * Respond with data.
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithData($data)
    {
        return $this->setStatusCode(200)->respond([
            'data' => $data,
        ]);
    }

    /**
     * Respond with error.
     *
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message)
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'code' => $this->getStatusCode(),
            ],
        ]);
    }

    /**
     * Respond not found.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * Respond with error.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    /**
     * Respond with unauthorized.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    /**
     * Respond with forbidden.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)->respondWithError($message);
    }

    /**
     * Respond with no content.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithNoContent()
    {
        return $this->setStatusCode(204)->respond(null);
    }

    /**
     * Note this function is same as the below function but instead of responding with error below function returns error json
     * Throw Validation.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function throwValidation($message)
    {
        return $this->setStatusCode(422)
            ->respondWithError($message);
    }
}
