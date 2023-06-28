<?php

declare(strict_types=1);

namespace BRCas\LaravelPackage\Adapters;

use BRCas\CA\Contracts\Items\ItemInterface;
use BRCas\CA\Contracts\Items\PaginationInterface;
use BRCas\LaravelPackage\Resources\DefaultResource;

class ApiAdapter
{
    public function __construct(
        protected PaginationInterface|ItemInterface $response
    ) {
        //
    }

    public function toJson()
    {
        $additional = [];
        if($this->response instanceof PaginationInterface) {
            $additional = [
                'meta' => [
                    'total' => $this->response->total(),
                    'current_page' => $this->response->currentPage(),
                    'last_page' => $this->response->lastPage(),
                    'first_page' => $this->response->firstPage(),
                    'per_page' => $this->response->perPage(),
                    'to' => $this->response->to(),
                    'from' => $this->response->from(),
                ],
            ];
        }

        return DefaultResource::collection($this->response->items())
            ->additional($additional);
    }

    public function toXml()
    {
        //
    }

    public static function json(object $data, int $statusCode = 200)
    {
        return (new DefaultResource($data))
            ->response()
            ->setStatusCode($statusCode);
    }
}
