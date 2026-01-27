<?php

namespace Alamia\RestApi\Http\Resources\V1\Attendance;

use Illuminate\Http\Resources\Json\JsonResource;
use Alamia\RestApi\Traits\JsonApiResponse;

class AttendanceResource extends JsonResource
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
            'check_in'  => $this->check_in,
            'check_out' => $this->check_out,
            'gps_lat'   => $this->gps_lat,
            'gps_lng'   => $this->gps_lng,
            'photo_url' => $this->photo_url,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
            'user_name' => $this->user ? $this->user->name : null,
            'user_email'=> $this->user ? $this->user->email : null,
        ];

        $relationships = [
            'user' => [
                'data' => $this->user_id ? ['type' => 'users', 'id' => (string) $this->user_id] : null
            ]
        ];

        return $this->jsonApiResource($this->resource, 'attendances', $attributes, $relationships);
    }
}
