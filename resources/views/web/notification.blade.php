@extends('web.layouts.dashboard')

@section('content')
<div class="container globalpadder">
    <h3>Notifications</h3>
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif
    <div class="viewdentaltemplate cardShadow borderNone notificationPadder">
        @if(count($notificationList))
        <ul class="notificationListContainer">
            @foreach ($notificationList as $notification)
            @php 
            $notificationDetails = json_decode($notification->notification_data);
            @endphp
            <li>
                @if($notification->seen == 0)
                <div class="onlineDot border-radius"></div>
                @endif
                <p class="deleteCard pull-right notificationDel"><span class="icon icon-deleteicon 
                    mr-r-5"></span><a href="{{ url($notification->id.'/delete-notification') }}">Delete</a></p>
                    <div class="media notificationList">
                        <div class="media-left ">
                            
                            <img class="media-object img-circle cir-36" src="{{ $notificationDetails->image }}" width="80" height="80" alt="...">
                            
                        </div>
                        <div class="media-body">
                            <h6 class="media-heading"><p><?php 
                                echo  $notificationDetails->message; ?></p></h6>
                                @if($notification->notification_type == App\Models\Notification::JOBSEEKERCANCELLED)
                                <p class="mr-b-5 mr-t-5 notify-desc"><?php  echo  $notificationDetails->cancel_reason; ?></p>
                                @endif
                                <p class="justNow"><span class="icon-clock"></span>{{ $notification->created_at->diffForHumans()}}</p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                {{ $notificationList->links() }}
                @else
                <ul class="notificationListContainer">
                   <li>
                    <h4>No notifications to show</h4>
                </li>
            </ul>
            @endif
        </div>
    </div>
    @endsection



