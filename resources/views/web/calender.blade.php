@extends('web.layouts.dashboard')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container">
    <!-- Modal -->
    <div class="modal fade calendar_list" role="dialog">
        <div class="modal-dialog custom-modal popup-wd522">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">November 02, 2016</h4>
                </div>
                <div class="modal-body content mCustomScrollbar light" data-mcs-theme="minimal-dark">
                    <div class="panel ">
                        <a href=".calendar_brief" data-toggle="modal" data-dismiss="modal" class="panel-body">
                            <div class="calender-list-title">
                                Dental Hygienist
                            </div>
                            <div class="seeker-list">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                            </div>
                        </a>
                    </div>
                    <div class="panel ">
                        <a href="#" class="panel-body">
                            <div class="calender-list-title">
                                Dental Hygienist
                            </div>
                            <div class="seeker-list">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                            </div>
                        </a>
                    </div>
                    <div class="panel ">
                        <a href="#" class="panel-body">
                            <div class="calender-list-title">
                                Office Staff
                            </div>
                            <div class="seeker-list">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                            </div>
                        </a>
                    </div>
                    <div class="panel ">
                        <a href="#" class="panel-body">
                            <div class="calender-list-title">
                                Office Staff
                            </div>
                            <div class="seeker-list">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                            </div>
                        </a>
                    </div>
                    <div class="panel ">
                        <a href="#" class="panel-body">
                            <div class="calender-list-title">
                                Office Staff
                            </div>
                            <div class="seeker-list">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                            </div>
                        </a>
                    </div>
                    <div class="panel ">
                        <a href="#" class="panel-body">
                            <div class="calender-list-title">
                                Office Staff
                            </div>
                            <div class="seeker-list">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                            </div>
                        </a>
                    </div>
                    <div class="panel ">
                        <a href="#" class="panel-body">
                            <div class="calender-list-title">
                                Office Staff
                            </div>
                            <div class="seeker-list">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                            </div>
                        </a>
                    </div>
                    <div class="panel ">
                        <a href="#" class="panel-body">
                            <div class="calender-list-title">
                                Office Staff
                            </div>
                            <div class="seeker-list">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                            </div>
                        </a>
                    </div>
                    <div class="panel ">
                        <a href="#" class="panel-body">
                            <div class="calender-list-title">
                                Office Staff
                            </div>
                            <div class="seeker-list">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                            </div>
                        </a>
                    </div>
                    <div class="panel ">
                        <a href="#" class="panel-body">
                            <div class="calender-list-title">
                                Office Staff
                            </div>
                            <div class="seeker-list">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                                <img src="http://placehold.it/28x28" class="img-circle cir-28">
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade calendar_brief" role="dialog">
    <div class="modal-dialog custom-modal popup-wd522">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">November 02, 2016</h4>
            </div>
            <div class="modal-body content mCustomScrollbar light
                 " data-mcs-theme="minimal-dark">
                <div class="row">
                    <div class="col-xs-8 cal-detail-address">
                        <div class="job-title">Dental Hygienist</div>
                        <h5>Smiley Care</h5>
                        <span>General Dentistlinic</span>
                        <p>490 6th Avenue, San Francisco, CA 94118</p>
                    </div>
                    <div class="col-xs-4">
                        <button type="button" class="btn btn-primary pull-right">View Detail</button>
                    </div>
                </div>
                <div class="cal-hired-seeker mr-t-20">
                    Hired Jobseeker
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <a href="#" class="">
                            <div class="media">
                                <div class="media-left ">
                                    <img src="http://placehold.it/28x28" class="img-circle cir-55">
                                </div>
                                <div class="media-body pd-t-5">
                                    <h4 class="media-heading">Paula Jackson</h4>
                                    <p>Dental Assistant</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <a href="#" class="">
                            <div class="media">
                                <div class="media-left ">
                                    <img src="http://placehold.it/28x28" class="img-circle cir-55">
                                </div>
                                <div class="media-body pd-t-5">
                                    <h4 class="media-heading">Sylvia Lawrence</h4>
                                    <p>Dental Assistant</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <a href="#" class="">
                            <div class="media">
                                <div class="media-left ">
                                    <img src="http://placehold.it/28x28" class="img-circle cir-55">
                                </div>
                                <div class="media-body pd-t-5">
                                    <h4 class="media-heading">Suzanne Holroyd</h4>
                                    <p>Dental Assistant</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <a href="#" class="">
                            <div class="media">
                                <div class="media-left ">
                                    <img src="http://placehold.it/28x28" class="img-circle cir-55">
                                </div>
                                <div class="media-body pd-t-5">
                                    <h4 class="media-heading">Paula Jackson</h4>
                                    <p>Dental Assistant</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <a href="#" class="">
                            <div class="media">
                                <div class="media-left ">
                                    <img src="http://placehold.it/28x28" class="img-circle cir-55">
                                </div>
                                <div class="media-body pd-t-5">
                                    <h4 class="media-heading">Sylvia Lawrence</h4>
                                    <p>Dental Assistant</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <a href="#" class="">
                            <div class="media">
                                <div class="media-left ">
                                    <img src="http://placehold.it/28x28" class="img-circle cir-55">
                                </div>
                                <div class="media-body pd-t-5">
                                    <h4 class="media-heading">Suzanne Holroyd</h4>
                                    <p>Dental Assistant</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <a href="#" class="">
                            <div class="media">
                                <div class="media-left ">
                                    <img src="http://placehold.it/28x28" class="img-circle cir-55">
                                </div>
                                <div class="media-body pd-t-5">
                                    <h4 class="media-heading">Paula Jackson</h4>
                                    <p>Dental Assistant</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <a href="#" class="">
                            <div class="media">
                                <div class="media-left ">
                                    <img src="http://placehold.it/28x28" class="img-circle cir-55">
                                </div>
                                <div class="media-body pd-t-5">
                                    <h4 class="media-heading">Sylvia Lawrence</h4>
                                    <p>Dental Assistant</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <a href="#" class="">
                            <div class="media">
                                <div class="media-left ">
                                    <img src="http://placehold.it/28x28" class="img-circle cir-55">
                                </div>
                                <div class="media-body pd-t-5">
                                    <h4 class="media-heading">Suzanne Holroyd</h4>
                                    <p>Dental Assistant</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="calendar"></div>
@endsection
@section('js')
<script>
    $('#calendar').fullCalendar({
        header: {
            left: 'prev',
            center: 'title',
            right: 'next'
        },
        defaultView: "month",
        editable: false,
        firstDay: 1,
        columnFormat: 'dddd',
        displayEventTime: false,
        eventLimit: 2,
        eventLimitText: "more jobs >",
        eventLimitClick: function () {
            $('.calendar_list').modal();
        },
        eventClick: function (event, jsEvent, view) {
            // $('#modalTitle').html(event.title); // $('#modalBody').html(event.description); // $('#eventUrl').attr('href', event.url);

            $('.calendar_brief').modal();
        },
        eventRender: function (event, element, view) {

            for (var i = 0; i <= event.userDetails.length - 1; i++) {

                if (i < 2) {
                    $(element).find('span.fc-title').after('<img class="img-circle mr-r-2" src="' + event.userDetails[i].pic + '" />');
                }

            }
            ;
            if (event.userDetails.length > 2) {
                $(element).find('.fc-content').append('<span class="cir-22">' + (event.userDetails.length - 2) + "+" + '<span>');
            }

            var dateString = event.start.format("YYYY-MM-DD");

            $(view.el[0]).find('.fc-day[data-date=' + dateString + ']').addClass('eventdays');


        },
        events: [{
                title: 'EventName',
                start: '2017-02-02',
                userDetails: [{
                        pic: 'http://placehold.it/22x22',
                        name: 'a'
                    }, {
                        pic: 'http://placehold.it/22x22',
                        name: 'b'
                    }, {
                        pic: 'http://placehold.it/22x22',
                        name: 'c'
                    }]
            }, {
                title: 'Event',
                start: '2017-02-13',
                userDetails: [{
                        pic: 'http://placehold.it/22x22',
                        name: 'a'
                    }, {
                        pic: 'http://placehold.it/22x22',
                        name: 'b'
                    }, {
                        pic: 'http://placehold.it/22x22',
                        name: 'c'
                    }, {
                        pic: 'http://placehold.it/22x22',
                        name: 'd'
                    }, {
                        pic: 'http://placehold.it/22x22',
                        name: 'd'
                    }, {
                        pic: 'http://placehold.it/22x22',
                        name: 'e'
                    }]
            }, {
                title: 'Event1',
                start: '2017-02-13',
                userDetails: [{
                        pic: 'http://placehold.it/22x22',
                        name: 'a'
                    }, {
                        pic: 'http://placehold.it/22x22',
                        name: 'b'
                    }, {
                        pic: 'http://placehold.it/22x22',
                        name: 'c'
                    }, {
                        pic: 'http://placehold.it/22x22',
                        name: 'd'
                    }]
            }, {
                title: 'Event2',
                start: '2017-02-13',
                userDetails: [{
                        pic: 'http://placehold.it/22x22',
                        name: 'a'
                    }]

            }, {
                title: 'Eventname',
                start: '2017-02-14',
                userDetails: [{
                        pic: 'http://placehold.it/22x22',
                        name: 'a'
                    }]

            }]

    });
</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var CalenderVM = function (data) {
        var me = this;
        me.getCalenderDetails = function () {
            $.get('calender-details', {}, function(d){
               console.log(d); 
            });
        };
        me._init = function () {
            me.getCalenderDetails();
        };
        me._init();
    };
    var ssObj = new CalenderVM();
    ko.applyBindings(ssObj, $('#subscription')[0])
</script>
@endsection