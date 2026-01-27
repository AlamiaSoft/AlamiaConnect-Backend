<?php

namespace Alamia\RestApi\Http\Resources\V1\Setting;

use Illuminate\Http\Resources\Json\JsonResource;
use Alamia\RestApi\Traits\JsonApiResponse;

class RoleResource extends JsonResource
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
        return $this->jsonApiResource($this->resource, 'roles', [
            'name'            => $this->name,
            'description'     => $this->description,
            'access_level'    => $this->access_level,
            'permission_type' => $this->permission_type,
            'permissions'     => $this->permissions,
        ])['data'];
    }
}
