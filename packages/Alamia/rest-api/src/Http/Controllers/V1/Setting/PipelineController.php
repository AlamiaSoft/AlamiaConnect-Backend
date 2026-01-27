<?php

namespace Alamia\RestApi\Http\Controllers\V1\Setting;

use Alamia\RestApi\Http\Controllers\V1\Controller;
use Alamia\RestApi\Http\Resources\V1\Setting\PipelineResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\Lead\Repositories\PipelineRepository;

class PipelineController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PipelineRepository $pipelineRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $pipelines = $this->allResources($this->pipelineRepository);

        return PipelineResource::collection($pipelines);
    }

    /**
     * Show resource.
     */
    public function show(int $id): PipelineResource
    {
        $resource = $this->pipelineRepository->find($id);

        return new PipelineResource($resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResource
    {
        $data = $this->validateJsonApi([
            'name'          => 'required|unique:lead_pipelines,name',
            'rotten_days'   => 'required',
            'stages.*.name'        => 'required|string',
            'stages.*.code'        => 'nullable|string',
            'stages.*.description' => 'nullable|string',
            'stages.*.probability' => 'required|numeric|min:0|max:100',
        ]);

        // Validate unique stage names and codes
        $this->validateUniqueStages($data['stages'] ?? []);

        $data['is_default'] = isset($data['is_default']) && $data['is_default'] ? 1 : 0;

        Event::dispatch('settings.pipeline.create.before');

        $pipeline = $this->pipelineRepository->create($data);

        Event::dispatch('settings.pipeline.create.after', $pipeline);

        return (new PipelineResource($pipeline))
            ->additional(['meta' => ['message' => trans('rest-api::app.settings.pipelines.create-success')]]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): JsonResource
    {
        $data = $this->validateJsonApi([
            'name'          => 'required|unique:lead_pipelines,name,'.$id,
            'rotten_days'   => 'nullable',
            'stages.*.name' => 'required|string',
            'stages.*.code' => 'nullable|string',
        ]);

        // Validate unique stage names and codes
        $this->validateUniqueStages($data['stages'] ?? []);

        $data['is_default'] = isset($data['is_default']) && $data['is_default'] ? 1 : 0;

        // Transform stages to key-based array for Repository
        if (isset($data['stages']) && is_array($data['stages'])) {
            $formattedStages = [];
            foreach ($data['stages'] as $index => $stage) {
                if (isset($stage['id']) && $stage['id']) {
                    $formattedStages[$stage['id']] = $stage;
                } else {
                    $formattedStages['stage_' . $index] = $stage;
                }
            }
            $data['stages'] = $formattedStages;
        }

        Event::dispatch('settings.pipeline.update.before', $id);

        $pipeline = $this->pipelineRepository->update($data, $id);

        Event::dispatch('settings.pipeline.update.after', $pipeline);

        return (new PipelineResource($pipeline))
            ->additional(['meta' => ['message' => trans('rest-api::app.settings.pipelines.updated-success')]]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResource
    {
        $pipeline = $this->pipelineRepository->findOrFail($id);

        if ($pipeline->is_default) {
            return new JsonResource([
                'message' => trans('rest-api::app.settings.pipelines.default-delete-error'),
            ], 400);
        } else {
            $defaultPipeline = $this->pipelineRepository->getDefaultPipeline();

            $pipeline->leads()->update([
                'lead_pipeline_id'       => $defaultPipeline->id,
                'lead_pipeline_stage_id' => $defaultPipeline->stages()->first()->id,
            ]);
        }

        try {
            Event::dispatch('settings.pipeline.delete.before', $id);

            $this->pipelineRepository->delete($id);

            Event::dispatch('settings.pipeline.delete.after', $id);

            return new JsonResource([
                'message' => trans('rest-api::app.settings.pipelines.delete-success'),
            ]);
        } catch (\Exception $exception) {
            return new JsonResource([
                'message' => trans('rest-api::app.settings.pipelines.delete-failed'),
            ], 500);
        }
    }

    /**
     * Validate that stage names and codes are unique within the pipeline.
     *
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateUniqueStages(array $stages): void
    {
        $names = collect($stages)->pluck('name')->filter();
        $codes = collect($stages)->pluck('code')->filter();

        if ($names->count() !== $names->unique()->count()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'stages.*.name' => ['Stage names must be unique within the pipeline.'],
            ]);
        }

        if ($codes->count() !== $codes->unique()->count()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'stages.*.code' => ['Stage codes must be unique within the pipeline.'],
            ]);
        }
    }
}
