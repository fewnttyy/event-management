<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public $message;
    public $resource;

    public function __construct($message, $resource)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->resource = $resource;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message'   => $this->message,
            'data'      => $this->resource
        ];
    }

    public function withResponse($request, $response)
    {
        if (str_contains($this->message, 'registered')) {
            $response->setStatusCode(201);
        } elseif (str_contains($this->message, 'failed') || str_contains($this->message, 'Invalid')) {
            $response->setStatusCode(401);
        } else if (str_contains($this->message, 'Failed')) {
            $response->setStatusCode(404);
        } else {
            $response->setStatusCode(200);
        }
    }
}
