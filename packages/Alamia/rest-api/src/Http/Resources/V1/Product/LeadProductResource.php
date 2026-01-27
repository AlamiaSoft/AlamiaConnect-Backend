<?php

namespace Alamia\RestApi\Http\Resources\V1\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Alamia\RestApi\Traits\JsonApiResponse;

class LeadProductResource extends JsonResource
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
            'quantity'    => $this->quantity,
            'price'       => $this->price,
            'amount'      => $this->amount,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];

        $relationships = [
            'product' => [
                'data' => $this->product_id ? ['type' => 'products', 'id' => (string) $this->product_id] : null
            ]
        ];

        return $this->jsonApiResource($this->resource, 'lead_products', $attributes, $relationships);
    }
}
