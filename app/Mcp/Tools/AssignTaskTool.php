<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Server\Tool;
use Webkul\Activity\Repositories\ActivityRepository;

class AssignTaskTool extends Tool
{
    public function name(): string
    {
        return 'assign_task';
    }

    public function description(): string
    {
        return 'Assign a task or activity to a user';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'type'       => 'object',
            'properties' => [
                'title' => [
                    'type'        => 'string',
                    'description' => 'The title of the task',
                ],
                'comment' => [
                    'type'        => 'string',
                    'description' => 'A description or comment for the task',
                ],
                'type' => [
                    'type'    => 'string',
                    'enum'    => ['call', 'meeting', 'lunch', 'note'],
                    'default' => 'note',
                ],
                'user_id' => [
                    'type'        => 'integer',
                    'description' => 'The ID of the user to assign the task to',
                ],
                'lead_id' => [
                    'type'        => 'integer',
                    'description' => 'Optional: The ID of the lead this task is related to',
                ],
            ],
            'required' => ['title', 'user_id'],
        ];
    }

    public function handle(array $arguments): array
    {
        // Standard Krayin Activity creation logic
        $activity = app(ActivityRepository::class)->create(array_merge($arguments, [
            'is_done'       => false,
            'schedule_from' => now()->toDateTimeString(),
            'schedule_to'   => now()->addHour()->toDateTimeString(),
        ]));

        return [
            'content' => [
                [
                    'type' => 'text',
                    'text' => "Task '{$activity->title}' assigned successfully to User ID: {$arguments['user_id']}",
                ],
            ],
        ];
    }
}
