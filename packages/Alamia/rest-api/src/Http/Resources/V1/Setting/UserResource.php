<?php

namespace Alamia\RestApi\Http\Resources\V1\Setting;

use Alamia\RestApi\Traits\JsonApiResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        return $this->jsonApiResource($this->resource, 'users', [
            'id'              => $this->id,
            'name'            => $this->name,
            'email'           => $this->email,
            'status'          => $this->status,
            'api_token'       => $this->api_token,
            'image_url'       => $this->image_url,
            'role'            => $this->role ? [
                'id'              => $this->role->id,
                'name'            => $this->role->name,
                'permission_type' => $this->role->permission_type,
                'permissions'     => $this->role->permissions,
                'access_level'    => $this->role->access_level,
            ] : null,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            'billing'         => [
                'subscription_status' => core()->getConfigData('billing.subscription.settings.status'),
                'portal_url'          => core()->getConfigData('billing.subscription.settings.portal_url'),
                'portal_label'        => core()->getConfigData('billing.subscription.settings.portal_label') ?? 'View Invoice',
            ],
        ])['data'];
    }
}
