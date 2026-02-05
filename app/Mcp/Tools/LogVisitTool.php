<?php

namespace App\Mcp\Tools;

use Laravel\Mcp\Server\Tool;
use Alamia\KTD\Repositories\VisitRepository;
use Illuminate\Contracts\JsonSchema\JsonSchema;

class LogVisitTool extends Tool
{
    public function name(): string
    {
        return 'log_visit';
    }

    public function description(): string
    {
        return 'Log a client visit with GPS coordinates';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'lead_id' => [
                    'type' => 'integer',
                    'description' => 'The ID of the lead visited',
                ],
                'title' => [
                    'type' => 'string',
                    'description' => 'A short title for the visit',
                ],
                'description' => [
                    'type' => 'string',
                    'description' => 'Details of the visit',
                ],
                'latitude' => [
                    'type' => 'number',
                    'description' => 'GPS latitude',
                ],
                'longitude' => [
                    'type' => 'number',
                    'description' => 'GPS longitude',
                ],
            ],
            'required' => ['lead_id', 'title'],
        ];
    }

    public function handle(array $arguments): array
    {
        $visit = app(VisitRepository::class)->create(array_merge($arguments, [
            'user_id' => auth()->id() ?? 1,
            'visit_time' => now(),
        ]));

        return [
            'content' => [
                [
                    'type' => 'text',
                    'text' => "Visit logged successfully for lead ID: {$arguments['lead_id']}",
                ],
            ],
        ];
    }
}
