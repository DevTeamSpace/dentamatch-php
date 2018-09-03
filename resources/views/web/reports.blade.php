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
                                    <div class="row">
                                        <div class="col-sm-4 col-xs-12 text-left">
                                            <label>Include deleted: </label>
                                            <?php $checked = (request()->get('history')=='true')?'checked="checked"':''; ?>
                                            <input type="checkbox" {{ $checked }} onchange="javascript:loadReportData();" id="includeDeleted" name="includeDeleted" value="1" style="-webkit-appearance: checkbox;">
                                        </div>
                                        <div class="col-sm-2 col-xs-12 report-filter text-right">
                                            <label>Filter:</label>
                                        </div>
                                        <div class="col-sm-3 col-xs-12 pull-right mr-b-10">
                                            <div class="form-group">
                                                <div class='input-group date' id='datetimepicker7' data-bind="datetimePicker: {opt:filterTo}">
                                                    <input type='text' class="form-control" placeholder="To" data-bind="value: filterTo, event: {update: $root.filterList}" />
                                                    <span class="input-group-addon">
                                                        <span class="fa fa-angle-down"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-12 pull-right mr-b-10">
                                            <div class="form-group">
                                                <div class='input-group date' id='datetimepicker6' data-bind="datetimePicker: {opt:filterFrom}">
                                                    <input type='text' class="form-control" placeholder="From" data-bind="value: filterFrom" />
                                                    <span class="input-group-addon">
                                                        <span class="fa fa-angle-down"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
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
                                            <td colspan="4">
                                                <div class="scroll-table">
                                                    <table class="table ">
                                                        <td><strong data-bind="text: jobTitle"></strong></td>
                                                        <td data-bind="text: noOfJobs"></td>
                                                        <td>

                                                            <!--ko foreach: jobs-->
                                                            <p class="report-jobdate mr-b-10" data-bind="text: jobDate"></p>
                                                            <!--/ko-->

                                                        </td>
                                                        <td>
                                                            <!--ko foreach: jobs-->
                                                            <ul class="list-hired">
                                                                <!--ko foreach: jobSeekers-->
                                                                <!--ko if: $index() <= 3-->
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
                                                    </table>
                                                </div>
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
                            Hired Talent
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
    var public_path = '<?php echo URL::to('');?>/';
    var loadReportData = function loadReportData() {
        var checked = $('#includeDeleted').is(':checked')
        console.log(checked);
        window.location.href = public_path+'reports?history='+checked;
    }
</script>
<script src="{{asset('web/scripts/reports.js')}}"></script>
@endsection
