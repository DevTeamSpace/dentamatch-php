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
                                                <input type='text' class="form-control" placeholder="To" />
                                                <span class="input-group-addon">
                                                    <span class="fa fa-angle-down"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 pull-right mr-b-10">
                                        <div class="form-group">
                                            <div class='input-group date' id='datetimepicker6'>
                                                <input type='text' class="form-control" placeholder="From" />
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
                                        <!--ko foreach: allJobs-->
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
                                                    <li>
                                                        <a href="#" data-toggle="tooltip" data-placement="bottom" data-bind="attr: {title: seekerName}">
                                                            <img class="cir-28 img-circle" src="http://placehold.it/28x28" onerror="this.src = 'http://placehold.it/28x28'" data-bind="attr: {src: seekerProfilePic}">
                                                        </a>
                                                    </li>
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
    
    var SeekerModel = function(d){
        var me = this;
        me.seekerId = ko.observable();
        me.seekerName = ko.observable('');
        me.seekerProfilePic = ko.observable('');
        
        me._init = function(d){
            me.seekerId(d.seeker_id);
            me.seekerName(d.first_name+" "+d.last_name);
            me.seekerProfilePic(d.profile_pic);
        };
        
        me._init(d);
    };
    
    var JobModel = function(d){
        var me = this;
        me.jobDate = ko.observable();
        me.jobSeekers = ko.observableArray([]);
        
        me._init = function(d){
            if(typeof d == "undefined"){
                return false;
            }
            me.jobDate(moment(d.job_created_at).format('ll'));
            for(i in d.seekers){
                for(j in d.seekers[i]){
                   me.jobSeekers.push(new SeekerModel(d.seekers[i][j])); 
                }
            }
//            if(d.seekers.length == 0){
//                me.jobSeekers([]);
//            }else{
//                for(i in d.seekers){
//                    for(j in d.seekers[i]){
//                       me.jobSeekers.push(new SeekerModel(d.seekers[i][j])); 
//                    }
//                }
//            }
        };
        
        me._init(d);
    };
    
    var allJobModel = function(d){
      var me = this;
      me.jobTitle = ko.observable('');
      me.noOfJobs = ko.observable();
      me.jobs = ko.observableArray([]);
      
      me._init = function(d){
          if(typeof d == "undefined"){
              return false;
          }
          me.jobTitle(d.jobtitle_name);
          me.noOfJobs(d.jobs_count);
          for(i in d.jobs){
              me.jobs.push(new JobModel(d.jobs[i]));
          }
      };
      
      me._init(d);
    };

    var ReportsVM = function () {
        var me = this;
        me.isLoading = ko.observable(false);
        me.allJobs = ko.observableArray([]);
        
        me.getAllTempJobs = function(){
            $.get('reports-temp-jobs', function(d){
                for(i in d.data){
                    me.allJobs.push(new allJobModel(d.data[i]));
                }
                console.log(me.allJobs());
            });
        };
        
        me._init = function () {
            me.getAllTempJobs();
        };
        me._init();
    };
    var ssObj = new ReportsVM();
    ko.applyBindings(ssObj, $('#report')[0])
</script>
@endsection
