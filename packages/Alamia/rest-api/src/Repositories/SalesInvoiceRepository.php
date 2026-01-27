<?php

namespace Alamia\RestApi\Repositories;

use Webkul\Core\Eloquent\Repository;

class SalesInvoiceRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Alamia\RestApi\Models\SalesInvoice';
    }
}

