<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="{{asset('web/images/favicon.png')}}" type="image/x-png" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('web/plugins/font-awesome-4.6.2/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/bootstrap-select.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/bootstrap-custom-theme.css')}}">

    <link rel="stylesheet" href="{{asset('web/plugins/parsley/css/parsley.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/dentaIcon.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/dashboard.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/bootstrap-multiselect.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/checkBox.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/dentaIcon.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('web/plugins/calender/css/fullcalendar.css')}}">
    <link rel="stylesheet" href="{{asset('web/plugins/custom-scroller/css/mCustomScrollbar.min.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('web/css/editor.css')}}">
    <link rel="stylesheet" href="{{asset('web/plugins/rating/tipi.css')}}">
    <script src="{{asset('web/scripts/jQuery-2.2.0.min.js')}}"></script>
    <script src="{{asset('web/plugins/rating/tipi.jquery.js')}}"></script>
    <script src="{{asset('web/plugins/rating/rating.jquery.js')}}"></script>
    <script src="{{asset('web/scripts/editor.js')}}"></script>
    @yield('css')

    <title>DentaMatch| Home</title>
</head>

<body>
    <?php $notificationList = \App\Helpers\NotificationHelper::topNotificationList(Auth::user()->id); ?>
    <nav class="customNav navbar navbar-default navbar-fixed-top">

        <div class="container pos-rel">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img src="{{asset('web/images/dentaMatchLogo.png')}}" alt=""></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <ul class=" topIconBox navbar-right customnavRight">
                <li>
                    <a href="{{ url('chat') }}"><span class="icon icon-message"></span></a>
                </li>
                <li class="notificaionbell dropdown">
                    @if(!empty($notificationList['total']))
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="icon icon-bell "></span>
                        @if(!empty($notificationList['total']))
                        <div class="notificationCircle "> {{ $notificationList['total'] }}</div>
                        @endif
                    </a>
                    @else
                    <a href="{{ url('notification-lists') }}" ><span class="icon icon-bell "></span></a>
                    @endif
                    <div class="dropdown-menu noficationListContainer small-border-radius box-shadow">

                        <span class="fa fa-caret-up notificationCaret"></span>
                        <ul class="notificationList">
                            @if(!empty($notificationList['data']))
                            @foreach($notificationList['data'] as $notification)
                            <?php $data = json_decode($notification->notification_data); ?>
                            <li>
                                <p ><strong><?php echo strip_tags($data->message); ?></strong></p>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                                <a href="{{ url($notification->id.'/delete-notification') }}"><i class="icon icon-deleteicon notificationdelIcon"></i></a>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                        <a href="{{ url('notification-lists') }}" class="notificationSeeAll text-center">See All</a>

                    </div>

                </li>
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Session::get('userData.profile.office_name') }} <span class="caret"></span></a>
                    <ul class="dropdown-menu menuLastBox borderNone">
                        
                        <span class="fa fa-caret-up notificationCaret"></span>
                        <li><span class="icon icon-account-circle navRightIcon"></span><a href="{{ url('edit-profile') }}"><b>{{Auth::user()->email}}</b><br>	<button type="button" class="btn btn-primary rghtMenuBtn pd-l-10 pd-r-10">View Profile</button></a>

                        </li>
                        <li><span class="icon icon-drive-document navRightIcon"></span><a href="{{ url('reports') }}">Reports</a></li>
                        <li><span class="icon icon-lock navRightIcon"><a href="#"></a></span><a href="{{ url('change-password') }}">Change Password</a></li>
                        <li><span class="icon icon-drive-form navRightIcon"></span><a href="{{ url('setting-subscription') }}">Subscription Details</a></li>
                        <li><span class="icon icon-text-document-black-interface-symbol navRightIcon"></span>
                         <a href="{{ url('setting-terms-conditions') }}">Terms &amp; Conditions</a></li>
                         <li><span class="icon icon-logout-web-button navRightIcon"></span><a href="{{ url('logout') }}">Logout</a></li>

                     </ul>
                 </li>
             </ul>
             @php 
             $navActive = isset($navActive)?$navActive:'';
             @endphp
             <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="{{ ($navActive=='calendar')?'active':''}}">
                        <a href="{{ url('calender') }}">Calendar</a>
                    </li>
                    <li class="{{ ($navActive=='joblisting')?'active':''}}">
                        <a href="{{ url('job/lists') }}">Job Listing</a>
                    </li>
                    <li class="{{ ($navActive=='favseeker')?'active':''}}">
                        <a href="{{url('favorite-jobseeker')}}">Favourite Jobseeker</a>
                    </li>
                    <li class="{{ ($navActive=='template')?'active':''}}">
                        <a href="{{ url('jobtemplates') }}">Template</a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
            @if(!empty($jobTemplateModalData))
            <a class="modalClick add-on-nav" data-toggle="modal" data-target="#jobTemplate" >+</a>
            @endif
        </div><!-- /.container-fluid -->
    </nav>
    @yield('content')

    <!-- Bootstrap 3.3.6 -->
    <script src="{{asset('web/scripts/bootstrap.min.js')}}"></script>
    <script src="{{asset('web/scripts/bootstrap-select.js')}}"></script>
    <script src="{{asset('web/scripts/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('web/scripts/bootstrap-multiselect.js')}}"></script>
    <script src ="{{asset('web/plugins/range-slider/js/bootstrap-slider.js')}}"></script>

    <script src="{{asset('web/plugins/parsley/js/parsley.js')}}"></script>
    <script src="{{asset('web/scripts/custom.js')}}"></script>
    <script src ="{{asset('web/scripts/web.js')}}"></script>
    <script src ="{{asset('web/scripts/moment.min.js')}}"></script>
    <script src ="{{asset('web/scripts/bootstrap-datetimepicker.js')}}"></script>
    <script src ="{{asset('web/scripts/main.js')}}"></script>


<!-- <script src ="{{asset('web/scripts/tabScript1.js')}}"></script>
-->

<script type="text/javascript" src="{{asset('web/scripts/knockout-3.4.1.js')}}"></script>
<script src ="{{asset('web/plugins/custom-scroller/js/mCustomScrollbar.js')}}"></script>
<script src="{{asset('web/plugins/calender/js/fullcalendar.js')}}"></script>
<script src="{{asset('web/plugins/inputmask/dist/jquery.inputmask.bundle.js')}}"></script>
@yield('js')

@if(!empty($jobTemplateModalData))
@include('web.recuriterJob.job-template-modal')
@endif
</body>

</html>
