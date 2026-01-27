<?php

namespace Alamia\RestApi\Http\Resources\V1\Setting;

use Illuminate\Http\Resources\Json\JsonResource;
use Alamia\RestApi\Traits\JsonApiResponse;

class TypeResource extends JsonResource
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
        return $this->jsonApiResource($this->resource, 'types', [
            'id'         => $this->id,
            'name'       => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ])['data'];
    }
}
