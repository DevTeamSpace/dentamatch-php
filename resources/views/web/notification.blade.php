@extends('web.layouts.dashboard')

@section('content')
<div class="container globalpadder">
        <h3>Notifications</h3>
        <div class="viewdentaltemplate cardShadow borderNone notificationPadder">
            <ul class="notificationListContainer">
                @foreach ($notificationList as $notification)
                @php 
                    $notificationDetails = json_decode($notification->notification_data);
                @endphp
                <li>
                    <div class="onlineDot border-radius"></div>
                    <p class="deleteCard pull-right notificationDel"><span class="icon icon-deleteicon "></span><a href="">Delete</a></p>
                    <div class="media notificationList">
                        <div class="media-left ">
                            <a href="#">
                                <img class="media-object img-circle" src="{{ $notificationDetails->image }}" width="80" height="80" alt="...">
                            </a>
                        </div>
                        <div class="media-body">
                            <h6 class="media-heading"><p>{{ $notificationDetails->message }}</p></h6>
                            <p class="justNow"><span class="icon-clock"></span>{{ $notification->created_at->diffForHumans()}}</p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
