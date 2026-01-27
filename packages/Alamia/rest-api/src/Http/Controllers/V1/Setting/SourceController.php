<?php

namespace Alamia\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\Lead\Repositories\SourceRepository;
use Alamia\RestApi\Http\Controllers\V1\Controller;
use Alamia\RestApi\Http\Resources\V1\Setting\SourceResource;

class SourceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected SourceRepository $sourceRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $sources = $this->allResources($this->sourceRepository);

        return SourceResource::collection($sources);
    }

    /**
     * Show resource.
     */
    public function show(int $id): SourceResource
    {
        $resource = $this->sourceRepository->findOrFail($id);

        return new SourceResource($resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResource
    {
        $data = $this->validateJsonApi([
            'name' => 'required|unique:lead_sources,name',
        ]);

        Event::dispatch('settings.source.create.before');

        $source = $this->sourceRepository->create($data);

        Event::dispatch('settings.source.create.after', $source);

        return (new SourceResource($source))
            ->additional(['meta' => ['message' => trans('rest-api::app.settings.sources.create-success')]]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): JsonResource
    {
        $data = $this->validateJsonApi([
            'name' => 'required|unique:lead_sources,name,'.$id,
        ]);

        Event::dispatch('settings.source.update.before', $id);

        $source = $this->sourceRepository->update($data, $id);

        Event::dispatch('settings.source.update.after', $source);

        return (new SourceResource($source))
            ->additional(['meta' => ['message' => trans('rest-api::app.settings.sources.update-success')]]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResource
    {
        try {
            Event::dispatch('settings.source.delete.before', $id);

            $this->sourceRepository->delete($id);

            Event::dispatch('settings.source.delete.after', $id);

            return response()->json($this->jsonApiMessage(trans('rest-api::app.settings.sources.delete-success')));
        } catch (\Exception $exception) {
            return response()->json($this->jsonApiError('Delete Failed', trans('rest-api::app.settings.sources.delete-failed'), 500), 500);
        }
    }
}

