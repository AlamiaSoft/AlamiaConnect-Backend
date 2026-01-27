<?php

namespace Alamia\RestApi\Http\Controllers\V1\SalesVisit;

use Alamia\RestApi\Http\Controllers\V1\Controller;
use Alamia\RestApi\Http\Resources\V1\SalesVisit\SalesVisitResource;
use Alamia\RestApi\Repositories\SalesVisitRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesVisitController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SalesVisitRepository $salesVisitRepository
    ) {}

    /**
     * Returns a listing of the resource.
     */
    public function index(): JsonResource
    {
        $query = $this->salesVisitRepository->query();

        if ($includes = request()->input('include')) {
            $query->with(explode(',', $includes));
        }

        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('notes', 'like', '%' . $search . '%')
                  ->orWhere('outcome', 'like', '%' . $search . '%');
            });
        }

        foreach (request()->except(array_merge($this->excludeKeys, ['include'])) as $input => $value) {
            $query->whereIn($input, array_map('trim', explode(',', $value)));
        }

        if ($sort = request()->input('sort')) {
            $query->orderBy($sort, request()->input('order') ?? 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $visits = $query->paginate(request()->input('limit') ?? 10);

        return SalesVisitResource::collection($visits);
    }

    /**
     * Show resource.
     */
    public function show(int $id): JsonResource
    {
        $visit = $this->salesVisitRepository->findOrFail($id);

        return new SalesVisitResource($visit);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResource
    {
        $this->validate($request, [
            'lead_id'  => 'required|exists:leads,id',
            'visit_at' => 'required|date',
            'outcome'  => 'nullable|string',
            'notes'    => 'nullable|string',
            'gps_lat'  => 'nullable|numeric',
            'gps_lng'  => 'nullable|numeric',
            'check_in_at' => 'nullable|date',
            'check_out_at' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        $visit = $this->salesVisitRepository->create($data);

        return (new SalesVisitResource($visit))
            ->additional(['meta' => ['message' => 'Sales visit created successfully']]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResource
    {
        $this->validate($request, [
            'lead_id'  => 'exists:leads,id',
            'visit_at' => 'date',
            'check_in_at' => 'nullable|date',
            'check_out_at' => 'nullable|date',
        ]);

        $visit = $this->salesVisitRepository->update($request->all(), $id);

        return (new SalesVisitResource($visit))
            ->additional(['meta' => ['message' => 'Sales visit updated successfully']]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->salesVisitRepository->delete($id);

            return response()->json($this->jsonApiMessage('Sales visit deleted successfully'));
        } catch (\Exception $exception) {
            return response()->json($this->jsonApiError('Delete Failed', $exception->getMessage(), 500), 500);
        }
    }
}
