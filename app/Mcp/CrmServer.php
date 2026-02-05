<?php

namespace App\Mcp;

use App\Mcp\Tools\AssignTaskTool;
use App\Mcp\Tools\CreateLeadTool;
use App\Mcp\Tools\LogVisitTool;
use Laravel\Mcp\Server;

class CrmServer extends Server
{
    protected string $name = 'AlamiaConnect CRM Server';

    protected string $version = '1.0.0';

    protected string $instructions = 'This server allows AI agents to manage leads, log field visits, and assign tasks in AlamiaConnect.';

    protected array $tools = [
        CreateLeadTool::class,
        LogVisitTool::class,
        AssignTaskTool::class,
    ];
}
