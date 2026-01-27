<?php

namespace Alamia\RestApi\Http\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class OrganizationSearchCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $search = request()->get('search');

        if (empty($search)) {
            return $model;
        }

        return $model->where(function ($query) use ($search) {
            $query->where('organizations.name', 'like', "%{$search}%")
                  ->orWhere('organizations.address', 'like', "%{$search}%");
        });
    }
}
