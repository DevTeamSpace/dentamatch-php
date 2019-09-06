<?php

namespace App\Http\Controllers\Cms;

use App\Enums\ActionCategory;
use App\Enums\ActionType;
use App\Helpers\WebResponse;
use App\Models\ActionLog;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\HtmlString;
use Yajra\Datatables\Datatables;
use Facades\App\Transformers\ActivityTransformer;

class ActivityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('cms');
    }

    /**
     * List all areas.
     *
     * @return Response
     */
    protected function index()
    {
        return view('cms.activity.index');
    }

    /**


    /**
     * Method to get list of activities
     * @return Response
     * @throws \Exception
     */
    public function activitiesList()
    {
        $query = ActionLog::query()
            ->with(['user:id,email'])
            ->with(['job.jobTemplate.jobTitle:id,jobtitle_name'])
            ->latest();
        return Datatables::of($query)
            ->editColumn('category', function($entry) {return ActionCategory::ToString($entry->category);})
            ->editColumn('type', function($entry) {return ActionType::ToString($entry->type);})
            ->editColumn('user', function($entry) {return $entry->user->email;})
            ->addColumn('jobTitle', function($entry) {return object_get($entry, 'job.jobTemplate.jobTitle.jobtitle_name');})
            ->editColumn('request_data', function($entry) {
                if (!$entry)
                    return '';
                $parsed = json_decode($entry->request_data, true);
                if (!$parsed) return '';
                $data = [];
                foreach ($parsed as $name => $value) {
                    $data[] = "$name: " . (is_array($value)? implode(', ', $value) : $value);
                }
                $dataStr = implode("\r\n", $data);
                return new HtmlString("<pre>$dataStr</pre>");
            })
            ->make(true);
    }

    /**
     * GET /cms/jobseeker/csvJobseeker
     */
    public function csvActivities(){
        $list = ActionLog::query()
            ->with(['user:id,email'])
            ->with(['job.jobTemplate.jobTitle:id,jobtitle_name'])
            ->latest()->get();

        $fields = ['category', 'type', 'user', 'job_title', 'date', 'data'];

        $data = ActivityTransformer::transformAll($list, $fields);

        return WebResponse::csvResponse($data, $fields, 'activities');
    }

}
