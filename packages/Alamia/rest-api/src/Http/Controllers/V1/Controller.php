<?php

namespace Alamia\RestApi\Http\Controllers\V1;

use Webkul\Core\Eloquent\Repository;
use Alamia\RestApi\Http\Controllers\RestApiController;

class Controller extends RestApiController
{
    /**
     * Exclude keys which not needed during searching.
     *
     * @var array
     */
    protected $excludeKeys = [
        'entity_type',
        'limit',
        'page',
        'per_page',
        'pagination',
        'order',
        'sort',
        'search',
    ];

    /**
     * Add entity type.
     *
     * @return void
     */
    protected function addEntityTypeInRequest($entityType)
    {
        request()->request->add(['entity_type' => $entityType]);
    }

    /**
     * Returns a listing of the resource.
     *
     * @return Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    protected function allResources(Repository $repository)
    {
        $query = $repository->query();

        if ($includes = request()->input('include')) {
            $query->with(explode(',', $includes));
        }

        foreach (request()->except($this->excludeKeys) as $input => $value) {
            $query = $query->whereIn($input, array_map('trim', explode(',', $value)));
        }

        if ($sort = request()->input('sort')) {
            $query = $query->orderBy($sort, request()->input('order') ?? 'desc');
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        if (is_null(request()->input('pagination')) || request()->input('pagination')) {
            return $query->paginate(request()->input('limit') ?? 10);
        }

        return $query->get();
    }

    /**
     * Validate and get JSON:API data
     */
    protected function validateJsonApi(array $rules)
    {
        $data = $this->getJsonApiAttributes();
        
        $validator = validator($data, $rules);

        if ($validator->fails()) {
            // Throw validation exception that Laravel handles
            $validator->validate();
        }

        return $data;
    }
}

