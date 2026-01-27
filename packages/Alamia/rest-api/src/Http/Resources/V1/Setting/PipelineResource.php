<?php

namespace Alamia\RestApi\Http\Resources\V1\Setting;

use Illuminate\Http\Resources\Json\JsonResource;
use Alamia\RestApi\Traits\JsonApiResponse;

class PipelineResource extends JsonResource
{
    use JsonApiResponse;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $attributes = [
            'id'          => $this->id,
            'name'        => $this->name,
            'is_default'  => $this->is_default,
            'rotten_days' => $this->rotten_days,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'stages'      => $this->stages->map(function ($stage) {
                return [
                    'id'          => (string) $stage->id,
                    'name'        => $stage->name,
                    'code'        => $stage->code,
                    'description' => $stage->description,
                    'sort_order'  => (int) $stage->sort_order,
                    'probability' => (int) $stage->probability,
                ];
            })->toArray(),
        ];

        return $this->jsonApiResource($this->resource, 'pipelines', $attributes)['data'];
    }
}
