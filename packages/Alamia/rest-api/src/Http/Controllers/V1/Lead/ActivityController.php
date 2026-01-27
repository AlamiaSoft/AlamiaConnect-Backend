<?php

namespace Alamia\RestApi\Http\Controllers\V1\Lead;

use Illuminate\Support\Facades\DB;
use Webkul\Activity\Repositories\ActivityRepository;
use Webkul\Email\Repositories\AttachmentRepository;
use Webkul\Email\Repositories\EmailRepository;
use Alamia\RestApi\Http\Controllers\V1\Controller;
use Alamia\RestApi\Http\Resources\V1\Activity\ActivityResource;

class ActivityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ActivityRepository $activityRepository,
        protected EmailRepository $emailRepository,
        protected AttachmentRepository $attachmentRepository,
        protected \Alamia\RestApi\Repositories\SalesVisitRepository $salesVisitRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $activities = $this->activityRepository
            ->leftJoin('lead_activities', 'activities.id', '=', 'lead_activities.activity_id')
            ->where('lead_activities.lead_id', $id)
            ->get();
        
        $merged = $this->concatEmailAsActivities($id, $activities);
        $merged = $this->concatSalesVisits($id, $merged);

        return ActivityResource::collection($merged->sortByDesc('created_at'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function concatEmailAsActivities($leadId, $activities)
    {
        $emails = DB::table('emails as child')
            ->select('child.*')
            ->join('emails as parent', 'child.parent_id', '=', 'parent.id')
            ->where('parent.lead_id', $leadId)
            ->union(DB::table('emails as parent')->where('parent.lead_id', $leadId))
            ->get();

        return $activities->concat($emails->map(function ($email) {
            return (object) [
                'id'            => $email->id,
                'parent_id'     => $email->parent_id,
                'title'         => $email->subject,
                'type'          => 'email',
                'is_done'       => 1,
                'comment'       => $email->reply,
                'schedule_from' => null,
                'schedule_to'   => null,
                'user'          => auth()->guard('user')->user(),
                'participants'  => [],
                'location'      => null,
                'additional'    => [
                    'folders' => json_decode($email->folders),
                    // 'from'    => json_decode($email->from),
                    // 'to'      => json_decode($email->reply_to),
                    // 'cc'      => json_decode($email->cc),
                    // 'bcc'     => json_decode($email->bcc),
                ],
                'files'         => $this->attachmentRepository->findWhere(['email_id' => $email->id])->map(function ($attachment) {
                    return (object) [
                        'id'         => $attachment->id,
                        'name'       => $attachment->name,
                        'path'       => $attachment->path,
                        'created_at' => $attachment->created_at,
                        'updated_at' => $attachment->updated_at,
                    ];
                }),
                'created_at'    => $email->created_at,
                'updated_at'    => $email->updated_at,
            ];
        }));
    }

    public function concatSalesVisits($leadId, $collection)
    {
        $visits = $this->salesVisitRepository->findWhere(['lead_id' => $leadId]);

        return $collection->concat($visits->map(function ($visit) {
            $user = $visit->user;
            return (object) [
                'id'            => 'visit_' . $visit->id,
                'title'         => 'Sales Visit: ' . ($visit->outcome ?? 'Scheduled'),
                'type'          => 'meeting', // Maps to Calendar icon usually, or Use 'visit' if supported
                'is_done'       => in_array($visit->outcome, ['Completed', 'Rescheduled', 'Cancelled']),
                'comment'       => $visit->notes,
                'schedule_from' => $visit->visit_at,
                'schedule_to'   => $visit->visit_at, // Assuming instantaneous or blocked 1h
                'user'          => $user ? (object)['id' => $user->id, 'name' => $user->name] : null,
                'created_at'    => $visit->created_at,
                'updated_at'    => $visit->updated_at,
                // Add specific visit data to 'additional' if needed
            ];
        }));
    }
}

