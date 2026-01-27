<?php

namespace Alamia\RestApi\Http\Resources\V1\Contact;

use Illuminate\Http\Resources\Json\JsonResource;
use Alamia\RestApi\Traits\JsonApiResponse;

class PersonResource extends JsonResource
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
            'name'            => $this->name,
            'emails'          => $this->emails,
            'contact_numbers' => $this->contact_numbers,
            'job_title'       => $this->job_title,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            'organization_name' => $this->organization ? $this->organization->name : null,
            'sales_owner_name'  => $this->user ? $this->user->name : null,
        ];

        $relationships = [
            'organization' => [
                'data' => $this->organization_id ? ['type' => 'organizations', 'id' => (string) $this->organization_id] : null
            ],
            'sales_owner' => [
                'data' => $this->user_id ? ['type' => 'users', 'id' => (string) $this->user_id] : null
            ]
        ];

        return $this->jsonApiResource($this->resource, 'persons', $attributes, $relationships)['data'];
    }
}
