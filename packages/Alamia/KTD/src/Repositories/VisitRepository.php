<?php

namespace Alamia\KTD\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Alamia\KTD\Models\Visit;

class VisitRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Visit::class;
    }
}
