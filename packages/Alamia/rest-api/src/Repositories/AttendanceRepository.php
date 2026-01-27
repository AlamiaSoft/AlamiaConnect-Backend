<?php

namespace Alamia\RestApi\Repositories;

use Webkul\Core\Eloquent\Repository;

class AttendanceRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Alamia\RestApi\Models\Attendance';
    }
}

