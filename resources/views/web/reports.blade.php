@extends('web.layouts.dashboard')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container globalpadder">
    <!-- Tab-->
    <div class="row" id="report">
        @include('web.layouts.sidebar')
        <div class="col-sm-8 ">
            <div class="addReplica">
                <div class="resp-tabs-container commonBox pd-0 cboxbottom ">
                    <div class="descriptionBox">
                        <form autocomplete="off" data-parsley-validate>
                            <div class="viewProfileRightCard">
                                <div class="detailTitleBlock profilePadding">
                                    <div class="frm-title mr-b-25">Reports</div>
                                    <div class="col-sm-3 pull-right mr-b-10">
                                        <div class="form-group">
                                            <div class='input-group date' id='datetimepicker7'>
                                                <input type='text' class="form-control" placeholder="To" data-bind="datetimePicker: {optA:'aa'}, value: filterTo, event: {update: $root.filterList}" />
                                                <span class="input-group-addon">
                                                    <span class="fa fa-angle-down"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 pull-right mr-b-10">
                                        <div class="form-group">
                                            <div class='input-group date' id='datetimepicker6'>
                                                <input type='text' class="form-control" placeholder="From" data-bind="datetimePicker: {optA:'bb'}, value: filterFrom" />
                                                <span class="input-group-addon">
                                                    <span class="fa fa-angle-down"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <label>Filter:</label>
                                    </div>
                                </div>
                                <table id="report-table" class="table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>No. of Jobs</th>
                                            <th>Date</th>
                                            <th>Hired</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--ko foreach: filterJobList-->
                                        <tr>
                                            <td><strong data-bind="text: jobTitle"></strong></td>
                                            <td data-bind="text: noOfJobs"></td>
                                            <td>
                                                <!--ko foreach: jobs-->
                                                <p data-bind="text: jobDate"></p>
                                                <!--/ko-->
                                            </td>
                                            <td>
                                                <!--ko foreach: jobs-->
                                                <ul class="list-hired">
                                                    <!--ko foreach: jobSeekers-->
                                                    <!--ko if: $index() <= 1-->
                                                    <li>
                                                        <a href="#" data-toggle="tooltip" data-placement="bottom" data-bind="attr: {title: seekerName, 'href': seekerUrl}, event: {mouseover: $root.showToolTip}">
                                                            <img class="cir-28 img-circle" src="http://placehold.it/28x28" onerror="this.src = 'http://placehold.it/28x28'" data-bind="attr: {src: seekerProfilePic}">
                                                        </a>
                                                    </li>
                                                    <!--/ko-->
                                                    <!--/ko-->
                                                    <!--ko if: extraJobSeekers !== undefined-->
                                                    <span data-bind="text: extraJobSeekers, click: $root.showHiredJobSeekers"></span>
                                                    <!--/ko-->
                                                </ul>
                                                <!--/ko-->
                                            </td>
                                        </tr>
                                        <!--/ko-->
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade reportsModal" role="dialog">
            <div class="modal-dialog custom-modal popup-wd522">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" data-bind="text: modalJobDate"></h4>
                    </div>
                    <div class="modal-body content mCustomScrollbar light" data-mcs-theme="minimal-dark">
                        <div class="cal-hired-seeker mr-t-20">
                            Hired Jobseeker
                        </div>
                        <div class="row flex-row">
                            <!--ko foreach: modalSeekers-->
                            <div class="col-xs-12 col-sm-6">
                                <a class="" data-bind="attr: {href: seekerUrl}">
                                    <div class="media">
                                        <div class="media-left ">
                                            <img src="http://placehold.it/28x28" onerror="this.src = 'http://placehold.it/28x28'" data-bind="attr: {src: seekerProfilePic}" class="img-circle cir-55">
                                        </div>
                                        <div class="media-body pd-t-5">
                                            <h4 class="media-heading" data-bind="text: seekerName">Paula Jackson</h4>
                                            <p data-bind="text: seekerJobTitle"></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!--/ko-->
                        </div>
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

    var SeekerModel = function (d) {
        var me = this;
        me.seekerId = ko.observable();
        me.seekerName = ko.observable('');
        me.seekerProfilePic = ko.observable('');
        me.seekerUrl = ko.observable('');
        me.seekerJobTitle = ko.observable('');
        
        me._init = function (d) {
            me.seekerId(d.seeker_id);
            me.seekerName(d.first_name + " " + d.last_name);
            me.seekerProfilePic(d.profile_pic);
            me.seekerJobTitle(d.jobtitle_name);
            me.seekerUrl('');
        };

        me._init(d);
    };

    var JobModel = function (d) {
        var me = this;
        me.jobDate = ko.observable();
        me.jobSeekers = ko.observableArray([]);
        me.extraJobSeekers = ko.observable('');
        me.jobId = ko.observable();

        me._init = function (d) {
            if (typeof d == "undefined") {
                return false;
            }
            me.jobDate(moment(d.job_created_at).format('ll'));
            me.jobId(d.recruiter_job_id);
            for (i in d.seekers) {
                if (d.seekers[i].length > 2) {
                    me.extraJobSeekers((d.seekers[i].length - 2).toString() + '+');
                }
                for (j in d.seekers[i]) {
                    me.jobSeekers.push(new SeekerModel(d.seekers[i][j]));
                }
                for(i in me.jobSeekers()){
                    me.jobSeekers()[i].seekerUrl('job/seekerdetails/'+me.jobSeekers()[i].seekerId()+'/'+d.recruiter_job_id);
                }
            }
        };

        me._init(d);
    };

    var allJobModel = function (d) {
        var me = this;
        me.jobTitle = ko.observable('');
        me.noOfJobs = ko.observable();
        me.jobs = ko.observableArray([]);

        me._init = function (d) {
            if (typeof d == "undefined") {
                return false;
            }
            me.jobTitle(d.jobtitle_name);
            me.noOfJobs(d.jobs_count);
            for (i in d.jobs) {
                me.jobs.push(new JobModel(d.jobs[i]));
            }
        };

        me._init(d);
    };

    ko.bindingHandlers.datetimePicker = {
        init: function (element, valueAccessor, bContext, allBindingsAccessor, bindingContext) {
            $(element).datetimepicker({
                format: 'L',
            }).on('dp.change', function (a) {
                bContext().value($(this).val());
//                console.log(bindingContext.$root.filterJobList());
//                bindingContext.$root.filterList(allBindingsAccessor);
            });
        }
    };

    var ReportsVM = function () {
        var me = this;
        me.isLoading = ko.observable(false);
        me.allJobs = ko.observableArray([]);
        me.filterTo = ko.observable();
        me.filterFrom = ko.observable();
        me.filterError = ko.observable('');
        me.modalJobDate = ko.observable('');
        me.modalSeekers = ko.observableArray('');
        me.modalJobId = ko.observable();

        me.getAllTempJobs = function () {
            $.get('reports-temp-jobs', function (d) {
                for (i in d.data) {
                    me.allJobs.push(new allJobModel(d.data[i]));
                }
            });
        };

        me.showToolTip = function (d, e) {
            $(e.currentTarget).tooltip();
        }

        me.filterJobList = ko.computed(function () {
            if (typeof me.filterFrom() !== "undefined" && typeof me.filterTo() !== "undefined") {
                if (moment(me.filterFrom()) <= moment(me.filterTo())) {
                    return ko.utils.arrayFilter(me.allJobs(), function (job) {
                        var z = ko.utils.arrayFilter(job.jobs(), function (pjob) {
                            var a = pjob.jobDate();
                            var b = me.filterTo();
                            var c = me.filterFrom();
                            var result =  moment(a) <= moment(b) && moment(a) >= moment(c) ? true : false;
                            return result;
                        });
                        return z.length > 0;
                    });
                } else {
                    return me.allJobs();
                }
            } else {
                return me.allJobs();
            }
        });

        me.showHiredJobSeekers = function (d, e) {
            me.modalSeekers([]);
            me.modalJobDate('');
            me.modalJobId('');
            
            me.modalJobDate(d.jobDate());
            me.modalJobId(d.jobId);
            for(i in d.jobSeekers()){
                d.jobSeekers()[i].seekerUrl('job/seekerdetails/'+d.jobSeekers()[i].seekerId()+'/'+d.jobId());
                me.modalSeekers.push(d.jobSeekers()[i]);
            }
            $('.reportsModal').modal('show');
        };

        me._init = function () {
            me.getAllTempJobs();
//            $('#datetimepicker6').datetimepicker({
//                format: 'L'
//            });
//            $('#datetimepicker7').datetimepicker({
//                format: 'L',
//                useCurrent: false //Important! See issue #1075
//            });
//            $("#datetimepicker6").on("dp.change", function(e) {
//                $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
//            });
//            $("#datetimepicker7").on("dp.change", function(e) {
//                $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
//            });
        };
        me._init();
    };
    var ssObj = new ReportsVM();
    ko.applyBindings(ssObj, $('#report')[0])
</script>
@endsection
