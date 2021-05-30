<?php

namespace PrimeX\Packages\Core\Traits;

use Illuminate\Http\JsonResponse;

trait BaseController
{
    /**
     * @var int used for displaying paginated results
     */
    protected $pageSize = 10;

    /**
     * Generic API response
     *
     * @param $data
     * @param $status
     * @return JsonResponse
     */
    protected function respond($data, $status)
    {
        return response()->json($data, $status);
    }

    /**
     * Not found response
     *
     * @param string $data
     * @param int $status
     * @return JsonResponse
     */
    protected function notFound(string $data = '', int $status = 404): JsonResponse
    {
        return $this->respond($data, $status);
    }

    /**
     * For delete successful, etc.
     *
     * @return JsonResponse
     */
    protected function noContent(): JsonResponse
    {
        return $this->respond('', 204);
    }

    /**
     * @param string $data
     * @param int $status
     * @return JsonResponse
     */
    protected function badRequest(string $data = '', int $status = 400): JsonResponse
    {
        return $this->respond($data, $status);
    }
}
