<?php

namespace App\Mcp;

use Laravel\Mcp\Server;
use App\Mcp\Tools\CreateLeadTool;
use App\Mcp\Tools\LogVisitTool;

class CrmServer extends Server
{
    protected string $name = 'AlamiaConnect CRM Server';

    protected string $version = '1.0.0';

    protected string $instructions = 'This server allows AI agents to manage leads and log field visits in AlamiaConnect.';

    protected array $tools = [
        CreateLeadTool::class,
        LogVisitTool::class,
    ];
}
