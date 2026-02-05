<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Mcp\Facades\Mcp;
use Alamia\KTD\Repositories\VisitRepository;
use Webkul\Lead\Repositories\LeadRepository;

class McpToolsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Mcp::local('crm', \App\Mcp\CrmServer::class);
    }
}
