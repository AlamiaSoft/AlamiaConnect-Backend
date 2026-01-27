<?php

namespace Alamia\RestApi\Traits;

trait JsonApiResponse
{
    /**
     * Format a single resource as JSON:API
     *
     * @param mixed $resource
     * @param string $type
     * @param array $relationships
     * @param array $included
     * @return array
     */
    protected function jsonApiResource($resource, string $type, ?array $attributes = null, array $relationships = [], array $included = []): array
    {
        if (!$resource) {
            return ['data' => null];
        }

        $data = [
            'type' => $type,
            'id' => (string) $resource->id,
            'attributes' => $attributes ?? $this->formatAttributes($resource),
            'links' => [
                'self' => url("/api/v1/{$type}/{$resource->id}")
            ]
        ];

        if (!empty($relationships)) {
            $data['relationships'] = $relationships;
        }

        $response = ['data' => $data];

        if (!empty($included)) {
            $response['included'] = $included;
        }

        return $response;
    }

    /**
     * Format a collection as JSON:API
     *
     * @param mixed $collection
     * @param string $type
     * @return array
     */
    protected function jsonApiCollection($collection, string $type): array
    {
        $data = $collection->map(function ($item) use ($type) {
            return [
                'type' => $type,
                'id' => (string) $item->id,
                'attributes' => $this->formatAttributes($item),
                'links' => [
                    'self' => url("/api/v1/{$type}/{$item->id}")
                ]
            ];
        })->toArray();

        return [
            'data' => $data,
            'meta' => [
                'total' => $collection->count()
            ]
        ];
    }

    /**
     * Format resource attributes
     *
     * @param mixed $resource
     * @return array
     */
    protected function formatAttributes($resource): array
    {
        $attributes = $resource->toArray();
        
        // Remove id from attributes as it's in the top level
        unset($attributes['id']);
        
        // Remove timestamps if you want to handle them separately
        // unset($attributes['created_at'], $attributes['updated_at']);
        
        return $attributes;
    }

    /**
     * Format JSON:API error response
     *
     * @param string $title
     * @param string $detail
     * @param int $status
     * @param string|null $pointer
     * @return array
     */
    protected function jsonApiError(string $title, string $detail, int $status = 400, ?string $pointer = null): array
    {
        $error = [
            'status' => (string) $status,
            'title' => $title,
            'detail' => $detail
        ];

        if ($pointer) {
            $error['source'] = ['pointer' => $pointer];
        }

        return ['errors' => [$error]];
    }

    /**
     * Format validation errors as JSON:API
     *
     * @param array $errors
     * @return array
     */
    protected function jsonApiValidationErrors(array $errors): array
    {
        $formattedErrors = [];

        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $formattedErrors[] = [
                    'status' => '422',
                    'title' => 'Validation Error',
                    'detail' => $message,
                    'source' => [
                        'pointer' => "/data/attributes/{$field}"
                    ]
                ];
            }
        }

        return ['errors' => $formattedErrors];
    }

    /**
     * Format a success message response
     *
     * @param string $message
     * @param int $status
     * @return array
     */
    protected function jsonApiMessage(string $message, int $status = 200): array
    {
        return [
            'meta' => [
                'message' => $message
            ]
        ];
    }

    /**
     * Get attributes from a JSON:API request
     *
     * @return array
     */
    protected function getJsonApiAttributes(): array
    {
        $data = request()->input('data');

        if (!isset($data['attributes'])) {
            return request()->all();
        }

        $attributes = $data['attributes'];

        if (isset($data['relationships'])) {
            foreach ($data['relationships'] as $key => $value) {
                if (isset($value['data']['id'])) {
                    $attributes[$key . '_id'] = $value['data']['id'];
                }
            }
        }

        return $attributes;
    }
}
