<?php

namespace Alamia\RestApi\Http\Resources\V1\Lead;

use Illuminate\Http\Resources\Json\JsonResource;
use Alamia\RestApi\Traits\JsonApiResponse;

class LeadResource extends JsonResource
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
            'title'                  => $this->title,
            'description'            => $this->description,
            'lead_value'             => $this->lead_value,
            'status'                 => $this->status,
            'lost_reason'            => $this->lost_reason,
            'closed_at'              => $this->closed_at,
            'lead_source_id'         => $this->lead_source_id,
            'lead_type_id'           => $this->lead_type_id,
            'lead_pipeline_id'       => $this->lead_pipeline_id,
            'lead_pipeline_stage_id' => $this->lead_pipeline_stage_id,
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
            'expected_close_date'    => $this->expected_close_date,
            
            // Computed Attributes for Easy Frontend Consumption
            'person_name'            => $this->person ? $this->person->name : null,
            'organization_name'      => $this->person && $this->person->organization ? $this->person->organization->name : null,
            'user_name'              => $this->user ? $this->user->name : null,
            'stage_name'             => $this->stage ? $this->stage->name : null,
            'source_name'            => $this->source ? $this->source->name : null,
            'contact_number'         => $this->person && !empty($this->person->contact_numbers) ? $this->person->contact_numbers[0]['value'] ?? null : null,
            'contact_email'          => $this->person && !empty($this->person->emails) ? $this->person->emails[0]['value'] ?? null : null,
        ];

        $relationships = [
            'user' => [
                'data' => $this->user_id ? ['type' => 'users', 'id' => (string) $this->user_id] : null
            ],
            'person' => [
                'data' => $this->person_id ? ['type' => 'persons', 'id' => (string) $this->person_id] : null
            ],
            'products' => [
                'data' => $this->products->map(function ($product) {
                    return [
                        'type' => 'products',
                        'id'   => (string) $product->id,
                    ];
                })->toArray(),
            ],
        ];

        return $this->jsonApiResource($this->resource, 'leads', $attributes, $relationships);
    }
}
