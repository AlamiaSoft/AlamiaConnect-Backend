<?php

namespace Alamia\RestApi\Http\Resources\V1\Setting;

use Illuminate\Http\Resources\Json\JsonResource;
use Alamia\RestApi\Traits\JsonApiResponse;

class StageResource extends JsonResource
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
        return $this->jsonApiResource($this->resource, 'stages', [
            'id'                   => $this->id,
            'code'                 => $this->code,
            'name'                 => $this->name,
            'lead_value'           => $this->lead_value,
            'formatted_lead_value' => core()->formatBasePrice($this->lead_value),
            'is_user_defined'      => $this->is_user_defined,
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ])['data'];
    }
}
