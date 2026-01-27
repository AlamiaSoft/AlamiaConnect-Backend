<?php

namespace Alamia\RestApi\Http\Resources\V1\SalesInvoice;

use Illuminate\Http\Resources\Json\JsonResource;
use Alamia\RestApi\Traits\JsonApiResponse;

class SalesInvoiceResource extends JsonResource
{
    use JsonApiResponse;

    public function toArray($request)
    {
        $attributes = [
            'invoice_number'  => $this->invoice_number,
            'customer_name'   => $this->customer_name ?? ($this->person ? $this->person->name : null),
            'sales_manager'   => $this->user ? $this->user->name : 'Unknown',
            'total_amount'    => (float) $this->total_amount,
            'amount_received' => (float) $this->amount_received,
            'commissionStatus'=> $this->status,
            'machineryCategory' => $this->category,
            'date'            => $this->issued_at ? $this->issued_at->format('Y-m-d') : null,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];

        $relationships = [
            'person' => [
                'data' => $this->person_id ? ['type' => 'persons', 'id' => (string) $this->person_id] : null
            ],
            'user' => [
                'data' => $this->user_id ? ['type' => 'users', 'id' => (string) $this->user_id] : null
            ]
        ];

        return $this->jsonApiResource($this->resource, 'sales-invoices', $attributes, $relationships);
    }
}
