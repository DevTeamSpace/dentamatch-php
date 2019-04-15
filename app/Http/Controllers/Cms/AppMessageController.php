<?php

namespace App\Http\Controllers\cms;

use App\Helpers\WebResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\AppMessage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Yajra\Datatables\Datatables;
use App\Jobs\AppMessageJob;

class AppMessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('cms');
    }

    /**
     * Show the form to create a new appMessage.
     *
     * @return Response
     */
    public function create()
    {
        return view('cms.appMessage.create');
    }

    /**
     * List all app-messages.
     *
     * @return Response
     */
    protected function index()
    {
        return view('cms.appMessage.index');
    }

    /**
     * Show the form to update an existing appMessage.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        $appMessage = AppMessage::findOrFail($id);
        return view('cms.appMessage.update', ['appMessage' => $appMessage]);
    }

    /**
     * Store a new/update appMessage.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'message' => ['required', 'min:2', 'max:150'],
        ];

        $this->validate($request, $rules);

        $appMessage = $request->id ? AppMessage::find($request->id) : new AppMessage;
        $msg = $request->id ? trans('messages.app_message_updated') : trans('messages.app_message_added');

        $appMessage->message = $request->message;
        $appMessage->message_to = $request->message_to;
        $appMessage->message_sent = isset($request->message_sent) ? 1 : 0;
        $appMessage->save();

        Session::flash('message', $msg);
        return redirect('cms/notify/index');
    }

    /**
     * Send notification to users.
     *
     * @param  int $id
     * @return Response
     */
    public function sendNotification($id)
    {
        $appMessage = AppMessage::findOrFail($id);

        if (!$appMessage->message_sent) {
            $appMessage->message_sent = 1;
            $appMessage->cron_message_sent = 0;
            $appMessage->save();
            $this->dispatch(new AppMessageJob($appMessage));
        } else {
            Session::flash('message', trans('messages.already_sent_message'));
        }

        return redirect('cms/notify/index');
    }

    /**
     * Soft delete a appMessage.
     *
     * @param  int $id
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function delete($id)
    {
        AppMessage::findOrFail($id)->delete();
        return WebResponse::successResponse(trans('messages.app_message_deleted'));
    }

    /**
     * Method to get list of admin scheduled messages
     * @return Response
     * @throws \Exception
     */
    public function messageList()
    {
        $appMessages = AppMessage::SELECT(['message', 'message_to', 'message_sent', 'created_at', 'id'])->orderBy('created_at', SORT_DESC);
        return Datatables::of($appMessages)->make(true);
    }
}
