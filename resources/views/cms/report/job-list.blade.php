@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">Job List
            <div class="span6 pull-right" style="text-align:right; margin-right: 15px;">
              <a href="{{ url('cms/report/csvJobs') }}">Download CSV</a>
            </div>
          </div>

          <div class="panel-body">
            <table id="job_list" class="display responsive wrap" cellspacing="0" width="100%">
              <thead>
              <tr>
                <th>Office Name</th>
                <th>Office Location</th>
                <th>Job Title</th>
                <th>Job Type</th>
                <th>Hourly Wage Offered</th>
                <th>Action</th>
              </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
