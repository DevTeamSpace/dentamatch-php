@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-12 col-md-offset-0">
        <div class="panel panel-default">
          <div class="panel-heading">Response Rate
            <div class="span6 pull-right" style="text-align:right"><a
                      href="{{ url('cms/report/download/responselist') }}">Download CSV</a></div>
          </div>
        </div>

        <div class="panel-body">
          <table id="response_list" class="display responsive wrap" cellspacing="0">
            <thead>
            <tr>
              <th>Office Name</th>
              <th>Job Title</th>
              <th>Job Type</th>
              <th>Hourly Wage Offered</th>
              <th>Invited</th>
              <th>Applied</th>
              <th>Interviewing</th>
              <th>Hired</th>
              <th>Reject</th>
              <th>Cancel</th>
            </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  </div>
@endsection
