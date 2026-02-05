<?php

namespace App\Mcp\Tools;

use Laravel\Mcp\Server\Tool;
use Webkul\Lead\Repositories\LeadRepository;
use Illuminate\Contracts\JsonSchema\JsonSchema;

class CreateLeadTool extends Tool
{
    public function name(): string
    {
        return 'create_lead';
    }

    public function description(): string
    {
        return 'Create a new lead in the CRM';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'title' => [
                    'type' => 'string',
                    'description' => 'The title of the lead',
                ],
                'person_id' => [
                    'type' => 'integer',
                    'description' => 'The ID of the person/contact',
                ],
                'lead_value' => [
                    'type' => 'number',
                    'description' => 'The potential value of the lead',
                ],
            ],
            'required' => ['title', 'person_id'],
        ];
    }

    public function handle(array $arguments): array
    {
        $lead = app(LeadRepository::class)->create(array_merge($arguments, [
            'user_id' => auth()->id() ?? 1,
            'status' => 'new',
            'lead_pipeline_id' => 1,
            'lead_pipeline_stage_id' => 1,
        ]));

        return [
            'content' => [
                [
                    'type' => 'text',
                    'text' => "Lead '{$lead->title}' created successfully with ID: {$lead->id}",
                ],
            ],
        ];
    }
}
