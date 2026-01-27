<?php

namespace Alamia\RestApi\Http\Controllers\V1\Attendance;

use Alamia\RestApi\Http\Controllers\V1\Controller;
use Alamia\RestApi\Http\Resources\V1\Attendance\AttendanceResource;
use Alamia\RestApi\Repositories\AttendanceRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

class AttendanceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected AttendanceRepository $attendanceRepository
    ) {}

    /**
     * @OA\Get(
     *      path="/api/v1/attendance",
     *      operationId="attendanceIndex",
     *      tags={"Attendance"},
     *      summary="Get list of attendance logs",
     *      description="Returns list of attendance logs",
     *      security={{"sanctum_admin": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     * )
     */
    public function index(): JsonResource
    {
        $query = $this->attendanceRepository->query();

        if ($includes = request()->input('include')) {
            $query->with(explode(',', $includes));
        }

        foreach (request()->except(array_merge($this->excludeKeys, ['include'])) as $input => $value) {
            $query->whereIn($input, array_map('trim', explode(',', $value)));
        }

        if ($sort = request()->input('sort')) {
            $query->orderBy($sort, request()->input('order') ?? 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $attendances = $query->paginate(request()->input('limit') ?? 10);

        return AttendanceResource::collection($attendances);
    }

    /**
     * Check In user.
     */
    public function checkIn(Request $request): JsonResource
    {
        $this->validate($request, [
            'gps_lat' => 'nullable|numeric',
            'gps_lng' => 'nullable|numeric',
            'photo_url' => 'nullable|string',
        ]);

        // Check if user has already checked in today and not checked out? 
        // Optional business rule. Skipping for verified "simple" implementation.
        
        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['check_in'] = Carbon::now();

        $attendance = $this->attendanceRepository->create($data);

        return (new AttendanceResource($attendance))
            ->additional(['meta' => ['message' => 'Checked in successfully']]);
    }

    /**
     * Check Out user.
     */
    public function checkOut(Request $request)
    {
        $userId = auth()->id();
        
        // Find latest active check-in for user
        $attendance = $this->attendanceRepository->scopeQuery(function($query) use ($userId) {
            return $query->where('user_id', $userId)
                         ->whereNull('check_out')
                         ->orderBy('check_in', 'desc');
        })->first();

        if (!$attendance) {
            return response()->json($this->jsonApiError('Check-out Failed', 'No active check-in found.', 404), 404);
        }

        $data = $request->only(['gps_lat', 'gps_lng']); // Usually checkout location is also tracked
        $data['check_out'] = Carbon::now();

        $updatedAttendance = $this->attendanceRepository->update($data, $attendance->id);

        return (new AttendanceResource($updatedAttendance))
             ->additional(['meta' => ['message' => 'Checked out successfully']]);
    }
}
