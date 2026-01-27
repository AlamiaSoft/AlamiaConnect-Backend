<?php

namespace Alamia\RestApi\Http\Resources\V1\SalesVisit;

use Illuminate\Http\Resources\Json\JsonResource;
use Alamia\RestApi\Traits\JsonApiResponse;

class SalesVisitResource extends JsonResource
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
            'visit_at'  => $this->visit_at,
            'check_in_at' => $this->check_in_at,
            'check_out_at' => $this->check_out_at,
            'outcome'   => $this->outcome,
            'notes'     => $this->notes,
            'gps_lat'   => $this->gps_lat,
            'gps_lng'   => $this->gps_lng,
            'photo_url' => $this->photo_url,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
        ];

        $relationships = [
            'user' => [
                'data' => $this->user_id ? ['type' => 'users', 'id' => (string) $this->user_id] : null
            ],
            'lead' => [
                'data' => $this->lead_id ? ['type' => 'leads', 'id' => (string) $this->lead_id] : null
            ]
        ];

        $included = [];
        
        if ($this->resource->relationLoaded('user') && $this->user) {
            $included[] = [
                'type' => 'users',
                'id' => (string) $this->user->id,
                'attributes' => [
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ]
            ];
        }

        if ($this->resource->relationLoaded('lead') && $this->lead) {
            $included[] = [
                'type' => 'leads',
                'id' => (string) $this->lead->id,
                'attributes' => [
                    'title' => $this->lead->title,
                    'description' => $this->lead->description,
                    'person_name' => $this->lead->person_name,
                ]
            ];
        }

        return $this->jsonApiResource($this->resource, 'sales_visits', $attributes, $relationships, $included);
    }
}
