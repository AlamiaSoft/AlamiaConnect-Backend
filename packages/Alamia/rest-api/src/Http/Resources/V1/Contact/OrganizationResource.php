<?php

namespace Alamia\RestApi\Http\Resources\V1\Contact;

use Illuminate\Http\Resources\Json\JsonResource;

use Alamia\RestApi\Traits\JsonApiResponse;

class OrganizationResource extends JsonResource
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
            'name'       => $this->name,
            'address'    => $this->address,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'website'    => $this->website,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        return $this->jsonApiResource($this->resource, 'organizations', $attributes);
    }
}
