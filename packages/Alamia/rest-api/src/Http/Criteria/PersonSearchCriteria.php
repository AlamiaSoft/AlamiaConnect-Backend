<?php

namespace Alamia\RestApi\Http\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class PersonSearchCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $search = request()->get('search');

        if (empty($search)) {
            return $model;
        }

        return $model->where(function ($query) use ($search) {
            $query->where('persons.name', 'like', "%{$search}%")
                  ->orWhere('persons.job_title', 'like', "%{$search}%")
                  ->orWhere('persons.emails', 'like', "%{$search}%")
                  ->orWhere('persons.contact_numbers', 'like', "%{$search}%")
                  ->orWhereHas('organization', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        });
    }
}
