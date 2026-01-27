<?php

namespace Alamia\RestApi\Repositories;

use Webkul\Core\Eloquent\Repository;

class SalesVisitRepository extends Repository
{
    public function model()
    {
        return 'Alamia\RestApi\Models\SalesVisit';
    }
}

