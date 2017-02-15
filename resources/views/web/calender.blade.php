@extends('web.layouts.dashboard')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container" id="calenderParent">
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

    <div id="calendar" class="dev_calender_div"></div>

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
                            <div class="job-title" data-bind="text: particularJobTitle"></div>
                            <h5 data-bind="text: particularOfficeName"></h5>
                            <span data-bind="text: particularOfficeTypeName"></span>
                            <p data-bind="text: particularOfficeAddress"></p>
                        </div>
                        <div class="col-xs-4">
                            <button type="button" class="btn btn-primary pull-right">View Detail</button>
                        </div>
                    </div>
                    <div class="cal-hired-seeker mr-t-20">
                        Hired Jobseeker
                    </div>
                    <div class="row">
                        <!--ko foreach: seekersOfParticularJob-->
                        <div class="col-xs-12 col-sm-6">
                            <a href="#" class="">
                                <div class="media">
                                    <div class="media-left ">
                                        <img src="http://placehold.it/28x28" onerror="this.src = 'http://placehold.it/28x28'" data-bind="attr: {src: seekerPic}" class="img-circle cir-55">
                                    </div>
                                    <div class="media-body pd-t-5">
                                        <h4 class="media-heading" data-bind="text: seekerName">Paula Jackson</h4>
                                        <p data-bind="text: seekerJobTitle"></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <br>
                        <!--/ko-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>

</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var JobModel = function (data) {
        var me = this;
        me.title = ko.observable('');
        me.start = ko.observable('');
        me.officeTypeName = ko.observable('');
        me.userDetails = ko.observableArray([]);
        me.officeAddress = ko.observable('');
        me.officeName = ko.observable('');

        me._init = function (d) {
            me.title = d.jobtitle_name;
            me.start = moment(d.created_at).format('YYYY-MM-DD');
            me.officeTypeName = d.office_type_name;
            me.officeAddress = d.address;
            me.officeName = d.office_name;
            for (i in d.seekers) {
                for (j in d.seekers[i]) {
                    d.seekers[i][j].pic = d.seekers[i][j].profile_pic;
                    d.seekers[i][j].name = d.seekers[i][j].first_name + ' ' + d.seekers[i][j].last_name;
                }
                me.userDetails.push(d.seekers[i]);
            }
        };
        me._init(data);
    };

    var SeekersModel = function (data) {
        var me = this;
        me.seekerJobTitle = ko.observable('');
        me.seekerPic = ko.observable('');
        me.seekerName = ko.observable('');
        me.seekerId = ko.observable();

        me._init = function (d) {
            me.seekerJobTitle = d.jobtitle_name;
            me.seekerPic = d.profile_pic;
            me.seekerName = d.first_name + ' ' + d.last_name;
            me.seekerId = d.seeker_id;
        };
        me._init(data);
    };

    var CalenderVM = function (data) {
        var me = this;
        me.datesData = ko.observableArray([]);
        me.seekersOfParticularJob = ko.observableArray([]);
        me.particularJobTitle = ko.observable();
        me.particularOfficeName = ko.observable();
        me.particularOfficeTypeName = ko.observable();
        me.particularOfficeAddress = ko.observable();

        me.getCalenderDetails = function () {
            $.get('calender-details', {}, function (d) {
                if (d.jobs.length !== 0 || typeof d.jobs !== "undefined") {
                    for (i in d.jobs)
                        me.datesData.push(new JobModel(d.jobs[i]));
                }
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
                        me.showSeekers(event);
                    },
                    eventRender: function (event, element, view) {
                        if (event.userDetails().length == 0) {
                            userDetails = event.userDetails();
                        } else {
                            userDetails = event.userDetails()[0];
                        }
                        for (var i = 0; i <= userDetails.length - 1; i++) {
                            if (i < 2) {
                                $(element).find('span.fc-title').after('<img class="img-circle mr-r-2" src="' + userDetails[i].pic + '" />');
                            }

                        }
                        ;
                        if (userDetails.length > 2) {
                            $(element).find('.fc-content').append('<span class="cir-22">' + (userDetails.length - 2) + "+" + '<span>');
                        }

                        var dateString = event.start.format("YYYY-MM-DD");

                        $(view.el[0]).find('.fc-day[data-date=' + dateString + ']').addClass('eventdays');


                    },
                    events: me.datesData()

                });
            });
        };

        me.showSeekers = function (d, e) {
            me.particularJobTitle('');
            me.particularOfficeName('');
            me.particularOfficeTypeName('');
            me.particularOfficeAddress('');
            me.seekersOfParticularJob([]);

            me.particularJobTitle(d.title);
            me.particularOfficeName(d.officeName);
            me.particularOfficeTypeName(d.officeTypeName);
            me.particularOfficeAddress(d.officeAddress);
            if (d.userDetails().length !== 0) {
                for (i in d.userDetails()[0]) {
                    me.seekersOfParticularJob.push(new SeekersModel(d.userDetails()[0][i]));
                }
            }
            $('.calendar_brief').modal();
        };

        me._init = function () {
            me.getCalenderDetails();
        };
        me._init();
    };
    var ssObj = new CalenderVM();
    ko.applyBindings(ssObj, $('#calenderParent')[0])
</script>
@endsection