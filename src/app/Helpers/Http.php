<?php

namespace App\Helpers;

use Illuminate\Http\Response;

trait Http
{
    private function ok(mixed $data): array
    {
        return $this->buildResponse(Response::HTTP_OK, $data);
    }

    private function created(mixed $data): array
    {
        return $this->buildResponse(Response::HTTP_CREATED, $data);
    }

    private function notFound(mixed $data): array
    {
        return $this->buildResponse(Response::HTTP_NOT_FOUND, $data);
    }

    private function unprocessableEntity(mixed $data): array
    {
        return $this->buildResponse(Response::HTTP_UNPROCESSABLE_ENTITY, $data);
    }

    private function serverError(): array
    {
        return $this->buildResponse(Response::HTTP_INTERNAL_SERVER_ERROR, 'An internal error has occurred, please contact an administrator.');
    }

    private function forbidden($data): array
    {
        return $this->buildResponse(Response::HTTP_FORBIDDEN, $data);
    }

    private function buildResponse(int $code, mixed $data): array
    {
        return [
            'code' => $code,
            'response' => [
                'data' => $data
            ]
        ];
    }
}
